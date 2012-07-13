<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_news extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 首頁輪播
	 * @param  array  $setting [description]
	 * @return [type]          [description]
	 */
	public function get_circle_loop( $setting = array() ) {
		
		return array(
			'circle_loop_data' => array(
				array(
					'img'   => '/static/img/unsorted/CTipI.png',
					'title' => '暗盟《暗黑破壞神III》電競情報站 開張!!',
					'descr' => '由星盟電競團隊全力開發，全台唯一以《暗黑破壞神III》為主題之專屬網站，提供各類暗黑破壞神的全球新訊、直播頻道、交易平台、約戰中心，與電競賽事等專業服務。',
					'link'  => '',
				),
			),
		);
	}

	/**
	 * 取得新聞資訊流條目
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_flow( $setting = array() ) {

		$setting['fid']    = ( ! is_null( $setting['fid'] ) ) ? $setting['fid'] : NULL;
		$setting['typeid'] = ( ! is_null( $setting['typeid'] ) ) ? $setting['typeid'] : NULL;
		$setting['digest'] = ( ! is_null( $setting['digest'] ) ) ? $setting['digest'] : NULL;

		$this->db->select( 't.*, tc.name' );
		$this->db->from( 'd3bbs_forum_thread as t' );
		$this->db->join( 'd3bbs_forum_threadclass as tc', 't.typeid = tc.typeid' );
		$this->db->limit( 15, 0 );
		if ( ! is_null( $setting['fid'] ) ) $this->db->where_in( 't.fid', $setting['fid'] );
		if ( ! is_null( $setting['typeid'] ) ) $this->db->where_in( 't.typeid', $setting['typeid'] );
		if ( ! is_null( $setting['digest'] ) ) $this->db->where_in( 't.digest', $setting['digest'] );
		$this->db->order_by( 'tid', 'desc' );

		return $this->db->get()->result_array();
	}
}
