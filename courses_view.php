<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/courses.php");
	include("$currDir/courses_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('courses');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "courses";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`courses`.`courseId`" => "courseId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`courses`.`courseName`" => "courseName",
		"`courses`.`courseCode`" => "courseCode",
		"if(CHAR_LENGTH(`courses`.`link`)>15, concat(left(`courses`.`link`,15),' ...'), `courses`.`link`)" => "link",
		"IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') /* Teacher */" => "teacher",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subject */" => "subjects",
		"if(CHAR_LENGTH(`courses`.`description`)>50, concat(left(`courses`.`description`,50),' ...'), `courses`.`description`)" => "description",
		"`courses`.`file`" => "file",
		"FORMAT(`courses`.`amount`, 0)" => "amount",
		"if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'')" => "dateUploaded",
		"`courses`.`isActivate`" => "isActivate",
		"`courses`.`isApproved`" => "isApproved"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`courses`.`courseId`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => '`teachers1`.`firstname`',
		7 => '`subjects1`.`subjectName`',
		8 => 8,
		9 => 9,
		10 => '`courses`.`amount`',
		11 => '`courses`.`dateUploaded`',
		12 => '`courses`.`isActivate`',
		13 => '`courses`.`isApproved`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`courses`.`courseId`" => "courseId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`courses`.`courseName`" => "courseName",
		"`courses`.`courseCode`" => "courseCode",
		"`courses`.`link`" => "link",
		"IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') /* Teacher */" => "teacher",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subject */" => "subjects",
		"`courses`.`description`" => "description",
		"`courses`.`file`" => "file",
		"FORMAT(`courses`.`amount`, 0)" => "amount",
		"if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'')" => "dateUploaded",
		"`courses`.`isActivate`" => "isActivate",
		"`courses`.`isApproved`" => "isApproved"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`courses`.`courseId`" => "CourseId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "Institute",
		"`courses`.`courseName`" => "Name",
		"`courses`.`courseCode`" => "Course Code",
		"`courses`.`link`" => "Link",
		"IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') /* Teacher */" => "Teacher",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subject */" => "Subject",
		"`courses`.`description`" => "Description",
		"`courses`.`file`" => "File",
		"`courses`.`amount`" => "Amount",
		"`courses`.`dateUploaded`" => "DateUpload",
		"`courses`.`isActivate`" => "IsActivate",
		"`courses`.`isApproved`" => "IsApproved"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`courses`.`courseId`" => "courseId",
		"IF(    CHAR_LENGTH(`institutes1`.`instituteCode`) || CHAR_LENGTH(`institutes1`.`instituteName`), CONCAT_WS('',   `institutes1`.`instituteCode`, '-', `institutes1`.`instituteName`), '') /* Institute */" => "instituteNumber",
		"`courses`.`courseName`" => "courseName",
		"`courses`.`courseCode`" => "courseCode",
		"`courses`.`link`" => "Link",
		"IF(    CHAR_LENGTH(`teachers1`.`firstname`), CONCAT_WS('',   `teachers1`.`firstname`), '') /* Teacher */" => "teacher",
		"IF(    CHAR_LENGTH(`subjects1`.`subjectName`), CONCAT_WS('',   `subjects1`.`subjectName`), '') /* Subject */" => "subjects",
		"`courses`.`description`" => "Description",
		"`courses`.`file`" => "file",
		"FORMAT(`courses`.`amount`, 0)" => "amount",
		"if(`courses`.`dateUploaded`,date_format(`courses`.`dateUploaded`,'%d/%m/%Y'),'')" => "dateUploaded",
		"`courses`.`isActivate`" => "isActivate",
		"`courses`.`isApproved`" => "isApproved"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'instituteNumber' => 'Institute', 'teacher' => 'Teacher', 'subjects' => 'Subject');

	$x->QueryFrom = "`courses` LEFT JOIN `institutes` as institutes1 ON `institutes1`.`instituteNumber`=`courses`.`instituteNumber` LEFT JOIN `teachers` as teachers1 ON `teachers1`.`id`=`courses`.`teacher` LEFT JOIN `subjects` as subjects1 ON `subjects1`.`subjectid`=`courses`.`subjects` ";
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
	$x->ScriptFileName = "courses_view.php";
	$x->RedirectAfterInsert = "courses_view.php";
	$x->TableTitle = "Courses";
	$x->TableIcon = "./assets/img/course.png";
	$x->PrimaryKey = "`courses`.`courseId`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Institute", "Name", "Course Code", "Link", "Teacher", "Subject", "Description", "File", "Amount", "DateUpload");
	$x->ColFieldName = array('instituteNumber', 'courseName', 'courseCode', 'link', 'teacher', 'subjects', 'description', 'file', 'amount', 'dateUploaded');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11);

	// template paths below are based on the app main directory
	$x->Template = 'templates/courses_templateTV.html';
	$x->SelectedTemplate = 'templates/courses_templateTVS.html';
	$x->TemplateDV = 'templates/courses_templateDV.html';
	$x->TemplateDVP = 'templates/courses_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `courses`.`courseId`=membership_userrecords.pkValue and membership_userrecords.tableName='courses' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `courses`.`courseId`=membership_userrecords.pkValue and membership_userrecords.tableName='courses' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`courses`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: courses_init
	$render=TRUE;
	if(function_exists('courses_init')){
		$args=array();
		$render=courses_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: courses_header
	$headerCode='';
	if(function_exists('courses_header')){
		$args=array();
		$headerCode=courses_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: courses_footer
	$footerCode='';
	if(function_exists('courses_footer')){
		$args=array();
		$footerCode=courses_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
