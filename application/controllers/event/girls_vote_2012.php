<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Girls_vote_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'template' );
		$this->load->model( 'event/Model_girls_vote_2012' );
 
		// 關聯投票主題,順序為 氣質系->萌系->性感系->活潑系.
		$this->_list_tid = array(  );

		// 處理女孩們票選種類日期的active屬性與acitve主題
		$this->_active_tid = 0;
		$now_time = strtotime( date( 'Y-m-d H:i:s' ) );
		if ( $now_time > strtotime( '2012-07-01 00:00:00' ) && $now_time < strtotime( '2012-08-05 00:00:00' ) ) {
			$this->view->data['girls_vote_progress1_active'] = 'active';
			$this->_active_tid = $this->_list_tid[0];
		}

		if ( $now_time > strtotime( '2012-08-05 00:00:00' ) && $now_time < strtotime( '2012-08-11 00:00:00' ) ) {
			$this->view->data['girls_vote_progress2_active'] = 'active';
			$this->_active_tid = $this->_list_tid[1];
		}

		if ( $now_time > strtotime( '2012-08-11 00:00:00' ) && $now_time < strtotime( '2012-08-17 00:00:00' ) ) {
			$this->view->data['girls_vote_progress3_active'] = 'active';
			$this->_active_tid = $this->_list_tid[2];
		}

		if ( $now_time > strtotime( '2012-08-17 00:00:00' ) && $now_time < strtotime( '2012-08-23 00:00:00' ) ) {
			$this->view->data['girls_vote_progress4_active'] = 'active';
			$this->_active_tid = $this->_list_tid[3];
		}

		if ( $now_time > strtotime( '2012-08-23 00:00:00' ) ) {
			$this->_active_tid = -1;
		}
	}

	public function index() {

		// 女孩們的詳細資料檔案
		$this->view->data['girls'] = $this->Model_girls_vote_2012->get_girls( array(
				'tid' => $this->_list_tid
			) );

		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => "D-Girl",
				'view'     => 'event/girls_vote_2012/index',
				'js_files' => array(
					'plugin/jQuery/jquery-ui-1.8.21.no.effects.min',
					'event/girls_vote_2012/index',
				),
				'css_files' => array(
					'plugin/jQuery/jquery-ui-1.8.21.custom',
					'event/girls_vote_2012/index',
				),
			)
		);
	}

	public function vote_girl() {

		$setting = $this->input->post();

		echo $this->Model_girls_vote_2012->vote( array(
				'name'       => $setting['name'],
				'active_tid' => $this->_active_tid,
			) );
	}
}

//
