<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
 * View 是用來在 controller 中, 協助開發者對 html views 作半自動化設定之 lib.
 *
 * @author aiyswu at gmail
 */
class View {
	public $data; // 給內嵌於樣版中的view用的data
	private $cache_time; // 緩存時間

	/**
	 * 對 html view 初始化, 配置 controller 對應的 view 檔案以及各別 css/js 檔案.
	 *
	 * @param array   $setting 配置之集合.
	 * @return array           與傳統傳入view之$data無異.
	 */
	public function instance( $setting = array() ) {

		if ( ! empty( $setting['cache'] ) and is_numeric( $setting['cache'] ) ) {
			$this->cache_time = $setting['cache'];
		}
		else {
			$this->cache_time = 0;
		}

		return array(
			'loaded_title'     => $this->_set_title( $setting['title'] ), // 配置 view 的標題。
			'loaded_view'      => $this->_load_view( $setting['view'] ), // 配置內嵌在樣版之中的 diy view。
			'loaded_js_files'  => $this->_load_js_files( $setting['js_files'] ), // 配置 view 各別的 js 檔案(們)。
			'loaded_css_files' => $this->_load_css_files( $setting['css_files'] ), // 配置 view 各別的 css 檔案(們)。
			'linked_js_files'  => $this->_link_js_files( $setting['js_links'] ), // 配置 view 各別的 css 檔案(們)。
			'linked_css_files' => $this->_link_css_files( $setting['css_links'] ), // 配置 view 各別的 css 檔案(們)。
			'loaded_og_image'  => ( $setting['og_image'] ) ? $setting['og_image'] : base_url()."static/img/common/layout/160.d3clan_logo.png",
		);
	}

	/**
	 * 載入 view 並輸出至瀏覽器
	 *
	 * @param string  $path 所欲輸出至瀏覽器之必要載入的 view 之路徑。
	 * @param array   $data 所欲輸出至瀏覽器的任意資料。
	 * @return null         無回傳
	 */
	public function display( $path = 'common/common', $setting = array() ) {
		$CI =& get_instance();

		if ( is_array( $path ) ) {
			$setting = $path;
			unset( $path );
		}

		// 缺省路徑
		if ( empty( $path ) ) {
			$path = 'common/common';
		}

		// 緩存
		if ( ENVIRONMENT === 'production' ) {
			$CI->output->cache( $this->cache_time );
		}

		// Profile
		if ( ENVIRONMENT !== 'production' ) {
			$CI->output->enable_profiler(TRUE);
		}

		// 輸出 view 給瀏覽器
		$CI->load->view( $path, $this->instance( $setting ) );
	}

	/**
	 * 配置緩存
	 * 就如同 $this->output->cache(n) 一樣。n 以分鐘計。
	 *
	 * @param integer $cache_time [description]
	 * @return [type]              [description]
	 */
	public function cache( $cache_time = 0 ) {
		if ( is_numeric( $cache_time ) ) {
			$this->cache_time = $cache_time;
		}
		else {
			$this->cache_time = 0;
		}

		return $this;
	}

	/**
	 * 取得 canonical 網址
	 *
	 * @return string 返回 Canonical 網址
	 */
	public function get_canonical_url() {
		$url = preg_replace( '@index.php\/?@', '', current_url() );
		return preg_replace( '@\/%.*@', '', $url );
	}

	/**
	 * 配置 view 的標題
	 *
	 * @param string  $title 欲配置的標題
	 * @return [type]        配置好的標題
	 */
	private function _set_title( $title = '' ) {
		$CI =& get_instance();
		if ( ! empty( $title ) ) {
			return $title . ' - ' . $CI->config->item( 'site_name' );
		}
		elseif ( empty( $title ) ) {
			return $CI->config->item( 'site_name' );
		}
		else {
			return $CI->config->item( 'site_name' );
		}
	}

	/**
	 * 配置 controller 對應的 view
	 *
	 * @param string  $path view 的路徑.
	 * @return string       view 的純 html code 於 view 中直接 echo 出來.
	 */
	private function _load_view( $path = 'home/index' ) {
		$CI =& get_instance();
		// 不輸出給瀏覽器, 直接返回 html code 以供 php 利用.
		return $CI->load->view( $path, $this->data, TRUE );
	}

	/**
	 * 配置對應的 view 所需要各別載入的 js 檔案(們)
	 *
	 * @param array   $paths 各別的js檔案的路徑
	 * @return [type]        解析完並整合 common js 檔案(們)後的路徑
	 */
	private function _load_js_files( $paths = array() ) {
		$CI =& get_instance();
		if ( $paths ) {
			$merged_array = array_merge( $CI->config->item( 'js_common_files' ), $paths );
		}
		else {
			$merged_array = $CI->config->item( 'js_common_files' );
		}
		// 如果該檔案未建立,則移除,以免minify報錯.
		foreach ( $merged_array as $key => $path ) {
			if ( ! is_file( $CI->config->item( 'js_static_path' ).$path.'.js' ) ) {
				unset( $merged_array[$key] );
			}
		}
		return $merged_array;
	}

	/**
	 * 配置對應的 view 所需要各別載入的 CSS 檔案(們)
	 *
	 * @param array   $paths 各別的 CSS 檔案的路徑
	 * @return [type]        解析完並整合 common CSS 檔案(們)後的路徑
	 */
	private function _load_css_files( $paths = array() ) {
		$CI =& get_instance();
		if ( $paths ) {
			$merged_array = array_merge( $CI->config->item( 'css_common_files' ), $paths );
		}
		else {
			$merged_array = $CI->config->item( 'css_common_files' );
		}
		// 如果該檔案未建立,則移除,以免minify報錯.
		foreach ( $merged_array as $key => $path ) {
			if ( ! is_file( $CI->config->item( 'css_static_path' ).$path.'.css' ) ) {
				unset( $merged_array[$key] );
			}
		}
		return $merged_array;
	}

	/**
	 * 配置對應的 view 所需要各別引入的 js 檔案(們)
	 *
	 * @param array   $urls [description]
	 * @return [type]       [description]
	 */
	private function _link_js_files( $urls = array() ) {
		$CI =& get_instance();
		if ( is_array( $urls ) ) {
			return array_merge( $CI->config->item( 'js_common_links' ), $urls );
		}
		else {
			return $CI->config->item( 'js_common_links' );;
		}
	}

	/**
	 * 配置對應的 view 所需要各別引入的 css 檔案(們)
	 *
	 * @param array   $urls [description]
	 * @return [type]       [description]
	 */
	private function _link_css_files( $urls = array() ) {
		$CI =& get_instance();
		if ( is_array( $urls ) ) {
			return array_merge( $CI->config->item( 'css_common_links' ), $urls );
		}
		else {
			return $CI->config->item( 'css_common_links' );;
		}
	}
}

//