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
				"第一週" => array('min' => '2012-10-16 00:00:00','max' => '2012-10-22 20:00:00',),	
				"第二週" => array('min' => '2012-10-23 00:00:00','max' => '2012-10-29 20:00:00',),	
				"第三週" => array('min' => '2012-10-30 00:00:00','max' => '2012-11-05 20:00:00',),	
				"第四週" => array('min' => '2012-11-06 00:00:00','max' => '2012-11-12 20:00:00',),	
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
						"Rain" => array(
								'id' => '4',
								'name' => 'Rain',
								'class' => '野蠻人',
								'photo' => 'http://i.imgur.com/mAM7s.jpg',
								'level' => 40,
								'best_items' => array(
									),
								'personal' => 'AH上10億商品標1億',
								'intro' => '
									<p>根據Rain的研究，不管是先焚香沐浴還是禁槍七天一律沒用，想要見到那道朝思暮想的橘光，只有保持著：「打不到」的心態，才有可能打到！在D3中快樂悠遊的Rain，自認這是一款性格中需帶點M性格的人才能堅持下去的好遊戲！</p>
								',
							),
						"DenKaKEKE" => array(
								'id' => '5',
								'name' => 'DenKaKEKE',
								'class' => '武僧',
								'photo' => 'http://i.imgur.com/uwyU4.jpg',
								'level' => 0,
								'best_items' => array(
									),
								'personal' => 'COS界資深美少年～',
								'intro' => '
									<p>以各種COS造型引起廣大鄉民的追隨與愛戴，並以LOL中的九尾狐阿離造型謀殺諸多少年少女的心靈，此次將在打寶列車長當中，陪伴諸多D3的觀眾，度過一個八卦又美好的直播夜～</p>
								',
							),
						"Yui" => array(
								'id' => '6',
								'name' => 'Yui',
								'class' => '秘術師',
								'photo' => 'http://i.imgur.com/z0jn0.jpg',
								'level' => 49,
								'best_items' => array(
									),
								'personal' => '',
								'intro' => '
									<p>把D3定位為時間適當的「休閒遊戲」，巔峰等級卻也逼近50，不難讓人看出這位休閒玩家的執著與堅持！曾經打過破兩億的寶物──殷娜的節制，這次在直播互動大賽中，是否能為追隨他的觀眾，打出高價值傳奇呢？就讓我們繼～續～看～下～去！</p>
								',
							),
					),
				"第三週" => array(
						"Hide" => array(
								'id' => '7',
								'name' => 'Hide',
								'class' => '狩魔獵人',
								'photo' => 'http://i.imgur.com/zR9oA.jpg',
								'level' => 100,
								'best_items' => array(
									),
								'personal' => '台灣知名D3實況主',
								'intro' => '
									<p>活動舉辦以來的首位巔峰滿等列車長，想必本週將會帶來精彩無比的打寶實況，熱愛遊戲的他，因為D3的高難度與挑戰性，而深深愛上了這款遊戲，並成為台灣知名的遊戲實況主，希望能在本周的直播互動中，與全台的玩家共同討論配裝與技能的選擇！</p>
								',
							),
						"潘恩綺" => array(
								'id' => '9',
								'name' => '潘恩綺',
								'class' => '秘術師',
								'photo' => 'http://i.imgur.com/VmZWX.jpg',
								'level' => 3,
								'best_items' => array(
									),
								'personal' => '化學工程師兼模特兒',
								'intro' => '
									<p>本週最令人噴鼻血的打寶列車長，將與大家分享她在卸下模特兒生活之後，平常熱愛的電玩興趣，在「魚乾女」當道的現今，想知道火辣正妹的下班生活嗎？請快拉好椅子，鎖定11/5晚間的AMD直播互動！現場將視情況開放潘恩綺與觀眾的第一次視訊親密接觸！</p>
								',
							),
						"椋小颯" => array(
								'id' => '8',
								'name' => '椋小颯',
								'class' => '五職皆有',
								'photo' => 'http://i.imgur.com/cHs8x.jpg',
								'level' => 58,
								'best_items' => array(
									),
								'personal' => '',
								'intro' => '
									<p>希望能夠推廣D3的平民配裝法，認為找出最適合的裝備比砸大錢還要重要，是相當有理念的一位D3玩家，在本週的直播之夜，將與大家一起分享五大職業的樂趣，以及如何替自己量身打造出一套最適合的裝備，歡迎大家與實況中請教他任何問題～</p>
								',
							),
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