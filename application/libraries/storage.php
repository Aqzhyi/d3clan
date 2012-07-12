<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 碎片儲存器
 *
 * 本儲存器主要設計來儲存(緩存)程式片斷結果, 一般用來儲存SQL查詢結果.
 * 輔助但不取代 $this->view->cache( 5 );
 * 預設的緩存時間是 10分鐘.
 */
class Storage {

	function __construct() {
		$CI =& get_instance();
		$CI->load->driver( 'cache' );
	}

	/**
	 * 取得SQL查詢結果之緩存
	 *
	 * @param array   $setting      [description]
	 * @param integer $cache_minute [description]
	 * @return [type]                [description]
	 */
	public function get( $setting = array(), $cache_minute = 10 ) {

		$setting['callback']   = ( ! is_null( $setting['callback'] ) ) ? $setting['callback'] : NULL;
		$setting['params']     = ( ! is_null( $setting['params'] ) ) ? $setting['params'] : NULL;
		$setting['cache_name'] = ( ! is_null( $setting['cache_name'] ) ) ? $setting['cache_name'] : NULL;
		$setting['cache_id']   = ( ! is_null( $setting['cache_id'] ) ) ? $setting['cache_id'] : get_class( $setting['callback'][0] ) . '---' . $setting['callback'][1] . "({$setting['cache_name']})";

		if ( is_null( $setting['callback'] ) ) return array();

		$cache_file = $this->_get( $setting['cache_id'] );

		if ( $cache_file ) {
			return $cache_file;
		}
		else {
			$data = call_user_func( $setting['callback'], $setting['params'] );

			$cache_file = $this->_get( $setting['cache_id'], $data, $cache_minute );

			return $cache_file;
		}
	}

	/**
	 * 取得緩存
	 *
	 * @param string  $cache_id     [description]
	 * @param [type]  $data         [description]
	 * @param integer $cache_minute [description]
	 * @return [type]                [description]
	 */
	private function _get( $cache_id = 'common_storage', $data = NULL, $cache_minute = 10 ) {

		$CI =& get_instance();

		if ( $CI->cache->apc->is_supported() ) {
			if ( ! $cache_file = $CI->cache->apc->get( $cache_id ) ) {
				$cache_file = $data;
				$CI->cache->file->save( $cache_id, $cache_file, $cache_minute * 60 );
			}
		}
		else {
			if ( ! $cache_file = $CI->cache->file->get( $cache_id ) ) {
				$cache_file = $data;
				$CI->cache->file->save( $cache_id, $cache_file, $cache_minute * 60 );
			}
		}

		return $cache_file;
	}
}

//
