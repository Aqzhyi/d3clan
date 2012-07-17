<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Msi_game_2012 extends CI_Controller {

	/**
	 * 建構式用來設定本控制器的標準配置
	 */
	public function __construct() {
		parent::__construct();
		$this->view->append_title( 'msi微星盃' );
		$this->view->layout( 'event/msi_game_2012/layout' );
		$this->view->js_add( 'event/msi_game_2012/layout' );
		$this->view->css_add( 'event/msi_game_2012/layout' );
		$this->view->cache( 10 );
	}

	public function _remap( $page_name = 'index', $params = array() ) {

		$this->view->title_routes(array(
				'index'    => '賽事簡介',
				'product'  => '產品介紹',
				'news'     => '賽事新聞',
				'live'     => '賽事LIVE',
				'signup'   => '賽事報名',
				'contest'  => '競賽影片',
				'tutorial' => '錄影教學',
			));

		if ( method_exists( $this, $page_name ) ) return call_user_func_array( array( $this, $page_name ), $params );

		$this->view->page( "$page_name/index" );
		$this->view->show();
	}

	public function news( $setting = array() ) {
		$this->view->title( '賽事新聞' );
		$this->load->model( 'Model_news' );
		$this->view->data['news_flow'] = $this->Model_news->get_flow( array(
				'fid' => array( 63 ),
				'typeid' => array( 31, 32 ),
			) );
		$this->view->page( 'news/index' );
		$this->view->show();
		return $this;
	}

	public function contest( $setting = array() ) {
		$this->view->title( '競賽影片' );
		$this->load->model( 'Model_news' );
		$this->view->data['data']['news_flow'] = $this->Model_news->get_flow( array(
				'fid' => array( 63 ),
				'typeid' => array( 33 ),
			) );
		$this->view->page( 'contest/index' );
		$this->view->show();
		return $this;
	}
}

//
