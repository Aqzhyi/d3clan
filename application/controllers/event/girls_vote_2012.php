<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Girls_vote_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->view->title_append( 'D-Girls 美少女選拔' );
		$this->view->layout( 'event/girls_vote_2012/layout' );
		$this->view->js_add( 'event/girls_vote_2012/index' );
		$this->view->js_add( 'plugin/jQuery/jquery-ui-1.8.21.no.effects.min' );
		$this->view->css_add( 'event/girls_vote_2012/index' );
		$this->view->css_add( 'plugin/jQuery/jquery-ui-1.8.21.custom' );

		$this->load->model( 'event/Model_girls_vote_2012' );
		// 關聯投票主題,順序為 氣質系->萌系->性感系->活潑系.
		$this->_list_tid = array( 528 );
		$this->view->data['polls_name'] = array( '氣質','萌度','性感','活潑' );

		// 處理女孩們票選種類日期的active屬性、acitve主題與acitve投票系別
		$this->_active_tid = 0;
		$this->view->data['active_poll'] = 0;
		$now_time = strtotime( date( 'Y-m-d H:i:s' ) );
		if ( $now_time > strtotime( '2012-07-30 00:00:00' ) && $now_time < strtotime( '2012-08-04 23:59:59' ) ) {
			$this->view->data['girls_vote_progress1_active'] = 'active';
			$this->_active_tid = $this->_list_tid[0];
			$this->view->data['active_poll'] = 0;
		}

		if ( $now_time > strtotime( '2012-08-05 00:00:00' ) && $now_time < strtotime( '2012-08-10 23:59:59' ) ) {
			$this->view->data['girls_vote_progress2_active'] = 'active';
			$this->_active_tid = $this->_list_tid[1];
			$this->view->data['active_poll'] = 1;
		}

		if ( $now_time > strtotime( '2012-08-11 00:00:00' ) && $now_time < strtotime( '2012-08-16 23:59:59' ) ) {
			$this->view->data['girls_vote_progress3_active'] = 'active';
			$this->_active_tid = $this->_list_tid[2];
			$this->view->data['active_poll'] = 2;
		}

		if ( $now_time > strtotime( '2012-08-17 00:00:00' ) && $now_time < strtotime( '2012-08-22 23:59:59' ) ) {
			$this->view->data['girls_vote_progress4_active'] = 'active';
			$this->_active_tid = $this->_list_tid[3];
			$this->view->data['active_poll'] = 3;
		}

		if ( $now_time > strtotime( '2012-08-23 00:00:00' ) ) {
			$this->_active_tid = -1;
		}

		$this->view->cache( 0 );
	}

	public function _remap( $sub_page = 'index', $page_params = array() ) {

		$this->view->title_routes(array(
				'index' => '賽事首頁',
				'page'  => "第 {$page_params[0]} 頁 - 賽事首頁",
			));
		$this->view->page( $sub_page, $page_params );
		$this->view->init( $this );
	}

	public function index( $params = array() ) {

		// 女孩們的詳細資料檔案
		$this->page( 1 );
	}

	public function page( $page = 1, $sort = 'default' ) {
		// 女孩們的詳細資料檔案
		$this->view->data['page'] = $page;
		$this->view->data['girls'] = $this->Model_girls_vote_2012->get_girls( array(
				'tid'  => $this->_list_tid,
				'page' => $page,
				'sort' => $sort
			) );
	}

	/**
	 * 投票
	 * @return [type] [description]
	 */
	public function vote_girl() {

		$setting = $this->input->post();

		echo $this->Model_girls_vote_2012->vote( array(
				'name'       => $setting['name'],
				'active_tid' => $this->_active_tid,
			) );
	}
}

//