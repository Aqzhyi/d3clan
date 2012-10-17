<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
 * View 是用來在 controller 中, 協助開發者對 html views 作半自動化設定之 lib.
 *
 * 本library需配合 [Codeigniter-Smarty](https://github.com/QueenbyeR/Codeigniter-Smarty) library.
 */
class view {
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
	private $_page        = 'index';
	private $_page_params = array();

	// js檔案路徑
	private $_js_files        = array();
	private $_linked_js_files = array();

	// css檔案路徑
	private $_css_files = array();

	// plugin檔案
	private $_plugin_js_files  = array();
	private $_plugin_css_files = array();

	// og:image
	private $_og_image = '';

	// CI核心
	private $CI;

	// 請求的view是否已經向瀏覽器輸出過
	private $view_is_show = false;

	// 請求的view是否已有cache
	private $view_is_cached = false;

	// 是否是非同步請求
	public $request_is_ajax = false;

	function __construct() {

		$this->CI =& get_instance();
		$this->CI->config->load( 'site' ); // 載入網站基本配置
		$this->_js_files  = $this->CI->config->item( 'js_common_files' );
		$this->_css_files = $this->CI->config->item( 'css_common_files' );
		$this->_og_image  = null;
		$this->_title_routes = array(
				'index' => '首頁',
			);
	}

	//# 初始化一個視圖
	/**
	 * 初始化一個視圖
	 *
	 * 完成一個視圖該作的事, 例如自動載入plugin, 控制緩存, 控制_remap路由.
	 * 之後, 自動載入show()接口, 因此可取代show()接口成為視圖操作最後步驟.
	 * 
	 * @param  array  $controller [description]
	 * @return [type]             [description]
	 */
	public function init( $controller = array() ) {

		$this->view_is_cached = $this->CI->template->isCached( $this->CI->config->item( 'application_layout_path' ) . '.html', $this->CI->uri->uri_string() );

		//# 緩存
			if ( ENVIRONMENT === 'production' AND $this->_cache_time > 0 ) {
				$this->CI->template->setCaching( Smarty::CACHING_LIFETIME_SAVED );
				$this->CI->template->setCacheLifetime( ( $this->_cache_time*60 ) );
			}
			else {
				$this->CI->template->setCaching( Smarty::CACHING_OFF );
				$this->CI->template->setCacheLifetime( 0 );
				$this->CI->template->setCompileCheck( true );
				// $this->CI->template->clearCompiledTemplate();
				// $this->CI->template->clearAllCache();
			}

		//# 自動載入plugin
			if ( ! $this->view_is_cached ) {
				$plugin_autoload = $this->CI->config->item( 'plugin_autoload' );
				if ( ! empty( $plugin_autoload ) ) {
					foreach ( $plugin_autoload as $key => $plugin_name ) {
						$this->plugin_add( $plugin_name );
					}
				}
			}

		//# 如果控制器呼叫的頁面存在, 則執行它.
			if ( ! $this->view_is_cached ) {
				if ( method_exists( $controller, $this->_page ) ) {
					call_user_func_array( array( $controller, $this->_page ), $this->_page_params );
				}
			}

		//#
			if ( $this->view_is_show === false and $this->CI->input->is_ajax_request() === false and $this->request_is_ajax === false ) {
				$this->show();
				$this->view_is_show = true;
			}
	
		return $this;
	}

	//# 將視圖輸出至瀏覽器
	/**
	 * 將視圖輸出至瀏覽器
	 *
	 * 完成所有配置後, 在最後使用本接口把視圖輸出至瀏覽器, 本接口作為視圖操作的最後步驟.
	 * 
	 * @param  array  $setting [description]
	 * @return [type]          [description]
	 */
	public function show( $setting = array() ) {
		
		$view_path = FCPATH . APPPATH . 'views/';

		//# 如果有配置layout而且該layout檔案未建立, 則報錯.
			if ( $this->_layout !== '' AND ! is_file( FCPATH . APPPATH . 'views/' . $this->_layout . '.html' ) ) {
				show_error( "找不到配置的 layout 檔案：<br /><b>" . '/views/' . $this->_layout . '.html</b>' );
			}

		//# 如果該css檔案未建立, 則移除, 避免報錯.
			foreach ( $this->_css_files as $key => $path ) {
				if ( ! is_file( $this->CI->config->item( 'css_static_path' ) . $path . '.css' ) ) {
					unset( $this->_css_files[$key] );
				}
			}

		//# 如果該js檔案未建立, 則移除, 避免報錯.
			foreach ( $this->_js_files as $key => $path ) {
				if ( ! is_file( $this->CI->config->item( 'js_static_path' ) . $path . '.js' ) ) {
					unset( $this->_js_files[$key] );
				}
			}

		//# 檢查plugin檔案是否存在, 若未建立則移除避免錯誤.
			$plugin = array();
			// plugin css檔案
			foreach ( $this->_plugin_css_files as $key => $path ) {
				if ( ! is_file( $path . '.css' ) ) {
					unset( $this->_plugin_css_files[$key] );
				}
				else {
					$plugin['css'] .= '/' . $path . '.css,';
				}
			}
			// plugin js檔案
			foreach ( $this->_plugin_js_files as $key => $path ) {
				if ( ! is_file( $path . '.js' ) ) {
					unset( $this->_plugin_js_files[$key] );
				}
				else {
					$plugin['js'] .= '/' . $path . '.js,';
				}
			}
			// 將plugin組合好的路徑最佳化, 提供輸出.
			if ( ! empty( $this->_plugin_css_files ) ) $plugin['css'] = substr( $plugin['css'], 0, strripos( $plugin['css'], ',' ) );
			if ( ! empty( $this->_plugin_js_files ) )  $plugin['js']  = substr( $plugin['js'], 0, strripos( $plugin['js'], ',' ) );

		//# Profile
			if ( ENVIRONMENT !== 'production' ) {
				$this->CI->output->enable_profiler( TRUE );
			}

		//# 自動標題路由
			if ( $this->_title === '' && is_string( $this->_page ) && $this->_page !== '' ) {
				$this->_title = $this->_title_routes[ $this->_page ];
			}

		//# 建立視圖
			$this->CI->template->assign( 'CI', $this->CI );
			$this->CI->template->assign( 'TEMPLATE', array(
					'json'               => json_encode( $this->json ),
					'application_layout' => $this->_layout,
					'page'               => $this->_page,
					'js_files'           => $this->_js_files,
					'linked_js_files'    => $this->_linked_js_files,
					'css_files'          => $this->_css_files,
					'plugin_files'       => $plugin,
					'title'              => $this->_title,
					'title_append'       => $this->_title_append,
					'og_image'           => $this->_og_image,
				) );

		//# 輸出視圖給瀏覽器
			foreach ( array_keys( $this->data ) as $index => $key_name ) {
				$this->CI->template->assign( $key_name, $this->data[$key_name] );
			}
			
			// // 若使用output-filter, 當使用{nocache}標籤時, 將會遭遇無法將filter之結果緩存起來的問題.
			// // $this->CI->template->registerFilter("output", array($this, "view_whitespace_compress") );
			// // $this->CI->template->registerFilter("output", array($this, "view_image_auto_complete") );
			
			$this->CI->template->display( $this->CI->config->item( 'application_layout_path' ) . '.html', $this->CI->uri->uri_string() );
			// $this->CI->template->parse( $this->CI->config->item( 'application_layout_path' ), $this->data );
	
		return $this;
	}

	//# 將static檔案加入視圖中
	/**
	 * 將static檔案加入視圖中
	 * 
	 * @param  string $path     [路徑]
	 * @param  string $file_ext [副檔名僅支援css, js.]
	 */
	public function static_add( $path = '', $file_ext = null ) {

		if ( empty( $file_ext ) OR is_null( $file_ext ) ) {
			show_error( 'view::static_add( $path, $file_ext ) 需要在第二個參數配置, 妥當配置副檔名字串.' );
		}

		// 配置中段路徑
		switch ( $file_ext ):
		case 'css' : $static_path = $this->CI->config->item( 'css_static_path' ); break;
		case 'js'  : $static_path = $this->CI->config->item( 'js_static_path' ); break;
		default    : $static_path = ''; break;
		endswitch;

		// 傳入的是陣列
		if ( is_array( $path ) ) {

			// 遞迴
			foreach ( $path as $key => $single ) {
				$this->static_add( $single, $file_ext );
			}
		}

		// 傳入的是字串
		elseif ( is_string( $path ) ) {

			// 尋找通配符
			// 通配符標準語法必須是: view->static_add( "{$controller_name}/{$folder}/*" );
			$pos = strpos( $path, '*' );

			// 如果有通配符, 則找出該資料夾中, 所有的static檔案並引入.
			if ( $pos > 0 ) {

				// 找出該資料夾
				$folder_path = substr( $path, - $pos - 1, $pos - 1 );

				$fc_folder_path = FCPATH . $static_path . $folder_path;

				if ( is_dir( $fc_folder_path ) ) {

					// 掃描資料夾, 把所有static檔案引入.
					foreach ( scandir( $fc_folder_path ) as $index => $file ) {
						if ( strpos( $file, '.' . $file_ext ) ) {
							$this->static_add( $folder_path . '/' . $file, $file_ext );
						}
					}
				}
			}
			
			// 檢查副檔名重複
			if ( strpos( $path, '.' . $file_ext ) ) {
				$path = substr( $path, 0, strlen( $path ) - ( strlen( $file_ext ) + 1 ) );
			}

			// 檢查static檔案是否存在
			if ( is_file( FCPATH . $static_path . $path . '.' . $file_ext ) ) {
				switch ( $file_ext ):
				case 'css' : $this->css( $path ); break;
				case 'js'  : $this->js( $path ); break;
				endswitch;
			}
		}

		return $this;
	}

	//# 增加plugin檔案
	/**
	 * 增加plugin檔案
	 * 
	 * 本接口會自動載入相對應plugin名稱之下的, 所有事先配置好的css/js檔案.
	 * plugin相對應之檔案須要在 app/config/site.php 就事先配置完成.
	 * 
	 * @param  string $plugin_name [description]
	 * @return [type]              [description]
	 */
	public function plugin_add( $plugin_name = '' ) {
		
		$plugin_files = $this->CI->config->item( 'plugin_files' );

		foreach ( $plugin_files[$plugin_name] as $type => $paths) {
			if ( $type === 'css' ) {
				foreach ( $paths as $index => $path ) {
					$this->_plugin_css_files[] = $this->CI->config->item( 'plugin_static_path' ) . $path;
				}
			}
			if ( $type === 'js' ) {
				foreach ( $paths as $index => $path ) {
					$this->_plugin_js_files[] = $this->CI->config->item( 'plugin_static_path' ) . $path;
				}
			}
		}

		return $this;
	}

	//# 增加css檔案至視圖供瀏覽器載入
	/**
	 * 增加css檔案至視圖供瀏覽器載入
	 * 
	 * @param  array  $paths [description]
	 * @return [type]        [description]
	 */
	public function css( $paths = array() ) {
		// 增加css檔案至視圖供瀏覽器載入.
		if ( is_array( $paths ) ) {
			$this->_css_files = array_merge( $this->_css_files, $paths );
		}
		elseif ( is_string( $paths ) ) {
			$this->_css_files[] = $paths;
		}

		return $this;
	}

	//# 增加js檔案至視圖供瀏覽器載入
	/**
	 * 增加js檔案至視圖供瀏覽器載入
	 * 
	 * @param  array  $paths [description]
	 * @return [type]        [description]
	 */
	public function js( $paths = array() ) {
		// 增加js檔案至視圖供瀏覽器載入.
		if ( is_array( $paths ) ) {
			$this->_js_files = array_merge( $this->_js_files, $paths );
		}
		elseif ( is_string( $paths ) ) {
			$this->_js_files[] = $paths;
		}

		return $this;
	}

	//# 增加js連結至視圖供瀏覽器載入
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

	//# 用於指定父頁面之統一接口
	public function layout( $setting = '' ) {
		$this->_layout = $setting;

		return $this;
	}

	//# 用於指定子頁面之統一接口
	/**
	 * 用於指定子頁面之統一接口
	 * @param  string $setting [description]
	 * @return [type]          [description]
	 */
	public function page( $page = '', $params = array() ) {
		$this->_page        = $page;
		$this->_page_params = $params;

		return $this;
	}

	//# 網頁標題路由
	public function title_routes( $setting = array() ) {
		$this->_title_routes = array_merge( $this->_title_routes, $setting );

		return $this;
	}

	//# 手動指定標題
	public function title( $setting = '' ) {
		$this->_title = $setting;

		return $this;
	}

	//# title的二級層
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

	//# 指定緩存時間
	/**
	 * 指定緩存時間
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

	//# 取得 canonical 網址
	/**
	 * 取得 canonical 網址
	 *
	 * @return string 返回 Canonical 網址
	 */
	public function get_canonical_url() {
		$url = preg_replace( '@index.php\/?@', '', current_url() );
		return preg_replace( '@\/%.*@', '', $url );
	}

	//# 處理掉多餘的空格與換行
	/**
	 * 將輸出進行 compress 處理.
	 * 處理掉多餘的空格與換行.
	 * 處理掉 /index.php/ 醜網址.
	 *
	 * @param array   $setting=array() [description]
	 * @return [type]                  [description]
	 */
	public function view_whitespace_compress( $tpl_output, Smarty_Internal_Template $template ) {
		
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

		$tpl_output = preg_replace( $search, $replace, $tpl_output );

		return $tpl_output;
	}

	//# 自動完成<img>的屬性配置
	/**
	 * 將輸出的 圖片(<img>) 進行處理；自動配置 alt 屬性。
	 *
	 * @param array   $setting=array() 設定
	 *                $setting['output_display'] => 是否直接輸出給瀏覽器。FALSE代表不直接輸出，僅回存至buffer。
	 * @return NULL   不會回傳東西。
	 */
	public function view_image_auto_complete( $tpl_output, Smarty_Internal_Template $template ) {
		
		$CI =& get_instance();
		$CI->load->library( 'core/simple_html_dom' ); // require_once APPPATH . 'libraries/simple_html_dom.php';

		if ( ! empty( $tpl_output)) {
			$DOM = str_get_html( $tpl_output );
			foreach ( $DOM->find( 'img' ) as $key => $img ) {
				// 缺乏圖片網址
				if ( empty( $img->src ) ) {
					// $img->style = 'background: url(/static/img/common/icon/32img-landscape-error.png) no-repeat 50% 50%;';
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

			$tpl_output = $DOM->save();
		}

		return $tpl_output;
	}
}

//