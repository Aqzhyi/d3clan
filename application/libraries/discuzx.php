<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Discuz! 的用戶數據全部存儲在 UCenter 中，
 * 並可以使用 UCenter 的接口體系與第三方產品進行掛接。
 * 因此，瞭解 UCenter 也是瞭解 Discuz! 產品體系的重要一步。
 *
 * 使用本 library 需更改
 * \bbs\source\class\discuz\discuz_application.php 及
 * \bbs\source\function\function_core.php
 * 兩個檔案。具體見 @link[1].
 *
 * @link 在 CodeIgniter 中整合 Discuz X2.5, 引用超級變數 $_G 來獲取用戶名, uid等資訊.
 *       http://lzzpnk.blogspot.tw/2012/06/codeigniter-discuz-x25-g-uid.html
 *
 * @link http://dev.discuz.org/wiki/index.php
 * @link http://dev.discuz.org/wiki/index.php?title=UCenter接口
 */
class Discuzx {

	public function __construct() {
		$this->CI =& get_instance();
		// parent::__construct();
		define( 'UC_CONNECT', 'mysql' );
		define( 'UC_DBHOST', 'localhost' );
		define( 'UC_DBUSER', 'sc2clan' );
		define( 'UC_DBPW', 'ilovesc2!' );
		define( 'UC_DBNAME', 'sc2clan_diabloiii' );
		define( 'UC_DBCHARSET', 'utf8' );
		define( 'UC_DBTABLEPRE', '`sc2clan_diabloiii`.d3bbs_ucenter_' );
		define( 'UC_DBCONNECT', '0' );
		define( 'UC_KEY', 'D73eh2e6Uak6b2v5C8L6P5Ubf1X8C9y7sbVde8Y5h3t6Y9O9Uan5hc02FaJ3v2L1' );
		define( 'UC_API', 'http://d3clan.tw/bbs/uc_server' );
		define( 'UC_CHARSET', 'utf-8' );
		define( 'UC_IP', '' );
		define( 'UC_APPID', '1' );
		define( 'UC_PPP', '20' );
		require_once FCPATH . 'bbs/uc_client/client.php';
		require_once FCPATH . 'bbs/source/class/class_core.php';
		$this->discuzx =& discuz_core::instance();
		$this->discuzx->init_cron = false;
		$this->discuzx->init_session = false;
		$this->discuzx->init();
		$this->_G = $this->discuzx->var;

		// print_r($this->_G);
		// print_r($this->_G['clientip']);
		// print_r($this->_G['member']);
		// print_r($this->_G['member']['extgroupids']);
		// $this->_G['uid']
		// $this->_G['username']
		// $this->_G['adminid']
		// $this->_G['groupid']

		// print_r(uc_get_user($this->_G['username']));


		/**
		 * Discuz會擅改error_reporting, 所以這邊要使它改回來.
		 */
		include APPPATH . "config/error_reporting.php";
	}
	
	/**
	 * 將檔案上傳至 discuz 附件目錄
	 * 不包含主題發表
	 *
	 * @return array 上傳的檔案訊息(包含檔案上傳是否成功之訊息)
	 */
	public function attach_file() {

		$this->CI->load->library( 'upload' );
		$this->CI->load->library( 'image_lib' );

		if ( ! is_dir( './bbs/data/attachment/forum/' . date( "Ym" ) ) ) {
			mkdir( './bbs/data/attachment/forum/' . date( "Ym" ), 0755 );
		}
		if ( ! is_dir( './bbs/data/attachment/forum/' . date( "Ym" ) . '/' . date( 'd' ) ) ) {
			mkdir( './bbs/data/attachment/forum/' . date( "Ym" ) . '/' . date( 'd' ), 0755 );
		}

		$this->CI->upload->initialize( array(
				'upload_path'   => './bbs/data/attachment/forum/' . date( "Ym" ) . '/' . date( 'd' ),
				'allowed_types' => 'gif|jpg|jpeg|bmp|png',
				'max_size'      => 0,
				'max_width'     => 0,
				'max_height'    => 0,
				'encrypt_name'  => TRUE,
				'remove_spaces' => TRUE,
			) );

		$is_success = $this->CI->upload->do_upload();

		$file_info               = $this->CI->upload->data();
		$file_info['is_success'] = $is_success;

		// Array
		// (
		//  [file_name] => 045b2648714935c9549857b8f26959b4.png
		//  [file_type] => image/png
		//  [file_path] => F:/xampp/htdocs/d3clan.tw/bbs/data/attachment/forum/201206/26/
		//  [full_path] => F:/xampp/htdocs/d3clan.tw/bbs/data/attachment/forum/201206/26/045b2648714935c9549857b8f26959b4.png
		//  [raw_name] => 045b2648714935c9549857b8f26959b4
		//  [orig_name] => 9.png
		//  [client_name] => 9.png
		//  [file_ext] => .png
		//  [file_size] => 86.04
		//  [is_image] => 1
		//  [image_width] => 456
		//  [image_height] => 444
		//  [image_type] => png
		//  [image_size_str] => width="456" height="444"
		//  [is_success] => 1
		// )

		if ( $file_info['is_success']===TRUE ) {
			// 縮圖
			$this->CI->image_lib->initialize( array(
					'image_library'  => 'gd2',
					'source_image'   => $file_info['full_path'],
					'dynamic_output' => FALSE,
					'new_image'      => $file_info['file_name'] . '.thumb.jpg',
					'thumb_marker'   => '',
					'master_dim'     => 'width',
					'width'          => 400,
					'height'         => 300,
					'create_thumb'   => TRUE,
					'maintain_ratio' => TRUE,
				) );

			$this->CI->image_lib->resize();
		}

		return $file_info;
	}

	/**
	 * 簡單產生出 link 到 bbs thread 的 helper
	 * @param  array $setting 參數陣列
	 *                        target => '_blank', 同 html 屬性
	 *                        text => '',         包含在 a 元素內的顯示文字
	 *                        tid => '',          欲連結論壇 thread 的 threadId
	 *                        href => '/bbs',     若無 tid 則返回論壇首頁
	 * @return string         組合好的 a 元素字串
	 */
	public function alink_to_bbs( $setting ) {
		// 必須
		$setting['text']   = ( ! empty( $setting['text'] ) )   ? $setting['text'] : '';
		$setting['tid']    = ( ! empty( $setting['tid'] ) )    ? $setting['tid'] : '';
		// 選填
		$setting['target'] = ( ! empty( $setting['target'] ) ) ? $setting['target'] : '_blank';
		// 例外
		$setting['href']   = ( ! empty( $setting['tid'] ) )    ? "/bbs/forum.php?mod=viewthread&tid={$setting['tid']}" : '/bbs';

		$link_tpl = "<a target='{$setting['target']}' href='{$setting['href']}'>{$setting['text']}</a>";

		return $link_tpl;
	}

}
//
