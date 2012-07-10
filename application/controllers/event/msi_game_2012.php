<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Msi_game_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'template' );
		// $this->load->model( 'event/Girls_vote_2012_model' );
	}

	public function index() {
		// $this->view->data['some_key'] = 'some_data';
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => 'msi微星盃',
				'view'     => 'event/msi_game_2012/index',
				'js_files' => array(
					'event/msi_game_2012/index',
				),
				'css_files' => array(
					'event/msi_game_2012/index'
				),
			)
		);
	}
}

//
