<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Vod extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index( $type = NULL, $roll = NULL ) {

		$this->load->library( 'media' );
		$this->load->library( 'storage' );
		$this->load->model( 'Vod_model' );

		// 取論壇精彩vod推薦
		$_vod_list = $this->storage->get( array(
			'cache_name' => 'vod---index',
			'callback'   => array( $this->Vod_model, 'get_vod' ),
			'params'     => array( 'limit' => 16 ),
		) );

		if ( ! empty( $type ) and ! empty( $roll ) ) {
			$this->view->data['playing'] = array(
				array(
					'first_video_type' => $type,
					'first_video_code' => $roll,
				)
			);
		}
		else {
			$this->view->data['playing'] = array_slice( $_vod_list, 0, 1 );
		}
		
		// 其他推薦
		$this->view->data["videos_right_list"]  = array_slice( $_vod_list, 0, 4 );
		$this->view->data["videos_bottom_list"] = array_slice( $_vod_list, 4, 1000 );

		// 輸出
		$this->view->cache( 15 );
		$this->view->display(
			array(
				'title'    => "隨選視訊影片",
				'view'     => 'vod/index',
				'js_files' => array(
					'vod/index',
				),
				'css_files' => array(
					'vod/index'
				),
			)
		);

	}
}

//
