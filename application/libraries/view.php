<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
 * View 是用來在 controller 中, 協助開發者對 html views 作半自動化設定之 lib.
 *
 * @author aiyswu at gmail
 */
class View {
	// 給內嵌於樣版中的view用的data
	public $data = array();

	// 輸出給js的data
	public $json = array();

	// 緩存時間
	private $_cache_time = 15;

	// 自動標題路由
	private $_title_routes = array();

	// 一級標題
	private $_title = '';

	// 二級標題
	private $_append_title = '';

	// 版型路徑
	private $_layout = '';

	// 子版型路徑
	private $_page = '';
	private $_page_params = array();

	// js檔案路徑
	private $_js_files = array();
	private $_linked_js_files = array();

	// css檔案路徑
	private $_css_files = array();

	// og:image
	private $_og_image = '';

	// CI核心
	private $CI;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library( 'template' );
		$this->_js_files  = $this->CI->config->item( 'js_common_files' );
		$this->_css_files = $this->CI->config->item( 'css_common_files' );
		$this->_og_image  = base_url() . "static/img/common/layout/160.d3clan_logo.png";
		$this->_title_routes = array(
				'index' => '首頁',
			);
	}

	public function init( $controller = array() ) {
		
		// 如果控制器呼叫的頁面存在, 則執行它.
		if ( method_exists( $controller, $this->_page ) ) {
			return call_user_func_array( array( $controller, $this->_page ), $this->_page_params );
		}

		$this->show();
	
		return $this;
	}

	public function show( $setting = array() ) {
		
		// 如果該css檔案未建立, 則移除, 避免報錯.
		foreach ( $this->_css_files as $key => $path ) {
			if ( ! is_file( $this->CI->config->item( 'css_static_path' ) . $path . '.css' ) ) {
				unset( $this->_css_files[$key] );
			}
		}
		// 如果該js檔案未建立, 則移除, 避免報錯.
		foreach ( $this->_js_files as $key => $path ) {
			if ( ! is_file( $this->CI->config->item( 'js_static_path' ) . $path . '.js' ) ) {
				unset( $this->_js_files[$key] );
			}
		}

		// 緩存
		if ( ENVIRONMENT === 'production' ) {
			$this->CI->output->cache( $this->_cache_time );
		}

		// Profile
		if ( ENVIRONMENT !== 'production' ) {
			$this->CI->output->enable_profiler( TRUE );
		}

		// 自動標題路由
		if ( $this->_title === '' && is_string( $this->_page ) && $this->_page !== '' ) {
			$this->_title = $this->_title_routes[ $this->_page ];
		}
		
		// 輸出 view 給瀏覽器
		$this->CI->template->display( 'common/layout', array(
				'data'            => $this->data,
				'json'            => json_encode( $this->json ),
				'layout'          => $this->_layout,
				'page'            => $this->_page,
				'js_files'        => $this->_js_files,
				'linked_js_files' => $this->_linked_js_files,
				'css_files'       => $this->_css_files,
				'title'           => $this->_title,
				'append_title'    => $this->_append_title,
				'og_image'        => $this->_og_image,
			) );
	
		return $this;
	}

	public function css_add( $paths = array() ) {
		// 增加css檔案至視圖供瀏覽器載入.
		if ( is_array( $paths ) ) {
			$this->_css_files = array_merge( $this->_css_files, $paths );
		}
		elseif ( is_string( $paths ) ) {
			$this->_css_files[] = $paths;
		}

		return $this;
	}

	public function js_add( $paths = array() ) {
		// 增加js檔案至視圖供瀏覽器載入.
		if ( is_array( $paths ) ) {
			$this->_js_files = array_merge( $this->_js_files, $paths );
		}
		elseif ( is_string( $paths ) ) {
			$this->_js_files[] = $paths;
		}

		return $this;
	}

	public function layout( $setting = '' ) {
		$this->_layout = $setting;

		return $this;
	}

	/**
	 * 用於指定子頁面之統一接口
	 * @param  string $setting [description]
	 * @return [type]          [description]
	 */
	public function page( $page = '', $params = array() ) {
		$this->_page = $page;
		$this->_page_params = $params;

		return $this;
	}

	public function title_routes( $setting = array() ) {
		$this->_title_routes = array_merge( $this->_title_routes, $setting );

		return $this;
	}

	public function title( $setting = '' ) {
		$this->_title = $setting;

		return $this;
	}

	/**
	 * title的二級層
	 * title的格式為：「title('') - append_title('') - config['site_name']」
	 * 
	 * @param  string $setting [description]
	 * @return [type]          [description]
	 */
	public function append_title( $setting = '' ) {
		$this->_append_title = $setting;

		return $this;
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
			$this->_cache_time = $cache_time;
		}
		else {
			$this->_cache_time = 0;
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

	////////////////////////////////////////////////* 兼容 */
	////////////////////////////////////////////////* 兼容 */
	////////////////////////////////////////////////* 兼容 */

	/**
	 * 對 html view 初始化, 配置 controller 對應的 view 檔案以及各別 css/js 檔案.
	 *
	 * @param array   $setting 配置之集合.
	 * @return array           與傳統傳入view之$data無異.
	 */
	public function instance( $setting = array() ) {

		if ( ! empty( $setting['cache'] ) and is_numeric( $setting['cache'] ) ) {
			$this->_cache_time = $setting['cache'];
		}
		else {
			$this->_cache_time = 0;
		}

		return array(
			// 配置 view 的標題
			'loaded_title'         => $this->_set_title( $setting['title'] ),
			// 配置內嵌在樣版之中的 diy view
			'loaded_view'          => $this->_load_view( $setting['view'] ),
			// 配置 view 各別的 js 檔案(們)
			'loaded_js_files'      => $this->_load_js_files( $setting['js_files'] ),
			// 配置 view 各別的 css 檔案(們)
			'loaded_css_files'     => $this->_load_css_files( $setting['css_files'] ),
			// 配置 view 各別的 css 檔案(們)
			'linked_js_files'      => $this->_link_js_files( $setting['js_links'] ),
			// 配置 view 各別的 css 檔案(們)
			'linked_css_files'     => $this->_link_css_files( $setting['css_links'] ),
			// 輸出給js的data
			'loaded_json_metadata' => json_encode( $this->json ),
			// 輸出ogimage
			'loaded_og_image'      => ( $setting['og_image'] ) ? $setting['og_image'] : base_url()."static/img/common/layout/160.d3clan_logo.png",
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
			$CI->output->cache( $this->_cache_time );
		}

		// Profile
		if ( ENVIRONMENT !== 'production' ) {
			$CI->output->enable_profiler( TRUE );
		}

		// 輸出 view 給瀏覽器
		$CI->load->view( $path, $this->instance( $setting ) );
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
