<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class amd_cup_2012 extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->view->title_append( 'AMD盃之打寶列車長' );
		$this->view->layout( 'event/amd_cup_2012/layout' );
		$this->view->js( 'event/amd_cup_2012/layout' );
		$this->view->css( 'event/amd_cup_2012/layout' );
	}

	public function _remap() {

		$this->view->title_routes( array(
				'index' => '活動簡介',
				'news'  => '活動新聞',
				'live'  => '活動LIVE',
				'vote'  => '我要投票',
				'vod'   => '活動影片',
			) );
		$this->view->page( func_get_arg(0), func_get_arg(1) );
		$this->view->init( $this );
	}

	public function index() {
		
		$this->view->js('event/amd_cup_2012/index');
	}

	public function news() {

		$this->load->model( 'core/model_news' );

		$this->view->data['news_flows'] = $this->model_news->get_flow( array(
				'fid' => array( '67', ),
			) );
	}

	public function vote() {
		
		$this->load->model( 'event/model_amd_cup_2012' );
		$this->view->data['captains'] = $this->model_amd_cup_2012->get_captains( array(
				'week' => $this->model_amd_cup_2012->get_vote_week_key(),
			) );

		// 取得各列車長的總票數
		foreach ($this->view->data['captains'] as $key => &$captain) {
			$captain['total_vote'] = $this->model_amd_cup_2012->get_total_vote( array(
					'id' => $captain['id'],
				) );
		}
		
		$this->view->js( 'event/amd_cup_2012/vote' );
	}

	public function ajax() {
		//#
		$this->load->library( 'core/ajax' );
		$this->ajax->uri_offset( 3 );

		$this->ajax->uri_routes( array(
				'vote_player' => 'ajax_vote_player',
			) );

		$this->ajax->init( $this );
	}

	public function ajax_vote_player() {

		$this->load->model( 'event/model_amd_cup_2012' );
		
		$this->callback->response_if_condition( ! $this->user->is_login(), '尚未登入' );
		$this->callback->response_if_condition( $this->model_amd_cup_2012->is_vote(), '本週已投過票' );

		$this->model_amd_cup_2012->ajax_vote( $this->ajax->input() );
	}
}

//