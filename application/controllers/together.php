<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Together extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	// 週邊設備
	public function index() {
		// $this->view->data["some_key"] = "some_data";
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => '約團專區',
				'view'     => 'together/index',
				'js_files' => array(
					'together/index',
				),
				'css_files' => array(
					'together/index'
				),
			)
		);		

	}
}

//
