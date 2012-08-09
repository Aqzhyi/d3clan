<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 綜合性商業廣告與新聞
 * dbname = sc2clan_business
 * dbtable = common_ad_banners
 *
 */
class Model_ad extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->db_ad = $this->load->database( 'Business', TRUE );
	}

	/**
	 * 取得
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_ad( $setting = array() ) {

		$setting['case']  = ( ! is_null( $setting['case'] ) ) ? $setting['case'] : '270x60';
		$setting['limit'] = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 6;

		$this->db_ad->where( 'case', $setting['case'] );
		$this->db_ad->limit( $setting['limit'], 0 );
		$this->db_ad->from( 'common_ad_banners' );

		return $this->db_ad->get()->result_array();
	}

	/**
	 * 新增
	 *
	 * @param array   $setting [description]
	 */
	public function add( $setting = array() ) {

		$setting['data']['path'] = ( ! empty( $setting['data']['path'] ) ) ? $setting['data']['path'] : NULL;

		$this->callback->response_if_condition( is_null( $setting['data']['path'] ), '廣告路徑不得為空啊!' );

		$this->db_ad->insert( 'common_ad_banners', $setting['data'] );

		$this->callback->success_msg( '新增完成.' )->response();
	}

	/**
	 * 刪除
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function delete( $setting = array() ) {

		$setting['id'] = ( ! empty( $setting['id'] ) ) ? $setting['id'] : NULL;

		$this->callback->response_if_condition( is_null( $setting['id'] ), '刪除發生錯誤，找不到id.' );

		$this->db_ad->delete( 'common_ad_banners', $setting );

		$this->callback->success_msg( '刪除完成.' )->response();
	}
}

//