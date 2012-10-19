<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class amd_cup_2012 extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->view->title_append( 'AMD盃之打寶列車長' );
		$this->view->layout( 'event/amd_cup_2012/layout' );
		$this->view->js( 'event/amd_cup_2012/layout' );
		$this->view->css( 'event/amd_cup_2012/layout' );
		$this->view->cache( 0 );
	}

	public function _remap( $sub_page = 'index', $page_params = array() ) {

		$this->view->title_routes( array(
				'index'      => '活動簡介',
				'news'       => '活動新聞',
				'live'       => '活動LIVE',
				'vote'       => '我要投票',
				'vod'        => '活動影片',
				'lucky_user' => '幸運的得獎者們',
			) );
		$this->view->page( $sub_page, $page_params );
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
		$this->view->cache( 0 );
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

	public function vod() {

		$this->load->model( 'event/model_amd_cup_2012' );
		$this->view->data['captains'] = $this->model_amd_cup_2012->get_captains();
	}

	//# 取得幸運的投票者
	public function lucky_user() {
		$this->view->cache( 0 );
		//#
		$this->load->model( 'event/model_amd_cup_2012' );

		//#
		$this->view->data['lucky_user']['第一週選手shenhand']  = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第一週', 'vote_to' => 1) );
		$this->view->data['lucky_user']['第一週選手BellaBaby'] = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第一週', 'vote_to' => 2) );
		$this->view->data['lucky_user']['第一週選手Jeff']      = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第一週', 'vote_to' => 3) );
		$this->view->data['lucky_user']['第一週全部投票參與者']= $this->model_amd_cup_2012->get_lucky_user( array('week' => '第一週') );
		
		$this->view->data['lucky_user']['第二週選手4']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第二週', 'vote_to' => 4) );
		$this->view->data['lucky_user']['第二週選手5']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第二週', 'vote_to' => 5) );
		$this->view->data['lucky_user']['第二週選手6']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第二週', 'vote_to' => 6) );
		$this->view->data['lucky_user']['第二週全部投票參與者']= $this->model_amd_cup_2012->get_lucky_user( array('week' => '第二週') );
		
		$this->view->data['lucky_user']['第三週選手7']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第三週', 'vote_to' => 7) );
		$this->view->data['lucky_user']['第三週選手8']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第三週', 'vote_to' => 8) );
		$this->view->data['lucky_user']['第三週選手9']         = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第三週', 'vote_to' => 9) );
		$this->view->data['lucky_user']['第三週全部投票參與者']= $this->model_amd_cup_2012->get_lucky_user( array('week' => '第三週') );
		
		$this->view->data['lucky_user']['第四週選手10']        = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第四週', 'vote_to' => 10) );
		$this->view->data['lucky_user']['第四週選手11']        = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第四週', 'vote_to' => 11) );
		$this->view->data['lucky_user']['第四週選手12']        = $this->model_amd_cup_2012->get_lucky_user( array('week' => '第四週', 'vote_to' => 12) );
		$this->view->data['lucky_user']['第四週全部投票參與者']= $this->model_amd_cup_2012->get_lucky_user( array('week' => '第四週') );

		$this->view->js( 'event/amd_cup_2012/lucky_user' );
	}

	//##########################
	
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