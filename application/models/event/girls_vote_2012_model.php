<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Girls_vote_2012_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// $d3 = ->load->database( 'DiabloIII', TRUE );
		// $sc2 = ->load->database( 'StarCraftII', TRUE );
	}

	/**
	 * 撈取頁面必須資料
	 *
	 * @return [type] [description]
	 */
	public function get_girls( $setting = array() ) {

		$setting['tid'] = ( ! empty( $setting['tid'] ) ) ? $setting['tid'] : NULL;

		if ( is_null( $setting['tid'] ) OR ! is_array( $setting['tid'] )  ) {
			show_error('請以陣列格式輸入四個投票主題的tid.');
		}

		$girls = $this->_girls_detail();

		foreach ( $setting['tid'] as $key => $tid ) {
			$girls = $this->_merge_girl_poll( array(
					'tid'   => $tid,
					'girls' => $girls,
				) );
		}

		return $girls;
	}

	/**
	 * 結合論壇選票
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _merge_girl_poll( $setting = array() ) {

		$setting['tid']   = ( ! empty( $setting['tid'] ) ) ? $setting['tid'] : NULL;
		$setting['girls'] = ( ! empty( $setting['girls'] ) ) ? $setting['girls'] : NULL;

		if ( is_null( $setting['tid'] ) or ! is_numeric( $setting['tid'] ) ) {
			show_error('請輸入正確的tid數字格式.');
		}

		$this->db->where( 'tid', $setting['tid'] );
		$this->db->order_by( 'polloptionid', 'asc' );
		$sql = $this->db->get( 'd3bbs_forum_polloption' );

		$result = $sql->result_array();

		if ( ! count($result) ) {
			show_error("你的 tid 配置: {$setting['tid']}, 這個投票主題應該還未建立. 請先建立<b style='color: red;'>符合規劃</b>的投票主題後, 再重新配置 model 正確的 tid.");
		}

		foreach ( $sql->result_array() as $key => $girl ) {
			$girls[ $girl['polloption'] ] = $girl;
		}

		// 融合女孩們詳細資料與論壇票選
		foreach ( $setting['girls'] as $key => $value ) {
			if ( ! is_array( $girls[$key] ) or ! is_array( $setting['girls'][$key] ) ) continue;

			$girls[$key]                = array_merge( $girls[$key], $setting['girls'][$key] );
			$girls[$key]['total_votes'] += $girls[$key]['votes'];
			$girls[$key]['polls'][]     = $girls[$key]['votes'];

			unset( $girls[$key]['votes'] );
		}

		return $girls;
	}

	/**
	 * 詳細資料
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _girls_detail( $setting = array() ) {
		// 一號參賽者
		$girls['萱萱'] = array(
			'video' => 'http://www.youtube.com/watch?v=X6XXia5B2Wg',
			'photos' => array(
				'http://i.imgur.com/OzcoP.png',
				'http://i.imgur.com/hCAX3.png',
				'http://i.imgur.com/hCAX3.png',
				'http://i.imgur.com/OzcoP.png',
				'http://i.imgur.com/OzcoP.png',
			),
			'text_fields' => array(
				'nickname'   => '萱萱',
				'server'     => '亞服',
				'role_level' => '60',
				'birthday'   => '1993/03/24',
				'city'       => '台北',
				'class'      => '秘術師',
			),
			'intro' => '如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。',
		);

		// 二號參賽者
		$girls['貝拉'] = array(
			'video' => 'http://www.youtube.com/watch?v=UL4xVMtXP8k',
			'photos' => array(
				'http://i.imgur.com/OzcoP.png',
				'http://i.imgur.com/hCAX3.png',
				'http://i.imgur.com/hCAX3.png',
				'http://i.imgur.com/OzcoP.png',
				'http://i.imgur.com/OzcoP.png',
				'http://i.imgur.com/hCAX3.png',
				'http://i.imgur.com/OzcoP.png',
			),
			'text_fields' => array(
				'nickname'   => '貝拉',
				'server'     => '亞服',
				'role_level' => '60',
				'birthday'   => '1994/03/24',
				'city'       => '台北',
				'class'      => '野蠻人',
			),
			'intro' => '如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。如今，天生閃著妖異的亮光。',
		);

		return $girls;
	}
}
//
