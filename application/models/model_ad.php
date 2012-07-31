<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_ad extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->database( 'ad_banners' );
		// $this->ad = $this->load->database( 'ad_banners', TRUE );
	}

	public function get_ad( $setting = array() ) {

		$setting['case'] = ( ! is_null( $setting['case'] ) ) ? $setting['case'] : 'common_top3_270x60';
		
		switch ( $setting['case'] ) {
			case 'common_top3_270x60':
				$ad_entities = $this->_get_common_top3_270x60();
				break;
		}

		return $ad_entities;
	}

	private function _get_common_top3_270x60( $setting = array() ) {
		
		$this->db->query( "
				CREATE TABLE IF NOT EXISTS `ad_banners`(
					`id` SMALLINT(5)NOT NULL AUTO_INCREMENT,
					`case` VARCHAR(20)NULL,
					`type` VARCHAR(20)NULL DEFAULT 'flash',
					`path` VARCHAR(255)NULL,
					`link` VARCHAR(255)NULL,
					`active` TINYINT(1)NULL,
					PRIMARY KEY(`id`)
				);
			" );

		$this->db->from( 'ad_banners' );

		return $this->db->get()->result_array();
	}

}
