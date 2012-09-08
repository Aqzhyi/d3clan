<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Lives extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'core/model_live_channels' );
		$this->view->title_append( '直播頻道' );
		$this->view->layout( 'lives/layout' );
		$this->view->js_add( array(
				'lives/index',
			) );
		$this->view->css_add( array(
				'lives/index',
			) );
		$this->view->cache( 10 );
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'    => '列表',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	/**
	 * 直播列表
	 *
	 * @return [type] [description]
	 */
	public function index() {
		$this->view->data['live_channels']['taiwan'] = $this->model_live_channels->get( array(
				'order_by'  => 'status asc, detect_by desc, viewer_amount desc',
				'location'  => '臺灣',
				'game_type' => 'DiabloIII',
			) );
		$this->view->data['live_channels']['else'] = $this->model_live_channels->get( array(
				'order_by'  => 'status asc, detect_by desc, viewer_amount desc',
				'location'  => '其他',
				'game_type' => 'DiabloIII',
			) );
	}

	/**
	 * 傳統版型 直播單頁
	 *
	 * @param integer $id [description]
	 * @return [type]      [description]
	 */
	public function channel( $id = 0 ) {

		$this->load->library( 'core/media' );

		$this->view->title( $this->view->data['channel_host']['detect_by'] );

		if ( empty( $id ) ) {
			show_404();
		}

		$this->view->data['channel_host'] = $this->model_live_channels->get( array(
				'first_row' => TRUE,
				'id'        => $id,
			) );
		$this->view->data['channel_host']['player_embed_code'] = $this->media->embed_vod( array(
				'type'    => $this->view->data['channel_host']['media_by'],
				'channel' => $this->view->data['channel_host']['detect_by'],
				'width'   => '100%',
				'height'  => 422,
			) );
		$this->view->data['channel_host']['chatroom_embed_code'] = $this->media->embed_chatroom( array(
				'type'    => $this->view->data['channel_host']['media_by'],
				'channel' => $this->view->data['channel_host']['detect_by'],
				'width'   => '100%',
				'height'  => 414,
			) );
		
		$this->view->layout( 'lives/channel' );
		$this->view->js_add( array(
				'lives/channel',
			) );
		$this->view->css_add( array(
				'lives/channel',
			) );
	}
}


//
