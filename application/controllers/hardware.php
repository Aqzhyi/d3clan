<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Hardware extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'Model_hardware' );
		$this->load->library( 'template' );
	}

	// 週邊設備
	public function index() {

		$this->view->data['mouse_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 1,11 ) // 滑鼠
			) );
		$this->view->data['keyboard_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 2,10 ) // 鍵盤
			) );
		$this->view->data['headphone_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 3,12 ) // 耳機
			) );
		$this->view->data['else_flow'] = $this->Model_hardware->get_thread( array(
				'not_typeid' => array( 1,2,3,10,11,12 ) // 其它
			) );

		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'     => '週邊設備',
				'view'      => 'hardware/layout',
				'js_files'  => array(
					'hardware/layout',
				),
				'css_files' => array(
					'hardware/layout',
				),
			)
		);

	}
}

//
