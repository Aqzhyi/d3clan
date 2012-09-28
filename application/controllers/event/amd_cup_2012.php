<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class amd_cup_2012 extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->view->title_append( 'AMD盃之打寶列車長' );
		$this->view->layout( 'event/amd_cup_2012/layout' );
		$this->view->js( 'event/amd_cup_2012/layout' );
		$this->view->css( 'event/amd_cup_2012/layout' );
	}

	public function _remap() {

		$this->view->title_routes( array(
				'index' => '活動簡介',
				'news'  => '活動新聞',
				'live'  => '活動LIVE',
				'vote'  => '我要投票',
				'vod'   => '活動影片',
			) );
		$this->view->page( func_get_arg(0), func_get_arg(1) );
		$this->view->init( $this );
	}

	public function index( $setting = array() ) {
		
		$this->view->js('event/amd_cup_2012/index');
	}

	public function news() {

		$this->load->model( 'core/model_news' );

		$this->view->data['news_flows'] = $this->model_news->get_flow( array(
				'fid' => array( '67', ),
			) );
	}
}

//