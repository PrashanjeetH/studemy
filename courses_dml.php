<?php

// Data functions (insert, update, delete, form) for table courses

//


function courses_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('courses');
	if(!$arrPerm[1]){
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseName'] = makeSafe($_REQUEST['courseName']);
		if($data['courseName'] == empty_lookup_value){ $data['courseName'] = ''; }
	$data['courseCode'] = makeSafe($_REQUEST['courseCode']);
		if($data['courseCode'] == empty_lookup_value){ $data['courseCode'] = ''; }
	$data['link'] = makeSafe($_REQUEST['link']);
		if($data['link'] == empty_lookup_value){ $data['link'] = ''; }
	$data['teacher'] = makeSafe($_REQUEST['teacher']);
		if($data['teacher'] == empty_lookup_value){ $data['teacher'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	$data['description'] = br2nl(makeSafe($_REQUEST['description']));
	$data['amount'] = makeSafe($_REQUEST['amount']);
		if($data['amount'] == empty_lookup_value){ $data['amount'] = ''; }
	$data['dateUploaded'] = parseCode('<%%creationDate%%>', true, true);
	$data['isActivate'] = makeSafe($_REQUEST['isActivate']);
		if($data['isActivate'] == empty_lookup_value){ $data['isActivate'] = ''; }
	$data['isApproved'] = makeSafe($_REQUEST['isApproved']);
		if($data['isApproved'] == empty_lookup_value){ $data['isApproved'] = ''; }
	$data['file'] = PrepareUploadedFile('file', 10485760,'zip|jpeg|jpg|png|pdf|xls|xlsx|csv|docx|ppt', false, '');
	if($data['courseName']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Name': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	if($data['isActivate'] == '') $data['isActivate'] = "0";
	if($data['isApproved'] == '') $data['isApproved'] = "0";

	/* for empty upload fields, when saving a copy of an existing record, copy the original upload field */
	if($_REQUEST['SelectedID']){
		$res = sql("select * from courses where courseId='" . makeSafe($_REQUEST['SelectedID']) . "'", $eo);
		if($row = db_fetch_assoc($res)){
			if(!$data['file']) $data['file'] = makeSafe($row['file']);
		}
	}

	// hook: courses_before_insert
	if(function_exists('courses_before_insert')){
		$args=array();
		if(!courses_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `courses` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseName`=' . (($data['courseName'] !== '' && $data['courseName'] !== NULL) ? "'{$data['courseName']}'" : 'NULL') . ', `courseCode`=' . (($data['courseCode'] !== '' && $data['courseCode'] !== NULL) ? "'{$data['courseCode']}'" : 'NULL') . ', `link`=' . (($data['link'] !== '' && $data['link'] !== NULL) ? "'{$data['link']}'" : 'NULL') . ', `teacher`=' . (($data['teacher'] !== '' && $data['teacher'] !== NULL) ? "'{$data['teacher']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', ' . ($data['file'] != '' ? "`file`='{$data['file']}'" : '`file`=NULL') . ', `amount`=' . (($data['amount'] !== '' && $data['amount'] !== NULL) ? "'{$data['amount']}'" : 'NULL') . ', `dateUploaded`=' . "'{$data['dateUploaded']}'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"courses_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());
	// enforce pk zerofill
	$recID = str_pad($recID, sqlValue("select length(`courseId`) from `courses` limit 1"), '0', STR_PAD_LEFT);

	// hook: courses_after_insert
	if(function_exists('courses_after_insert')){
		$res = sql("select * from `courses` where `courseId`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!courses_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('courses', $recID, getLoggedMemberID());

	return $recID;
}

function courses_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('courses');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='courses' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='courses' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: courses_before_delete
	if(function_exists('courses_before_delete')){
		$args=array();
		if(!courses_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: assessments
	$res = sql("select `courseId` from `courses` where `courseId`='$selected_id'", $eo);
	$courseId = db_fetch_row($res);
	$rires = sql("select count(1) from `assessments` where `courseId`='".addslashes($courseId[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "assessments", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "assessments", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: modules
	$res = sql("select `courseId` from `courses` where `courseId`='$selected_id'", $eo);
	$courseId = db_fetch_row($res);
	$rires = sql("select count(1) from `modules` where `courseId`='".addslashes($courseId[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: questions
	$res = sql("select `courseId` from `courses` where `courseId`='$selected_id'", $eo);
	$courseId = db_fetch_row($res);
	$rires = sql("select count(1) from `questions` where `courseId`='".addslashes($courseId[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='courses_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `courses` where `courseId`='$selected_id'", $eo);

	// hook: courses_after_delete
	if(function_exists('courses_after_delete')){
		$args=array();
		courses_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='courses' and pkValue='$selected_id'", $eo);
}

function courses_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('courses');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='courses' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='courses' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['courseName'] = makeSafe($_REQUEST['courseName']);
		if($data['courseName'] == empty_lookup_value){ $data['courseName'] = ''; }
	if($data['courseName']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Name': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['courseCode'] = makeSafe($_REQUEST['courseCode']);
		if($data['courseCode'] == empty_lookup_value){ $data['courseCode'] = ''; }
	$data['link'] = makeSafe($_REQUEST['link']);
		if($data['link'] == empty_lookup_value){ $data['link'] = ''; }
	$data['teacher'] = makeSafe($_REQUEST['teacher']);
		if($data['teacher'] == empty_lookup_value){ $data['teacher'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	$data['description'] = br2nl(makeSafe($_REQUEST['description']));
	$data['amount'] = makeSafe($_REQUEST['amount']);
		if($data['amount'] == empty_lookup_value){ $data['amount'] = ''; }
	$data['dateUploaded'] = parseMySQLDate('', '<%%creationDate%%>');
	$data['isActivate'] = makeSafe($_REQUEST['isActivate']);
		if($data['isActivate'] == empty_lookup_value){ $data['isActivate'] = ''; }
	$data['isApproved'] = makeSafe($_REQUEST['isApproved']);
		if($data['isApproved'] == empty_lookup_value){ $data['isApproved'] = ''; }
	$data['selectedID']=makeSafe($selected_id);
	if($_REQUEST['file_remove'] == 1){
		$data['file'] = '';
	}else{
		$data['file'] = PrepareUploadedFile('file', 10485760, 'zip|jpeg|jpg|png|pdf|xls|xlsx|csv|docx|ppt', false, "");
	}

	// hook: courses_before_update
	if(function_exists('courses_before_update')){
		$args=array();
		if(!courses_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `courses` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `courseName`=' . (($data['courseName'] !== '' && $data['courseName'] !== NULL) ? "'{$data['courseName']}'" : 'NULL') . ', `courseCode`=' . (($data['courseCode'] !== '' && $data['courseCode'] !== NULL) ? "'{$data['courseCode']}'" : 'NULL') . ', `link`=' . (($data['link'] !== '' && $data['link'] !== NULL) ? "'{$data['link']}'" : 'NULL') . ', `teacher`=' . (($data['teacher'] !== '' && $data['teacher'] !== NULL) ? "'{$data['teacher']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', ' . ($data['file']!='' ? "`file`='{$data['file']}'" : ($_REQUEST['file_remove'] != 1 ? '`file`=`file`' : '`file`=NULL')) . ', `amount`=' . (($data['amount'] !== '' && $data['amount'] !== NULL) ? "'{$data['amount']}'" : 'NULL') . ', `dateUploaded`=`dateUploaded`' . " where `courseId`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="courses_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: courses_after_update
	if(function_exists('courses_after_update')){
		$res = sql("SELECT * FROM `courses` WHERE `courseId`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['courseId'];
		$args = array();
		if(!courses_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='courses' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function courses_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('courses');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_instituteNumber = thisOr(undo_magic_quotes($_REQUEST['filterer_instituteNumber']), '');
	$filterer_teacher = thisOr(undo_magic_quotes($_REQUEST['filterer_teacher']), '');
	$filterer_subjects = thisOr(undo_magic_quotes($_REQUEST['filterer_subjects']), '');

	// populate filterers, starting from children to grand-parents
	if($filterer_teacher && !$filterer_instituteNumber) $filterer_instituteNumber = sqlValue("select instituteNumber from teachers where id='" . makeSafe($filterer_teacher) . "'");

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: instituteNumber
	$combo_instituteNumber = new DataCombo;
	// combobox: teacher, filterable by: instituteNumber
	$combo_teacher = new DataCombo;
	// combobox: subjects
	$combo_subjects = new DataCombo;
	// combobox: dateUploaded
	$combo_dateUploaded = new DateCombo;
	$combo_dateUploaded->DateFormat = "dmy";
	$combo_dateUploaded->MinYear = 1900;
	$combo_dateUploaded->MaxYear = 2100;
	$combo_dateUploaded->DefaultDate = parseMySQLDate('<%%creationDate%%>', '<%%creationDate%%>');
	$combo_dateUploaded->MonthNames = $Translation['month names'];
	$combo_dateUploaded->NamePrefix = 'dateUploaded';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='courses' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='courses' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `courses` where `courseId`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'courses_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_instituteNumber->SelectedData = $row['instituteNumber'];
		$combo_teacher->SelectedData = $row['teacher'];
		$combo_subjects->SelectedData = $row['subjects'];
		$combo_dateUploaded->DefaultDate = $row['dateUploaded'];
	}else{
		$combo_instituteNumber->SelectedData = $filterer_instituteNumber;
		$combo_teacher->SelectedData = $filterer_teacher;
		$combo_subjects->SelectedData = $filterer_subjects;
	}
	$combo_instituteNumber->HTML = '<span id="instituteNumber-container' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_instituteNumber->MatchText = '<span id="instituteNumber-container-readonly' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_teacher->HTML = '<span id="teacher-container' . $rnd1 . '"></span><input type="hidden" name="teacher" id="teacher' . $rnd1 . '" value="' . html_attr($combo_teacher->SelectedData) . '">';
	$combo_teacher->MatchText = '<span id="teacher-container-readonly' . $rnd1 . '"></span><input type="hidden" name="teacher" id="teacher' . $rnd1 . '" value="' . html_attr($combo_teacher->SelectedData) . '">';
	$combo_subjects->HTML = $combo_subjects->MatchText = '<span id="subjects-container' . $rnd1 . '"></span>';

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_instituteNumber__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['instituteNumber'] : $filterer_instituteNumber); ?>"};
		studemy.current_teacher__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['teacher'] : $filterer_teacher); ?>"};
		studemy.current_subjects__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['subjects'] : $filterer_subjects); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(instituteNumber_reload__RAND__) == 'function') instituteNumber_reload__RAND__();
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(teacher_reload__RAND__) == \'function\') teacher_reload__RAND__(studemy.current_instituteNumber__RAND__.value);' : ''); ?>
				if(typeof(subjects_reload__RAND__) == 'function') subjects_reload__RAND__();
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
						data: { id: studemy.current_instituteNumber__RAND__.value, t: 'courses', f: 'instituteNumber' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="instituteNumber"]').val(resp.results[0].id);
							$j('[id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

						if(typeof(teacher_reload__RAND__) == 'function') teacher_reload__RAND__(studemy.current_instituteNumber__RAND__.value);

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
					data: function(term, page){ /* */ return { s: term, p: page, t: 'courses', f: 'instituteNumber' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_instituteNumber__RAND__.value = e.added.id;
				studemy.current_instituteNumber__RAND__.text = e.added.text;
				$j('[name="instituteNumber"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

						if(typeof(teacher_reload__RAND__) == 'function') teacher_reload__RAND__(studemy.current_instituteNumber__RAND__.value);

				if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
			});

			if(!$j("#instituteNumber-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_instituteNumber__RAND__.value, t: 'courses', f: 'instituteNumber' },
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
				data: { id: studemy.current_instituteNumber__RAND__.value, t: 'courses', f: 'instituteNumber' },
				success: function(resp){
					$j('[id=instituteNumber-container__RAND__], [id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

					if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function teacher_reload__RAND__(filterer_instituteNumber){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#teacher-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_instituteNumber: filterer_instituteNumber, id: studemy.current_teacher__RAND__.value, t: 'courses', f: 'teacher' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="teacher"]').val(resp.results[0].id);
							$j('[id=teacher-container-readonly__RAND__]').html('<span id="teacher-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=teachers_view_parent]').hide(); }else{ $j('.btn[id=teachers_view_parent]').show(); }


							if(typeof(teacher_update_autofills__RAND__) == 'function') teacher_update_autofills__RAND__();
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
					data: function(term, page){ /* */ return { filterer_instituteNumber: filterer_instituteNumber, s: term, p: page, t: 'courses', f: 'teacher' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_teacher__RAND__.value = e.added.id;
				studemy.current_teacher__RAND__.text = e.added.text;
				$j('[name="teacher"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=teachers_view_parent]').hide(); }else{ $j('.btn[id=teachers_view_parent]').show(); }


				if(typeof(teacher_update_autofills__RAND__) == 'function') teacher_update_autofills__RAND__();
			});

			if(!$j("#teacher-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_teacher__RAND__.value, t: 'courses', f: 'teacher' },
					success: function(resp){
						$j('[name="teacher"]').val(resp.results[0].id);
						$j('[id=teacher-container-readonly__RAND__]').html('<span id="teacher-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=teachers_view_parent]').hide(); }else{ $j('.btn[id=teachers_view_parent]').show(); }

						if(typeof(teacher_update_autofills__RAND__) == 'function') teacher_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: studemy.current_teacher__RAND__.value, t: 'courses', f: 'teacher' },
				success: function(resp){
					$j('[id=teacher-container__RAND__], [id=teacher-container-readonly__RAND__]').html('<span id="teacher-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=teachers_view_parent]').hide(); }else{ $j('.btn[id=teachers_view_parent]').show(); }

					if(typeof(teacher_update_autofills__RAND__) == 'function') teacher_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function subjects_reload__RAND__(){
			new Ajax.Updater("subjects-container__RAND__", "ajax_combo.php", {
				parameters: { t: "courses", f: "subjects", id: studemy.current_subjects__RAND__.value, text: studemy.current_subjects__RAND__.text, o: <?php echo (($AllowUpdate || $AllowInsert) && !$dvprint ? '1' : '0'); ?> },
				method: "get",
				encoding: "<?php echo datalist_db_encoding; ?>",
				evalScripts: true,
				onComplete: function(){ /* */ subjects_changed__RAND__(); }
			});
		}
		function subjects_changed__RAND__(){
			if($j("input[name=subjects]").length){
				if($j("input[name=subjects]:checked").length){
					studemy.current_subjects__RAND__.value = $j("input[name=subjects]:checked").val();
					studemy.current_subjects__RAND__.text = "";
				}else{
					studemy.current_subjects__RAND__.value = "";
					studemy.current_subjects__RAND__.text = "";
				}
				studemy.hideViewParentLinks();
			}

			if(typeof(subjects_update_autofills__RAND__) == 'function') subjects_update_autofills__RAND__();
		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/courses_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/courses_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return courses_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return courses_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return courses_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#courseName').replaceWith('<div class=\"form-control-static\" id=\"courseName\">' + (jQuery('#courseName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#courseCode').replaceWith('<div class=\"form-control-static\" id=\"courseCode\">' + (jQuery('#courseCode').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#link').replaceWith('<div class=\"form-control-static\" id=\"link\">' + (jQuery('#link').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#link, #link-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('#teacher').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#teacher_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#subjects').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#subjects_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#description').replaceWith('<div class=\"form-control-static\" id=\"description\">' + (jQuery('#description').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#file').replaceWith('<div class=\"form-control-static\" id=\"file\">' + (jQuery('#file').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#file, #file-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('#amount').replaceWith('<div class=\"form-control-static\" id=\"amount\">' + (jQuery('#amount').val() || '') + '</div>');\n";
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
	$templateCode = str_replace('<%%COMBO(teacher)%%>', $combo_teacher->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(teacher)%%>', $combo_teacher->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(teacher)%%>', urlencode($combo_teacher->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(subjects)%%>', $combo_subjects->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(subjects)%%>', $combo_subjects->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(subjects)%%>', urlencode($combo_subjects->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(dateUploaded)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_dateUploaded->GetHTML(true) . '</div>' : $combo_dateUploaded->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(dateUploaded)%%>', $combo_dateUploaded->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'instituteNumber' => array('institutes', 'Institute'), 'teacher' => array('teachers', 'Teacher'), 'subjects' => array('subjects', 'Subject'));
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
	$templateCode = str_replace('<%%UPLOADFILE(courseId)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(courseName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(courseCode)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(link)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(teacher)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(subjects)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(description)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(file)%%>', ($noUploads ? '' : '<input type=hidden name=MAX_FILE_SIZE value=10485760>'.$Translation['upload image'].' <input type="file" name="file" id="file">'), $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(amount)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(dateUploaded)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(isActivate)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(isApproved)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', safe_html($urow['courseId']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseId)%%>', html_attr($row['courseId']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode($urow['courseId']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseName)%%>', safe_html($urow['courseName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseName)%%>', html_attr($row['courseName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseName)%%>', urlencode($urow['courseName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(courseCode)%%>', safe_html($urow['courseCode']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(courseCode)%%>', html_attr($row['courseCode']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseCode)%%>', urlencode($urow['courseCode']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(link)%%>', safe_html($urow['link']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(link)%%>', html_attr($row['link']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(link)%%>', urlencode($urow['link']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(teacher)%%>', safe_html($urow['teacher']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(teacher)%%>', html_attr($row['teacher']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(teacher)%%>', urlencode($urow['teacher']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', safe_html($urow['subjects']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', html_attr($row['subjects']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(subjects)%%>', urlencode($urow['subjects']), $templateCode);
		if($dvprint || (!$AllowUpdate && !$AllowInsert)){
			$templateCode = str_replace('<%%VALUE(description)%%>', safe_html($urow['description']), $templateCode);
		}else{
			$templateCode = str_replace('<%%VALUE(description)%%>', html_attr($row['description']), $templateCode);
		}
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode($urow['description']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(file)%%>', safe_html($urow['file']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(file)%%>', html_attr($row['file']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(file)%%>', urlencode($urow['file']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(amount)%%>', safe_html($urow['amount']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(amount)%%>', html_attr($row['amount']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(amount)%%>', urlencode($urow['amount']), $templateCode);
		$templateCode = str_replace('<%%VALUE(dateUploaded)%%>', @date('d/m/Y', @strtotime(html_attr($row['dateUploaded']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dateUploaded)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['dateUploaded'])))), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(isActivate)%%>', safe_html($urow['isActivate']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(isActivate)%%>', html_attr($row['isActivate']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(isActivate)%%>', urlencode($urow['isActivate']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(isApproved)%%>', safe_html($urow['isApproved']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(isApproved)%%>', html_attr($row['isApproved']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(isApproved)%%>', urlencode($urow['isApproved']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(courseId)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseId)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(courseName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(courseCode)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(courseCode)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(link)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(link)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(teacher)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(teacher)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(subjects)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(subjects)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(description)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(file)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(file)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(amount)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(amount)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(dateUploaded)%%>', '<%%creationDate%%>', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dateUploaded)%%>', urlencode('<%%creationDate%%>'), $templateCode);
		$templateCode = str_replace('<%%VALUE(isActivate)%%>', '0', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(isActivate)%%>', urlencode('0'), $templateCode);
		$templateCode = str_replace('<%%VALUE(isApproved)%%>', '0', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(isApproved)%%>', urlencode('0'), $templateCode);
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
	$rdata = $jdata = get_defaults('courses');
	if($selected_id){
		$jdata = get_joined_record('courses', $selected_id);
		if($jdata === false) $jdata = get_defaults('courses');
		$rdata = $row;
	}
	$templateCode .= loadView('courses-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: courses_dv
	if(function_exists('courses_dv')){
		$args=array();
		courses_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>