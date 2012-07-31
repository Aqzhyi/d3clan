<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper( 'form' );
		$this->load->library( 'Json' );
		$this->view->layout( 'admin/layout' );
		$this->view->css_add( 'admin/diy' );
		$this->view->cache( 0 );

		// if ( ! $this->user->auth( 21 ) ) show_404(); // 直播頻道管理大師
		// if ( ! $this->user->auth( 22 ) ) show_404(); // 網站首頁管理大師
		// if ( ! $this->user->auth( 23 ) ) show_404(); // OCR封閉測試
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'         => '通用後台管理頁面',
				'live_channels' => '直播頻道管理',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	public function index() {

		if ( ! $this->user->auth( 22 ) ) show_404();

		$this->view->js_add( 'admin/index' );
		$this->view->css_add( 'admin/index' );
	}

	public function live_channels() {
		$this->load->model( 'Model_live_channel' );
		$this->view->data['live_channels'] = $this->Model_live_channel->get_d3_channels( array(
				'limit' => 200,
				'game_type' => 'DiabloIII',
			) );
		$this->view->js_add( 'admin/live-channels' );
		$this->view->css_add( 'admin/live-channels' );
	}

	/**
	 * 直播頻道CRUD
	 *
	 * @param [type]  $id [description]
	 * @return [type]     [description]
	 */
	public function channel( $id ) {

		if ( ! $this->user->auth( 21 ) ) {
			show_404();
		}

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'POST':
			if ( $this->input->is_ajax_request() ) {
				$this->_post_live_channel();
			}
			break;
		case 'DELETE':
			if ( $this->input->is_ajax_request() ) {
				$this->_delete_live_channel( $id );
			}
			break;
		default:
			echo "method: {$_SERVER['REQUEST_METHOD']}";
			break;
		}
	}

	/**
	 * 新增一個直播頻道
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _post_live_channel() {
		$this->load->model( 'Model_live_channel' );

		$this->Model_live_channel->post_channel( $this->input->post() );

		return $this;
	}

	/**
	 * 刪除指定ID的直播頻道
	 *
	 * @param integer $id [description]
	 * @return [type]      [description]
	 */
	private function _delete_live_channel( $id = 0 ) {

		$this->load->model( 'Model_live_channel' );

		$this->Model_live_channel->delete( array(
				'id' => $id,
			) );
	}
}

//
//
