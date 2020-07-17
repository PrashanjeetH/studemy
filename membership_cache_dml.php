<?php

// Data functions (insert, update, delete, form) for table membership_cache

//


function membership_cache_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('membership_cache');
	if(!$arrPerm[1]){
		return false;
	}

	$data['request'] = makeSafe($_REQUEST['request']);
		if($data['request'] == empty_lookup_value){ $data['request'] = ''; }
	$data['request_ts'] = makeSafe($_REQUEST['request_ts']);
		if($data['request_ts'] == empty_lookup_value){ $data['request_ts'] = ''; }
	$data['response'] = makeSafe($_REQUEST['response']);
		if($data['response'] == empty_lookup_value){ $data['response'] = ''; }
	if($data['request'] == '') {echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Request': " . $Translation['pkfield empty'] . '</div>'; exit;}


	// hook: membership_cache_before_insert
	if(function_exists('membership_cache_before_insert')){
		$args=array();
		if(!membership_cache_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `membership_cache` set       `request`=' . (($data['request'] !== '' && $data['request'] !== NULL) ? "'{$data['request']}'" : 'NULL') . ', `request_ts`=' . (($data['request_ts'] !== '' && $data['request_ts'] !== NULL) ? "'{$data['request_ts']}'" : 'NULL') . ', `response`=' . (($data['response'] !== '' && $data['response'] !== NULL) ? "'{$data['response']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"membership_cache_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = $data['request'];

	// hook: membership_cache_after_insert
	if(function_exists('membership_cache_after_insert')){
		$res = sql("select * from `membership_cache` where `request`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!membership_cache_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('membership_cache', $recID, getLoggedMemberID());

	return $recID;
}

function membership_cache_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('membership_cache');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='membership_cache' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='membership_cache' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: membership_cache_before_delete
	if(function_exists('membership_cache_before_delete')){
		$args=array();
		if(!membership_cache_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `membership_cache` where `request`='$selected_id'", $eo);

	// hook: membership_cache_after_delete
	if(function_exists('membership_cache_after_delete')){
		$args=array();
		membership_cache_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='membership_cache' and pkValue='$selected_id'", $eo);
}

function membership_cache_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('membership_cache');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='membership_cache' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='membership_cache' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['request'] = makeSafe($_REQUEST['request']);
		if($data['request'] == empty_lookup_value){ $data['request'] = ''; }
	$data['request_ts'] = makeSafe($_REQUEST['request_ts']);
		if($data['request_ts'] == empty_lookup_value){ $data['request_ts'] = ''; }
	$data['response'] = makeSafe($_REQUEST['response']);
		if($data['response'] == empty_lookup_value){ $data['response'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: membership_cache_before_update
	if(function_exists('membership_cache_before_update')){
		$args=array();
		if(!membership_cache_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `membership_cache` set       `request`=' . (($data['request'] !== '' && $data['request'] !== NULL) ? "'{$data['request']}'" : 'NULL') . ', `request_ts`=' . (($data['request_ts'] !== '' && $data['request_ts'] !== NULL) ? "'{$data['request_ts']}'" : 'NULL') . ', `response`=' . (($data['response'] !== '' && $data['response'] !== NULL) ? "'{$data['response']}'" : 'NULL') . " where `request`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="membership_cache_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}

	$data['selectedID'] = $data['request'];

	// hook: membership_cache_after_update
	if(function_exists('membership_cache_after_update')){
		$res = sql("SELECT * FROM `membership_cache` WHERE `request`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['request'];
		$args = array();
		if(!membership_cache_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."', pkValue='{$data['request']}' where tableName='membership_cache' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function membership_cache_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('membership_cache');
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
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='membership_cache' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='membership_cache' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `membership_cache` where `request`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'membership_cache_view.php', false);
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
	$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/membership_cache_templateDV.html';
	$templateCode = @file_get_contents($template_file);

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return membership_cache_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return membership_cache_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return membership_cache_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#request').replaceWith('<div class=\"form-control-static\" id=\"request\">' + (jQuery('#request').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#request_ts').replaceWith('<div class=\"form-control-static\" id=\"request_ts\">' + (jQuery('#request_ts').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#response').replaceWith('<div class=\"form-control-static\" id=\"response\">' + (jQuery('#response').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
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
	$templateCode = str_replace('<%%UPLOADFILE(request)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(request_ts)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(response)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(request)%%>', safe_html($urow['request']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(request)%%>', html_attr($row['request']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(request)%%>', urlencode($urow['request']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(request_ts)%%>', safe_html($urow['request_ts']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(request_ts)%%>', html_attr($row['request_ts']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(request_ts)%%>', urlencode($urow['request_ts']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(response)%%>', safe_html($urow['response']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(response)%%>', html_attr($row['response']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(response)%%>', urlencode($urow['response']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(request)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(request)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(request_ts)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(request_ts)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(response)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(response)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('membership_cache');
	if($selected_id){
		$jdata = get_joined_record('membership_cache', $selected_id);
		if($jdata === false) $jdata = get_defaults('membership_cache');
		$rdata = $row;
	}
	$templateCode .= loadView('membership_cache-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: membership_cache_dv
	if(function_exists('membership_cache_dv')){
		$args=array();
		membership_cache_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>