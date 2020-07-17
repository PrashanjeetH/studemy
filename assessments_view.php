<?php
	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/assessments.php");
	include("$currDir/assessments_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('assessments');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "assessments";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`assessments`.`assessmentId`" => "assessmentId",
		"`assessments`.`assessmentName`" => "assessmentName",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`assessments`.`assessmentId`',
		2 => 2,
		3 => 3,
		4 => 4
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`assessments`.`assessmentId`" => "assessmentId",
		"`assessments`.`assessmentName`" => "assessmentName",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`assessments`.`assessmentId`" => "AssessmentId",
		"`assessments`.`assessmentName`" => "AssessmentName",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "Institute",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "Course"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`assessments`.`assessmentId`" => "assessmentId",
		"`assessments`.`assessmentName`" => "assessmentName",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"IF(    CHAR_LENGTH(`courses1`.`courseCode`) || CHAR_LENGTH(`courses1`.`courseName`), CONCAT_WS('',   `courses1`.`courseCode`, '-', `courses1`.`courseName`), '') /* Course */" => "courseId"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'instituteNumber' => 'Institute', 'courseId' => 'Course');

	$x->QueryFrom = "`assessments` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`assessments`.`instituteNumber` LEFT JOIN `courses` as courses1 ON `courses1`.`courseId`=`assessments`.`courseId` ";
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
	$x->ScriptFileName = "assessments_view.php";
	$x->RedirectAfterInsert = "assessments_view.php";
	$x->TableTitle = "Assessments";
	$x->TableIcon = "./assets/img/assessment.png";
	$x->PrimaryKey = "`assessments`.`assessmentId`";

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("AssessmentName", "Institute", "Course");
	$x->ColFieldName = array('assessmentName', 'instituteNumber', 'courseId');
	$x->ColNumber  = array(2, 3, 4);

	// template paths below are based on the app main directory
	$x->Template = 'templates/assessments_templateTV.html';
	$x->SelectedTemplate = 'templates/assessments_templateTVS.html';
	$x->TemplateDV = 'templates/assessments_templateDV.html';
	$x->TemplateDVP = 'templates/assessments_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `assessments`.`assessmentId`=membership_userrecords.pkValue and membership_userrecords.tableName='assessments' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `assessments`.`assessmentId`=membership_userrecords.pkValue and membership_userrecords.tableName='assessments' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`assessments`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: assessments_init
	$render=TRUE;
	if(function_exists('assessments_init')){
		$args=array();
		$render=assessments_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: assessments_header
	$headerCode='';
	if(function_exists('assessments_header')){
		$args=array();
		$headerCode=assessments_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: assessments_footer
	$footerCode='';
	if(function_exists('assessments_footer')){
		$args=array();
		$footerCode=assessments_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
