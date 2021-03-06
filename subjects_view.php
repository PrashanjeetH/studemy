<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/subjects.php");
	include("$currDir/subjects_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('subjects');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "subjects";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`subjects`.`subjectid`" => "subjectid",
		"`subjects`.`subjectName`" => "subjectName"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`subjects`.`subjectid`',
		2 => '`subjects`.`subjectName`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`subjects`.`subjectid`" => "subjectid",
		"`subjects`.`subjectName`" => "subjectName"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`subjects`.`subjectid`" => "Subjectid",
		"`subjects`.`subjectName`" => "SubjectName"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`subjects`.`subjectid`" => "subjectid",
		"`subjects`.`subjectName`" => "subjectName"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`subjects` ";
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
	$x->ScriptFileName = "subjects_view.php";
	$x->RedirectAfterInsert = "subjects_view.php";
	$x->TableTitle = "Subjects";
	$x->TableIcon = "./assets/img/subjects.png";
	$x->PrimaryKey = "`subjects`.`subjectid`";

	$x->ColWidth   = array(  150);
	$x->ColCaption = array("SubjectName");
	$x->ColFieldName = array('subjectName');
	$x->ColNumber  = array(2);

	// template paths below are based on the app main directory
	$x->Template = 'templates/subjects_templateTV.html';
	$x->SelectedTemplate = 'templates/subjects_templateTVS.html';
	$x->TemplateDV = 'templates/subjects_templateDV.html';
	$x->TemplateDVP = 'templates/subjects_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `subjects`.`subjectid`=membership_userrecords.pkValue and membership_userrecords.tableName='subjects' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `subjects`.`subjectid`=membership_userrecords.pkValue and membership_userrecords.tableName='subjects' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`subjects`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: subjects_init
	$render=TRUE;
	if(function_exists('subjects_init')){
		$args=array();
		$render=subjects_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: subjects_header
	$headerCode='';
	if(function_exists('subjects_header')){
		$args=array();
		$headerCode=subjects_header($x->ContentType, getMemberInfo(), $args);
	}
	if(!$headerCode){
		include_once("$currDir/header.php");
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: subjects_footer
	$footerCode='';
	if(function_exists('subjects_footer')){
		$args=array();
		$footerCode=subjects_footer($x->ContentType, getMemberInfo(), $args);
	}
	if(!$footerCode){
		include_once("$currDir/footer.php");
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>
