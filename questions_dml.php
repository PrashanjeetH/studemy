<?php

// Data functions (insert, update, delete, form) for table questions

//


function questions_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('questions');
	if(!$arrPerm[1]){
		return false;
	}

	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	$data['moduleId'] = makeSafe($_REQUEST['moduleId']);
		if($data['moduleId'] == empty_lookup_value){ $data['moduleId'] = ''; }
	$data['assessmentId'] = makeSafe($_REQUEST['assessmentId']);
		if($data['assessmentId'] == empty_lookup_value){ $data['assessmentId'] = ''; }
	$data['question'] = makeSafe($_REQUEST['question']);
		if($data['question'] == empty_lookup_value){ $data['question'] = ''; }
	$data['option1'] = makeSafe($_REQUEST['option1']);
		if($data['option1'] == empty_lookup_value){ $data['option1'] = ''; }
	$data['option2'] = makeSafe($_REQUEST['option2']);
		if($data['option2'] == empty_lookup_value){ $data['option2'] = ''; }
	$data['option3'] = makeSafe($_REQUEST['option3']);
		if($data['option3'] == empty_lookup_value){ $data['option3'] = ''; }
	$data['option4'] = makeSafe($_REQUEST['option4']);
		if($data['option4'] == empty_lookup_value){ $data['option4'] = ''; }
	$data['answer'] = makeSafe($_REQUEST['answer']);
		if($data['answer'] == empty_lookup_value){ $data['answer'] = ''; }

	// hook: questions_before_insert
	if(function_exists('questions_before_insert')){
		$args=array();
		if(!questions_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `questions` set       `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL') . ', `moduleId`=' . (($data['moduleId'] !== '' && $data['moduleId'] !== NULL) ? "'{$data['moduleId']}'" : 'NULL') . ', `assessmentId`=' . (($data['assessmentId'] !== '' && $data['assessmentId'] !== NULL) ? "'{$data['assessmentId']}'" : 'NULL') . ', `question`=' . (($data['question'] !== '' && $data['question'] !== NULL) ? "'{$data['question']}'" : 'NULL') . ', `option1`=' . (($data['option1'] !== '' && $data['option1'] !== NULL) ? "'{$data['option1']}'" : 'NULL') . ', `option2`=' . (($data['option2'] !== '' && $data['option2'] !== NULL) ? "'{$data['option2']}'" : 'NULL') . ', `option3`=' . (($data['option3'] !== '' && $data['option3'] !== NULL) ? "'{$data['option3']}'" : 'NULL') . ', `option4`=' . (($data['option4'] !== '' && $data['option4'] !== NULL) ? "'{$data['option4']}'" : 'NULL') . ', `answer`=' . (($data['answer'] !== '' && $data['answer'] !== NULL) ? "'{$data['answer']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"questions_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: questions_after_insert
	if(function_exists('questions_after_insert')){
		$res = sql("select * from `questions` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!questions_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('questions', $recID, getLoggedMemberID());

	return $recID;
}

function questions_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('questions');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='questions' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='questions' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: questions_before_delete
	if(function_exists('questions_before_delete')){
		$args=array();
		if(!questions_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `questions` where `id`='$selected_id'", $eo);

	// hook: questions_after_delete
	if(function_exists('questions_after_delete')){
		$args=array();
		questions_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='questions' and pkValue='$selected_id'", $eo);
}

function questions_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('questions');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='questions' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='questions' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['courseId'] = makeSafe($_REQUEST['courseId']);
		if($data['courseId'] == empty_lookup_value){ $data['courseId'] = ''; }
	$data['moduleId'] = makeSafe($_REQUEST['moduleId']);
		if($data['moduleId'] == empty_lookup_value){ $data['moduleId'] = ''; }
	$data['assessmentId'] = makeSafe($_REQUEST['assessmentId']);
		if($data['assessmentId'] == empty_lookup_value){ $data['assessmentId'] = ''; }
	$data['question'] = makeSafe($_REQUEST['question']);
		if($data['question'] == empty_lookup_value){ $data['question'] = ''; }
	$data['option1'] = makeSafe($_REQUEST['option1']);
		if($data['option1'] == empty_lookup_value){ $data['option1'] = ''; }
	$data['option2'] = makeSafe($_REQUEST['option2']);
		if($data['option2'] == empty_lookup_value){ $data['option2'] = ''; }
	$data['option3'] = makeSafe($_REQUEST['option3']);
		if($data['option3'] == empty_lookup_value){ $data['option3'] = ''; }
	$data['option4'] = makeSafe($_REQUEST['option4']);
		if($data['option4'] == empty_lookup_value){ $data['option4'] = ''; }
	$data['answer'] = makeSafe($_REQUEST['answer']);
		if($data['answer'] == empty_lookup_value){ $data['answer'] = ''; }

	// hook: questions_before_update
	if(function_exists('questions_before_update')){
		$args=array();
		if(!questions_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `questions` set       `courseId`=' . (($data['courseId'] !== '' && $data['courseId'] !== NULL) ? "'{$data['courseId']}'" : 'NULL') . ', `moduleId`=' . (($data['moduleId'] !== '' && $data['moduleId'] !== NULL) ? "'{$data['moduleId']}'" : 'NULL') . ', `assessmentId`=' . (($data['assessmentId'] !== '' && $data['assessmentId'] !== NULL) ? "'{$data['assessmentId']}'" : 'NULL') . ', `question`=' . (($data['question'] !== '' && $data['question'] !== NULL) ? "'{$data['question']}'" : 'NULL') . ', `option1`=' . (($data['option1'] !== '' && $data['option1'] !== NULL) ? "'{$data['option1']}'" : 'NULL') . ', `option2`=' . (($data['option2'] !== '' && $data['option2'] !== NULL) ? "'{$data['option2']}'" : 'NULL') . ', `option3`=' . (($data['option3'] !== '' && $data['option3'] !== NULL) ? "'{$data['option3']}'" : 'NULL') . ', `option4`=' . (($data['option4'] !== '' && $data['option4'] !== NULL) ? "'{$data['option4']}'" : 'NULL') . ', `answer`=' . (($data['answer'] !== '' && $data['answer'] !== NULL) ? "'{$data['answer']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="questions_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: questions_after_update
	if(function_exists('questions_after_update')){
		$res = sql("SELECT * FROM `questions` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!questions_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='questions' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function questions_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('questions');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_courseId = thisOr(undo_magic_quotes($_REQUEST['filterer_courseId']), '');
	$filterer_moduleId = thisOr(undo_magic_quotes($_REQUEST['filterer_moduleId']), '');
	$filterer_assessmentId = thisOr(undo_magic_quotes($_REQUEST['filterer_assessmentId']), '');

	// populate filterers, starting from children to grand-parents
	if($filterer_moduleId && !$filterer_courseId) $filterer_courseId = sqlValue("select courseId from modules where moduleId='" . makeSafe($filterer_moduleId) . "'");
	if($filterer_assessmentId && !$filterer_courseId) $filterer_courseId = sqlValue("select courseId from assessments where assessmentId='" . makeSafe($filterer_assessmentId) . "'");

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: courseId
	$combo_courseId = new DataCombo;
	// combobox: moduleId, filterable by: courseId
	$combo_moduleId = new DataCombo;
	// combobox: assessmentId, filterable by: courseId
	$combo_assessmentId = new DataCombo;

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='questions' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='questions' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `questions` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'questions_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_courseId->SelectedData = $row['courseId'];
		$combo_moduleId->SelectedData = $row['moduleId'];
		$combo_assessmentId->SelectedData = $row['assessmentId'];
	}else{
		$combo_courseId->SelectedData = $filterer_courseId;
		$combo_moduleId->SelectedData = $filterer_moduleId;
		$combo_assessmentId->SelectedData = $filterer_assessmentId;
	}
	$combo_courseId->HTML = '<span id="courseId-container' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';
	$combo_courseId->MatchText = '<span id="courseId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="courseId" id="courseId' . $rnd1 . '" value="' . html_attr($combo_courseId->SelectedData) . '">';
	$combo_moduleId->HTML = '<span id="moduleId-container' . $rnd1 . '"></span><input type="hidden" name="moduleId" id="moduleId' . $rnd1 . '" value="' . html_attr($combo_moduleId->SelectedData) . '">';
	$combo_moduleId->MatchText = '<span id="moduleId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="moduleId" id="moduleId' . $rnd1 . '" value="' . html_attr($combo_moduleId->SelectedData) . '">';
	$combo_assessmentId->HTML = '<span id="assessmentId-container' . $rnd1 . '"></span><input type="hidden" name="assessmentId" id="assessmentId' . $rnd1 . '" value="' . html_attr($combo_assessmentId->SelectedData) . '">';
	$combo_assessmentId->MatchText = '<span id="assessmentId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="assessmentId" id="assessmentId' . $rnd1 . '" value="' . html_attr($combo_assessmentId->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_courseId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['courseId'] : $filterer_courseId); ?>"};
		studemy.current_moduleId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['moduleId'] : $filterer_moduleId); ?>"};
		studemy.current_assessmentId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['assessmentId'] : $filterer_assessmentId); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(courseId_reload__RAND__) == 'function') courseId_reload__RAND__();
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(moduleId_reload__RAND__) == \'function\') moduleId_reload__RAND__(studemy.current_courseId__RAND__.value);' : ''); ?>
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(assessmentId_reload__RAND__) == \'function\') assessmentId_reload__RAND__(studemy.current_courseId__RAND__.value);' : ''); ?>
			}, 10); /* we need to slightly delay client-side execution of the above code to allow studemy.ajaxCache to work */
		});
		function courseId_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#courseId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: studemy.current_courseId__RAND__.value, t: 'questions', f: 'courseId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="courseId"]').val(resp.results[0].id);
							$j('[id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

						if(typeof(moduleId_reload__RAND__) == 'function') moduleId_reload__RAND__(studemy.current_courseId__RAND__.value);
						if(typeof(assessmentId_reload__RAND__) == 'function') assessmentId_reload__RAND__(studemy.current_courseId__RAND__.value);

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
					data: function(term, page){ /* */ return { s: term, p: page, t: 'questions', f: 'courseId' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_courseId__RAND__.value = e.added.id;
				studemy.current_courseId__RAND__.text = e.added.text;
				$j('[name="courseId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

						if(typeof(moduleId_reload__RAND__) == 'function') moduleId_reload__RAND__(studemy.current_courseId__RAND__.value);
						if(typeof(assessmentId_reload__RAND__) == 'function') assessmentId_reload__RAND__(studemy.current_courseId__RAND__.value);

				if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
			});

			if(!$j("#courseId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_courseId__RAND__.value, t: 'questions', f: 'courseId' },
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
				data: { id: studemy.current_courseId__RAND__.value, t: 'questions', f: 'courseId' },
				success: function(resp){
					$j('[id=courseId-container__RAND__], [id=courseId-container-readonly__RAND__]').html('<span id="courseId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=courses_view_parent]').hide(); }else{ $j('.btn[id=courses_view_parent]').show(); }

					if(typeof(courseId_update_autofills__RAND__) == 'function') courseId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function moduleId_reload__RAND__(filterer_courseId){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#moduleId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_courseId: filterer_courseId, id: studemy.current_moduleId__RAND__.value, t: 'questions', f: 'moduleId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="moduleId"]').val(resp.results[0].id);
							$j('[id=moduleId-container-readonly__RAND__]').html('<span id="moduleId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=modules_view_parent]').hide(); }else{ $j('.btn[id=modules_view_parent]').show(); }


							if(typeof(moduleId_update_autofills__RAND__) == 'function') moduleId_update_autofills__RAND__();
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
					data: function(term, page){ /* */ return { filterer_courseId: filterer_courseId, s: term, p: page, t: 'questions', f: 'moduleId' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_moduleId__RAND__.value = e.added.id;
				studemy.current_moduleId__RAND__.text = e.added.text;
				$j('[name="moduleId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=modules_view_parent]').hide(); }else{ $j('.btn[id=modules_view_parent]').show(); }


				if(typeof(moduleId_update_autofills__RAND__) == 'function') moduleId_update_autofills__RAND__();
			});

			if(!$j("#moduleId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_moduleId__RAND__.value, t: 'questions', f: 'moduleId' },
					success: function(resp){
						$j('[name="moduleId"]').val(resp.results[0].id);
						$j('[id=moduleId-container-readonly__RAND__]').html('<span id="moduleId-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=modules_view_parent]').hide(); }else{ $j('.btn[id=modules_view_parent]').show(); }

						if(typeof(moduleId_update_autofills__RAND__) == 'function') moduleId_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: studemy.current_moduleId__RAND__.value, t: 'questions', f: 'moduleId' },
				success: function(resp){
					$j('[id=moduleId-container__RAND__], [id=moduleId-container-readonly__RAND__]').html('<span id="moduleId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=modules_view_parent]').hide(); }else{ $j('.btn[id=modules_view_parent]').show(); }

					if(typeof(moduleId_update_autofills__RAND__) == 'function') moduleId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function assessmentId_reload__RAND__(filterer_courseId){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#assessmentId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_courseId: filterer_courseId, id: studemy.current_assessmentId__RAND__.value, t: 'questions', f: 'assessmentId' },
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
					data: function(term, page){ /* */ return { filterer_courseId: filterer_courseId, s: term, p: page, t: 'questions', f: 'assessmentId' }; },
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
					data: { id: studemy.current_assessmentId__RAND__.value, t: 'questions', f: 'assessmentId' },
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
				data: { id: studemy.current_assessmentId__RAND__.value, t: 'questions', f: 'assessmentId' },
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/questions_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/questions_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return questions_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return questions_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return questions_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#courseId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#courseId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#moduleId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#moduleId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#assessmentId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#assessmentId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#question').replaceWith('<div class=\"form-control-static\" id=\"question\">' + (jQuery('#question').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#option1').replaceWith('<div class=\"form-control-static\" id=\"option1\">' + (jQuery('#option1').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#option2').replaceWith('<div class=\"form-control-static\" id=\"option2\">' + (jQuery('#option2').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#option3').replaceWith('<div class=\"form-control-static\" id=\"option3\">' + (jQuery('#option3').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#option4').replaceWith('<div class=\"form-control-static\" id=\"option4\">' + (jQuery('#option4').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#answer').replaceWith('<div class=\"form-control-static\" id=\"answer\">' + (jQuery('#answer').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif(($AllowInsert && !$selected_id) || ($AllowUpdate && $selected_id)){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(courseId)%%>', $combo_courseId->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(courseId)%%>', $combo_courseId->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(courseId)%%>', urlencode($combo_courseId->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(moduleId)%%>', $combo_moduleId->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(moduleId)%%>', $combo_moduleId->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(moduleId)%%>', urlencode($combo_moduleId->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(assessmentId)%%>', $combo_assessmentId->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(assessmentId)%%>', $combo_assessmentId->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(assessmentId)%%>', urlencode($combo_assessmentId->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'courseId' => array('courses', 'Course'), 'moduleId' => array('modules', 'Module'), 'assessmentId' => array('assessments', 'Assessment'));
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
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(courseId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(moduleId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(assessmentId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(question)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(option1)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(option2)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(option3)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(option4)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(answer)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', safe_html($urow['courseId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', html_attr($row['courseId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode($urow['courseId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(moduleId)%%>', safe_html($urow['moduleId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(moduleId)%%>', html_attr($row['moduleId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleId)%%>', urlencode($urow['moduleId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', safe_html($urow['assessmentId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(assessmentId)%%>', html_attr($row['assessmentId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode($urow['assessmentId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(question)%%>', safe_html($urow['question']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(question)%%>', html_attr($row['question']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(question)%%>', urlencode($urow['question']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(option1)%%>', safe_html($urow['option1']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(option1)%%>', html_attr($row['option1']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option1)%%>', urlencode($urow['option1']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(option2)%%>', safe_html($urow['option2']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(option2)%%>', html_attr($row['option2']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option2)%%>', urlencode($urow['option2']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(option3)%%>', safe_html($urow['option3']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(option3)%%>', html_attr($row['option3']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option3)%%>', urlencode($urow['option3']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(option4)%%>', safe_html($urow['option4']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(option4)%%>', html_attr($row['option4']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option4)%%>', urlencode($urow['option4']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(answer)%%>', html_attr($row['answer']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(answer)%%>', urlencode($urow['answer']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(courseId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(moduleId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(moduleId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(assessmentId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(assessmentId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(question)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(question)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(option1)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option1)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(option2)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option2)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(option3)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option3)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(option4)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(option4)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(answer)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(answer)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('questions');
	if($selected_id){
		$jdata = get_joined_record('questions', $selected_id);
		if($jdata === false) $jdata = get_defaults('questions');
		$rdata = $row;
	}
	$templateCode .= loadView('questions-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: questions_dv
	if(function_exists('questions_dv')){
		$args=array();
		questions_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>
