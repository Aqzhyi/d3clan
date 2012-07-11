<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// 處理<br />\r\n
$hook['display_override'][] = array(
	'class'    => 'site_output_hook',
	'function' => 'compress',
	'filename' => 'site_output_hook.php',
	'filepath' => 'hooks',
	'params'   => array( 'output_display' => FALSE )
);

// 處理<img>缺省alt=""
$hook['display_override'][] = array(
	'class'    => 'site_output_hook',
	'function' => 'images_auto_set',
	'filename' => 'site_output_hook.php',
	'filepath' => 'hooks',
	'params'   => array( 'output_display' => TRUE )
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
