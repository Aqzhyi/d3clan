<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class About extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		// $this->view->data['some_key'] = 'some_data';
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => '關於暗盟',
				'view'     => 'about/layout',
				'js_files' => array(
					'about/layout',
				),
				'css_files' => array(
					'about/layout'
				),
			)
		);
	}
}

//