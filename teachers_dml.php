<?php

// Data functions (insert, update, delete, form) for table teachers

//


function teachers_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('teachers');
	if(!$arrPerm[1]){
		return false;
	}

	$data['firstname'] = makeSafe($_REQUEST['firstname']);
		if($data['firstname'] == empty_lookup_value){ $data['firstname'] = ''; }
	$data['middlename'] = makeSafe($_REQUEST['middlename']);
		if($data['middlename'] == empty_lookup_value){ $data['middlename'] = ''; }
	$data['lastname'] = makeSafe($_REQUEST['lastname']);
		if($data['lastname'] == empty_lookup_value){ $data['lastname'] = ''; }
	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['pincode'] = makeSafe($_REQUEST['pincode']);
		if($data['pincode'] == empty_lookup_value){ $data['pincode'] = ''; }
	$data['city'] = makeSafe($_REQUEST['city']);
		if($data['city'] == empty_lookup_value){ $data['city'] = ''; }
	$data['state'] = makeSafe($_REQUEST['state']);
		if($data['state'] == empty_lookup_value){ $data['state'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	if($data['firstname']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Firstname': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: teachers_before_insert
	if(function_exists('teachers_before_insert')){
		$args=array();
		if(!teachers_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `teachers` set       `firstname`=' . (($data['firstname'] !== '' && $data['firstname'] !== NULL) ? "'{$data['firstname']}'" : 'NULL') . ', `middlename`=' . (($data['middlename'] !== '' && $data['middlename'] !== NULL) ? "'{$data['middlename']}'" : 'NULL') . ', `lastname`=' . (($data['lastname'] !== '' && $data['lastname'] !== NULL) ? "'{$data['lastname']}'" : 'NULL') . ', `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `pincode`=' . (($data['pincode'] !== '' && $data['pincode'] !== NULL) ? "'{$data['pincode']}'" : 'NULL') . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"teachers_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: teachers_after_insert
	if(function_exists('teachers_after_insert')){
		$res = sql("select * from `teachers` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!teachers_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('teachers', $recID, getLoggedMemberID());

	return $recID;
}

function teachers_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('teachers');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='teachers' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='teachers' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: teachers_before_delete
	if(function_exists('teachers_before_delete')){
		$args=array();
		if(!teachers_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: courses
	$res = sql("select `id` from `teachers` where `id`='$selected_id'", $eo);
	$id = db_fetch_row($res);
	$rires = sql("select count(1) from `courses` where `teacher`='".addslashes($id[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "courses", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "courses", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='teachers_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='teachers_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `teachers` where `id`='$selected_id'", $eo);

	// hook: teachers_after_delete
	if(function_exists('teachers_after_delete')){
		$args=array();
		teachers_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='teachers' and pkValue='$selected_id'", $eo);
}

function teachers_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('teachers');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='teachers' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='teachers' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['firstname'] = makeSafe($_REQUEST['firstname']);
		if($data['firstname'] == empty_lookup_value){ $data['firstname'] = ''; }
	if($data['firstname']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Firstname': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['middlename'] = makeSafe($_REQUEST['middlename']);
		if($data['middlename'] == empty_lookup_value){ $data['middlename'] = ''; }
	$data['lastname'] = makeSafe($_REQUEST['lastname']);
		if($data['lastname'] == empty_lookup_value){ $data['lastname'] = ''; }
	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['pincode'] = makeSafe($_REQUEST['pincode']);
		if($data['pincode'] == empty_lookup_value){ $data['pincode'] = ''; }
	$data['city'] = makeSafe($_REQUEST['city']);
		if($data['city'] == empty_lookup_value){ $data['city'] = ''; }
	$data['state'] = makeSafe($_REQUEST['state']);
		if($data['state'] == empty_lookup_value){ $data['state'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: teachers_before_update
	if(function_exists('teachers_before_update')){
		$args=array();
		if(!teachers_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `teachers` set       `firstname`=' . (($data['firstname'] !== '' && $data['firstname'] !== NULL) ? "'{$data['firstname']}'" : 'NULL') . ', `middlename`=' . (($data['middlename'] !== '' && $data['middlename'] !== NULL) ? "'{$data['middlename']}'" : 'NULL') . ', `lastname`=' . (($data['lastname'] !== '' && $data['lastname'] !== NULL) ? "'{$data['lastname']}'" : 'NULL') . ', `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `pincode`=' . (($data['pincode'] !== '' && $data['pincode'] !== NULL) ? "'{$data['pincode']}'" : 'NULL') . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="teachers_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: teachers_after_update
	if(function_exists('teachers_after_update')){
		$res = sql("SELECT * FROM `teachers` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!teachers_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='teachers' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function teachers_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('teachers');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_instituteNumber = thisOr(undo_magic_quotes($_REQUEST['filterer_instituteNumber']), '');
	$filterer_subjects = thisOr(undo_magic_quotes($_REQUEST['filterer_subjects']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: instituteNumber
	$combo_instituteNumber = new DataCombo;
	// combobox: subjects
	$combo_subjects = new DataCombo;

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='teachers' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='teachers' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `teachers` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'teachers_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_instituteNumber->SelectedData = $row['instituteNumber'];
		$combo_subjects->SelectedData = $row['subjects'];
	}else{
		$combo_instituteNumber->SelectedData = $filterer_instituteNumber;
		$combo_subjects->SelectedData = $filterer_subjects;
	}
	$combo_instituteNumber->HTML = '<span id="instituteNumber-container' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_instituteNumber->MatchText = '<span id="instituteNumber-container-readonly' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_subjects->HTML = $combo_subjects->MatchText = '<span id="subjects-container' . $rnd1 . '"></span>';

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_instituteNumber__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['instituteNumber'] : $filterer_instituteNumber); ?>"};
		studemy.current_subjects__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['subjects'] : $filterer_subjects); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(instituteNumber_reload__RAND__) == 'function') instituteNumber_reload__RAND__();
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
						data: { id: studemy.current_instituteNumber__RAND__.value, t: 'teachers', f: 'instituteNumber' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="instituteNumber"]').val(resp.results[0].id);
							$j('[id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }


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
					data: function(term, page){ /* */ return { s: term, p: page, t: 'teachers', f: 'instituteNumber' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				studemy.current_instituteNumber__RAND__.value = e.added.id;
				studemy.current_instituteNumber__RAND__.text = e.added.text;
				$j('[name="instituteNumber"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }


				if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
			});

			if(!$j("#instituteNumber-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: studemy.current_instituteNumber__RAND__.value, t: 'teachers', f: 'instituteNumber' },
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
				data: { id: studemy.current_instituteNumber__RAND__.value, t: 'teachers', f: 'instituteNumber' },
				success: function(resp){
					$j('[id=instituteNumber-container__RAND__], [id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

					if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function subjects_reload__RAND__(){
			new Ajax.Updater("subjects-container__RAND__", "ajax_combo.php", {
				parameters: { t: "teachers", f: "subjects", id: studemy.current_subjects__RAND__.value, text: studemy.current_subjects__RAND__.text, o: <?php echo (($AllowUpdate || $AllowInsert) && !$dvprint ? '1' : '0'); ?> },
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/teachers_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/teachers_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return teachers_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return teachers_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return teachers_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#firstname').replaceWith('<div class=\"form-control-static\" id=\"firstname\">' + (jQuery('#firstname').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#middlename').replaceWith('<div class=\"form-control-static\" id=\"middlename\">' + (jQuery('#middlename').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#lastname').replaceWith('<div class=\"form-control-static\" id=\"lastname\">' + (jQuery('#lastname').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#instituteNumber').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#instituteNumber_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#phone').replaceWith('<div class=\"form-control-static\" id=\"phone\">' + (jQuery('#phone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#email').replaceWith('<div class=\"form-control-static\" id=\"email\">' + (jQuery('#email').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#pincode').replaceWith('<div class=\"form-control-static\" id=\"pincode\">' + (jQuery('#pincode').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#city').replaceWith('<div class=\"form-control-static\" id=\"city\">' + (jQuery('#city').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#state').replaceWith('<div class=\"form-control-static\" id=\"state\">' + (jQuery('#state').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#subjects').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#subjects_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
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
	$templateCode = str_replace('<%%COMBO(subjects)%%>', $combo_subjects->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(subjects)%%>', $combo_subjects->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(subjects)%%>', urlencode($combo_subjects->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'instituteNumber' => array('institutes', 'Institute'), 'subjects' => array('subjects', 'Subjects'));
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
	$templateCode = str_replace('<%%UPLOADFILE(firstname)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(middlename)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(lastname)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(phone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(email)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(pincode)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(city)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(state)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(subjects)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(firstname)%%>', safe_html($urow['firstname']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(firstname)%%>', html_attr($row['firstname']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(firstname)%%>', urlencode($urow['firstname']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(middlename)%%>', safe_html($urow['middlename']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(middlename)%%>', html_attr($row['middlename']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(middlename)%%>', urlencode($urow['middlename']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(lastname)%%>', safe_html($urow['lastname']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(lastname)%%>', html_attr($row['lastname']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(lastname)%%>', urlencode($urow['lastname']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', safe_html($urow['phone']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', html_attr($row['phone']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode($urow['phone']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', safe_html($urow['email']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', html_attr($row['email']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode($urow['email']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(pincode)%%>', safe_html($urow['pincode']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(pincode)%%>', html_attr($row['pincode']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(pincode)%%>', urlencode($urow['pincode']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(city)%%>', safe_html($urow['city']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(city)%%>', html_attr($row['city']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(city)%%>', urlencode($urow['city']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(state)%%>', safe_html($urow['state']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(state)%%>', html_attr($row['state']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(state)%%>', urlencode($urow['state']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', safe_html($urow['subjects']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', html_attr($row['subjects']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(subjects)%%>', urlencode($urow['subjects']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(firstname)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(firstname)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(middlename)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(middlename)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(lastname)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(lastname)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(phone)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(email)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(pincode)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(pincode)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(city)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(city)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(state)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(state)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(subjects)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(subjects)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('teachers');
	if($selected_id){
		$jdata = get_joined_record('teachers', $selected_id);
		if($jdata === false) $jdata = get_defaults('teachers');
		$rdata = $row;
	}
	$templateCode .= loadView('teachers-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: teachers_dv
	if(function_exists('teachers_dv')){
		$args=array();
		teachers_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>