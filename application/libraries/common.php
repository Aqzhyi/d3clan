<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Common {

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model( 'core/model_ad' );
	}

	/**
	 * 取得全域 270x60 小橫幅廣告
	 * 這是位於全站<header>上的橫幅廣告, 尺寸為270x60, 隨機顯示其中三個.
	 *
	 * @return array [description]
	 */
	public function get_270x60_banners() {

		return $this->CI->model_ad->get_ad( array(
				'enable_d3clan' => true,
				'case'          => '270x60',
				'limit'         => 10000,
				'order'         => 0,
			) );
	}
}


//