<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class model_amd_cup_2012 extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->db    = $this->load->database( 'events', TRUE );
		// $this->table = 'd3_amd_cup_2012__voters';
	}

	//# 投票go
	public function ajax_vote( $setting = array() ) {
		
		$setting['vote_to'] = ( ! is_null( $setting['vote_to'] ) ) ? $setting['vote_to'] : null;
		unset($setting['id']);

		$this->callback->response_if_condition( $setting['vote_to'] === null, '沒有指定被投票者' );
		
		$this->db->set( array(
				'week'      => $this->get_vote_week_key(),
				'voter_id'  => $this->user->get_id(),
				'vote_to'   => $setting['vote_to'],
				'insert_at' => date('Y-m-d H:i'),
				'insert_by' => $this->user->get_id(),
			) );

		$this->db->insert( 'd3_amd_cup_2012__voters' );

		$this->callback->success_msg( '您已投票成功' )->response();
	}

	//# 取得是否投過票
	public function is_vote( $setting = array() ) {

		// 本週內
		$this->db->where( 'week', $this->get_vote_week_key() );
		// 本日內
		// $this->db->where( 'insert_at >' . date('Y-m-d') );
		// $this->db->where( 'insert_at < ' . date('Y-m-d', strtotime('1 day') ) );

		$this->db->where( 'voter_id', $this->user->get_id() );

		$result = $this->db->get( 'd3_amd_cup_2012__voters' )->first_row( 'array' );

		if ( empty($result) ) {
			return false;
		}
		else {
			return true;
		}
	}

	//# 取得幸運的投票者
	public function get_lucky_user( $setting = array() ) {
		
		$setting['week']    = ( ! is_null( $setting['week'] ) ) ? $setting['week'] : '第一週';
		$setting['vote_to'] = ( ! is_null( $setting['vote_to'] ) ) ? $setting['vote_to'] : null;

		$setting['week']    !== null and $this->db->where( 'week', $setting['week'] );
		$setting['vote_to'] !== null and $this->db->where( 'vote_to', $setting['vote_to'] );
		$this->db->group_by( 'voter_id' );
		$this->db->order_by( 'insert_at desc' );
		$result = $this->db->get( 'd3_amd_cup_2012__voters' )->result_array();

		return $result;
	}

	//# 依當時日期取得週期索引
	public function get_vote_week_key( $setting = array() ) {
		$period = $this->get_vote_period();
		$now    = date( 'Y-m-d H:i:s' );
	
		// 計算目前第幾週
		foreach ($period as $key => $max_min) {
			if ( $now<$max_min['max'] and $now>$max_min['min'] ) {
				$week = $key;
				break;
			}
		}

		return $week;
	}

	//# 取得週期
	public function get_vote_period( $key = null, $date = null ) {
		$period = array(
				"第一週" => array('min' => '2012-10-16 00:00:00','max' => '2012-10-22 19:00:00',),	
				"第二週" => array('min' => '2012-10-23 00:00:00','max' => '2012-10-29 19:00:00',),	
				"第三週" => array('min' => '2012-10-30 00:00:00','max' => '2012-11-05 19:00:00',),	
				"第四週" => array('min' => '2012-11-06 00:00:00','max' => '2012-11-12 19:00:00',),	
			);

		return ( $key === null ) ? $period : $period[$key] ;
	}

	//# 取得投票總數
	public function get_total_vote( $setting = array() ) {
		
		$setting['id'] = ( ! is_null( $setting['id'] ) ) ? $setting['id'] : null;
		
		if ($setting['id']===null) return 0;

		$this->db->where( 'vote_to', $setting['id'] );
		$this->db->from( 'd3_amd_cup_2012__voters' );
		return $this->db->count_all_results();
	}

	//# 列車長們
	public function get_captains( $setting = array() ) {

		$captains = array(
				"第一週" => array(
						"shenhand" => array(
								'id' => '1',
								'name' => 'shenhand',
								'class' => '獵人',
								'photo' => 'http://i.imgur.com/3bG88.jpg',
								'level' => 51,
								'best_items' => array(
									),
								'personal' => '台灣首屆D3微星盃激鬥賽冠軍',
								'intro' => '
									<p>D3各職業裝備搭配達人，擁有市場精準眼光，AH操盤大戶，想脫離D3通膨苦海嗎？快跟隨shenhand列車長，一起縱橫D3交易所，體驗一秒鐘幾千萬上下的快感，更有絕佳機會在直播中把傳奇武器帶回家！</p>
								',
							),
						"Bella" => array(
								'id' => '2',
								'name' => 'BellaBaby',
								'class' => '秘術師',
								'photo' => 'http://i.imgur.com/mHbFw.jpg',
								'level' => 0,
								'best_items' => array(
									),
								'personal' => '台灣電玩界專業主持人',
								'intro' => '
									<p>以SC2的亮麗播報聞名台灣電競圈，在D3上市之後也投入此股全民運動，以無邪的亮麗外表以及熱愛電玩的心，成為國內各大電玩廠商的首選主持人，想要度過一個美好的直播夜晚嗎？跟隨Bella就對了！</p>
								',
							),
						"Jeff" => array(
								'id' => '3',
								'name' => 'Jeff',
								'class' => '秘術師',
								'photo' => 'http://i.imgur.com/2Fzbk.jpg',
								'level' => 86,
								'best_items' => array(
									),
								'personal' => '無',
								'intro' => '
									<p>高達86級的巔峰等級，角色的打寶率是本週三位列車長中最高的！想要將主辦單位準備的D3週邊產品帶回家，看來Jeff將會是呼聲最高的一位！但是一切將會盡如人意嗎？請鎖定10/22晚間的直播之夜！</p>
								',
							),
					),
				"第二週" => array(
						
					),
				"第三週" => array(
						
					),
				"第四週" => array(
						
					),
			);

		if ( $setting['week'] !== null) {
			return $captains[$setting['week']];
		}
		else {
			return $captains;
		}
	}

}

//