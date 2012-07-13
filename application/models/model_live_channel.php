<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_live_channel extends CI_Model {

	function __construct() {
		parent::__construct();
	}


	public function post_channel( $values = array() ) {
		$sc_db = $this->load->database( 'StarCraftII', TRUE );
		$sc_db->insert( 'index_livestream_config', $values );
		return $this;
	}

	/**
	 * 獲取D3頻道
	 * @param  array  $setting [description]
	 * @return [type]          [description]
	 */
	public function get_d3_channels( $setting = array() ) {

		$sc_db = $this->load->database( 'StarCraftII', TRUE );

		if ( ! is_array( $setting ) ) {
			unset( $setting );
			$setting = array();
		}
		
		$setting['id']        = ( ! empty( $setting['id'] ) ) ? $setting['id'] : NULL;
		$setting['limit']     = ( ! empty( $setting['limit'] ) ) ? $setting['limit'] : 15;
		$setting['offset']    = ( ! empty( $setting['offset'] ) ) ? $setting['offset'] : 0;
		$setting['order_by']  = ( ! empty( $setting['order_by'] ) ) ? $setting['order_by'] : 'status asc, viewer_count desc';
		$setting['game_type'] = ( ! empty( $setting['game_type'] ) ) ? $setting['game_type'] : NULL;
		$setting['location']  = ( ! empty( $setting['location'] ) ) ? $setting['location'] : NULL;
		$setting['status']    = ( ! empty( $setting['status'] ) ) ? $setting['status'] : NULL;
		$setting['first_row'] = ( ! empty( $setting['first_row'] ) ) ? $setting['first_row'] : FALSE;

		if ( ! empty( $setting['game_type'] ) ) $sc_db->where( 'game_type', $setting['game_type'] );
		if ( ! empty( $setting['location'] ) )  $sc_db->where( 'location', $setting['location'] );
		if ( ! empty( $setting['status'] ) )    $sc_db->where( 'status', $setting['status'] );
		if ( ! empty( $setting['id'] ) )        $sc_db->where( 'sn', $setting['id'] );

		$sc_db->order_by( $setting['order_by'] );

		if ($setting['first_row']) {
			return $sc_db->get( 'index_livestream_config', $setting['limit'], $setting['offset'] )->first_row('array');
		}
		return $sc_db->get( 'index_livestream_config', $setting['limit'], $setting['offset'] )->result_array();
	}

	/**
	 * 刪除
	 * @param  array  $setting [description]
	 * @return [type]          [description]
	 */
	public function delete( $setting = array() ) {
		$sc_db = $this->load->database( 'StarCraftII', TRUE );

		$setting['id'] = ( ! empty( $setting['id'] ) ) ? $setting['id'] : 0;

		if ( empty( $setting['id'] ) ) {
			return FALSE;
		}

		$sc_db->delete( 'index_livestream_config', array(
				'sn' => $setting['id'],
			) );
	}
}
