<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper( 'form' );
		$this->view->cache( 0 );
	}

	public function index() {
		if ( ! $this->user->auth( 22 ) ) {
			show_404();
		}

		$this->view->display(
			array(
				'title'    => "通用後台管理頁面",
				'view'     => 'admin/index',
				'js_files' => array(
					'admin/index',
				),
				'css_files' => array(
					'admin/diy',
					'admin/index',
				),
			)
		);
	}

	public function live_channels() {
		$this->load->model( 'Model_live_channel' );
		$this->view->data['live_channels'] = $this->Model_live_channel->get_d3_channels( array(
				'limit' => 200,
				'game_type' => 'DiabloIII',
			) );
		$this->view->display(
			array(
				'title'    => "直播頻道管理",
				'view'     => 'admin/live-channels',
				'js_files' => array(
					'admin/live-channels',
				),
				'css_files' => array(
					'admin/diy',
					'admin/live-channels'
				),
			)
		);
	}

	public function news_bot( $type = '', $data = array() ) {
		switch ( $type ) {
		case 'latest-vod':
			$this->_fetch_latest_vod();
			break;
		default:
			if ( is_array( $type ) ) {
				$data = $type;
			}

			foreach ( $data as $key => $value ) {
				$this->view->data[$key] = $value;
			}

			$this->view->display(
				array(
					'title'    => "網易新聞爬蟲",
					'view'     => 'admin/news-bot',
					'js_files' => array(
						'admin/news-bot',
					),
					'css_files' => array(
						'admin/diy',
						'admin/news-bot'
					),
				)
			);
			break;
		}
	}

	/**
	 * 擷取最新視頻
	 * 拿最近新增進論壇的60筆，比對網易最新的15筆。
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _fetch_latest_vod( $setting = array() ) {
		$this->load->library( 'simple_html_dom' );
		$this->load->model( 'Model_vod' );
		// require_once FCPATH . 'extention/big2gb/big2gb.php';
		$html = file_get_html( "http://d.163.com/special/new_shipin/" );
		// $code = new big2gb;

		// 載入已轉過的vod
		$exisit_vod = $this->Model_vod->get_vod( array(
				'limit' => 60,
				'offset' => 0,
			) );

		$fetch_rows = array();

		// 列出網易最新vod
		foreach ( $html->find( '.lst-img-title li' ) as $index => $li ) {
			if ( $index > 14 ) break;
			$vod = $fetch_rows[$index];

			preg_match( '@\/([\d]{2})\/([\d]{4})\/([\d]{2})\/@m', $li->find( 'a.pic', 0 )->href, $date );

			$vod['date']  = "20$date[1]$date[2], $date[3]時";
			// $vod['title'] = $code->chg_utfcode( iconv( "GB2312", "utf-8", $li->find( 'h5 a', 0 )->title ) );
			$vod['title'] = iconv( "GB2312", "utf-8", $li->find( 'h5 a', 0 )->title );
			$vod['title'] = str_replace( '暗黑破壞神3 ', '', $vod['title'] );
			// $vod['text']  = $code->chg_utfcode( iconv( "GB2312", "utf-8", $li->find( 'h5 a', 0 )->innertext ) );
			$vod['text']  = iconv( "GB2312", "utf-8", $li->find( 'h5 a', 0 )->innertext );
			$vod['href']  = $li->find( 'h5 a', 0 )->href;

			// 判斷網易的vod是否已存在於暗盟論壇
			foreach ( $exisit_vod as $key => $entity ) {
				$fetched = str_get_html( $entity['message'] );
				// 轉文章時，於JS塞入的暗code
				if ( $fetched->find( 'a#fetched', 0 )->href == $vod['href'] ) {
					$vod['fetched']  = TRUE;
					$vod['bbs_link'] = $this->discuzx->alink_to_bbs( array(
							'text' => '已存在',
							'tid'  => $entity['tid'],
						) );
				}
				$fetched->clear();
				unset( $fetched );
			}

			$fetch_rows[$index] = $vod;
		}

		$this->view->data['fetch_rows'] = $fetch_rows;
		$this->view->display(
			array(
				'title'    => "網易新聞爬蟲 - 最近視頻",
				'view'     => 'admin/news-bot/latest-vod',
				'js_files' => array(
					'admin/news-bot',
				),
				'css_files' => array(
					'admin/diy',
					'admin/news-bot'
				),
			)
		);
	}

	/**
	 * 直播頻道CRUD
	 *
	 * @param [type]  $id [description]
	 * @return [type]     [description]
	 */
	public function channel( $id ) {

		if ( ! $this->user->auth( 21 ) ) {
			show_404();
		}

		switch ( $_SERVER['REQUEST_METHOD'] ) {
		case 'POST':
			if ( $this->input->is_ajax_request() ) {
				$this->_post_live_channel();
			}
			break;
		case 'DELETE':
			if ( $this->input->is_ajax_request() ) {
				$this->_delete_live_channel( $id );
			}
			break;
		default:
			echo "method: {$_SERVER['REQUEST_METHOD']}";
			break;
		}
	}

	/**
	 * 新增一個直播頻道
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _post_live_channel() {
		$this->load->model( 'Model_live_channel' );

		$this->Model_live_channel->post_channel( $this->input->post() );

		return $this;
	}

	/**
	 * 刪除指定ID的直播頻道
	 *
	 * @param integer $id [description]
	 * @return [type]      [description]
	 */
	private function _delete_live_channel( $id = 0 ) {

		$this->load->model( 'Model_live_channel' );

		$this->Model_live_channel->delete( array(
				'id' => $id,
			) );
	}
}

//
//
