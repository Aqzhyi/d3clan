<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_ad extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->ad = $this->load->database( 'Business', TRUE );
	}


	public function get_ad( $setting = array() ) {

		$setting['case'] = ( ! is_null( $setting['case'] ) ) ? $setting['case'] : '270x60';
		$setting['limit'] = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 6;
		
		$this->ad->where( 'case', $setting['case'] );
		$this->ad->limit( $setting['limit'], 0 );
		$this->ad->from( 'common_ad_banners' );

		return $this->ad->get()->result_array();
	}


	public function add( $setting = array() ) {
		
		$setting['data']['path'] = ( ! empty( $setting['data']['path'] ) ) ? $setting['data']['path'] : NULL;

		if ( is_null( $setting['data']['path'] ) ) return $this->callback->error_msg( '廣告路徑不得為空啊!' )->toJSON();

		$this->ad->insert( 'common_ad_banners', $setting['data'] );

		return $this->callback->success_msg( '新增完成.' )->toJSON();
	}


	public function delete( $setting = array() ) {
		
		$setting['id'] = ( ! empty( $setting['id'] ) ) ? $setting['id'] : NULL;

		if ( is_null( $setting['id'] ) ) return $this->callback->error_msg( '刪除發生錯誤，找不到id.' )->toJSON();

		$this->ad->delete( 'common_ad_banners', $setting );

		return $this->callback->success_msg( '刪除完成.' )->toJSON();
	}
}

// 