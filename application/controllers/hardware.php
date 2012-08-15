<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Hardware extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'Model_hardware' );
		$this->view->layout( 'hardware/layout' );
		$this->view->js_add( array(
				'hardware/layout',
			) );
		$this->view->css_add( array(
				'hardware/layout',
			) );
		$this->view->cache( 10 );
		
	}

	public function _remap( $method = 'index', $params = array() ) {
		$this->view->title_routes( array(
				'index'    => '週邊設備',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	// 週邊設備
	public function index() {

		$this->load->model( 'core/Model_news' );

		$this->view->data['news_class'] = array(
				0 => $this->Model_news->get_flow( array(
						'fid' => array( 44 ), // 硬體評測
					) ),
				1 =>$this->Model_news->get_flow( array(
						'fid' => array( 45 ), // 新聞快遞/週邊設備新聞
					) ),
				2 =>$this->Model_news->get_flow( array(
						'fid' => array( 44, 45, 46, 47 ), // 最新文章
					) ),
				3 =>$this->Model_news->get_flow( array(
						'fid' => array( 46 ), // 硬體綜合
					) ),
			);

		$this->view->data['circle_news'] = array(
			'0' => array(
				'img' => 'http://i418.photobucket.com/albums/pp266/vic111567/ASROCKB75/DSC_5875.jpg',
				'link' => '/bbs/forum.php?mod=viewthread&tid=431',
				'title' => 'ASRock B75 Pro3 and Pro3-M 1155-select 平價實用',
				'descr' => 'INTEL 1155腳位目前市面上可以購買到的晶片組，種類繁多除了之前的六系列現在還有小七系列，分別為B75、H77、Z75、Z77，其中B75也就是這次的文章主角是最讓大家訝異的，因為以往INTEL商用晶片組幾乎是沒在一般零售市場出現，不過其實去Intel官網看到此晶片定位在『為小型企業打造』，所以應該是新的模式專為一般小型企業辦公室電腦使用的晶片，這次ASRock的B75與其他家最大的不同在於B75原生只有一組SATA III 6G，而ASRock透過轉接晶片(經由PCI*1線路)多了兩組SATA III 6G，截至今天為止台灣目前可買到超過1組SATA 6G的B75板子也只有ASRock。',
			),
		);
		
		$this->load->model( 'core/Model_ad' );

		$this->view->data['flows'] = array(
				'hardware_mouse'     => $this->Model_ad->get_ad( array( 'case' => 'hardware_mouse', 'limit' => 6 ) ),
				'hardware_keyboard'  => $this->Model_ad->get_ad( array( 'case' => 'hardware_keyboard', 'limit' => 6 ) ),
				'hardware_headphone' => $this->Model_ad->get_ad( array( 'case' => 'hardware_headphone', 'limit' => 6 ) ),
				'hardware_else'      => $this->Model_ad->get_ad( array( 'case' => 'hardware_else', 'limit' => 6 ) ),
			);

		$this->view->data['flows_title'] = array(
				'hardware_mouse'     => '最新滑鼠',
				'hardware_keyboard'  => '最新鍵盤',
				'hardware_headphone' => '最新耳機',
				'hardware_else'      => '其它最新',
			);

		$this->view->data['ads'] = array(
				'hardware_mouse_ads'     => $this->Model_ad->get_ad( array( 'case' => 'hardware_mouse_ads', 'limit' => 2 ) ),
				'hardware_keyboard_ads'  => $this->Model_ad->get_ad( array( 'case' => 'hardware_keyboard_ads', 'limit' => 2 ) ),
				'hardware_headphone_ads' => $this->Model_ad->get_ad( array( 'case' => 'hardware_headphone_ads', 'limit' => 2 ) ),
				'hardware_else_ads'      => $this->Model_ad->get_ad( array( 'case' => 'hardware_else_ads', 'limit' => 2 ) ),
			);
	}
}

//
