<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper( 'form' );
		$this->view->layout( 'admin/layout' );
		$this->view->css_add( 'admin/common' );
		$this->view->cache( 0 );

		// if ( ! $this->user->auth( 21 ) ) show_404(); // 直播頻道管理大師
		// if ( ! $this->user->auth( 22 ) ) show_404(); // 網站首頁管理大師 // 必備
		// if ( ! $this->user->auth( 23 ) ) show_404(); // OCR封閉測試
		// if ( ! $this->user->auth( 24 ) ) show_404(); // 週邊設備管理大師
	}

	public function _remap( $method = 'index', $params = array() ) {

		if ( ! $this->user->auth( 22 ) ) show_404();

		$this->view->title_routes( array(
				'index'         => '通用後台管理頁面',
				'live_channels' => '直播頻道管理',
				'hardware'      => '週邊設備管理',
				'ad_banners'    => '270x60廣告',
				'home_circle'   => '首頁四輪播',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	// 通用後台管理頁面
	public function index() {

		$this->view->js_add( 'admin/index' );
		$this->view->css_add( 'admin/index' );
	}

	// 270x60廣告管理
	public function ad_banners( $setting = array() ) {

		$this->load->library( 'ads' );
		
		$this->view->data['ads'] = $this->ads->get( array(
				'case' => '270x60'
			) );

		$this->view->js_add( 'admin/ad_banners' );
		$this->view->css_add( 'admin/ad_banners' );
	}

	// 週邊設備管理
	public function hardware( $setting = array() ) {

		if ( ! $this->user->auth( 24 ) ) show_404();

		$this->load->library( 'ads' );
		
		$this->view->data['flows'] = array(
				'hardware_mouse'     => $this->ads->get( array( 'case' => 'hardware_mouse' ) ),
				'hardware_keyboard'  => $this->ads->get( array( 'case' => 'hardware_keyboard' ) ),
				'hardware_headphone' => $this->ads->get( array( 'case' => 'hardware_headphone' ) ),
				'hardware_else'      => $this->ads->get( array( 'case' => 'hardware_else' ) ),
			);

		$this->view->js_add( 'admin/hardware' );
		$this->view->css_add( 'admin/hardware' );
	}

	// 直播頻道管理
	public function live_channels() {
		$this->load->model( 'Model_live_channel' );
		$this->view->data['live_channels'] = $this->Model_live_channel->get_d3_channels( array(
				'limit' => 200,
				'game_type' => 'DiabloIII',
			) );
		$this->view->js_add( 'admin/live_channels' );
		$this->view->css_add( 'admin/live_channels' );
	}

	// 首頁四輪播管理
	public function home_circle( $setting = array() ) {

		$this->load->model( 'Model_news' );
		$this->view->data['home_4_circle'] = $this->Model_news->get_circle_loop();
		$this->view->js_add( 'admin/home_circle' );
		$this->view->css_add( 'admin/home_circle' );
	}

	// ---------------------------------------------------------
	// AJAX集
	public function ajax() {
		
		$this->load->library( 'ajax' );
		$this->ajax->uri_routes( array(
				'ad-banners'   => 'ajax_ad_banners',
				'home-circle'  => 'ajax_home_circle',
				'live-channel' => 'ajax_live_channel',
				'hardware'     => 'ajax_hardware',
			) );

		$this->ajax->init( $this );
	}

	public function ajax_hardware( $setting = array() ) {
		
		$this->load->model( 'Model_ad' );

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'DELETE':
			echo $this->Model_ad->delete( $this->ajax->uris );
			break;

		case 'POST':
			$post = array_merge( array(
					'type' => 'img',
				), $this->input->post() );

			echo $this->Model_ad->add( array(
					'data' => $this->input->post(),
				) );
			break;
		}
	}

	public function ajax_ad_banners( $setting = array() ) {

		$this->load->model( 'Model_ad' );

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'DELETE':
			echo $this->Model_ad->delete( $this->ajax->uris );
			break;

		case 'POST':
			echo $this->Model_ad->add( array(
					'data' => $this->input->post(),
				) );
			break;
		}
	}

	public function ajax_home_circle( $setting = array() ) {

		$this->load->model( 'Model_ad' );

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'DELETE':
			echo $this->Model_ad->delete( $this->ajax->uris );
			break;

		case 'POST':
			$post = array_merge( array(
					'case' => 'home_4_circle',
					'type' => 'img',
				), $this->input->post() );

			echo $this->Model_ad->add( array(
					'data' => $post,
				) );
			break;
		}
	}

	public function ajax_live_channel( $setting = array() ) {

		if ( ! $this->user->auth( 21 ) ) {
			show_404();
		}

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'DELETE':
			$this->load->model( 'Model_live_channel' );

			$this->Model_live_channel->delete( $this->ajax->uris );
			break;

		case 'POST':

			$this->load->model( 'Model_live_channel' );

			$this->Model_live_channel->post_channel( $this->input->post() );
			break;
		}
	}
}

//
//
