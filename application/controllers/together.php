<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Together extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	// 週邊設備
	public function index() {
		$this->load->model( 'Model_news' );
		$this->view->data['news_flow'] = $this->Model_news->get_flow( array(
				'fid'            => array( 39 ),
				'limit'          => 90,
				'exclude_typeid' => TRUE,
			) );

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
