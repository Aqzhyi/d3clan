<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_api extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function is_repeat( $setting = array() ) {

		/* (array) */$setting['fid']           = ( ! is_null( $setting['fid'] ) ) ? $setting['fid'] : array( 54, 55 );
		/* (int) */$setting['limit']           = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 10;
		/* (string) */$setting['subject']      = ( ! is_null( $setting['subject'] ) ) ? $setting['subject'] : NULL;

		$this->db->select( '*' );
		$this->db->from( 'd3bbs_forum_thread as t' );
		if ( ! is_null( $setting['fid'] ) ) $this->db->where_in( 't.fid', $setting['fid'] );
		$this->db->where( 't.displayorder !=', '-1' );
		$this->db->where( 'subject', $setting['subject'] );
		$this->db->order_by( 'tid', 'desc' );
		$this->db->limit( $setting['limit'], 0 );

		$result = $this->db->get()->result_array();

		// 找到 result 代表重複了
		if ( ! empty( $result ) ) {
			return true;
		}

		// 找不到 result 代表這篇是未PO過的文章
		else {
			return false;
		}

		// return $this->db->get()->result_array();
	}
}
