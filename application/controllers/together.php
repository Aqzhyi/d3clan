<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Together extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->view->layout( 'together/layout' );
		$this->view->js_add( array(
				'together/index',
			) );
		$this->view->css_add( array(
				'together/index',
			) );
		$this->view->cache( 10 );
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'    => '約團專區',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	// 週邊設備
	public function index() {
		$this->load->model( 'Model_news' );
		$this->view->data['news_flow'] = $this->Model_news->get_flow( array(
				'fid'            => array( 39 ),
				'limit'          => 90,
				'exclude_typeid' => TRUE,
			) );
	}
}

//
