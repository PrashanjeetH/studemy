<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/institutes.php");
	include("$currDir/institutes_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('institutes');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "institutes";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`institutes`.`instituteNumber`" => "instituteNumber",
		"`institutes`.`instituteName`" => "instituteName",
		"`institutes`.`instituteCode`" => "instituteCode",
		"CONCAT_WS('-', LEFT(`institutes`.`phone`,3), MID(`institutes`.`phone`,4,3), RIGHT(`institutes`.`phone`,4))" => "phone",
		"`institutes`.`email`" => "email",
		"`institutes`.`pincode`" => "pincode",
		"`institutes`.`city`" => "city",
		"`institutes`.`state`" => "state",
		"`institutes`.`ownerName`" => "ownerName",
		"`institutes`.`ownerPhone`" => "ownerPhone",
		"`institutes`.`ownerEmail`" => "ownerEmail",
		"`institutes`.`adminName`" => "adminName",
		"`institutes`.`adminPhone`" => "adminPhone",
		"`institutes`.`adminEmail`" => "adminEmail",
		"`institutes`.`subjects`" => "subjects"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`institutes`.`instituteNumber`',
		2 => 2,
		3 => 3,
		4 => '`institutes`.`phone`',
		5 => 5,
		6 => '`institutes`.`pincode`',
		7 => 7,
		8 => 8,
		9 => 9,
		10 => '`institutes`.`ownerPhone`',
		11 => 11,
		12 => 12,
		13 => '`institutes`.`adminPhone`',
		14 => 14,
		15 => 15
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`institutes`.`instituteNumber`" => "instituteNumber",
		"`institutes`.`instituteName`" => "instituteName",
		"`institutes`.`instituteCode`" => "instituteCode",
		"CONCAT_WS('-', LEFT(`institutes`.`phone`,3), MID(`institutes`.`phone`,4,3), RIGHT(`institutes`.`phone`,4))" => "phone",
		"`institutes`.`email`" => "email",
		"`institutes`.`pincode`" => "pincode",
		"`institutes`.`city`" => "city",
		"`institutes`.`state`" => "state",
		"`institutes`.`ownerName`" => "ownerName",
		"`institutes`.`ownerPhone`" => "ownerPhone",
		"`institutes`.`ownerEmail`" => "ownerEmail",
		"`institutes`.`adminName`" => "adminName",
		"`institutes`.`adminPhone`" => "adminPhone",
		"`institutes`.`adminEmail`" => "adminEmail",
		"`institutes`.`subjects`" => "subjects"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`institutes`.`instituteNumber`" => "InstituteNumber",
		"`institutes`.`instituteName`" => "Name",
		"`institutes`.`instituteCode`" => "Institute Code",
		"`institutes`.`phone`" => "Phone",
		"`institutes`.`email`" => "Email",
		"`institutes`.`pincode`" => "Pincode",
		"`institutes`.`city`" => "City",
		"`institutes`.`state`" => "State",
		"`institutes`.`ownerName`" => "Owner Name",
		"`institutes`.`ownerPhone`" => "Owner Phone",
		"`institutes`.`ownerEmail`" => "Owner Email",
		"`institutes`.`adminName`" => "Admin Name",
		"`institutes`.`adminPhone`" => "Admin Phone",
		"`institutes`.`adminEmail`" => "Admin Email",
		"`institutes`.`subjects`" => "Subjects"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`institutes`.`instituteNumber`" => "instituteNumber",
		"`institutes`.`instituteName`" => "instituteName",
		"`institutes`.`instituteCode`" => "instituteCode",
		"CONCAT_WS('-', LEFT(`institutes`.`phone`,3), MID(`institutes`.`phone`,4,3), RIGHT(`institutes`.`phone`,4))" => "phone",
		"`institutes`.`email`" => "email",
		"`institutes`.`pincode`" => "pincode",
		"`institutes`.`city`" => "city",
		"`institutes`.`state`" => "state",
		"`institutes`.`ownerName`" => "ownerName",
		"`institutes`.`ownerPhone`" => "ownerPhone",
		"`institutes`.`ownerEmail`" => "ownerEmail",
		"`institutes`.`adminName`" => "adminName",
		"`institutes`.`adminPhone`" => "adminPhone",
		"`institutes`.`adminEmail`" => "adminEmail",
		"`institutes`.`subjects`" => "subjects"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`institutes` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 1;
	$x->AllowFilters = 0;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 50;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "institutes_view.php";
	$x->RedirectAfterInsert = "institutes_view.php";
	$x->TableTitle = "Institutes";
	$x->TableIcon = "./assets\img/institute.png";
	$x->PrimaryKey = "`institutes`.`instituteNumber`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Name", "Institute Code", "Phone", "Email", "Pincode", "City", "State", "Owner Name", "Owner Phone", "Owner Email", "Admin Name", "Admin Phone", "Admin Email");
	$x->ColFieldName = array('instituteName', 'instituteCode', 'phone', 'email', 'pincode', 'city', 'state', 'ownerName', 'ownerPhone', 'ownerEmail', 'adminName', 'adminPhone', 'adminEmail');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

	// template paths below are based on the app main directory
	$x->Template = 'templates/institutes_templateTV.html';
	$x->SelectedTemplate = 'templates/institutes_templateTVS.html';
	$x->TemplateDV = 'templates/institutes_templateDV.html';
	$x->TemplateDVP = 'templates/institutes_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `institutes`.`instituteNumber`=membership_userrecords.pkValue and membership_userrecords.tableName='institutes' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `institutes`.`instituteNumber`=membership_userrecords.pkValue and membership_userrecords.tableName='institutes' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`institutes`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: institutes_init
	$render=TRUE;
	if(function_exists('institutes_init')){
		$args=array();
		$render=institutes_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: institutes_header
	$headerCode='';
	if(function_exists('institutes_header')){
		$args=array();
		$headerCode=institutes_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: institutes_footer
	$footerCode='';
	if(function_exists('institutes_footer')){
		$args=array();
		$footerCode=institutes_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
