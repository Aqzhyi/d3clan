<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Hardware extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	// 週邊設備
	public function index() {
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'     => '週邊設備',
				'view'      => 'hardware/index',
				'js_files'  => array(
					'hardware/index',
				),
				'css_files' => array(
					'hardware/index',
				),
			)
		);

	}
}

//
