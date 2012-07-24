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

		foreach ( $result_array as $key => $result ) {
			$voterids = explode( '	', $result['voterids'] );
			if ( in_array( $this->user->get_id(), $voterids ) ) {
				return $this->callback->error_msg( '您已投票過了' )->toJSON();;
			}
		}

		// 獲取 polloptionid
		$this->db->where( 'tid', $setting['active_tid'] );
		$this->db->where( 'polloption', $setting['name'] );
		$sql = $this->db->get( 'd3bbs_forum_polloption' );
		$first_row = $sql->first_row( 'array' );

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

		$girls['土萌瑩'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl8/a1.jpg',
					'/static/img/event/girls_vote_2012/girl8/a2.jpg',
					'/static/img/event/girls_vote_2012/girl8/a3.jpg',

				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl8/b1.jpg',
					'/static/img/event/girls_vote_2012/girl8/b2.jpg',
					'/static/img/event/girls_vote_2012/girl8/b3.jpg',

				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl8/c1.jpg',
					'/static/img/event/girls_vote_2012/girl8/c2.jpg',

				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl8/d1.jpg',
					'/static/img/event/girls_vote_2012/girl8/d2.jpg',
					'/static/img/event/girls_vote_2012/girl8/d3.jpg',
					'/static/img/event/girls_vote_2012/girl8/d4.jpg',

				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/Simyia1024',
				'nickname'     => '土萌瑩',
				'server'       => '亞服',
				'role_name'    => '土萌瑩',
				'role_level'   => '42',
				'class'        => '巫醫',
				'birthday'     => '10/24',
				'city'         => '台中市',
				'play_per_day' => '凌晨00:30～05:00'
			),
			'intro' => '我是魔獸世界UI作者土萌螢，個人偏好歐美風格遊戲，玩過魔獸世界、戰鎚online、英雄聯盟(美版就開始玩了) 、暗黑破壞神3、激戰2，個人認為好的遊戲不受語言限制，只要是好遊戲我就會去嘗試，而歐美遊戲因原創性較重、遊戲幣端較少，所以較為喜好，因為歐美遊戲設定不會讓玩家以台幣玩家為重。<br /><br />個人這雖完全是衝著五萬塊獎金而來，因為想換一台新電腦開遊戲實況，個人偏好以遊戲畫面為主故如果觀眾無要求就是只開遊戲畫面而已，請放心我不會輕易出來嚇人的XD～因為很多好的遊戲往往因為大家不了解其中的樂趣而作罷，希望可以將遊戲的樂趣以最直接的方式分享給大家，讓大家也能自己衡量什麼遊戲才是真正你所想要、所能自己當主人的，而不再只是被遊戲牽著鼻子走的。<br /><br />歐美遊戲設計也常將幣設定為最低需求，讓玩家不會因大量的買賣幣而降低遊戲環境品質，有些遊戲甚至是可以自訂UI介面，讓每個玩家擁有自己的介面風格，而技能的CD時間也有特別算過，不會CD太短而導致可以無腦施放技能，如果想知道我玩過的遊戲可以上粉絲團觀看，而製作過的UI可以上無名看，因為圖片實在是有點多，還記得一開始要買暗黑3的實體光碟時，特地跑到鹿港去採購…真的是一片難求阿！朋友都說我瘋了XD！不過身為一個骨灰級的專業玩家(這句可是某大陸哥教拳皇的必備用詞呢~)，這不是一定要的嗎XD?!<br />對於歐美遊戲，我有一份執著，對於台灣女子電競環境，我有一份夢想，這些由熱交織出來的，就是我的使命，希望可以改變女生對於選擇遊戲上的良好風氣！<br />',
			'opinion' => "這款遊戲系列偏向普及化市場的策略設定也頗為吸引人，它不像早期的魔獸世界，好的裝備需要從高端副本去靠dkp追求，這那些高端副本往往是需要40人出團的，讓三五成群的同學好友無法因為自己想出團就出團，除非去加入別人的工會或是辛苦建立一個工會起來，而在暗黑破壞神系列，它是不需要如此的，它打造了一個讓親朋好友可以五人結伴一起共闖遊戲世界的好環境，不用怕別人來打擾，也不需要拜託別人來加入幫忙，如果說魔獸世界是暴雪旗下多人團隊的主力遊戲，那麼暗黑破壞神則是跟它相對應的小團隊主力遊戲了，雙方互不矛盾，也替暴雪締造了雙贏的市場。<br /><br />如果說40人團隊有它的艱難，那麼小團隊則也有其團結的感動，經過多年後，回想起一定會增添幾分趣味。<br /><br />而暗黑破壞神III這款遊戲，繼承了上一代的遊戲模式，享受打寶物的樂趣，以及與不同職業分工一起打王的快感，其中，裝備屬性以及地圖都是隨機的，不會讓人感受到幾乎都是玩一模一樣的東西，寶物屬性也是隨機的，每一個裝備都是獨一無二的，鮮少有全部一樣的數值、屬性在同一件裝備裡面。寶石鑲入方面也與上一代做了些微調，改善了上一代的缺點，細緻有震撼力又較不傷眼的暗色系動畫場景，我想也是遊戲最大的特色吧！<br />",
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
				'fb'           => 'https://www.facebook.com/only.candy',
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
		$girls['小璇'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl6/a1.jpg',
					'/static/img/event/girls_vote_2012/girl6/a2.jpg',
					'/static/img/event/girls_vote_2012/girl6/a3.jpg',
					'/static/img/event/girls_vote_2012/girl6/a4.jpg',
					'/static/img/event/girls_vote_2012/girl6/a5.jpg',
					'/static/img/event/girls_vote_2012/girl6/a6.jpg',
					'/static/img/event/girls_vote_2012/girl6/a7.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl6/b1.jpg',
					'/static/img/event/girls_vote_2012/girl6/b2.jpg',
					'/static/img/event/girls_vote_2012/girl6/b3.jpg',
					'/static/img/event/girls_vote_2012/girl6/b4.jpg',
					'/static/img/event/girls_vote_2012/girl6/b5.jpg',
					'/static/img/event/girls_vote_2012/girl6/b6.jpg',
					'/static/img/event/girls_vote_2012/girl6/b7.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl6/c1.jpg',
					'/static/img/event/girls_vote_2012/girl6/c2.jpg',
					'/static/img/event/girls_vote_2012/girl6/c3.jpg',
					'/static/img/event/girls_vote_2012/girl6/c4.jpg',
					'/static/img/event/girls_vote_2012/girl6/c5.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl6/d1.jpg',
					'/static/img/event/girls_vote_2012/girl6/d2.jpg',
					'/static/img/event/girls_vote_2012/girl6/d3.jpg',
					'/static/img/event/girls_vote_2012/girl6/d4.jpg',
					'/static/img/event/girls_vote_2012/girl6/d5.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/S.humi.Fans',
				'nickname'     => '小璇‏',
				'server'       => '美服',
				'role_name'    => '凜玥蕾',
				'role_level'   => '59',
				'class'        => '狩魔獵人',
				'birthday'     => '1990/10/30',
				'city'         => '基隆市',
				'play_per_day' => '晚上不定時'
			),
			'intro' => '哈囉~大家好!我叫小璇(Humi)，身高<br />160公分，體重43公斤，現在是護理<br />科五專剛畢業的學生，平常就是一個<br />很喜歡交朋友的人。<br />努力練到60等中的小璇，也很歡迎<br />各位朋友們來找我一起玩呦 >w<<br />很高興可以參加這次D3的活動，<br />活動開始的時候，<br />別忘了投小璇一票呢>///',
			'opinion' => "其實在玩這款遊戲之前，就已經聽很多朋友說過這款遊戲了<<br />聽說從一代開始就是一款很熱門的遊戲，所以在要出D3的時候<br />真的非常的興奮，但是戰網卡還有D3包卻馬上被搶購一空<br />我花了好多的時間才將戰網卡買到 ˊ口ˋ 但是這一切都很值得<br />因為D3讓我覺得很熱血，不管是畫風或者是遊戲音樂都讓我覺得<br />很酷又很震撼。<br />在技能這塊，跟其他遊戲不同的是，快捷技能的擺放格數有限<br />沒辦法把所有技能都丟上去 ><”所以在練習技能搭配這方面<br />找了好多人的擺放位置來參考，才終於讓我在選擇技能上覺得完成了<br />還有在遊戲畫面的部分，果然做得十分的細緻<br />尤其是武器的不同屬性會有不同的特效在上面，讓我覺得超棒<br />完全沒有辜負我跑來跑去買遊戲的努力呢^^<br />也希望大家可以跟小璇一起來支持D3! 真的是款值得玩下去的遊戲^^<br />",
		);
		$girls['小翔翔'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl9/a1.jpg',
					'/static/img/event/girls_vote_2012/girl9/a2.jpg',
					'/static/img/event/girls_vote_2012/girl9/a3.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl9/b1.jpg',
					'/static/img/event/girls_vote_2012/girl9/b2.jpg',
					'/static/img/event/girls_vote_2012/girl9/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl9/c1.jpg',
					'/static/img/event/girls_vote_2012/girl9/c2.jpg',
					'/static/img/event/girls_vote_2012/girl9/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl9/d1.jpg',
					'/static/img/event/girls_vote_2012/girl9/d2.jpg',
					'/static/img/event/girls_vote_2012/girl9/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => '',
				'nickname'     => '小翔翔‏',
				'server'       => '美服',
				'role_name'    => '聖落櫻飛',
				'role_level'   => '56',
				'class'        => '祕術師',
				'birthday'     => '77/2/26',
				'city'         => '台北市',
				'play_per_day' => 'Pm 5:00~12:00'
			),
			'intro' => '哈囉<br />你好我是紫櫻<br />在暗黑破壞神鐘我使在美服伺服器中<br />我是玩祕術師的職業角色名稱是 聖落櫻飛<br />大家快點來跟我一起玩吧！！',
			'opinion' => "一開始剛玩的時候其實我不太懂怎麼操作.因為我是自己一個人玩<br />後來慢慢的自己研究 從不知道快速鍵到知道美個快速鍵是什麼 <br />後來覺得其實還蠻容易上手的 然後25等候我開始公開遊戲根大家一起玩<br />其實我覺得大家一起來比較開心 而且打怪的數度也變得比較快 雖然怪會隨著人數多寡而變強變弱<br />可是我覺得一起玩還蠻開心根有趣的 升等的時間也比較快喔！！",
		);
		$girls['心儀'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl5/a1.jpg',
					'/static/img/event/girls_vote_2012/girl5/a2.jpg',
					'/static/img/event/girls_vote_2012/girl5/a3.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl5/b1.jpg',
					'/static/img/event/girls_vote_2012/girl5/b2.jpg',
					'/static/img/event/girls_vote_2012/girl5/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl5/c1.jpg',
					'/static/img/event/girls_vote_2012/girl5/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl5/d1.jpg',
					'/static/img/event/girls_vote_2012/girl5/d2.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/LoveHeartShin',
				'nickname'     => '王心豬‏',
				'server'       => '美服',
				'role_name'    => '泰妮與安貝爾',
				'role_level'   => '60',
				'birthday'     => '06/02',
				'city'         => '台北市',
				'class'        => '秘術師',
				'play_per_day' => '3~5小時'
			),
			'intro' => '哈囉大家好~~我是心儀<br />平常的興趣就是玩遊戲<br />舉凡單機 線上 家用主機都很愛玩XDD<br />希望大家可以投我一票喔!!<br />',
			'opinion' => "其實我是個沒有玩過D2的玩家，所以當初D3預購時我並沒有跟上，後來周遭好友開始洗版FB時，才決定跟朋友借體驗序號來玩，結果整個就是愛上了！！！<br />體驗序號只能玩到13等骷髏王，結果在買到正式版之前我刷了十多次，好不容易總算買到正式版，內心完全就是吶喊加上灑花的狀態啊！！<br />第一次破關很用心的體驗所有的對話加上劇情，只能說我有猜到莉亞跟狄亞布羅有關係啊XDDD<br />之後就是跟朋友組隊刷寶囉，每次組隊總是會開SKYPE語音，打到金色裝備總是令人期待又怕受傷害，我的運氣不錯，目前資產也破千萬囉，顆顆顆。<br />還有我最愛的其實是小馬關，雖然大家都說音樂很詭異，但我超愛這種風格的！！！網路謠傳有第二隱藏關卡，我也希望可以有啊！！！！<br />最後就是要說，D3真的不錯玩XDDDD<br />",
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
				'fb'           => 'https://www.facebook.com/musicsaki',
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
				'fb'           => 'http://www.facebook.com/nila0518',
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
		$girls['Tiffany'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl7/a1.jpg',

				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl7/b1.jpg',

				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl7/c1.jpg',

				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl7/d1.jpg',

				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/rionana',
				'nickname'     => 'Tiffany‏',
				'server'       => '亞服',
				'role_name'    => '月光仙子',
				'role_level'   => '40',
				'class'        => '狩魔獵人',
				'birthday'     => '1990/09/22',
				'city'         => '台北市',
				'play_per_day' => '三小時以上'
			),
			'intro' => '大家好～我是Tiffany<br />平常的興趣是打電玩跟吃美食<br />希望可以跟各位成為好朋友喲～',
			'opinion' => "雖然沒玩過二代，但是也聽很多朋友說過，雖然真的會越打越上癮，<br />不過我還不敢自己一個人玩，因為我覺得很恐怖，<br />尤其我又很喜歡在半夜玩，會怕怕的但是又很想玩，<br />所以都會找朋友陪我一起玩，哈哈。",
		);
		$girls['Hedy'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl10/a1.jpg',
					'/static/img/event/girls_vote_2012/girl10/a2.jpg',
					'/static/img/event/girls_vote_2012/girl10/a3.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl10/b1.jpg',
					'/static/img/event/girls_vote_2012/girl10/b2.jpg',
					'/static/img/event/girls_vote_2012/girl10/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl10/c1.jpg',
					'/static/img/event/girls_vote_2012/girl10/c2.jpg',
					'/static/img/event/girls_vote_2012/girl10/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl10/d1.jpg',
					'/static/img/event/girls_vote_2012/girl10/d2.jpg',
					'/static/img/event/girls_vote_2012/girl10/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/kaai00000',
				'nickname'     => 'Hedy',
				'server'       => '亞服',
				'role_name'    => 'MissHedy',
				'role_level'   => '60',
				'class'        => '武僧',
				'birthday'     => '6/23',
				'city'         => '台北',
				'play_per_day' => '1~2小時'
			),
			'intro' => '一個小小宅宅的嫩嫩涅法雷姆<br />明明是想玩帥帥的男DH卻莫名其妙變成Monk<br />靠撿破爛拯救聖修亞瑞<br />現在和夥伴們正過著穿全身破爛打寶裝襲擊野外無辜菁英並搶奪財物的生活',
			'opinion' => "1.攻速Nerf比率也太高Q_Q,擺明砍DH的頭卻連武僧和野蠻的頭一起砍了,雖然攻速在D3的確是過於強勢的素質(連法師法施法速度都跟武器攻速有關?這什麼邏輯???)<br />但原本+25%攻速變成+12%功速這點滿詭異的,無條件被捨去了0.5%攻速嗎???<br /><br />2.Nerf後爆擊和爆傷素質裝備被眾多玩家過度重視,甚至出現認為爆擊機率也需要Nerf的聲浪,個人認為”爆擊機率”終究是機率高低的問題,在功速已經被NERF的情況下,強勢性相對也下降很多了,況且,爆擊與否終究在於人品,人品不好99%機率也未必爆擊啊!<br />希望別再砍數值了~要是連擊中都砍我就哭哭了哈哈哈哈<br /><br />3.撕魂獸的舌頭為什麼可以穿牆<br /><br />4.1.03改版後難度下降好多<br /><br />5.雖然上拉里拉咂說一堆但D3在我心中還是神作",
		);

		return $girls;
	}
}
//
