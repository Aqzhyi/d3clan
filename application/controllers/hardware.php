<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Hardware extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'Model_hardware' );
		$this->load->library( 'template' );
	}

	// 週邊設備
	public function index() {

		$this->load->model( 'Model_news' );

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
					// 'plugin/circle_loop/base',
					'hardware/layout',
				),
				'css_files' => array(
					// 'plugin/circle_loop/base',
					'hardware/layout',
				),
			)
		);

	}
}

//
