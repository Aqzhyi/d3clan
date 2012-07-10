<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
 * home controller
 *
 * @author aiyswu at gmail
 */
class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	// 首頁..
	public function index() {
		$this->load->model( 'Vod_model' );
		$this->load->model( 'News_model' );
		$this->load->model( 'Live_channel_model' );
		$this->view->cache( 10 );

		// 隨選視訊條目
		$this->view->data["videos"] = $this->Vod_model->get_vod( array( 
			'limit'     => 4,
		) );

		// print_r($this->view->data["videos"]);

		// 流水資訊流條目列
		$this->view->data['news_flows']['comprehensive'] = $this->News_model->get_flow(); // 綜合
		$this->view->data['news_flows']['game_strategy'] = $this->News_model->get_flow( array( 'fid' => array( '55' ) ) );
		$this->view->data['news_flows']['videos']        = $this->News_model->get_flow( array( 'fid' => array( '54' ) ) );
		$this->view->data['news_flows']['blue_posts']    = $this->News_model->get_flow( array( 'fid' => array( '63', '64' ) ) );
		$this->view->data['news_flows']['hardware']      = $this->News_model->get_flow( array( 'fid' => array( '44', '45' ) ) );
		$this->view->data['news_flows']['events']        = $this->News_model->get_flow( array( 'fid' => array( '54', '55', '56' ) ) );

		// 流水資訊流條目分類
		$this->view->data['news_cata'] = array(
			'comprehensive' => '綜合',
			'game_strategy' => '攻略',
			'blue_posts'    => '藍帖',
			'events'        => '活動',
			'hardware'      => '硬體',
			'elite'         => '精選(測試)',
		);

		// 直播列表
		$this->view->data['live_channels'] = $this->Live_channel_model->get_d3_channels( array( 
			'limit'     => 12,
			'game_type' => 'DiabloIII',
		) );

		// 輪播點點點條目
		$this->view->data['circle_loop'] = $this->News_model->get_circle_loop();

		// display
		$this->view->display(
			array(
				'title'     => null,
				'view'      => 'home/index',
				'js_files'  => array(
					'home/index',
					'plugin/circle_loop/base',
					// 'plugin/abgneImgCircle/base'
				),
				'css_files' => array(
					'home/index',
					'plugin/circle_loop/base',
					// 'plugin/abgneImgCircle/base'
				),
			)
		);
		
	}
}

//
//
