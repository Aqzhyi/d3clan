<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Ads {

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model( 'model_ad' );
	}

	public function get( $setting = array() ) {

		$setting['case'] = ( ! is_null( $setting['case'] ) ) ? $setting['case'] : '270x60';
		$setting['limit'] = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 1000;

		return $this->CI->model_ad->get_ad( array(
				'case' => $setting['case'],
				'limit' => $setting['limit'],
			) );
	}
}


//
