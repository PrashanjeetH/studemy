<?php

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/students.php");
	include("$currDir/students_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('students');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "students";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`students`.`id`" => "id",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`students`.`username`" => "username",
		"`students`.`password`" => "password",
		"`students`.`firstname`" => "firstname",
		"`students`.`middlename`" => "middlename",
		"`students`.`lastname`" => "lastname",
		"`students`.`gender`" => "gender",
		"`students`.`email`" => "email",
		"CONCAT_WS('-', LEFT(`students`.`phone`,3), MID(`students`.`phone`,4,3), RIGHT(`students`.`phone`,4))" => "phone",
		"if(`students`.`dob`,date_format(`students`.`dob`,'%d/%m/%Y'),'')" => "dob",
		"if(`students`.`signupDate`,date_format(`students`.`signupDate`,'%d/%m/%Y %h:%i %p'),'')" => "signupDate",
		"`students`.`city`" => "city",
		"`students`.`state`" => "state"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`students`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8,
		9 => 9,
		10 => '`students`.`phone`',
		11 => '`students`.`dob`',
		12 => '`students`.`signupDate`',
		13 => 13,
		14 => 14
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`students`.`id`" => "id",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`students`.`username`" => "username",
		"`students`.`password`" => "password",
		"`students`.`firstname`" => "firstname",
		"`students`.`middlename`" => "middlename",
		"`students`.`lastname`" => "lastname",
		"`students`.`gender`" => "gender",
		"`students`.`email`" => "email",
		"CONCAT_WS('-', LEFT(`students`.`phone`,3), MID(`students`.`phone`,4,3), RIGHT(`students`.`phone`,4))" => "phone",
		"if(`students`.`dob`,date_format(`students`.`dob`,'%d/%m/%Y'),'')" => "dob",
		"if(`students`.`signupDate`,date_format(`students`.`signupDate`,'%d/%m/%Y %h:%i %p'),'')" => "signupDate",
		"`students`.`city`" => "city",
		"`students`.`state`" => "state"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`students`.`id`" => "Id",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "Institute",
		"`students`.`username`" => "Username",
		"`students`.`password`" => "Password",
		"`students`.`firstname`" => "Firstname",
		"`students`.`middlename`" => "Middlename",
		"`students`.`lastname`" => "Lastname",
		"`students`.`gender`" => "Gender",
		"`students`.`email`" => "Email",
		"`students`.`phone`" => "Phone",
		"`students`.`dob`" => "Dob",
		"`students`.`signupDate`" => "SignupDate",
		"`students`.`city`" => "City",
		"`students`.`state`" => "State"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`students`.`id`" => "id",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`students`.`username`" => "username",
		"`students`.`password`" => "password",
		"`students`.`firstname`" => "firstname",
		"`students`.`middlename`" => "middlename",
		"`students`.`lastname`" => "lastname",
		"`students`.`gender`" => "gender",
		"`students`.`email`" => "email",
		"CONCAT_WS('-', LEFT(`students`.`phone`,3), MID(`students`.`phone`,4,3), RIGHT(`students`.`phone`,4))" => "phone",
		"if(`students`.`dob`,date_format(`students`.`dob`,'%d/%m/%Y'),'')" => "dob",
		"if(`students`.`signupDate`,date_format(`students`.`signupDate`,'%d/%m/%Y %h:%i %p'),'')" => "signupDate",
		"`students`.`city`" => "city",
		"`students`.`state`" => "state"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'instituteNumber' => 'Institute');

	$x->QueryFrom = "`students` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`students`.`instituteNumber` ";
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
	$x->ScriptFileName = "students_view.php";
	$x->RedirectAfterInsert = "students_view.php";
	$x->TableTitle = "Students";
	$x->TableIcon = "./assets/img/student.png";
	$x->PrimaryKey = "`students`.`id`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Institute", "Username", "Password", "Firstname", "Middlename", "Lastname", "Gender", "Email", "Phone", "Dob", "SignupDate", "City", "State");
	$x->ColFieldName = array('instituteNumber', 'username', 'password', 'firstname', 'middlename', 'lastname', 'gender', 'email', 'phone', 'dob', 'signupDate', 'city', 'state');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);

	// template paths below are based on the app main directory
	$x->Template = 'templates/students_templateTV.html';
	$x->SelectedTemplate = 'templates/students_templateTVS.html';
	$x->TemplateDV = 'templates/students_templateDV.html';
	$x->TemplateDVP = 'templates/students_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `students`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='students' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `students`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='students' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`students`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: students_init
	$render=TRUE;
	if(function_exists('students_init')){
		$args=array();
		$render=students_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: students_header
	$headerCode='';
	if(function_exists('students_header')){
		$args=array();
		$headerCode=students_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: students_footer
	$footerCode='';
	if(function_exists('students_footer')){
		$args=array();
		$footerCode=students_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
