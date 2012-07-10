<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 處理網站會員的類
 */
class User {

	function __construct() {
		$this->CI =& get_instance();

		if ( ! isset( $this->CI->discuzx ) ) {
			show_error( 'discuzx lib 必須在 user lib 之前被載入.' );
		}
	}

	/**
	 * 是否有登入
	 * @param  array   $setting [description]
	 * @return boolean          [description]
	 */
	public function is_login( $setting = array() ) {
		
		$isLogin = $this->CI->discuzx->_G['uid'];
	
		if ( $isLogin == 0 ) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 取得會員名
	 * @return [type] [description]
	 */
	public function get_username() {
	
		return $this->CI->discuzx->_G['username'];
	}

	/**
	 * 取得會員uid
	 * @return [type] [description]
	 */
	public function get_id() {
	
		return $this->CI->discuzx->_G['uid'];
	}

	/**
	 * 驗證權限
	 * @param  [type] $groupId [description]
	 * @return [type]          [description]
	 */
	public function auth( $groupId = NULL ) {

		if ( ! $this->is_login() ) {
			return FALSE;
		}

		$extgroupids = $this->CI->discuzx->_G['member']['extgroupids'];

		$extgroupids = explode( '	', $extgroupids );

		foreach ( $extgroupids as $id ) {
			if ( $id == $groupId ) {
				return TRUE;
			}
		}

		return FALSE;
	}
}


//
