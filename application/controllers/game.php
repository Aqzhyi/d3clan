<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Game extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'simple_html_dom' );

		$this->view->title_append( '遊戲資料' );
		$this->view->layout( 'game/layout' );
		$this->view->js_add( array(
			'game/index',
		) );
		$this->view->js_link_add( 'http://tw.battle.net/d3/static/js/tooltips.js' );
		$this->view->css_add( array(
			'game/index',
			'game/d3-guide',
		) );
		$this->view->cache( 10 );
	}

	public function _remap( $method = 'index', $params = array() ) {

		$this->view->title_routes( array(
				'index'    => '首頁',
			) );
		$this->view->page( $method, $params );
		$this->view->init( $this );
	}

	//
	public function index( $path = "intro", $page = "history" ) {

		// 從各子頁 h2 元素取得網頁標題
		$this->view->data["fetched_page_content"] = $this->template->parse( "game/$path/$page", $this->view->data, true );
		$DOM    = str_get_html( $this->view->data["fetched_page_content"] );
		$h2_str = $DOM->find( 'h2', 0 )->innertext;
		$title  = ( empty( $h2_str ) ) ? "遊戲資料" : $h2_str;

		$this->view->title( $title );

		$this->view->js_add( array(
				"game/$path",
				"game/$path/$page",
			) );
		$this->view->css_add( array(
				"game/$path",
				"game/$path/$page",
			) );

		$this->view->data['menu_list'][] = array(
			'folder_name' => '職業介紹',
			'links' => array(
				'class/barbarian'    => array( 'text' => '野蠻人', ),
				'class/wizard'       => array( 'text' => '秘術師', ),
				'class/demon-hunter' => array( 'text' => '狩魔獵人', ),
				'class/witch-doctor' => array( 'text' => '巫醫', ),
				'class/monk'         => array( 'text' => '武僧', ),
			),
		);
		$this->view->data['menu_list'][] = array(
			'folder_name' => '基本說明',
			'links' => array(
				'遊戲簡介',
				'intro/what-is-d3'              => array( 'text' => '什麼是《暗黑破壞神III》？', ),
				'intro/history'                 => array( 'text' => '故事提要', ),
				'遊戲操作',
				'gameplay/fundamentals'         => array( 'text' => '基礎操作' ),
				'gameplay/combat-skills'        => array( 'text' => '戰鬥技能' ),
				'gameplay/world'                => array( 'text' => '遊戲場景' ),
				'gameplay/objects'              => array( 'text' => '物件' ),
				'gameplay/followers'            => array( 'text' => '追隨者' ),
				'gameplay/playing-with-friends' => array( 'text' => '與好友一同遊戲' ),
				'物品',
				'intro/equipment'                => array( 'text' => '物品與裝備' ),
				'intro/inventory'                => array( 'text' => '物品欄' ),
				'intro/crafting'                 => array( 'text' => '製作與工匠' ),
				'intro/auction-house'            => array( 'text' => '拍賣場' ),
			),
		);
		$this->view->data['menu_list'][] = array(
			'folder_name' => '追隨者',
			'links' => array(
				'follower/index'       => array( 'text' => '追隨者系統', ),
				'follower/enchantress' => array( 'text' => '巫女', ),
				'follower/scoundrel'   => array( 'text' => '盜賊', ),
				'follower/templar'     => array( 'text' => '聖堂騎士', ),
			),
		);
		$this->view->data['menu_list'][] = array(
			'folder_name' => '物品',
			'links' => array(
				'item/gem'               => array( 'text' => '寶石', ),
				'item/dye'               => array( 'text' => '染料', ),
				'item/potion'            => array( 'text' => '藥水', ),
				'item/crafting-material' => array( 'text' => '製作材料', ),
			),
		);
		$this->view->data['menu_list'][] = array(
			'folder_name' => '工匠',
			'links' => array(
				'artisan/index'      => array( 'text' => '工匠系統', ),
				'artisan/blacksmith' => array( 'text' => '鐵匠', ),
				'artisan/jeweler'    => array( 'text' => '珠寶匠', ),
			),
		);

	}
}

//
