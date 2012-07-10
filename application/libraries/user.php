<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class User {

	function __construct() {
		$this->CI =& get_instance();

		if ( ! isset( $this->CI->discuzx ) ) {
			show_error( 'discuzx lib 必須在 user lib 之前被載入.' );
		}
	}

	public function auth( $groupId = NULL ) {

		$isLogin = $this->CI->discuzx->_G['uid'];

		if ( $isLogin == 0 ) {
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
