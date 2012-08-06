<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * ajax類
 */
class Ajax {

	private $uri_to_assoc = 2;

	private $routes = array();

	public $uris = array();

	function __construct() {
		$this->CI =& get_instance();
	}

	/**
	 * 初始化 ajax
	 * @param  array  $controller [description]
	 * @return [type]             [description]
	 */
	public function init( $controller = array() ) {

		// uri parse
		$this->uris = $this->CI->uri->uri_to_assoc( $this->uri_to_assoc );

		// 別把 ajax id 一起傳入給 func
		// ajax id 是作為 "路由" 所使用
		$ajax_id = $this->uris['ajax'];
		unset( $this->uris['ajax'] );

		// post data parser
		parse_str( file_get_contents( 'php://input' ), $params );
		
		// 執行 ajax 分流
		if ( $this->CI->input->is_ajax_request() ) {
			call_user_func_array( array( $controller, $this->routes[ $ajax_id ] ), $params );
		}
		else {
			show_404();
		}
	}

	/**
	 * uri 路由
	 * @param  array  $routes [description]
	 * @return [type]         [description]
	 */
	public function uri_routes( $routes = array() ) {
		
		$this->routes = $routes;
	
		return $this;
	}

	/**
	 * uri 偏移
	 * @param  integer $setting [description]
	 * @return [type]           [description]
	 */
	public function uri_offset( $setting = 3 ) {
		
		$this->uri_to_assoc = $setting;
	
		return $this;
	}
}

//
