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
	private $_title_append = '';

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

	// 是否show過了版形
	private $is_show = false;

	public $is_ajax_request = false;

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
			call_user_func_array( array( $controller, $this->_page ), $this->_page_params );
		}

		if ( $this->is_show === false and $this->CI->input->is_ajax_request() === false and $this->is_ajax_request === false ) {
			$this->show();
			$this->is_show = true;
		}
	
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
		$this->CI->template->assign( 'CI', $this->CI );
		$this->CI->template->assign( 'TEMPLATE', array(
				'json'               => json_encode( $this->json ),
				'application_layout' => $this->_layout,
				'page'               => $this->_page,
				'js_files'           => $this->_js_files,
				'linked_js_files'    => $this->_linked_js_files,
				'css_files'          => $this->_css_files,
				'title'              => $this->_title,
				'title_append'       => $this->_title_append,
				'og_image'           => $this->_og_image,
			) );

		// 輸出 view 給瀏覽器
		$this->CI->template->parse( $this->CI->config->item( 'application_layout_path' ), $this->data );
	
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

	public function js_link_add( $paths = array() ) {
		// 增加js連結至視圖供瀏覽器載入.
		if ( is_array( $paths ) ) {
			$this->_linked_js_files = array_merge( $this->_linked_js_files, $paths );
		}
		elseif ( is_string( $paths ) ) {
			$this->_linked_js_files[] = $paths;
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
	 * title的格式為：「title('') - title_append('') - config['site_name']」
	 * 
	 * @param  string $setting [description]
	 * @return [type]          [description]
	 */
	public function title_append( $setting = '' ) {
		$this->_title_append = $setting;

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
}

//
