<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Msi_game_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'template' );
		// $this->load->model( 'event/Girls_vote_2012_model' );
	}

	public function index( $page = 'index' ) {
		$this->view->data['main_diy'] = $page;
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => 'msi微星盃',
				'view'     => "event/msi_game_2012/layout",
				'js_files' => array(
					'event/msi_game_2012/layout',
					"event/msi_game_2012/$page",
				),
				'css_files' => array(
					'event/msi_game_2012/layout',
					"event/msi_game_2012/$page",
				),
			)
		);
	}

	public function signup() {
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => '微星盃 - 賽事報名',
				'view'     => 'event/msi_game_2012/signup',
				'js_files' => array(
					'event/msi_game_2012/signup',
				),
				'css_files' => array(
					'event/msi_game_2012/signup'
				),
			)
		);

	}
}

//
