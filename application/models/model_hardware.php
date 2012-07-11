<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_hardware extends CI_Model {

	public function __construct() {
		parent::__construct();
		// $d3 = $this->load->database( 'DiabloIII', TRUE );
		// $sc2 = $this->load->database( 'StarCraftII', TRUE );
	}

	public function get_thread( $setting = array() ) {
		$this->load->library( 'bbcode' );
		$this->load->library( 'simple_html_dom' );
		$this->load->helper( 'string' );

		$setting['typeid']     = ( ! is_null( $setting['typeid'] ) ) ? $setting['typeid'] : NULL;
		$setting['not_typeid'] = ( ! is_null( $setting['not_typeid'] ) ) ? $setting['not_typeid'] : NULL;
		$setting['limit']      = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 6;
		$setting['offset']     = ( ! is_null( $setting['offset'] ) ) ? $setting['offset'] : 0;

		if ( is_null( $setting['typeid'] ) AND is_null( $setting['not_typeid'] ) ) { return array(); }

		$this->db->select( '*' );
		$this->db->from( 'd3bbs_forum_post as p' );
		$this->db->join( 'd3bbs_forum_thread as t', 't.tid = p.tid' );
		$this->db->where( 'p.first', 1 );
		if ( ! is_null( $setting['typeid'] ) ) $this->db->where_in( 't.typeid', $setting['typeid'] );
		if ( ! is_null( $setting['not_typeid'] ) ) $this->db->where_not_in( 't.typeid', $setting['not_typeid'] );
		$this->db->where_in( 't.fid', array( 44, 45 ) );
		$this->db->limit( $setting['limit'], $setting['offset'] );
		$this->db->order_by( 'pid', 'desc' );
		$sql = $this->db->get();

		$threads = array();
		// 取得略縮圖,略縮文
		foreach ( $sql->result_array() as $key => $thread ) {

			$threads[$key] = $thread;

			// 略縮圖
			$thread['message'] = $this->bbcode->toHTML( $thread['message'] );
			$html = str_get_html( $thread['message'] );
			$threads[$key]['first_img_thumb'] = $html->find( 'img', 0 )->src;

			// 略縮文
			foreach ( $html->find( 'img' ) as $index => $dom ) $dom->outertext = '';
			$html->save();
			$threads[$key]['first_text_thumb'] = string_cut( strip_tags( $html->find( 'strong,font,span,p,div', 0 )->innertext ), 100 );

			// 略縮標題
			$threads[$key]['subject'] = string_cut( $threads[$key]['subject'], 20 );

			// 垃圾回收
			$html->clear();
			unset( $html );
		}
		return $threads;
	}
}

//
