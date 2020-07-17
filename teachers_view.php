<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/teachers.php");
	include("$currDir/teachers_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('teachers');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "teachers";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`teachers`.`id`" => "id",
		"`teachers`.`firstname`" => "firstname",
		"`teachers`.`middlename`" => "middlename",
		"`teachers`.`lastname`" => "lastname",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"CONCAT_WS('-', LEFT(`teachers`.`phone`,3), MID(`teachers`.`phone`,4,3), RIGHT(`teachers`.`phone`,4))" => "phone",
		"`teachers`.`email`" => "email",
		"`teachers`.`pincode`" => "pincode",
		"`teachers`.`city`" => "city",
		"`teachers`.`state`" => "state",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subjects */" => "subjects"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`teachers`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => '`teachers`.`phone`',
		7 => 7,
		8 => '`teachers`.`pincode`',
		9 => 9,
		10 => 10,
		11 => '`subjects1`.`subjectName`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`teachers`.`id`" => "id",
		"`teachers`.`firstname`" => "firstname",
		"`teachers`.`middlename`" => "middlename",
		"`teachers`.`lastname`" => "lastname",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"CONCAT_WS('-', LEFT(`teachers`.`phone`,3), MID(`teachers`.`phone`,4,3), RIGHT(`teachers`.`phone`,4))" => "phone",
		"`teachers`.`email`" => "email",
		"`teachers`.`pincode`" => "pincode",
		"`teachers`.`city`" => "city",
		"`teachers`.`state`" => "state",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subjects */" => "subjects"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`teachers`.`id`" => "Id",
		"`teachers`.`firstname`" => "Firstname",
		"`teachers`.`middlename`" => "Middlename",
		"`teachers`.`lastname`" => "Lastname",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "Institute",
		"`teachers`.`phone`" => "Phone",
		"`teachers`.`email`" => "Email",
		"`teachers`.`pincode`" => "Pincode",
		"`teachers`.`city`" => "City",
		"`teachers`.`state`" => "State",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subjects */" => "Subjects"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`teachers`.`id`" => "id",
		"`teachers`.`firstname`" => "firstname",
		"`teachers`.`middlename`" => "middlename",
		"`teachers`.`lastname`" => "lastname",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"CONCAT_WS('-', LEFT(`teachers`.`phone`,3), MID(`teachers`.`phone`,4,3), RIGHT(`teachers`.`phone`,4))" => "phone",
		"`teachers`.`email`" => "email",
		"`teachers`.`pincode`" => "pincode",
		"`teachers`.`city`" => "city",
		"`teachers`.`state`" => "state",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subjects */" => "subjects"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'instituteNumber' => 'Institute', 'subjects' => 'Subjects');

	$x->QueryFrom = "`teachers` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`teachers`.`instituteNumber` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`teachers`.`subjects` ";
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
	$x->ScriptFileName = "teachers_view.php";
	$x->RedirectAfterInsert = "teachers_view.php";
	$x->TableTitle = "Teachers";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`teachers`.`id`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Firstname", "Middlename", "Lastname", "Institute", "Phone", "Email", "Pincode", "City", "State", "Subjects");
	$x->ColFieldName = array('firstname', 'middlename', 'lastname', 'instituteNumber', 'phone', 'email', 'pincode', 'city', 'state', 'subjects');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11);

	// template paths below are based on the app main directory
	$x->Template = 'templates/teachers_templateTV.html';
	$x->SelectedTemplate = 'templates/teachers_templateTVS.html';
	$x->TemplateDV = 'templates/teachers_templateDV.html';
	$x->TemplateDVP = 'templates/teachers_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `teachers`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='teachers' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `teachers`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='teachers' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`teachers`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: teachers_init
	$render=TRUE;
	if(function_exists('teachers_init')){
		$args=array();
		$render=teachers_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: teachers_header
	$headerCode='';
	if(function_exists('teachers_header')){
		$args=array();
		$headerCode=teachers_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: teachers_footer
	$footerCode='';
	if(function_exists('teachers_footer')){
		$args=array();
		$footerCode=teachers_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>