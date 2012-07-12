<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Game extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'simple_html_dom' );
	}

	//
	public function index( $path="intro", $page="history" ) {

		$this->view->data["page"] = $this->load->view( "game/$path/$page", $this->view->data, TRUE );

		$DOM = str_get_html( $this->view->data["page"] );

		// 從各子頁 h2 元素取得網頁標題
		$h2_str = $DOM->find( 'h2', 0 )->innertext;
		$title  = ( empty( $h2_str ) ) ? "遊戲資料" : $h2_str;

		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => $title,
				'view'     => 'game/index',
				'js_files' => array(
					'game/index',
					"game/$path",
					"game/$path/$page",
				),
				'css_files' => array(
					'game/index',
					'game/d3-guide',
					"game/$path",
					"game/$path/$page",
				),
				'js_links' => array(
					'http://tw.battle.net/d3/static/js/tooltips.js',
				),
			)
		);
	}
}

//
