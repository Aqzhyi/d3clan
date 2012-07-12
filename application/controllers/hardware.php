<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Hardware extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'Model_hardware' );
		$this->load->library( 'template' );
	}

	// 週邊設備
	public function index() {

		$this->view->data['circle_loop']['circle_loop_data'] = array(
			'0' => array(
				'img' => 'http://i1258.photobucket.com/albums/ii528/joe200362/IMG_0625.jpg',
				'link' => 'http://d3clan.tw/bbs/forum.php?mod=viewthread&tid=376&extra=page%3D1',
				'title' => 'Tt eSPORTS Black Element 魔戰黑者-天使白',
				'descr' => '屈指一算,已有好一陣子沒有做新的產品開箱,畢竟一篇好的開箱文是需要花上大量時間做測試、評鑑與調教的,而我平常工作又太忙了,所以跟不少酷炫新穎的電競周邊擦肩而過,而在前陣子剛落幕的ComputeX展中,看到了這支曜越科技的《魔戰黑者-天使白》,當下就被她白金線條的包裝深深吸引,連忙請朋友幫我透過管道取得這隻線條優雅、配色大膽的電競滑鼠。',
			),
			'1' => array(
				'img' => 'http://i491.photobucket.com/albums/rr273/zhen737/sc2clan/razer/Unboxing%20Razer%20StarCraft%20II%20Mesengger%20Bag/sc2-zerg-edition-messenger-bag.jpg',
				'link' => 'http://d3clan.tw/bbs/forum.php?mod=viewthread&tid=152&extra=page%3D1',
				'title' => '壽司の 雷蛇《星海爭霸®II》Zerg 信差包開箱文分享！',
				'descr' => '《星海爭霸®II》蟲族版信差包是為想要保護遊戲裝備安全，保持高舒適度並且同時擁有超酷外觀的玩家所設計的。它防水耐磨的設計能夠像蟲族防禦對方攻勢一般有效抵御風雨和日常生活中的突發狀況。打開它你會發現有足夠的空間來放置你的筆記型電腦、遊戲周邊和戰場上需要的其他任何裝備。 《星海爭霸®II》蟲族版信差包確保你無論在哪都可以隨時投入戰鬥。',
			),
		);

		$this->view->data['mouse_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 1, 11 ) // 滑鼠
			) );
		$this->view->data['keyboard_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 2, 10 ) // 鍵盤
			) );
		$this->view->data['headphone_flow'] = $this->Model_hardware->get_thread( array(
				'typeid' => array( 3, 12 ) // 耳機
			) );
		$this->view->data['else_flow'] = $this->Model_hardware->get_thread( array(
				'not_typeid' => array( 1, 2, 3, 10, 11, 12 ) // 其它
			) );

		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'     => '週邊設備',
				'view'      => 'hardware/layout',
				'js_files'  => array(
					'plugin/circle_loop/base',
					'hardware/layout',
				),
				'css_files' => array(
					'plugin/circle_loop/base',
					'hardware/layout',
				),
			)
		);

	}
}

//
