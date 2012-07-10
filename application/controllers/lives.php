<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Lives extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'Live_channel_model' );
		$this->load->library( 'template' );
	}

	/**
	 * 直播列表
	 *
	 * @return [type] [description]
	 */
	public function index() {
		$this->view->data['live_channels']['taiwan'] = $this->Live_channel_model->get_d3_channels( array(
				'order_by' => 'status asc, live_name desc, viewer_count desc',
				'location' => '臺灣',
				'game_type' => 'DiabloIII',
			) );
		$this->view->data['live_channels']['else'] = $this->Live_channel_model->get_d3_channels( array(
				'order_by' => 'status asc, live_name desc, viewer_count desc',
				'location' => '其他',
				'game_type' => 'DiabloIII',
			) );
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => "直播頻道列表",
				'view'     => 'lives/index',
				'js_files' => array(
					'lives/index',
				),
				'css_files' => array(
					'lives/index'
				),
			)
		);
	}

	/**
	 * 傳統版型 直播單頁
	 *
	 * @param integer $id [description]
	 * @return [type]      [description]
	 */
	public function channel( $id = 0 ) {

		$this->load->library( 'media' );

		if ( empty( $id ) ) {
			show_404();
		}

		$this->view->data['channel_host'] = $this->Live_channel_model->get_d3_channels( array(
				'first_row' => TRUE,
				'id'        => $id,
			) );
		$this->view->data['channel_host']['player_embed_code'] = $this->media->embed_vod( array(
				'type'    => $this->view->data['channel_host']['type'],
				'channel' => $this->view->data['channel_host']['live_account'],
				'width'   => '100%',
				'height'  => 422,
			) );
		$this->view->data['channel_host']['chatroom_embed_code'] = $this->media->embed_chatroom( array(
				'type'    => $this->view->data['channel_host']['type'],
				'channel' => $this->view->data['channel_host']['live_account'],
				'width'   => '100%',
				'height'  => 414,
			) );
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => $this->view->data['channel_host']['live_name'] . '的直播頻道',
				'view'     => 'lives/channel',
				'js_files' => array(
					'lives/channel',
				),
				'css_files' => array(
					'lives/channel'
				),
			)
		);

		return $this;
	}
}


//
