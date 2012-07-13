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
		$this->load->model( 'Model_vod' );
		$this->load->model( 'Model_news' );
		$this->load->model( 'Model_live_channel' );
		$this->load->library( 'storage' );
		$this->view->cache( 5 );

		// 隨選視訊條目
		$this->view->data["videos"] = $this->storage->get( array(
			'cache_name' => 'home---index',
			'callback'   => array( $this->Model_vod, 'get_vod' ),
			'params'     => array( 'limit' => 4 ),
		) );

		// 流水資訊流條目列
		$this->view->data['news_flows']['comprehensive'] = $this->Model_news->get_flow( array( // 綜合
				'fid' => array(
					'44', // 硬體設備評測分享
					'45', // 週邊設備新聞
					'54', // 最新消息
					'55', // 攻略推薦
					'56', // 精彩視頻
					'63', // msi盃
					'64', // d-girl選拔
				),
			) );
		$this->view->data['news_flows']['game_strategy'] = $this->Model_news->get_flow( array(
				'fid' => array( '55' ),
			) );
		$this->view->data['news_flows']['blue_posts']    = $this->Model_news->get_flow( array(
				'fid'    => array( '54' ),
				'typeid' => array( '22' ),
			) );
		$this->view->data['news_flows']['events']        = $this->Model_news->get_flow( array(
				'fid' => array( '63', '64' ),
			) );
		$this->view->data['news_flows']['hardware']      = $this->Model_news->get_flow( array(
				'fid' => array( '44', '45' ),
			) );
		$this->view->data['news_flows']['elite']         = $this->Model_news->get_flow( array(
				'fid'    => array( '54', '55', '56' ),
				'digest' => array( '1', '2', '3' ),
			) );

		// 流水資訊流條目分類
		$this->view->data['news_cata'] = array(
			'comprehensive' => '綜合',
			'game_strategy' => '攻略',
			'blue_posts'    => '藍帖',
			'events'        => '活動',
			'hardware'      => '硬體',
			'elite'         => '精選',
		);

		// 直播列表
		$this->view->data['live_channels'] = $this->Model_live_channel->get_d3_channels( array(
				'limit'     => 12,
				'game_type' => 'DiabloIII',
			) );

		// 輪播點點點條目
		$this->view->data['circle_loop'] = $this->Model_news->get_circle_loop();

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
