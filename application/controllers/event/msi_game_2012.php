<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Msi_game_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'template' );
		// $this->load->model( 'event/Model_girls_vote_2012' );
	}

	public function index( $page = 'index' ) {
		$this->view->data['main_diy'] = $page;

		switch ( $page ) {
		case 'news'    : $this->_page_news(); break;
		case 'contest' : $this->_page_contest(); break;
		}

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

	private function _page_news( $setting = array() ) {
		$this->load->model( 'Model_news' );
		$this->view->data['data']['news_flow'] = $this->Model_news->get_flow( array(
				'fid' => array( 63 ),
				'typeid' => array( 31, 32 ),
			) );

		return $this;
	}

	private function _page_contest( $setting = array() ) {
		$this->load->model( 'Model_news' );
		$this->view->data['data']['news_flow'] = $this->Model_news->get_flow( array(
				'fid' => array( 63 ),
				'typeid' => array( 33 ),
			) );

		return $this;
	}
}

//
