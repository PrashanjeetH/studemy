<?php

// Data functions (insert, update, delete, form) for table students

//


function students_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('students');
	if(!$arrPerm[1]){
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['username'] = makeSafe($_REQUEST['username']);
		if($data['username'] == empty_lookup_value){ $data['username'] = ''; }
	$data['password'] = makeSafe($_REQUEST['password']);
		if($data['password'] == empty_lookup_value){ $data['password'] = ''; }
	$data['firstname'] = makeSafe($_REQUEST['firstname']);
		if($data['firstname'] == empty_lookup_value){ $data['firstname'] = ''; }
	$data['middlename'] = makeSafe($_REQUEST['middlename']);
		if($data['middlename'] == empty_lookup_value){ $data['middlename'] = ''; }
	$data['lastname'] = makeSafe($_REQUEST['lastname']);
		if($data['lastname'] == empty_lookup_value){ $data['lastname'] = ''; }
	$data['gender'] = makeSafe($_REQUEST['gender']);
		if($data['gender'] == empty_lookup_value){ $data['gender'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['dob'] = intval($_REQUEST['dobYear']) . '-' . intval($_REQUEST['dobMonth']) . '-' . intval($_REQUEST['dobDay']);
	$data['dob'] = parseMySQLDate($data['dob'], '');
	$data['signupDate'] = parseCode('<%%creationDate%%>', true, true);
	$data['city'] = makeSafe($_REQUEST['city']);
		if($data['city'] == empty_lookup_value){ $data['city'] = ''; }
	$data['state'] = makeSafe($_REQUEST['state']);
		if($data['state'] == empty_lookup_value){ $data['state'] = ''; }
	if($data['username']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Username': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: students_before_insert
	if(function_exists('students_before_insert')){
		$args=array();
		if(!students_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `students` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `username`=' . (($data['username'] !== '' && $data['username'] !== NULL) ? "'{$data['username']}'" : 'NULL') . ', `password`=' . (($data['password'] !== '' && $data['password'] !== NULL) ? "'{$data['password']}'" : 'NULL') . ', `firstname`=' . (($data['firstname'] !== '' && $data['firstname'] !== NULL) ? "'{$data['firstname']}'" : 'NULL') . ', `middlename`=' . (($data['middlename'] !== '' && $data['middlename'] !== NULL) ? "'{$data['middlename']}'" : 'NULL') . ', `lastname`=' . (($data['lastname'] !== '' && $data['lastname'] !== NULL) ? "'{$data['lastname']}'" : 'NULL') . ', `gender`=' . (($data['gender'] !== '' && $data['gender'] !== NULL) ? "'{$data['gender']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `dob`=' . (($data['dob'] !== '' && $data['dob'] !== NULL) ? "'{$data['dob']}'" : 'NULL') . ', `signupDate`=' . "'{$data['signupDate']}'" . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"students_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: students_after_insert
	if(function_exists('students_after_insert')){
		$res = sql("select * from `students` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!students_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('students', $recID, getLoggedMemberID());

	return $recID;
}

function students_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('students');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='students' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='students' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: students_before_delete
	if(function_exists('students_before_delete')){
		$args=array();
		if(!students_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `students` where `id`='$selected_id'", $eo);

	// hook: students_after_delete
	if(function_exists('students_after_delete')){
		$args=array();
		students_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='students' and pkValue='$selected_id'", $eo);
}

function students_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('students');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='students' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='students' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['instituteNumber'] = makeSafe($_REQUEST['instituteNumber']);
		if($data['instituteNumber'] == empty_lookup_value){ $data['instituteNumber'] = ''; }
	$data['username'] = makeSafe($_REQUEST['username']);
		if($data['username'] == empty_lookup_value){ $data['username'] = ''; }
	if($data['username']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Username': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['password'] = makeSafe($_REQUEST['password']);
		if($data['password'] == empty_lookup_value){ $data['password'] = ''; }
	$data['firstname'] = makeSafe($_REQUEST['firstname']);
		if($data['firstname'] == empty_lookup_value){ $data['firstname'] = ''; }
	$data['middlename'] = makeSafe($_REQUEST['middlename']);
		if($data['middlename'] == empty_lookup_value){ $data['middlename'] = ''; }
	$data['lastname'] = makeSafe($_REQUEST['lastname']);
		if($data['lastname'] == empty_lookup_value){ $data['lastname'] = ''; }
	$data['gender'] = makeSafe($_REQUEST['gender']);
		if($data['gender'] == empty_lookup_value){ $data['gender'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['dob'] = intval($_REQUEST['dobYear']) . '-' . intval($_REQUEST['dobMonth']) . '-' . intval($_REQUEST['dobDay']);
	$data['dob'] = parseMySQLDate($data['dob'], '');
	$data['city'] = makeSafe($_REQUEST['city']);
		if($data['city'] == empty_lookup_value){ $data['city'] = ''; }
	$data['state'] = makeSafe($_REQUEST['state']);
		if($data['state'] == empty_lookup_value){ $data['state'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: students_before_update
	if(function_exists('students_before_update')){
		$args=array();
		if(!students_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `students` set       `instituteNumber`=' . (($data['instituteNumber'] !== '' && $data['instituteNumber'] !== NULL) ? "'{$data['instituteNumber']}'" : 'NULL') . ', `username`=' . (($data['username'] !== '' && $data['username'] !== NULL) ? "'{$data['username']}'" : 'NULL') . ', `password`=' . (($data['password'] !== '' && $data['password'] !== NULL) ? "'{$data['password']}'" : 'NULL') . ', `firstname`=' . (($data['firstname'] !== '' && $data['firstname'] !== NULL) ? "'{$data['firstname']}'" : 'NULL') . ', `middlename`=' . (($data['middlename'] !== '' && $data['middlename'] !== NULL) ? "'{$data['middlename']}'" : 'NULL') . ', `lastname`=' . (($data['lastname'] !== '' && $data['lastname'] !== NULL) ? "'{$data['lastname']}'" : 'NULL') . ', `gender`=' . (($data['gender'] !== '' && $data['gender'] !== NULL) ? "'{$data['gender']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `dob`=' . (($data['dob'] !== '' && $data['dob'] !== NULL) ? "'{$data['dob']}'" : 'NULL') . ', `signupDate`=`signupDate`' . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="students_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: students_after_update
	if(function_exists('students_after_update')){
		$res = sql("SELECT * FROM `students` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!students_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='students' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function students_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('students');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_instituteNumber = thisOr(undo_magic_quotes($_REQUEST['filterer_instituteNumber']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: instituteNumber
	$combo_instituteNumber = new DataCombo;
	// combobox: gender
	$combo_gender = new Combo;
	$combo_gender->ListType = 2;
	$combo_gender->MultipleSeparator = ', ';
	$combo_gender->ListBoxHeight = 10;
	$combo_gender->RadiosPerLine = 1;
	if(is_file(dirname(__FILE__).'/hooks/students.gender.csv')){
		$gender_data = addslashes(implode('', @file(dirname(__FILE__).'/hooks/students.gender.csv')));
		$combo_gender->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions($gender_data)));
		$combo_gender->ListData = $combo_gender->ListItem;
	}else{
		$combo_gender->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions("Male;;Female")));
		$combo_gender->ListData = $combo_gender->ListItem;
	}
	$combo_gender->SelectName = 'gender';
	// combobox: dob
	$combo_dob = new DateCombo;
	$combo_dob->DateFormat = "dmy";
	$combo_dob->MinYear = 1900;
	$combo_dob->MaxYear = 2100;
	$combo_dob->DefaultDate = parseMySQLDate('', '');
	$combo_dob->MonthNames = $Translation['month names'];
	$combo_dob->NamePrefix = 'dob';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='students' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='students' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `students` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'students_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_instituteNumber->SelectedData = $row['instituteNumber'];
		$combo_gender->SelectedData = $row['gender'];
		$combo_dob->DefaultDate = $row['dob'];
	}else{
		$combo_instituteNumber->SelectedData = $filterer_instituteNumber;
		$combo_gender->SelectedText = ( $_REQUEST['FilterField'][1]=='8' && $_REQUEST['FilterOperator'][1]=='<=>' ? (get_magic_quotes_gpc() ? stripslashes($_REQUEST['FilterValue'][1]) : $_REQUEST['FilterValue'][1]) : "");
	}
	$combo_instituteNumber->HTML = '<span id="instituteNumber-container' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_instituteNumber->MatchText = '<span id="instituteNumber-container-readonly' . $rnd1 . '"></span><input type="hidden" name="instituteNumber" id="instituteNumber' . $rnd1 . '" value="' . html_attr($combo_instituteNumber->SelectedData) . '">';
	$combo_gender->Render();

	ob_start();
	?>

	<script>
		// initial lookup values
		studemy.current_instituteNumber__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['instituteNumber'] : $filterer_instituteNumber); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(instituteNumber_reload__RAND__) == 'function') instituteNumber_reload__RAND__();
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
						data: { id: studemy.current_instituteNumber__RAND__.value, t: 'students', f: 'instituteNumber' },
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
					data: function(term, page){ /* */ return { s: term, p: page, t: 'students', f: 'instituteNumber' }; },
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
					data: { id: studemy.current_instituteNumber__RAND__.value, t: 'students', f: 'instituteNumber' },
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
				data: { id: studemy.current_instituteNumber__RAND__.value, t: 'students', f: 'instituteNumber' },
				success: function(resp){
					$j('[id=instituteNumber-container__RAND__], [id=instituteNumber-container-readonly__RAND__]').html('<span id="instituteNumber-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=institutes_view_parent]').hide(); }else{ $j('.btn[id=institutes_view_parent]').show(); }

					if(typeof(instituteNumber_update_autofills__RAND__) == 'function') instituteNumber_update_autofills__RAND__();
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/students_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/students_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return students_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return students_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return students_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#username').replaceWith('<div class=\"form-control-static\" id=\"username\">' + (jQuery('#username').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#password').replaceWith('<div class=\"form-control-static\" id=\"password\">' + (jQuery('#password').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#firstname').replaceWith('<div class=\"form-control-static\" id=\"firstname\">' + (jQuery('#firstname').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#middlename').replaceWith('<div class=\"form-control-static\" id=\"middlename\">' + (jQuery('#middlename').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#lastname').replaceWith('<div class=\"form-control-static\" id=\"lastname\">' + (jQuery('#lastname').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('input[name=gender]').parent().html('<div class=\"form-control-static\">' + jQuery('input[name=gender]:checked').next().text() + '</div>')\n";
		$jsReadOnly .= "\tjQuery('#email').replaceWith('<div class=\"form-control-static\" id=\"email\">' + (jQuery('#email').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#phone').replaceWith('<div class=\"form-control-static\" id=\"phone\">' + (jQuery('#phone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#dob').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#dobDay, #dobMonth, #dobYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#city').replaceWith('<div class=\"form-control-static\" id=\"city\">' + (jQuery('#city').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#state').replaceWith('<div class=\"form-control-static\" id=\"state\">' + (jQuery('#state').val() || '') + '</div>');\n";
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
	$templateCode = str_replace('<%%COMBO(gender)%%>', $combo_gender->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(gender)%%>', $combo_gender->SelectedData, $templateCode);
	$templateCode = str_replace('<%%COMBO(dob)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_dob->GetHTML(true) . '</div>' : $combo_dob->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(dob)%%>', $combo_dob->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'instituteNumber' => array('institutes', 'Institute'));
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
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(username)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(password)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(firstname)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(middlename)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(lastname)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(gender)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(email)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(phone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(dob)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(signupDate)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(city)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(state)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(username)%%>', safe_html($urow['username']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(username)%%>', html_attr($row['username']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(username)%%>', urlencode($urow['username']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(password)%%>', safe_html($urow['password']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(password)%%>', html_attr($row['password']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(password)%%>', urlencode($urow['password']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(firstname)%%>', safe_html($urow['firstname']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(firstname)%%>', html_attr($row['firstname']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(firstname)%%>', urlencode($urow['firstname']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(middlename)%%>', safe_html($urow['middlename']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(middlename)%%>', html_attr($row['middlename']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(middlename)%%>', urlencode($urow['middlename']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(lastname)%%>', safe_html($urow['lastname']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(lastname)%%>', html_attr($row['lastname']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(lastname)%%>', urlencode($urow['lastname']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(gender)%%>', safe_html($urow['gender']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(gender)%%>', html_attr($row['gender']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(gender)%%>', urlencode($urow['gender']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', safe_html($urow['email']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', html_attr($row['email']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode($urow['email']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', safe_html($urow['phone']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', html_attr($row['phone']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode($urow['phone']), $templateCode);
		$templateCode = str_replace('<%%VALUE(dob)%%>', @date('d/m/Y', @strtotime(html_attr($row['dob']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dob)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['dob'])))), $templateCode);
		$templateCode = str_replace('<%%VALUE(signupDate)%%>', app_datetime($row['signupDate'], 'dt'), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(signupDate)%%>', urlencode(app_datetime($urow['signupDate'], 'dt')), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(city)%%>', safe_html($urow['city']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(city)%%>', html_attr($row['city']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(city)%%>', urlencode($urow['city']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(state)%%>', safe_html($urow['state']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(state)%%>', html_attr($row['state']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(state)%%>', urlencode($urow['state']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(username)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(username)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(password)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(password)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(firstname)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(firstname)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(middlename)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(middlename)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(lastname)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(lastname)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(gender)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(gender)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(email)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(phone)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(dob)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dob)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(signupDate)%%>', '<%%creationDate%%>', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(signupDate)%%>', urlencode('<%%creationDate%%>'), $templateCode);
		$templateCode = str_replace('<%%VALUE(city)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(city)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(state)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(state)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('students');
	if($selected_id){
		$jdata = get_joined_record('students', $selected_id);
		if($jdata === false) $jdata = get_defaults('students');
		$rdata = $row;
	}
	$templateCode .= loadView('students-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: students_dv
	if(function_exists('students_dv')){
		$args=array();
		students_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>