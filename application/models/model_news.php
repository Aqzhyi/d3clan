<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_news extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->ad = $this->load->database( 'Business', TRUE );
	}

	/**
	 * 首頁輪播
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_circle_loop( $setting = array() ) {

		$this->ad->order_by( 'id', 'desc' );
		$this->ad->where( 'case', 'home_4_circle' );
		$this->ad->from( 'common_ad_banners' );

		return $this->ad->get()->result_array();
	}

	/**
	 * 取得新聞資訊流條目
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_flow( $setting = array() ) {

		/* (array) */$setting['fid']           = ( ! is_null( $setting['fid'] ) ) ? $setting['fid'] : NULL;
		/* (array) */$setting['typeid']        = ( ! is_null( $setting['typeid'] ) ) ? $setting['typeid'] : NULL;
		/* (array) */$setting['digest']        = ( ! is_null( $setting['digest'] ) ) ? $setting['digest'] : NULL;
		/* (int) */$setting['limit']           = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 15;
		/* (bool) */$setting['exclude_typeid'] = ( ! is_null( $setting['exclude_typeid'] ) ) ? $setting['exclude_typeid'] : FALSE;

		$this->db->select( '*' );
		$this->db->from( 'd3bbs_forum_thread as t' );
		if ( $setting['exclude_typeid'] === FALSE ) $this->db->join( 'd3bbs_forum_threadclass as tc', 't.typeid = tc.typeid' );
		if ( ! is_null( $setting['fid'] ) ) $this->db->where_in( 't.fid', $setting['fid'] );
		if ( ! is_null( $setting['typeid'] ) ) $this->db->where_in( 't.typeid', $setting['typeid'] );
		if ( ! is_null( $setting['digest'] ) ) $this->db->where_in( 't.digest', $setting['digest'] );
		$this->db->where( 't.displayorder !=', '-1' );
		$this->db->order_by( 'tid', 'desc' );
		$this->db->limit( $setting['limit'], 0 );

		return $this->db->get()->result_array();
	}
}
