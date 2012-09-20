<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class About extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->view->layout( 'about/layout' );
		$this->view->js( array(
				'about/layout',
			) );
		$this->view->css( array(
				'about/layout',
			) );
		$this->view->cache( 10 );
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'    => '關於暗盟',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}
}

//