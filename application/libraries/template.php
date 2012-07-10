<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Template {

	public function __construct() {
		
	}

	public function fetch( $template = '', $data = array() ) {
		$smarty = $this->create_smarty_lib();
		$smarty->assign( 'data', $data );
		return $smarty->fetch( $template . '.html' );
	}

	private function create_smarty_lib() {
		require_once APPPATH . 'third_party/Smarty-3.1.11/libs/Smarty.class.php';

		$smarty = new Smarty();
		$smarty->left_delimiter  = '{';
		$smarty->right_delimiter = '}';
		$smarty->compile_dir     = APPPATH . 'cache/smarty_compile_dir';
		$smarty->cache_dir       = APPPATH . 'cache/smarty_cache_dir';
		$smarty->template_dir    = APPPATH . 'views';
		$smarty->caching         = FALSE;
		
		if ( ! file_exists( $smarty->compile_dir ) ) {
			mkdir( $smarty->compile_dir, 0777, true );
		}
		if ( ! file_exists( $smarty->cache_dir ) ) {
			mkdir( $smarty->cache_dir, 0777, true );
		}

		return $smarty;
	}
}

//
