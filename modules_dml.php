<?php

// Data functions (insert, update, delete, form) for table modules

//


function modules_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('modules');
	if(!$arrPerm[1]){
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	$data['assessmentId'] = makeSafe($_REQUEST['assessmentId']);
		if($data['assessmentId'] == empty_lookup_value){ $data['assessmentId'] = ''; }
	$data['moduleName'] = makeSafe($_REQUEST['moduleName']);
		if($data['moduleName'] == empty_lookup_value){ $data['moduleName'] = ''; }
	$data['link'] = makeSafe($_REQUEST['link']);
		if($data['link'] == empty_lookup_value){ $data['link'] = ''; }
	$data['description'] = br2nl(makeSafe($_REQUEST['description']));
	$data['file'] = PrepareUploadedFile('file', 10485760,'zip|jpeg|jpg|png|pdf|xls|xlsx|csv|docx|ppt', false, '');

	/* for empty upload fields, when saving a copy of an existing record, copy the original upload field */
	if($_REQUEST['SelectedID']){
		$res = sql("select * from modules where moduleId='" . makeSafe($_REQUEST['SelectedID']) . "'", $eo);
		if($row = db_fetch_assoc($res)){
			if(!$data['file']) $data['file'] = makeSafe($row['file']);
		}
	}

	// hook: modules_before_insert
	if(function_exists('modules_before_insert')){
		$args=array();
		if(!modules_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `modules` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL') . ', `assessmentId`=' . (($data['assessmentId'] !== '' && $data['assessmentId'] !== NULL) ? "'{$data['assessmentId']}'" : 'NULL') . ', `moduleName`=' . (($data['moduleName'] !== '' && $data['moduleName'] !== NULL) ? "'{$data['moduleName']}'" : 'NULL') . ', `link`=' . (($data['link'] !== '' && $data['link'] !== NULL) ? "'{$data['link']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', ' . ($data['file'] != '' ? "`file`='{$data['file']}'" : '`file`=NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"modules_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: modules_after_insert
	if(function_exists('modules_after_insert')){
		$res = sql("select * from `modules` where `moduleId`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!modules_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('modules', $recID, getLoggedMemberID());

	return $recID;
}

function modules_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('modules');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='modules' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='modules' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: modules_before_delete
	if(function_exists('modules_before_delete')){
		$args=array();
		if(!modules_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: questions
	$res = sql("select `moduleId` from `modules` where `moduleId`='$selected_id'", $eo);
	$moduleId = db_fetch_row($res);
	$rires = sql("select count(1) from `questions` where `moduleId`='".addslashes($moduleId[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "questions", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "questions", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='modules_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='modules_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `modules` where `moduleId`='$selected_id'", $eo);

	// hook: modules_after_delete
	if(function_exists('modules_after_delete')){
		$args=array();
		modules_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='modules' and pkValue='$selected_id'", $eo);
}

function modules_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('modules');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='modules' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='modules' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	$data['assessmentId'] = makeSafe($_REQUEST['assessmentId']);
		if($data['assessmentId'] == empty_lookup_value){ $data['assessmentId'] = ''; }
	$data['moduleName'] = makeSafe($_REQUEST['moduleName']);
		if($data['moduleName'] == empty_lookup_value){ $data['moduleName'] = ''; }
	$data['link'] = makeSafe($_REQUEST['link']);
		if($data['link'] == empty_lookup_value){ $data['link'] = ''; }
	$data['description'] = br2nl(makeSafe($_REQUEST['description']));
	$data['selectedID']=makeSafe($selected_id);
	if($_REQUEST['file_remove'] == 1){
		$data['file'] = '';
	}else{
		$data['file'] = PrepareUploadedFile('file', 10485760, 'zip|jpeg|jpg|png|pdf|xls|xlsx|csv|docx|ppt', false, "");
	}

	// hook: modules_before_update
	if(function_exists('modules_before_update')){
		$args=array();
		if(!modules_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `modules` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL') . ', `assessmentId`=' . (($data['assessmentId'] !== '' && $data['assessmentId'] !== NULL) ? "'{$data['assessmentId']}'" : 'NULL') . ', `moduleName`=' . (($data['moduleName'] !== '' && $data['moduleName'] !== NULL) ? "'{$data['moduleName']}'" : 'NULL') . ', `link`=' . (($data['link'] !== '' && $data['link'] !== NULL) ? "'{$data['link']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', ' . ($data['file']!='' ? "`file`='{$data['file']}'" : ($_REQUEST['file_remove'] != 1 ? '`file`=`file`' : '`file`=NULL')) . " where `moduleId`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="modules_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: modules_after_update
	if(function_exists('modules_after_update')){
		$res = sql("SELECT * FROM `modules` WHERE `moduleId`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['moduleId'];
		$args = array();
		if(!modules_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='modules' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function modules_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('modules');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_instituteNumber = thisOr(undo_magic_quotes($_REQUEST['filterer_instituteNumber']), '');
	$filterer_courseId = thisOr(undo_magic_quotes($_REQUEST['filterer_courseId']), '');
	$filterer_assessmentId = thisOr(undo_magic_quotes($_REQUEST['filterer_assessmentId']), '');

	// populate filterers, starting from children to grand-parents
	if($filterer_courseId && !$filterer_instituteNumber) $filterer_instituteNumber = sqlValue("select instituteNumber from courses where courseId='" . makeSafe($filterer_courseId) . "'");
	if($filterer_assessmentId && !$filterer_instituteNumber) $filterer_instituteNumber = sqlValue("select instituteNumber from assessments where assessmentId='" . makeSafe($filterer_assessmentId) . "'");
	if($filterer_assessmentId && !$filterer_courseId) $filterer_courseId = sqlValue("select courseId from assessments where assessmentId='" . makeSafe($filterer_assessmentId) . "'");

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: instituteNumber
	$combo_instituteNumber = new DataCombo;
	// combobox: courseId, filterable by: instituteNumber
	$combo_courseId = new DataCombo;
	// combobox: assessmentId, filterable by: instituteNumber,courseId
	$combo_assessmentId = new DataCombo;

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='modules' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='modules' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `modules` where `moduleId`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'modules_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_instituteNumber->SelectedData = $row['instituteNumber'];
		$combo_courseId->SelectedData = $row['courseId'];
		$combo_assessmentId->SelectedData = $row['assessmentId'];
	}else{
		$combo_instituteNumber->SelectedData = $filterer_instituteNumber;
		$combo_courseId->SelectedData = $filterer_courseId;
		$combo_assessmentId->SelectedData = $filterer_assessmentId;
	}
	$combo_instituteNumber->HTML = '<span id="instituteNumber-container' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_instituteNumber->MatchText = '<span id="instituteNumber-container-readonly' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_courseId->HTML = '<span id="courseId-container' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';
	$combo_courseId->MatchText = '<span id="courseId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';
	$combo_assessmentId->HTML = '<span id="assessmentId-container' . $rnd1 . '"></span><input type="hidden" name="assessmentId" id="assessmentId' . $rnd1 . '" value="' . html_attr($combo_assessmentId->SelectedData) . '">';
	$combo_assessmentId->MatchText = '<span id="assessmentId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="assessmentId" id="assessmentId' . $rnd1 . '" value="' . html_attr($combo_assessmentId->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_instituteNumber__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['instituteNumber'] : $filterer_instituteNumber); ?>"};
		studemy.current_courseId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['courseId'] : $filterer_courseId); ?>"};
		studemy.current_assessmentId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['assessmentId'] : $filterer_assessmentId); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(instituteNumber_reload__RAND__) == 'function') instituteNumber_reload__RAND__();
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(courseId_reload__RAND__) == \'function\') courseId_reload__RAND__(studemy.current_instituteNumber__RAND__.value);' : ''); ?>
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(assessmentId_reload__RAND__) == \'function\') assessmentId_reload__RAND__(studemy.current_instituteNumber__RAND__.value, studemy.current_courseId__RAND__.value);' : ''); ?>
			}, 10); /* we need to slightly delay client-side execution of the above code to allow studemy.ajaxCache to work */
		});
		function instituteNumber_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#instituteNumber-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: studemy.current_instituteNumber__RAND__.value, t: 'modules', f: 'instituteNumber' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="instituteNumber"]').val(resp.results[0].id);
							$j('[id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

						if(typeof(courseId_reload__RAND__) == 'function') courseId_reload__RAND__(studemy.current_instituteNumber__RAND__.value);

							if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ /* */ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ /* */ return { s: term, p: page, t: 'modules', f: 'instituteNumber' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_instituteNumber__RAND__.value = e.added.id;
				studemy.current_instituteNumber__RAND__.text = e.added.text;
				$j('[name="instituteNumber"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

						if(typeof(courseId_reload__RAND__) == 'function') courseId_reload__RAND__(studemy.current_instituteNumber__RAND__.value);

				if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
			});

			if(!$j("#instituteNumber-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_instituteNumber__RAND__.value, t: 'modules', f: 'instituteNumber' },
					success: function(resp){
						$j('[name="instituteNumber"]').val(resp.results[0].id);
						$j('[id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

						if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: studemy.current_instituteNumber__RAND__.value, t: 'modules', f: 'instituteNumber' },
				success: function(resp){
					$j('[id=instituteNumber-container__RAND__], [id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

					if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function courseId_reload__RAND__(filterer_instituteNumber){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#courseId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_instituteNumber: filterer_instituteNumber, id: studemy.current_courseId__RAND__.value, t: 'modules', f: 'courseId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="courseId"]').val(resp.results[0].id);
							$j('[id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

						if(typeof(assessmentId_reload__RAND__) == 'function') assessmentId_reload__RAND__($j('#instituteNumber').val(), studemy.current_courseId__RAND__.value);

							if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ /* */ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ /* */ return { filterer_instituteNumber: filterer_instituteNumber, s: term, p: page, t: 'modules', f: 'courseId' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_courseId__RAND__.value = e.added.id;
				studemy.current_courseId__RAND__.text = e.added.text;
				$j('[name="courseId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

						if(typeof(assessmentId_reload__RAND__) == 'function') assessmentId_reload__RAND__($j('#instituteNumber').val(), studemy.current_courseId__RAND__.value);

				if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
			});

			if(!$j("#courseId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_courseId__RAND__.value, t: 'modules', f: 'courseId' },
					success: function(resp){
						$j('[name="courseId"]').val(resp.results[0].id);
						$j('[id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

						if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: studemy.current_courseId__RAND__.value, t: 'modules', f: 'courseId' },
				success: function(resp){
					$j('[id=courseId-container__RAND__], [id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

					if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function assessmentId_reload__RAND__(filterer_instituteNumber, filterer_courseId){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#assessmentId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_instituteNumber: filterer_instituteNumber, filterer_courseId: filterer_courseId, id: studemy.current_assessmentId__RAND__.value, t: 'modules', f: 'assessmentId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="assessmentId"]').val(resp.results[0].id);
							$j('[id=assessmentId-container-readonly__RAND__]').html('<span id="assessmentId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=assessments_view_parent]').hide(); }else{ $j('.btn[id=assessments_view_parent]').show(); }


							if(typeof(assessmentId_update_autofills__RAND__) == 'function') assessmentId_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ /* */ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ /* */ return { filterer_instituteNumber: filterer_instituteNumber, filterer_courseId: filterer_courseId, s: term, p: page, t: 'modules', f: 'assessmentId' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_assessmentId__RAND__.value = e.added.id;
				studemy.current_assessmentId__RAND__.text = e.added.text;
				$j('[name="assessmentId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=assessments_view_parent]').hide(); }else{ $j('.btn[id=assessments_view_parent]').show(); }


				if(typeof(assessmentId_update_autofills__RAND__) == 'function') assessmentId_update_autofills__RAND__();
			});

			if(!$j("#assessmentId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_assessmentId__RAND__.value, t: 'modules', f: 'assessmentId' },
					success: function(resp){
						$j('[name="assessmentId"]').val(resp.results[0].id);
						$j('[id=assessmentId-container-readonly__RAND__]').html('<span id="assessmentId-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=assessments_view_parent]').hide(); }else{ $j('.btn[id=assessments_view_parent]').show(); }

						if(typeof(assessmentId_update_autofills__RAND__) == 'function') assessmentId_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: studemy.current_assessmentId__RAND__.value, t: 'modules', f: 'assessmentId' },
				success: function(resp){
					$j('[id=assessmentId-container__RAND__], [id=assessmentId-container-readonly__RAND__]').html('<span id="assessmentId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=assessments_view_parent]').hide(); }else{ $j('.btn[id=assessments_view_parent]').show(); }

					if(typeof(assessmentId_update_autofills__RAND__) == 'function') assessmentId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/modules_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/modules_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return modules_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return modules_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'studemy.closeParentModal(); return false;';
	}else{
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return modules_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#instituteNumber').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#instituteNumber_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#courseId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#courseId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#assessmentId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#assessmentId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#moduleName').replaceWith('<div class=\"form-control-static\" id=\"moduleName\">' + (jQuery('#moduleName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#link').replaceWith('<div class=\"form-control-static\" id=\"link\">' + (jQuery('#link').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#link, #link-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('#description').replaceWith('<div class=\"form-control-static\" id=\"description\">' + (jQuery('#description').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#file').replaceWith('<div class=\"form-control-static\" id=\"file\">' + (jQuery('#file').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#file, #file-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif(($AllowInsert && !$selected_id) || ($AllowUpdate && $selected_id)){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(instituteNumber)%%>', $combo_instituteNumber->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(instituteNumber)%%>', $combo_instituteNumber->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(instituteNumber)%%>', urlencode($combo_instituteNumber->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(courseId)%%>', $combo_courseId->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(courseId)%%>', $combo_courseId->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(courseId)%%>', urlencode($combo_courseId->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(assessmentId)%%>', $combo_assessmentId->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(assessmentId)%%>', $combo_assessmentId->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(assessmentId)%%>', urlencode($combo_assessmentId->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'instituteNumber' => array('institutes', 'Institute'), 'courseId' => array('courses', 'Course'), 'assessmentId' => array('assessments', 'Assessment'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(moduleId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(courseId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(assessmentId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(moduleName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(link)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(description)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(file)%%>', ($noUploads ? '' : '<input type=hidden name=MAX_FILE_SIZE value=10485760>'.$Translation['upload image'].' <input type="file" name="file" id="file">'), $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(moduleId)%%>', safe_html($urow['moduleId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(moduleId)%%>', html_attr($row['moduleId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleId)%%>', urlencode($urow['moduleId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', safe_html($urow['courseId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', html_attr($row['courseId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode($urow['courseId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', safe_html($urow['assessmentId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', html_attr($row['assessmentId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode($urow['assessmentId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(moduleName)%%>', safe_html($urow['moduleName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(moduleName)%%>', html_attr($row['moduleName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleName)%%>', urlencode($urow['moduleName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(link)%%>', safe_html($urow['link']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(link)%%>', html_attr($row['link']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(link)%%>', urlencode($urow['link']), $templateCode);
		if($dvprint || (!$AllowUpdate && !$AllowInsert)){
			$templateCode = str_replace('<%%VALUE(description)%%>', safe_html($urow['description']), $templateCode);
		}else{
			$templateCode = str_replace('<%%VALUE(description)%%>', html_attr($row['description']), $templateCode);
		}
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode($urow['description']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(file)%%>', safe_html($urow['file']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(file)%%>', html_attr($row['file']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(file)%%>', urlencode($urow['file']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(moduleId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(courseId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(assessmentId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(moduleName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(link)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(link)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(description)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(file)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(file)%%>', urlencode(''), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode = str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
			$templateCode.="\n\tif(document.getElementById('linkEdit')){ document.getElementById('linkEdit').style.display='inline'; }";
			$templateCode.="\n\tif(document.getElementById('linkEditLink')){ document.getElementById('linkEditLink').style.display='none'; }";
			$templateCode.="\n\tif(document.getElementById('fileEdit')){ document.getElementById('fileEdit').style.display='inline'; }";
			$templateCode.="\n\tif(document.getElementById('fileEditLink')){ document.getElementById('fileEditLink').style.display='none'; }";
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('modules');
	if($selected_id){
		$jdata = get_joined_record('modules', $selected_id);
		if($jdata === false) $jdata = get_defaults('modules');
		$rdata = $row;
	}
	$templateCode .= loadView('modules-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: modules_dv
	if(function_exists('modules_dv')){
		$args=array();
		modules_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>