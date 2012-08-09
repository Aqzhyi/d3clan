<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

/**
 * 回調類
 * 用來自動格式化給 JS 能夠吃的 JSON 格式，
 * 協助 JS 能夠理解 Controller 或 Model 所返回的狀態。
 * 這是一種暗盟的 PHP 與 AJAX 溝通的標準。
 */
class Callback {

	/**
	 * 資料陣列
	 *
	 * @var array
	 */
	private $data_array = array();

	function __construct() {

		$this->reset();
	}

	/**
	 * 響應
	 * 
	 * @return [type] [description]
	 */
	public function response() {
		
		echo json_encode( $this->data_array );
		exit;
	}

	/**
	 * 轉成 json 字串
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function toJSON( $setting = array() ) {

		return json_encode( $this->data_array );
	}

	/**
	 * 驗證是否有錯誤
	 *
	 * @return boolean          [description]
	 */
	public function is_error() {
		if ( $this->data_array['success'] !== TRUE ) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * 如果"條件"錯誤, 則立刻記錄"錯誤記錄"
	 * 
	 * @param  [type] $condition [description]
	 * @param  string $error_msg [description]
	 * @return [type]            [description]
	 */
	public function if_condition( $condition = NULL, $error_msg = '' ) {
		
		if ( $condition === true ) {
			$this->error_msg( $error_msg );
		}

		return $this;
	}

	/**
	 * 如果"條件"錯誤, 則立刻響應錯誤訊息.
	 * 
	 * @param  [type] $condition    [description]
	 * @param  string $error_msg [description]
	 * @return [type]            [description]
	 */
	public function response_if_condition( $condition = NULL, $error_msg = '' ) {
		
		if ( $condition === true ) {
			$this->error_msg( $error_msg );
			echo $this->toJSON();
			exit;
		}

		return $this;
	}

	/**
	 * 如果"錯誤記錄"中有任何錯誤, 則立刻響應錯誤訊息.
	 * 
	 * @return [type] [description]
	 */
	public function response_if_error() {
		
		if ( $this->is_error() ) {
			echo $this->toJSON();
			exit;
		}

		return $this;
	}
	
	/**
	 * 塞入成功判斷
	 *
	 * @param boolean $setting [description]
	 * @return [type]           [description]
	 */
	public function success( $setting = TRUE ) {

		if ( is_bool( $setting ) ) {
			$this->data_array['success'] = $setting;
		}
		else {
			$this->data_array['success'] = (bool) $setting;
		}

		return $this;
	}

	/**
	 * 塞入白話文錯誤訊息
	 * 如果有錯誤訊息的話，代表算是執行不成功了..
	 *
	 * @param string  $setting [description]
	 * @return [type]          [description]
	 */
	public function error_msg( $setting = '' ) {

		$this->success( FALSE );
		$this->data_array['error_msg'][] = $setting;

		return $this;
	}

	/**
	 * 成功訊息
	 * 但是它不能與錯誤並存
	 *
	 * @param string  $setting [description]
	 * @return [type]          [description]
	 */
	public function success_msg( $setting = '' ) {

		if ( $this->is_error() ) {
			return $this->error_msg( '你無法同時配置一條又成功又錯誤的回調訊息' );
		}

		$this->success( TRUE );
		$this->data_array['success_msg'] = $setting;

		return $this;
	}

	/**
	 * 塞入 data
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function data( $setting = array() ) {

		$this->data_array['data'] = $setting;

		return $this;
	}

	/**
	 * 回復類至初始化的狀態
	 *
	 * @return [type] [description]
	 */
	public function reset() {
		/**
		 * 本次執行結果是否算是令人滿意
		 *
		 * @var boolean
		 */
		$this->data_array['success'] = TRUE;

		/**
		 * 白話文錯誤訊息
		 *
		 * @var string
		 */
		$this->data_array['error_msg'] = array();

		/**
		 * 白話文成功訊息
		 */
		$this->data_array['success_msg'] = '';

		return $this;
	}

}

//
