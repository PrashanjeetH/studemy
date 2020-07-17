<?php
//



error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(!defined('datalist_db_encoding')) define('datalist_db_encoding', 'UTF-8');
if(!defined('maxSortBy')) define('maxSortBy', 4);
if(!defined('empty_lookup_value')) define('empty_lookup_value', '{empty_value}');

if(function_exists('date_default_timezone_set')) @date_default_timezone_set('Asia/Kolkata');
if(function_exists('set_magic_quotes_runtime')) @set_magic_quotes_runtime(0);

$GLOBALS['filter_operators'] = array(
	'equal-to' => '<=>',
	'not-equal-to' => '!=',
	'greater-than' => '>',
	'greater-than-or-equal-to' => '>=',
	'less-than' => '<',
	'less-than-or-equal-to' => '<=',
	'like' => 'like',
	'not-like' => 'not like',
	'is-empty' => 'isEmpty',
	'is-not-empty' => 'isNotEmpty'
);

$currDir = dirname(__FILE__);
include("$currDir/settings-manager.php");
detect_config();
migrate_config();

include("$currDir/config.php");
include("$currDir/db.php");
include("$currDir/ci_input.php");
include("$currDir/datalist.php");
include("$currDir/incCommon.php");
include("$currDir/admin/incFunctions.php");

	// detecting classes not included above
	@spl_autoload_register(function($class) {
		$app_dir = dirname(__FILE__);
		@include("{$app_dir}/resources/lib/{$class}.php");
	});

	ob_start();


	/* trim $_POST, $_GET, $_REQUEST */
	if(count($_POST)) $_POST = array_trim($_POST);
	if(count($_GET)) $_GET = array_trim($_GET);
	if(count($_REQUEST)) $_REQUEST = array_trim($_REQUEST);

	// include global hook functions
	@include_once("$currDir/hooks/__global.php");

	// check sessions config
	$noPathCheck = true;
	$arrPath = explode(';', ini_get('session.save_path'));
	$save_path = $arrPath[count($arrPath)-1];
	if(!$noPathCheck && !is_dir($save_path)){
		?>
		<center>
			<div class="alert alert-danger">
				Your site is not configured to support sessions correctly. Please edit your php.ini file and change the value of <i>session.save_path</i> to a valid path.
			</div>
		</center>
		<?php
		exit;
	}
	if(session_id()){ session_write_close(); }
	$configured_save_handler = @ini_get('session.save_handler');
	if($configured_save_handler != 'memcache' && $configured_save_handler != 'memcached')
		@ini_set('session.save_handler', 'files');
	@ini_set('session.serialize_handler', 'php');
	@ini_set('session.use_cookies', '1');
	@ini_set('session.use_only_cookies', '1');
	@ini_set('session.cookie_httponly', '1');
	@ini_set('session.use_strict_mode', '1');
	@session_cache_expire(2);
	@session_cache_limiter($_SERVER['REQUEST_METHOD'] == 'POST' ? 'private' : 'nocache');
	@session_name('db_studemy');
	session_start();

	// check if membership system exists
	setupMembership();

	// silently apply db changes, if any
	@include_once("$currDir/updateDB.php");

	// do we have a login request?
	logInMember();

	// convert expanded sorting variables, if provided, to SortField and SortDirection
	$postedOrderBy = array();
	for($i = 0; $i < maxSortBy; $i++){
		if(isset($_REQUEST["OrderByField$i"])){
			$sd = ($_REQUEST["OrderDir$i"] == 'desc' ? 'desc' : 'asc');
			if($sfi = intval($_REQUEST["OrderByField$i"])){
				$postedOrderBy[] = array($sfi => $sd);
			}
		}
	}
	if(count($postedOrderBy)){
		$_REQUEST['SortField'] = '';
		$_REQUEST['SortDirection'] = '';
		foreach($postedOrderBy as $obi){
			$sfi = ''; $sd = '';
			foreach($obi as $sfi => $sd);
			$_REQUEST['SortField'] .= "$sfi $sd,";
		}
		$_REQUEST['SortField'] = substr($_REQUEST['SortField'], 0, -2 - strlen($sd));
		$_REQUEST['SortDirection'] = $sd;
	}elseif($_REQUEST['apply_sorting']){
		/* no sorting and came from filters page .. so clear sorting */
		$_REQUEST['SortField'] = $_REQUEST['SortDirection'] = '';
	}

	// include nav menu links
	@include_once("$currDir/hooks/links-navmenu.php");
