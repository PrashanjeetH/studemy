<?php
//


	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/membership_cache.php");
	include("$currDir/membership_cache_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('membership_cache');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "membership_cache";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`membership_cache`.`request`" => "request",
		"`membership_cache`.`request_ts`" => "request_ts",
		"`membership_cache`.`response`" => "response"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => 1,
		2 => '`membership_cache`.`request_ts`',
		3 => 3
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`membership_cache`.`request`" => "request",
		"`membership_cache`.`request_ts`" => "request_ts",
		"`membership_cache`.`response`" => "response"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`membership_cache`.`request`" => "Request",
		"`membership_cache`.`request_ts`" => "Request_ts",
		"`membership_cache`.`response`" => "Response"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`membership_cache`.`request`" => "request",
		"`membership_cache`.`request_ts`" => "request_ts",
		"`membership_cache`.`response`" => "response"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`membership_cache` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 0;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = false;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 0;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 0;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 0;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 0;
	$x->AllowPrintingDV = 0;
	$x->AllowCSV = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 0;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "membership_cache_view.php";
	$x->RedirectAfterInsert = "membership_cache_view.php?SelectedID=#ID#";
	$x->TableTitle = "Membership_cache";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`membership_cache`.`request`";

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("Request", "Request_ts", "Response");
	$x->ColFieldName = array('request', 'request_ts', 'response');
	$x->ColNumber  = array(1, 2, 3);

	// template paths below are based on the app main directory
	$x->Template = 'templates/membership_cache_templateTV.html';
	$x->SelectedTemplate = 'templates/membership_cache_templateTVS.html';
	$x->TemplateDV = 'templates/membership_cache_templateDV.html';
	$x->TemplateDVP = 'templates/membership_cache_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `membership_cache`.`request`=membership_userrecords.pkValue and membership_userrecords.tableName='membership_cache' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `membership_cache`.`request`=membership_userrecords.pkValue and membership_userrecords.tableName='membership_cache' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`membership_cache`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: membership_cache_init
	$render=TRUE;
	if(function_exists('membership_cache_init')){
		$args=array();
		$render=membership_cache_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: membership_cache_header
	$headerCode='';
	if(function_exists('membership_cache_header')){
		$args=array();
		$headerCode=membership_cache_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: membership_cache_footer
	$footerCode='';
	if(function_exists('membership_cache_footer')){
		$args=array();
		$footerCode=membership_cache_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>