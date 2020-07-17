<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/modules.php");
	include("$currDir/modules_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('modules');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "modules";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`modules`.`moduleId`" => "moduleId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId",
		"IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') /* Assessment */" => "assessmentId",
		"`modules`.`moduleName`" => "moduleName",
		"`modules`.`link`" => "link",
		"if(CHAR_LENGTH(`modules`.`description`)>50, concat(left(`modules`.`description`,50),' ...'), `modules`.`description`)" => "description",
		"`modules`.`file`" => "file"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`modules`.`moduleId`',
		2 => 2,
		3 => 3,
		4 => '`assessments1`.`assessmentName`',
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`modules`.`moduleId`" => "moduleId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId",
		"IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') /* Assessment */" => "assessmentId",
		"`modules`.`moduleName`" => "moduleName",
		"`modules`.`link`" => "link",
		"`modules`.`description`" => "description",
		"`modules`.`file`" => "file"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`modules`.`moduleId`" => "ModuleId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "Institute",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "Course",
		"IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') /* Assessment */" => "Assessment",
		"`modules`.`moduleName`" => "Name",
		"`modules`.`link`" => "Link",
		"`modules`.`description`" => "Description",
		"`modules`.`file`" => "File"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`modules`.`moduleId`" => "moduleId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId",
		"IF(    CHAR_LENGTH(`assessments1`.`assessmentName`), CONCAT_WS('',   `assessments1`.`assessmentName`), '') /* Assessment */" => "assessmentId",
		"`modules`.`moduleName`" => "moduleName",
		"`modules`.`link`" => "link",
		"`modules`.`description`" => "Description",
		"`modules`.`file`" => "file"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'instituteNumber' => 'Institute', 'courseId' => 'Course', 'assessmentId' => 'Assessment');

	$x->QueryFrom = "`modules` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`modules`.`instituteNumber` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`modules`.`courseId` LEFT JOIN `assessments` as assessments1 ON `assessments1`.`assessmentId`=`modules`.`assessmentId` ";
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
	$x->ScriptFileName = "modules_view.php";
	$x->RedirectAfterInsert = "modules_view.php";
	$x->TableTitle = "Modules";
	$x->TableIcon = "./assets/img/modules.png";
	$x->PrimaryKey = "`modules`.`moduleId`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Institute", "Course", "Assessment", "Name", "Link", "Description", "File");
	$x->ColFieldName = array('instituteNumber', 'courseId', 'assessmentId', 'moduleName', 'link', 'description', 'file');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8);

	// template paths below are based on the app main directory
	$x->Template = 'templates/modules_templateTV.html';
	$x->SelectedTemplate = 'templates/modules_templateTVS.html';
	$x->TemplateDV = 'templates/modules_templateDV.html';
	$x->TemplateDVP = 'templates/modules_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `modules`.`moduleId`=membership_userrecords.pkValue and membership_userrecords.tableName='modules' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `modules`.`moduleId`=membership_userrecords.pkValue and membership_userrecords.tableName='modules' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`modules`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: modules_init
	$render=TRUE;
	if(function_exists('modules_init')){
		$args=array();
		$render=modules_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: modules_header
	$headerCode='';
	if(function_exists('modules_header')){
		$args=array();
		$headerCode=modules_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: modules_footer
	$footerCode='';
	if(function_exists('modules_footer')){
		$args=array();
		$footerCode=modules_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
