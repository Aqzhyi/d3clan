<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Trade extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => "交易專區",
				'view'     => 'trade/index',
				'js_files' => array(
					'trade/index',
				),
				'css_files' => array(
					'trade/index'
				),
			)
		);
	}

	/**
	 * 商品出售
	 * @return [type] [description]
	 */
	public function assist_sell() {

		if ( ! $this->user->auth( 23 ) ) {
			show_error('本系統內部測試中');
		}

		$this->load->helper( 'form' );
		$this->view->cache( 5 );
		$this->view->display(
			array(
				'title'    => "出售商品",
				'view'     => 'trade/assist-sell',
				'js_files' => array(
					'trade/assist-sell',
				),
				'css_files' => array(
					'trade/assist-sell'
				),
			)
		);
	
		return $this;
	}

	/**
	 * 商品:建立
	 * @param  string $act 執行動作: create, ?.
	 * @return [type]      [description]
	 */
	public function good( $act = NULL ) {
		$this->load->library( 'ocr' );
		$this->load->helper( 'directory' );
		$this->view->cache( 0 );

		switch ( $act ) {
		case 'create':
			if ( ! $this->user->auth( 23 ) ) {
				show_error('本系統內部測試中');
			}

			$file_info = $this->discuzx->attach_file();
			$this->load->model( 'Model_trade' );
			$item = $this->ocr->parser( $file_info );
			$this->view->data['tid'] = $this->Model_trade->create_good( $file_info, $item['identifed_item'], array() );
			$this->view->data['identifed_item'] = $item['identifed_item'];
			break;

		default:
			show_404();
			break;
		}

		if ( $file_info['is_success'] === TRUE ) {
			$title = "上傳成功";
			$view  = "trade/good_create_success";
		}
		else {
			$title = "失敗";
			$view  = "trade/good_create_error";
		}

		$this->view->data['file_info'] = $file_info;
		$this->view->display(
			array(
				'title'    => $title,
				'view'     => $view,
				'js_files' => array(
					// $view,
				),
				'css_files' => array(
					// $view
				),
			)
		);

		return $this;
	}
}

//
