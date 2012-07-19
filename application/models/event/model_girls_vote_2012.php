<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_girls_vote_2012 extends CI_Model {

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

		$girls = $this->_girls_detail();

		if ( is_null( $setting['tid'] ) or ! is_array( $setting['tid'] )  ) {
			return $girls;
		}

		foreach ( $setting['tid'] as $key => $tid ) {
			$girls = $this->_merge_girl_poll( array(
					'tid'   => $tid,
					'girls' => $girls,
				) );
		}

		return $girls;
	}

	/**
	 * 投票
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function vote( $setting = array() ) {

		// 檢查基礎變量
		if ( ! $this->user->is_login() ) {
			$this->callback->error_msg( '尚未登入' );
		}

		$setting['name']       = ( ! is_null( $setting['name'] ) ) ? $setting['name'] : NULL;
		$setting['active_tid'] = ( ! is_null( $setting['active_tid'] ) ) ? $setting['active_tid'] : NULL;
		
		if ( $this->user->get_id() == 0 ) $this->callback->error_msg( "請先註冊成為《暗盟》會員並登入論壇！方可投票！\n\n本站亦採用 Facebook 登入，好快好方便！" );
		if ( $this->callback->is_error() ) return $this->callback->toJSON();

		if ( is_null( $setting['name'] ) ) $this->callback->error_msg( '缺少女孩暱稱' );
		if ( ! $setting['active_tid'] ) $this->callback->error_msg( '投票於07/30(一) 凌晨 00:00開始，謝謝您的支持。' );
		if ( $setting['active_tid'] === -1 ) $this->callback->error_msg( '投票已經結束!' );
		if ( $this->callback->is_error() ) return $this->callback->toJSON();

		// 檢查是否投過票
		$this->db->where( 'tid', $setting['active_tid'] );
		$sql = $this->db->get( 'd3bbs_forum_polloption' );

		$result_array = $sql->result_array();

		foreach ($result_array as $key => $result) {
			$voterids = explode( '	', $result['voterids'] );
			if ( in_array( $this->user->get_id(), $voterids ) ) {
				return $this->callback->error_msg( '您已投票過了' )->toJSON();;
			}
		}

		// 獲取 polloptionid
		$this->db->where( 'tid', $setting['active_tid'] );
		$this->db->where( 'polloption', $setting['name'] );
		$sql = $this->db->get( 'd3bbs_forum_polloption' );
		$first_row = $sql->first_row('array');

		// 真正進行投票儲存
		$voterids[] = $this->user->get_id();
		$voterids = implode( '	', $voterids );
		$this->db->set( 'voterids', $voterids );
		$this->db->set( 'votes', 'votes+1', FALSE );
		$this->db->where( 'tid', $setting['active_tid'] );
		$this->db->where( 'polloption', $setting['name'] );
		$this->db->update( 'd3bbs_forum_polloption' );
		// ---
		$this->db->set( 'voters', 'voters+1', FALSE );
		$this->db->where( 'tid', $setting['active_tid'] );
		$this->db->update( 'd3bbs_forum_poll' );
		// ---
		$this->db->set( 'tid', $setting['active_tid'] );
		$this->db->set( 'uid', $this->user->get_id() );
		$this->db->set( 'username', $this->user->get_username() );
		$this->db->set( 'options', $first_row['polloptionid'] );
		$this->db->set( 'dateline', time() );
		$this->db->insert( 'd3bbs_forum_pollvoter' );
		// ---

		return $this->callback->success_msg( '您已投票成功!' )->toJSON();
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
			show_error( '請輸入正確的tid數字格式.' );
		}

		$this->db->where( 'tid', $setting['tid'] );
		$this->db->order_by( 'polloptionid', 'asc' );
		$sql = $this->db->get( 'd3bbs_forum_polloption' );

		$result = $sql->result_array();

		if ( ! count( $result ) ) {
			show_error( "你的 tid 配置: {$setting['tid']}, 這個投票主題應該還未建立. 請先建立<b style='color: red;'>符合規劃</b>的投票主題後, 再重新配置 model 正確的 tid." );
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
		// 關聯投票主題,順序為 0氣質系->1萌系->2性感系->3活潑系.

		// 一號參賽者
		$girls['賴億珊'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
						'/static/img/event/girls_vote_2012/girl1/1.jpg',
						'/static/img/event/girls_vote_2012/girl1/2.jpg',
						'/static/img/event/girls_vote_2012/girl1/3.jpg',
					),
				1 => array(
						'/static/img/event/girls_vote_2012/girl1/4.jpg',
						'/static/img/event/girls_vote_2012/girl1/5.jpg',
						'/static/img/event/girls_vote_2012/girl1/6.jpg',
					),
				2 => array(
						'/static/img/event/girls_vote_2012/girl1/7.jpg',
						'/static/img/event/girls_vote_2012/girl1/8.jpg',
						'/static/img/event/girls_vote_2012/girl1/9.jpg',
					),
				3 => array(
						'/static/img/event/girls_vote_2012/girl1/10.jpg',
						'/static/img/event/girls_vote_2012/girl1/11.jpg',
					),
			),
			'text_fields' => array(
				'fb' => 'http://www.facebook.com/nila0518',
				'nickname'     => '姜雨珊‏',
				'server'       => '亞服',
				'role_name'    => '娜塔莎',
				'role_level'   => '60',
				'birthday'     => '1991/5/18',
				'city'         => '台北市',
				'class'        => '秘術師',
				'play_per_day' => '3~5小時'
			),
			'intro' => '您好,我是珊珊,<br />今年21歲,喜歡創作與閱讀,<br />興趣是寫作唱歌及瑜珈<br />平常的休閒娛樂就是宅在家裡打電動,<br />勇於嘗試挑戰各種新事物<br />最喜歡打D3菁英怪掉出寶物的期待與刺激感 <br />我還是個愛狗狗的好女孩',
			'opinion' => "這款遊戲我從二代就開始玩囉~<br />所以非常期待3的推出。<br />它的遊戲畫面非常流暢而且精緻細膩<br />我最喜歡玩的是法師，所以選擇秘術師這個職業<br />一開始玩的時候非常順利所以很開心，到了地獄打怪漸漸變得吃力，<br />我才知道要開始打帶跑，最後地獄破關了以為遊戲就這樣結束了，<br />沒想到還有煉獄！一開始再煉獄只有被秒殺的份，<br />因為精英的狀態都是隨機的，不會像一般遊戲的死板，玩起來非常有意思，<br />我覺得這款遊戲最好玩的地方在於打寶，<br />因為寶物的屬性全都是隨機，所以配裝及技能的選擇很多變<br />讓玩家的職業有了更多的選擇與變化<br />只要進入遊戲中就會令人無法自拔的玩下去，想停都停不下來呢~",
		);

		$girls['糖糖'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
						'/static/img/event/girls_vote_2012/girl2/11.jpg',
						'/static/img/event/girls_vote_2012/girl2/12.jpg',
						'/static/img/event/girls_vote_2012/girl2/13.jpg',
					),
				1 => array(
						'/static/img/event/girls_vote_2012/girl2/21.jpg',
						'/static/img/event/girls_vote_2012/girl2/22.jpg',
						'/static/img/event/girls_vote_2012/girl2/23.jpg',
						'/static/img/event/girls_vote_2012/girl2/24.jpg',
					),
				2 => array(
						'/static/img/event/girls_vote_2012/girl2/31.jpg',
						'/static/img/event/girls_vote_2012/girl2/32.jpg',
						'/static/img/event/girls_vote_2012/girl2/33.jpg',
					),
				3 => array(
						'/static/img/event/girls_vote_2012/girl2/41.jpg',
						'/static/img/event/girls_vote_2012/girl2/42.jpg',
						'/static/img/event/girls_vote_2012/girl2/43.jpg',
						'/static/img/event/girls_vote_2012/girl2/44.jpg',
					),
			),
			'text_fields' => array(
				'fb' => 'https://www.facebook.com/only.candy',
				'nickname'     => '糖糖',
				'server'       => '亞服',
				'role_name'    => '艾莉絲',
				'role_level'   => '57',
				'birthday'     => '5/13',
				'city'         => '新北市',
				'class'        => '野蠻人',
				'play_per_day' => '0~3小時'
			),
			'intro' => '哈囉～大家好，我是糖糖。<br />很高興這次可以參加ＤIII的票選活動，<br />請大家多多支持，票選活動開始時，不要忘了投糖糖一票噢＞口＜<br />糖糖快要練到６０等了，希望有空的朋友們可以陪我一起練等唷！＞皿＜',
			'opinion' => "ＤIII這遊戲早在１０年前就有消息了。<br />因為在ＤII的時候我就有在玩了，一直很注意這款遊戲。<br />ＤII、ＤIII其實可以說差不多的，但也可以說進步很多，<br />大致上玩法內容差不多，有玩過上一代的，一定很快就可以上手，<br />不過ＤIII一定要先登入Battle的伺服器有時比較晚下班，<br /><br />就會碰到伺服器爆滿的情形@@ 有時都要等超久的啦>”<<br />不能像以前ＤII一樣，自己想玩隨時都能直接登進去單機遊戲。<br />如果要區分的話，我覺得ＤIII屬於online 遊戲；ＤII的話屬於單機遊戲。<br /><br />在這點我就覺得ＤIII做了很大的突破，畫面精緻了很多，<br />在技能的用法不像ＤII這麼的單調的樹狀圖，在ＤIII人物等級上升時，<br />技能就會自動學好了，要自己搭配不同的技能以及符文的搭配，<br />光是用技能有時就要花了好多時間>”<<br />睽違10年的遊戲巨作真沒讓我失望!!!!! <br />希望大家跟糖糖一起來支持這遊戲^^”",
		);

		$girls['簡沛沛'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
						'/static/img/event/girls_vote_2012/girl3/11.jpg',
						'/static/img/event/girls_vote_2012/girl3/12.jpg',
						'/static/img/event/girls_vote_2012/girl3/13.jpg',
					),
				1 => array(
						'/static/img/event/girls_vote_2012/girl3/21.jpg',
						'/static/img/event/girls_vote_2012/girl3/22.jpg',
						'/static/img/event/girls_vote_2012/girl3/23.jpg',
					),
				2 => array(
						'/static/img/event/girls_vote_2012/girl3/31.jpg',
						'/static/img/event/girls_vote_2012/girl3/32.jpg',
						'/static/img/event/girls_vote_2012/girl3/33.jpg',
					),
				3 => array(
						'/static/img/event/girls_vote_2012/girl3/41.jpg',
						'/static/img/event/girls_vote_2012/girl3/42.jpg',
						'/static/img/event/girls_vote_2012/girl3/43.jpg',
					),
			),
			'text_fields' => array(
				'nickname'     => '簡沛沛',
				'server'       => '亞服',
				'role_name'    => 'peiling',
				'role_level'   => '42',
				'birthday'     => '10/24',
				'city'         => '桃園縣',
				'class'        => '秘術師',
				'play_per_day' => '晚上10點後'
			),
			'intro' => '哈囉大家好,我是簡沛翎,朋友們都叫我簡沛，身高170CM體重55KG，三圍34E/26/36，本身在通訊行上班，個性方面活潑、開朗、直率，平常休閒活動是上網、逛街、看電影、玩線上遊戲等等，個人最大特色是擁有白晰的肌膚以及修長的雙腿，希望大家可以多多支持我唷！',
			'opinion' => "暗黑破壞神III上市時看到那麼多人這麼夯這款遊戲，對於有在玩online gmae得我來說真的嚇到我了，讓我也玩下去了。這是我第一次玩暗黑破壞神，遊戲畫面、特效跟音效，跟我以往所玩過的遊戲差很多，真的很棒。角色方面我選擇秘術師，因為我喜歡遠攻+範圍技能的角色，清怪的速度蠻快的。還沒玩暗黑破壞神III的朋友可以試玩看看喔！",
		);

		$girls['紫紫'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
						'/static/img/event/girls_vote_2012/girl4/12.jpg',
						'/static/img/event/girls_vote_2012/girl4/11.jpg',
					),
				1 => array(
						'/static/img/event/girls_vote_2012/girl4/21.jpg',
						'/static/img/event/girls_vote_2012/girl4/22.jpg',
					),
				2 => array(
						'/static/img/event/girls_vote_2012/girl4/31.jpg',
						'/static/img/event/girls_vote_2012/girl4/32.jpg',
					),
				3 => array(
						'/static/img/event/girls_vote_2012/girl4/41.jpg',
						'/static/img/event/girls_vote_2012/girl4/42.jpg',
					),
			),
			'text_fields' => array(
				'fb' => 'https://www.facebook.com/musicsaki',
				'nickname'     => '紫紫',
				'server'       => '美服',
				'role_name'    => '紫馨菲',
				'role_level'   => '52',
				'birthday'     => '1990/04/19',
				'city'         => '新北市',
				'class'        => '狩魔獵人',
				'play_per_day' => '2~4小時'
			),
			'intro' => '我是紫紫，一個活潑開朗的女生！！很喜歡交朋友。<br />興趣是聽音樂，逛街，看電影，偶爾看看書，無聊時候打遊戲，<br />我很愛我的現在的生活，每天都很多彩多姿。',
			'opinion' => "一開始的簡單破關，讓我非常得意的覺得其實也還好，沒有想像中的難，<br />但到了惡夢，可能是因為裝備防禦不夠高武器不夠強，使我死亡率次數上升，<br />有的時候真的死到會生氣，尤其是第二關卡的魔王，還記得我打了30幾次才成功，<br />真的覺得那關的王要有非常好的專注力才有辦法成功打死他，再來最後的魔王，<br />我始終怎麼打都打不死，次次將我秒殺，於是只好請朋友幫忙拯救我一下，<br />終於到了地域級的關卡，我只能說我好想翻桌，連小怪都打不死！<br />每次出城都是瞬間死亡回儲存點，所以只好慢慢的來，慢慢的打，<br />不過我想我現在最重要的是先用好裝備，<br />因為我的裝備以到達這個地域級的關卡來說是非常爛的。<br />",
		);


		return $girls;
	}
}
//
