<?php


function assessments_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('assessments');
	if(!$arrPerm[1]){
		return false;
	}

	$data['assessmentName'] = makeSafe($_REQUEST['assessmentName']);
		if($data['assessmentName'] == empty_lookup_value){ $data['assessmentName'] = ''; }
	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	if($data['assessmentName']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'AssessmentName': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: assessments_before_insert
	if(function_exists('assessments_before_insert')){
		$args=array();
		if(!assessments_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `assessments` set       `assessmentName`=' . (($data['assessmentName'] !== '' && $data['assessmentName'] !== NULL) ? "'{$data['assessmentName']}'" : 'NULL') . ', `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"assessments_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: assessments_after_insert
	if(function_exists('assessments_after_insert')){
		$res = sql("select * from `assessments` where `assessmentId`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!assessments_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('assessments', $recID, getLoggedMemberID());

	return $recID;
}

function assessments_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('assessments');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assessments' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assessments' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: assessments_before_delete
	if(function_exists('assessments_before_delete')){
		$args=array();
		if(!assessments_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: modules
	$res = sql("select `assessmentId` from `assessments` where `assessmentId`='$selected_id'", $eo);
	$assessmentId = db_fetch_row($res);
	$rires = sql("select count(1) from `modules` where `assessmentId`='".addslashes($assessmentId[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "modules", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "modules", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='assessments_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='assessments_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: questions
	$res = sql("select `assessmentId` from `assessments` where `assessmentId`='$selected_id'", $eo);
	$assessmentId = db_fetch_row($res);
	$rires = sql("select count(1) from `questions` where `assessmentId`='".addslashes($assessmentId[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='assessments_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='assessments_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `assessments` where `assessmentId`='$selected_id'", $eo);

	// hook: assessments_after_delete
	if(function_exists('assessments_after_delete')){
		$args=array();
		assessments_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='assessments' and pkValue='$selected_id'", $eo);
}

function assessments_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('assessments');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assessments' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assessments' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['assessmentName'] = makeSafe($_REQUEST['assessmentName']);
		if($data['assessmentName'] == empty_lookup_value){ $data['assessmentName'] = ''; }
	if($data['assessmentName']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'AssessmentName': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: assessments_before_update
	if(function_exists('assessments_before_update')){
		$args=array();
		if(!assessments_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `assessments` set       `assessmentName`=' . (($data['assessmentName'] !== '' && $data['assessmentName'] !== NULL) ? "'{$data['assessmentName']}'" : 'NULL') . ', `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL') . " where `assessmentId`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="assessments_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: assessments_after_update
	if(function_exists('assessments_after_update')){
		$res = sql("SELECT * FROM `assessments` WHERE `assessmentId`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['assessmentId'];
		$args = array();
		if(!assessments_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='assessments' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function assessments_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('assessments');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_instituteNumber = thisOr(undo_magic_quotes($_REQUEST['filterer_instituteNumber']), '');
	$filterer_courseId = thisOr(undo_magic_quotes($_REQUEST['filterer_courseId']), '');

	// populate filterers, starting from children to grand-parents
	if($filterer_courseId && !$filterer_instituteNumber) $filterer_instituteNumber = sqlValue("select instituteNumber from courses where courseId='" . makeSafe($filterer_courseId) . "'");

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: instituteNumber
	$combo_instituteNumber = new DataCombo;
	// combobox: courseId, filterable by: instituteNumber
	$combo_courseId = new DataCombo;

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assessments' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assessments' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `assessments` where `assessmentId`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'assessments_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_instituteNumber->SelectedData = $row['instituteNumber'];
		$combo_courseId->SelectedData = $row['courseId'];
	}else{
		$combo_instituteNumber->SelectedData = $filterer_instituteNumber;
		$combo_courseId->SelectedData = $filterer_courseId;
	}
	$combo_instituteNumber->HTML = '<span id="instituteNumber-container' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_instituteNumber->MatchText = '<span id="instituteNumber-container-readonly' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_courseId->HTML = '<span id="courseId-container' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';
	$combo_courseId->MatchText = '<span id="courseId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_instituteNumber__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['instituteNumber'] : $filterer_instituteNumber); ?>"};
		studemy.current_courseId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['courseId'] : $filterer_courseId); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(instituteNumber_reload__RAND__) == 'function') instituteNumber_reload__RAND__();
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(courseId_reload__RAND__) == \'function\') courseId_reload__RAND__(studemy.current_instituteNumber__RAND__.value);' : ''); ?>
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
						data: { id: studemy.current_instituteNumber__RAND__.value, t: 'assessments', f: 'instituteNumber' },
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
					data: function(term, page){ /* */ return { s: term, p: page, t: 'assessments', f: 'instituteNumber' }; },
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
					data: { id: studemy.current_instituteNumber__RAND__.value, t: 'assessments', f: 'instituteNumber' },
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
				data: { id: studemy.current_instituteNumber__RAND__.value, t: 'assessments', f: 'instituteNumber' },
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
						data: { filterer_instituteNumber: filterer_instituteNumber, id: studemy.current_courseId__RAND__.value, t: 'assessments', f: 'courseId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="courseId"]').val(resp.results[0].id);
							$j('[id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }


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
					data: function(term, page){ /* */ return { filterer_instituteNumber: filterer_instituteNumber, s: term, p: page, t: 'assessments', f: 'courseId' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_courseId__RAND__.value = e.added.id;
				studemy.current_courseId__RAND__.text = e.added.text;
				$j('[name="courseId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }


				if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
			});

			if(!$j("#courseId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_courseId__RAND__.value, t: 'assessments', f: 'courseId' },
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
				data: { id: studemy.current_courseId__RAND__.value, t: 'assessments', f: 'courseId' },
				success: function(resp){
					$j('[id=courseId-container__RAND__], [id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

					if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/assessments_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/assessments_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return assessments_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return assessments_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return assessments_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#assessmentName').replaceWith('<div class=\"form-control-static\" id=\"assessmentName\">' + (jQuery('#assessmentName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#instituteNumber').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#instituteNumber_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#courseId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#courseId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
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

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'instituteNumber' => array('institutes', 'Institute'), 'courseId' => array('courses', 'Course'));
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
	$templateCode = str_replace('<%%UPLOADFILE(assessmentId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(assessmentName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(courseId)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', safe_html($urow['assessmentId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', html_attr($row['assessmentId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode($urow['assessmentId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(assessmentName)%%>', safe_html($urow['assessmentName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(assessmentName)%%>', html_attr($row['assessmentName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentName)%%>', urlencode($urow['assessmentName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', safe_html($urow['courseId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', html_attr($row['courseId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode($urow['courseId']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(assessmentId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(assessmentName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(courseId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('assessments');
	if($selected_id){
		$jdata = get_joined_record('assessments', $selected_id);
		if($jdata === false) $jdata = get_defaults('assessments');
		$rdata = $row;
	}
	$templateCode .= loadView('assessments-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: assessments_dv
	if(function_exists('assessments_dv')){
		$args=array();
		assessments_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>
