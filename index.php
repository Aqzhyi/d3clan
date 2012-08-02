<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
$development_hosts = array(
	'127.0.0.1',
	'localhost',
	'server.d3clan.tw',
	'dev.d3clan.tw',
	'd3.queenbyer.com',
	'queenbyer.com',
);

$development_ip = array(
	// '114.34.187.28'
);

if ( in_array( $_SERVER['HTTP_HOST'], $development_hosts ) ) {
	define( 'ENVIRONMENT', 'development' );
}
else {
	if ( in_array($_SERVER['REMOTE_ADDR'], $development_ip) ) {
		define( 'ENVIRONMENT', 'development' );
	}
	else {
		define( 'ENVIRONMENT', 'production' );
	}
}

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
$application_folder = 'application';


/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
// include "$application_folder/config/error_reporting.php";

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
// The directory name, relative to the "controllers" folder.  Leave blank
// if your controller is not in a sub-folder within the "controllers" folder
// $routing['directory'] = '';

// The controller class file name.  Example:  Mycontroller
// $routing['controller'] = '';

// The controller function you wish to be called.
// $routing['function'] = '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';

// -----------------
// 缺省網站配置
// -----------------
$assign_to_config['site_name']   = "暗盟《暗黑破壞神III》電競情報站";
$assign_to_config['description'] = "由星盟電競團隊全力開發，全台唯一以《暗黑破壞神III》為主題之專屬網站，提供各類暗黑破壞神的全球新訊、直播頻道、交易平台、約戰中心，與電競賽事等專業服務。";
$assign_to_config['keywords']    = "暗黑破壞神,暗黑,d3,diablo,blizzard,台灣暗盟,暗盟,暗盟論壇,暗盟遊戲,星盟";

// -----------------
// 缺省路徑
// -----------------
$assign_to_config['js_static_path']  = "static/js/";
$assign_to_config['css_static_path'] = "static/css/";

// -----------------
// 缺省應用程式版型
// -----------------
// 這是一個最底層的版型
// 子版型堆疊在此版型之上, 孫版型則堆疊在子版型上.
$assign_to_config['application_layout_path'] = 'common/layout';

// -----------------
// 缺省靜態檔案
// -----------------
// 	如 css 或 js 檔案等, 分為 load 與 link 兩種形態.
// 	load 形態代表檔案儲存於本地, 並透過 minify 函式庫壓縮成一個檔案, 交由 view 一次引入全部.
// 		$config['css_common_files']
// 		$config['js_common_files']
// 	link 形態則為傳統單檔引入, 因此檔案多時, 引入就多, 速度會被拖慢.
// 		$config['css_common_links']
// 		$config['js_common_links']

$assign_to_config['css_common_files'] = array(
	// 'plugin/jQuery/jquery-ui-1.8.21.custom',
	'twitter-bootstrap/bootstrap',
	'twitter-bootstrap/darkstrap',
	'common/layout',
	'common/utils',
	'twitter-bootstrap/bootstrap.replenisher',
);
$assign_to_config['css_common_links'] = array();

$assign_to_config['js_common_files'] = array(
	'plugin/jQuery/jquery.1.7.2.min',
	// 'plugin/jQuery/jquery-ui-1.8.21.no.effects.min',
	// 'plugin/mustache/mustache.0.5.1-dev',
	'plugin/Underscore/underscore.1.3.3.min',
	'plugin/Backbone/backbone.0.9.2.min',
	'common/layout',
);
$assign_to_config['js_common_links'] = array(
	// 'http://tw.battle.net/d3/static/js/tooltips.js',
);

// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if ( defined( 'STDIN' ) ) {
	chdir( dirname( __FILE__ ) );
}

if ( realpath( $system_path ) !== FALSE ) {
	$system_path = realpath( $system_path ).'/';
}

// ensure there's a trailing slash
$system_path = rtrim( $system_path, '/' ).'/';

// Is the system path correct?
if ( ! is_dir( $system_path ) ) {
	exit( "Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo( __FILE__, PATHINFO_BASENAME ) );
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define( 'SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );

// The PHP file extension
// this global constant is deprecated.
define( 'EXT', '.php' );

// Path to the system folder
define( 'BASEPATH', str_replace( "\\", "/", $system_path ) );

// Path to the front controller (this file)
define( 'FCPATH', str_replace( SELF, '', __FILE__ ) );

// Name of the "system folder"
define( 'SYSDIR', trim( strrchr( trim( BASEPATH, '/' ), '/' ), '/' ) );


// The path to the "application" folder
if ( is_dir( $application_folder ) ) {
	define( 'APPPATH', $application_folder.'/' );
}
else {
	if ( ! is_dir( BASEPATH.$application_folder.'/' ) ) {
		header("Content-Type:text/html; charset=utf-8");
		exit( "更新中，請稍後。 <a href='https://www.facebook.com/D3clan.tw'>暗盟粉絲頁</a>" );
	}

	define( 'APPPATH', BASEPATH.$application_folder.'/' );
}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */
