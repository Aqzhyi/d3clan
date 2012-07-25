<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Api extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->view->append_title( '' );
		$this->view->layout( '' );
		$this->view->js_add( array(
		) );
		$this->view->css_add( array(
		) );
	}

	public function _remap( $sub_page = 'index', $page_params = array() ) {

		$this->view->title_routes(array(
			));
		$this->view->page( $sub_page, $page_params );
		$this->view->init( $this );
	}

	public function is_repeat( $params = array() ) {
		$this->load->model( 'Model_api' );

		$post_uri   = $_GET['post_uri'];
		$post_title = $_GET['post_title'];

		$is_repeat = $this->Model_api->is_repeat( array(
				'subject' => $post_title,
			) );

		if ( $is_repeat ) {
			$is_repeat = 'true';
		}
		else {
			$is_repeat = 'false';
		}

		$this->view->is_ajax_request = true;
		echo $_GET['callback'] . "($is_repeat);";
	}
}

//