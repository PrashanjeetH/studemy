<?php

// Data functions (insert, update, delete, form) for table institutes

//


function institutes_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('institutes');
	if(!$arrPerm[1]){
		return false;
	}

	$data['instituteName'] = makeSafe($_REQUEST['instituteName']);
		if($data['instituteName'] == empty_lookup_value){ $data['instituteName'] = ''; }
	$data['instituteCode'] = makeSafe($_REQUEST['instituteCode']);
		if($data['instituteCode'] == empty_lookup_value){ $data['instituteCode'] = ''; }
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
	$data['ownerName'] = makeSafe($_REQUEST['ownerName']);
		if($data['ownerName'] == empty_lookup_value){ $data['ownerName'] = ''; }
	$data['ownerPhone'] = makeSafe($_REQUEST['ownerPhone']);
		if($data['ownerPhone'] == empty_lookup_value){ $data['ownerPhone'] = ''; }
	$data['ownerEmail'] = makeSafe($_REQUEST['ownerEmail']);
		if($data['ownerEmail'] == empty_lookup_value){ $data['ownerEmail'] = ''; }
	$data['adminName'] = makeSafe($_REQUEST['adminName']);
		if($data['adminName'] == empty_lookup_value){ $data['adminName'] = ''; }
	$data['adminPhone'] = makeSafe($_REQUEST['adminPhone']);
		if($data['adminPhone'] == empty_lookup_value){ $data['adminPhone'] = ''; }
	$data['adminEmail'] = makeSafe($_REQUEST['adminEmail']);
		if($data['adminEmail'] == empty_lookup_value){ $data['adminEmail'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	if($data['instituteName']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Name': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	if($data['instituteCode']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Institute Code': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: institutes_before_insert
	if(function_exists('institutes_before_insert')){
		$args=array();
		if(!institutes_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `institutes` set       `instituteName`=' . (($data['instituteName'] !== '' && $data['instituteName'] !== NULL) ? "'{$data['instituteName']}'" : 'NULL') . ', `instituteCode`=' . (($data['instituteCode'] !== '' && $data['instituteCode'] !== NULL) ? "'{$data['instituteCode']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `pincode`=' . (($data['pincode'] !== '' && $data['pincode'] !== NULL) ? "'{$data['pincode']}'" : 'NULL') . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL') . ', `ownerName`=' . (($data['ownerName'] !== '' && $data['ownerName'] !== NULL) ? "'{$data['ownerName']}'" : 'NULL') . ', `ownerPhone`=' . (($data['ownerPhone'] !== '' && $data['ownerPhone'] !== NULL) ? "'{$data['ownerPhone']}'" : 'NULL') . ', `ownerEmail`=' . (($data['ownerEmail'] !== '' && $data['ownerEmail'] !== NULL) ? "'{$data['ownerEmail']}'" : 'NULL') . ', `adminName`=' . (($data['adminName'] !== '' && $data['adminName'] !== NULL) ? "'{$data['adminName']}'" : 'NULL') . ', `adminPhone`=' . (($data['adminPhone'] !== '' && $data['adminPhone'] !== NULL) ? "'{$data['adminPhone']}'" : 'NULL') . ', `adminEmail`=' . (($data['adminEmail'] !== '' && $data['adminEmail'] !== NULL) ? "'{$data['adminEmail']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"institutes_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());
	// enforce pk zerofill
	$recID = str_pad($recID, sqlValue("select length(`instituteNumber`) from `institutes` limit 1"), '0', STR_PAD_LEFT);

	// hook: institutes_after_insert
	if(function_exists('institutes_after_insert')){
		$res = sql("select * from `institutes` where `instituteNumber`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!institutes_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('institutes', $recID, getLoggedMemberID());

	return $recID;
}

function institutes_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('institutes');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='institutes' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='institutes' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: institutes_before_delete
	if(function_exists('institutes_before_delete')){
		$args=array();
		if(!institutes_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: assessments
	$res = sql("select `instituteNumber` from `institutes` where `instituteNumber`='$selected_id'", $eo);
	$instituteNumber = db_fetch_row($res);
	$rires = sql("select count(1) from `assessments` where `instituteNumber`='".addslashes($instituteNumber[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: courses
	$res = sql("select `instituteNumber` from `institutes` where `instituteNumber`='$selected_id'", $eo);
	$instituteNumber = db_fetch_row($res);
	$rires = sql("select count(1) from `courses` where `instituteNumber`='".addslashes($instituteNumber[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: modules
	$res = sql("select `instituteNumber` from `institutes` where `instituteNumber`='$selected_id'", $eo);
	$instituteNumber = db_fetch_row($res);
	$rires = sql("select count(1) from `modules` where `instituteNumber`='".addslashes($instituteNumber[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: students
	$res = sql("select `instituteNumber` from `institutes` where `instituteNumber`='$selected_id'", $eo);
	$instituteNumber = db_fetch_row($res);
	$rires = sql("select count(1) from `students` where `instituteNumber`='".addslashes($instituteNumber[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "students", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "students", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: teachers
	$res = sql("select `instituteNumber` from `institutes` where `instituteNumber`='$selected_id'", $eo);
	$instituteNumber = db_fetch_row($res);
	$rires = sql("select count(1) from `teachers` where `instituteNumber`='".addslashes($instituteNumber[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "teachers", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "teachers", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='institutes_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `institutes` where `instituteNumber`='$selected_id'", $eo);

	// hook: institutes_after_delete
	if(function_exists('institutes_after_delete')){
		$args=array();
		institutes_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='institutes' and pkValue='$selected_id'", $eo);
}

function institutes_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('institutes');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='institutes' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='institutes' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['instituteName'] = makeSafe($_REQUEST['instituteName']);
		if($data['instituteName'] == empty_lookup_value){ $data['instituteName'] = ''; }
	if($data['instituteName']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Name': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['instituteCode'] = makeSafe($_REQUEST['instituteCode']);
		if($data['instituteCode'] == empty_lookup_value){ $data['instituteCode'] = ''; }
	if($data['instituteCode']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Institute Code': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
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
	$data['ownerName'] = makeSafe($_REQUEST['ownerName']);
		if($data['ownerName'] == empty_lookup_value){ $data['ownerName'] = ''; }
	$data['ownerPhone'] = makeSafe($_REQUEST['ownerPhone']);
		if($data['ownerPhone'] == empty_lookup_value){ $data['ownerPhone'] = ''; }
	$data['ownerEmail'] = makeSafe($_REQUEST['ownerEmail']);
		if($data['ownerEmail'] == empty_lookup_value){ $data['ownerEmail'] = ''; }
	$data['adminName'] = makeSafe($_REQUEST['adminName']);
		if($data['adminName'] == empty_lookup_value){ $data['adminName'] = ''; }
	$data['adminPhone'] = makeSafe($_REQUEST['adminPhone']);
		if($data['adminPhone'] == empty_lookup_value){ $data['adminPhone'] = ''; }
	$data['adminEmail'] = makeSafe($_REQUEST['adminEmail']);
		if($data['adminEmail'] == empty_lookup_value){ $data['adminEmail'] = ''; }
	$data['subjects'] = makeSafe($_REQUEST['subjects']);
		if($data['subjects'] == empty_lookup_value){ $data['subjects'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: institutes_before_update
	if(function_exists('institutes_before_update')){
		$args=array();
		if(!institutes_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `institutes` set       `instituteName`=' . (($data['instituteName'] !== '' && $data['instituteName'] !== NULL) ? "'{$data['instituteName']}'" : 'NULL') . ', `instituteCode`=' . (($data['instituteCode'] !== '' && $data['instituteCode'] !== NULL) ? "'{$data['instituteCode']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `pincode`=' . (($data['pincode'] !== '' && $data['pincode'] !== NULL) ? "'{$data['pincode']}'" : 'NULL') . ', `city`=' . (($data['city'] !== '' && $data['city'] !== NULL) ? "'{$data['city']}'" : 'NULL') . ', `state`=' . (($data['state'] !== '' && $data['state'] !== NULL) ? "'{$data['state']}'" : 'NULL') . ', `ownerName`=' . (($data['ownerName'] !== '' && $data['ownerName'] !== NULL) ? "'{$data['ownerName']}'" : 'NULL') . ', `ownerPhone`=' . (($data['ownerPhone'] !== '' && $data['ownerPhone'] !== NULL) ? "'{$data['ownerPhone']}'" : 'NULL') . ', `ownerEmail`=' . (($data['ownerEmail'] !== '' && $data['ownerEmail'] !== NULL) ? "'{$data['ownerEmail']}'" : 'NULL') . ', `adminName`=' . (($data['adminName'] !== '' && $data['adminName'] !== NULL) ? "'{$data['adminName']}'" : 'NULL') . ', `adminPhone`=' . (($data['adminPhone'] !== '' && $data['adminPhone'] !== NULL) ? "'{$data['adminPhone']}'" : 'NULL') . ', `adminEmail`=' . (($data['adminEmail'] !== '' && $data['adminEmail'] !== NULL) ? "'{$data['adminEmail']}'" : 'NULL') . ', `subjects`=' . (($data['subjects'] !== '' && $data['subjects'] !== NULL) ? "'{$data['subjects']}'" : 'NULL') . " where `instituteNumber`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="institutes_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: institutes_after_update
	if(function_exists('institutes_after_update')){
		$res = sql("SELECT * FROM `institutes` WHERE `instituteNumber`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['instituteNumber'];
		$args = array();
		if(!institutes_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='institutes' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function institutes_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('institutes');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}


	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='institutes' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='institutes' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `institutes` where `instituteNumber`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'institutes_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
	}else{
	}

	ob_start();
	?>

	<script>
		// initial lookup values

		jQuery(function() {
			setTimeout(function(){
			}, 10); /* we need to slightly delay client-side execution of the above code to allow studemy.ajaxCache to work */
		});
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/institutes_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/institutes_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return institutes_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return institutes_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return institutes_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#instituteName').replaceWith('<div class=\"form-control-static\" id=\"instituteName\">' + (jQuery('#instituteName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#instituteCode').replaceWith('<div class=\"form-control-static\" id=\"instituteCode\">' + (jQuery('#instituteCode').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#phone').replaceWith('<div class=\"form-control-static\" id=\"phone\">' + (jQuery('#phone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#email').replaceWith('<div class=\"form-control-static\" id=\"email\">' + (jQuery('#email').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#pincode').replaceWith('<div class=\"form-control-static\" id=\"pincode\">' + (jQuery('#pincode').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#city').replaceWith('<div class=\"form-control-static\" id=\"city\">' + (jQuery('#city').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#state').replaceWith('<div class=\"form-control-static\" id=\"state\">' + (jQuery('#state').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#ownerName').replaceWith('<div class=\"form-control-static\" id=\"ownerName\">' + (jQuery('#ownerName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#ownerPhone').replaceWith('<div class=\"form-control-static\" id=\"ownerPhone\">' + (jQuery('#ownerPhone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#ownerEmail').replaceWith('<div class=\"form-control-static\" id=\"ownerEmail\">' + (jQuery('#ownerEmail').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#adminName').replaceWith('<div class=\"form-control-static\" id=\"adminName\">' + (jQuery('#adminName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#adminPhone').replaceWith('<div class=\"form-control-static\" id=\"adminPhone\">' + (jQuery('#adminPhone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#adminEmail').replaceWith('<div class=\"form-control-static\" id=\"adminEmail\">' + (jQuery('#adminEmail').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#subjects').replaceWith('<div class=\"form-control-static\" id=\"subjects\">' + (jQuery('#subjects').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif(($AllowInsert && !$selected_id) || ($AllowUpdate && $selected_id)){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array();
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
	$templateCode = str_replace('<%%UPLOADFILE(instituteNumber)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(instituteCode)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(phone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(email)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(pincode)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(city)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(state)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(ownerName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(ownerPhone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(ownerEmail)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(adminName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(adminPhone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(adminEmail)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(subjects)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', safe_html($urow['instituteNumber']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteNumber)%%>', html_attr($row['instituteNumber']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode($urow['instituteNumber']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteName)%%>', safe_html($urow['instituteName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteName)%%>', html_attr($row['instituteName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteName)%%>', urlencode($urow['instituteName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(instituteCode)%%>', safe_html($urow['instituteCode']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(instituteCode)%%>', html_attr($row['instituteCode']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteCode)%%>', urlencode($urow['instituteCode']), $templateCode);
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
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ownerName)%%>', safe_html($urow['ownerName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ownerName)%%>', html_attr($row['ownerName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerName)%%>', urlencode($urow['ownerName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ownerPhone)%%>', safe_html($urow['ownerPhone']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ownerPhone)%%>', html_attr($row['ownerPhone']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerPhone)%%>', urlencode($urow['ownerPhone']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ownerEmail)%%>', safe_html($urow['ownerEmail']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ownerEmail)%%>', html_attr($row['ownerEmail']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerEmail)%%>', urlencode($urow['ownerEmail']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(adminName)%%>', safe_html($urow['adminName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(adminName)%%>', html_attr($row['adminName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminName)%%>', urlencode($urow['adminName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(adminPhone)%%>', safe_html($urow['adminPhone']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(adminPhone)%%>', html_attr($row['adminPhone']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminPhone)%%>', urlencode($urow['adminPhone']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(adminEmail)%%>', safe_html($urow['adminEmail']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(adminEmail)%%>', html_attr($row['adminEmail']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminEmail)%%>', urlencode($urow['adminEmail']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', safe_html($urow['subjects']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(subjects)%%>', html_attr($row['subjects']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(subjects)%%>', urlencode($urow['subjects']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(instituteNumber)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteNumber)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(instituteCode)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(instituteCode)%%>', urlencode(''), $templateCode);
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
		$templateCode = str_replace('<%%VALUE(ownerName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(ownerPhone)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerPhone)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(ownerEmail)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ownerEmail)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(adminName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(adminPhone)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminPhone)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(adminEmail)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(adminEmail)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('institutes');
	if($selected_id){
		$jdata = get_joined_record('institutes', $selected_id);
		if($jdata === false) $jdata = get_defaults('institutes');
		$rdata = $row;
	}
	$templateCode .= loadView('institutes-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: institutes_dv
	if(function_exists('institutes_dv')){
		$args=array();
		institutes_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>