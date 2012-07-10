<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Girls_vote_2012 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( 'template' );
		$this->load->model( 'event/Girls_vote_2012_model' );
	}

	public function index() {
		
		// 處理女孩們票選種類日期的active屬性
		$now_time = strtotime( date('Y-m-d H:i:s') );
		if ( $now_time > strtotime('2012-07-01 00:00:00') && $now_time < strtotime('2012-07-26 00:00:00') )
			$this->view->data['girls_vote_progress1_active'] = 'active';
		if ( $now_time > strtotime('2012-07-26 00:00:00') && $now_time < strtotime('2012-08-01 00:00:00') )
			$this->view->data['girls_vote_progress2_active'] = 'active';
		if ( $now_time > strtotime('2012-08-01 00:00:00') && $now_time < strtotime('2012-08-06 00:00:00') )
			$this->view->data['girls_vote_progress3_active'] = 'active';
		if ( $now_time > strtotime('2012-08-06 00:00:00') && $now_time < strtotime('2012-08-11 00:00:00') )
			$this->view->data['girls_vote_progress4_active'] = 'active';

		// 女孩們的詳細資料檔案
		$this->view->data['girls'] = $this->Girls_vote_2012_model->get_girls( array(
			// 'tid' => array('367', '368', '369', '340') // 關聯投票主題,順序為 氣質系->萌系->性感系->活潑系.
			'tid' => array('367', '368') // 關聯投票主題,順序為 氣質系->萌系->性感系->活潑系.
		) );

		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => "D-Girl",
				'view'     => 'event/girls_vote_2012/index',
				'js_files' => array(
					'event/girls_vote_2012/index',
				),
				'css_files' => array(
					'event/girls_vote_2012/index'
				),
			)
		);
	}
}

//