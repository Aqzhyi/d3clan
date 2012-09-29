<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

// -----------------
// 缺省網站配置
// -----------------
$config['site_name']   = "暗盟《暗黑破壞神III》電競情報站";
$config['description'] = "由星盟電競團隊全力開發，全台唯一以《暗黑破壞神III》為主題之專屬網站，提供各類暗黑破壞神的全球新訊、直播頻道、交易平台、約戰中心，與電競賽事等專業服務。";
$config['keywords']    = "暗黑破壞神,暗黑,d3,diablo,blizzard,台灣暗盟,暗盟,暗盟論壇,暗盟遊戲,星盟";

// -----------------
// 缺省路徑
// -----------------
$config['js_static_path']  = "static/js/";
$config['css_static_path'] = "static/css/";

// -----------------
// 缺省應用程式版型
// -----------------
// 這是一個最底層的版型
// 子版型堆疊在此版型之上, 孫版型則堆疊在子版型上.
$config['application_layout_path'] = 'common/layout';

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

$config['css_common_files'] = array(
	// 'plugin/jQuery/jquery-ui-1.8.21.custom',
	'twitter-bootstrap/bootstrap',
	'twitter-bootstrap/darkstrap',
	'common/layout',
	'common/utils',
	'twitter-bootstrap/bootstrap.replenisher',
);
$config['css_common_links'] = array();

$config['js_common_files'] = array(
	'plugin/jQuery/jquery.1.7.2.min',
	// 'plugin/jQuery/jquery-ui-1.8.21.no.effects.min',
	// 'plugin/mustache/mustache.0.5.1-dev',
	'plugin/Underscore/underscore.1.3.3.min',
	'plugin/Backbone/backbone.0.9.2.min',
	'common/layout',
	'common/helper',
);
$config['js_common_links'] = array(
	// 'http://tw.battle.net/d3/static/js/tooltips.js',
);

//