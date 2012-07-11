<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 *
 */
class site_output_hook {

	function __construct() {
		// code...
	}

	/**
	 * 將輸出進行 compress 處理.
	 * 處理掉多餘的空格與換行.
	 * 處理掉 /index.php/ 醜網址.
	 *
	 * @param array   $setting=array() [description]
	 * @return [type]                  [description]
	 */
	public function compress( $setting=array() ) {
		$CI =& get_instance();
		$buffer = $CI->output->get_output();

		$search = array(
			'/\n/',   // replace end of line by a space
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',  // shorten multiple whitespace sequences
			'/\/index\.php\//'
		);

		$replace = array(
			' ',
			'>',
			'<',
			'\\1',
			'/'
		);

		$buffer = preg_replace( $search, $replace, $buffer );

		$CI->output->set_output( $buffer );

		if ( $setting['output_display'] ) {
			$CI->output->_display();
		}
	}

	/**
	 * 將輸出的 圖片(<img>) 進行處理；自動配置 alt 屬性。
	 *
	 * @param array   $setting=array() 設定
	 *                $setting['output_display'] => 是否直接輸出給瀏覽器。FALSE代表不直接輸出，僅回存至buffer。
	 * @return NULL                    不會回傳東西。
	 */
	public function images_auto_set( $setting=array() ) {
		$CI =& get_instance();
		$CI->load->library( 'simple_html_dom' ); // require_once APPPATH . 'libraries/simple_html_dom.php';
		$buffer = $CI->output->get_output();

		if ( ! empty($buffer)) {
			$DOM = str_get_html( $buffer );
			foreach ( $DOM->find( 'img' ) as $key => $img ) {
				// 缺乏圖片網址
				if ( empty( $img->src ) ) {
					$img->style = 'background: url(/static/img/common/icon/32img-landscape-error.png) no-repeat 50% 50%;';
					$img->alt = '圖片遺失 - ' . $CI->config->item( 'site_name' );
				}
				else {
					if ( empty( $img->alt ) && ! strpos( $img->alt, $CI->config->item( 'site_name' ) ) ) {
					$img->alt = $CI->config->item( 'site_name' );
					}
					else {
						$img->alt .= ' - ' . $CI->config->item( 'site_name' );
					}
				}
			}

			$CI->output->set_output( $DOM->save() );
		}
		
		if ( $setting['output_display'] ) {
			$CI->output->_display();
		}
	}

}

/* End of file compress.php */
/* Location: ./system/application/hools/compress.php */
