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
		$setting['page'] = ( ! is_null( $setting['page'] ) ) ? $setting['page'] : 1;

		$girls = $this->_girls_detail( array(
				'page' => $setting['page'],
			) );

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

		if ( $this->user->is_login() == 0 ) $this->callback->error_msg( "請先註冊成為《暗盟》會員並登入論壇！方可投票！\n\n本站亦採用 Facebook 登入，好快好方便！" );
		if ( $this->callback->is_error() ) return $this->callback->toJSON();

		if ( is_null( $setting['name'] ) ) $this->callback->error_msg( '缺少女孩暱稱' );
		if ( ! $setting['active_tid'] ) $this->callback->error_msg( '投票於07/30(一) 凌晨 00:00開始，謝謝您的支持。' );
		if ( $setting['active_tid'] === -1 ) $this->callback->error_msg( '投票已經結束!' );
		if ( $this->callback->is_error() ) return $this->callback->toJSON();

		// 檢查是否投過票
		$this->db->where( 'tid', $setting['active_tid'] );
		$this->db->where( 'uid', $this->user->get_id() );
		$sql = $this->db->get( 'd3bbs_forum_pollvoter' );

		if ( count( $sql->result_array() ) ) {
			return $this->callback->error_msg( '您已投票過了' )->toJSON();
		}
		else {
			// 二次檢查是否投過票
			$this->db->where( 'tid', $setting['active_tid'] );
			$sql = $this->db->get( 'd3bbs_forum_polloption' );

			$result_array = $sql->result_array();

			foreach ( $result_array as $key => $result ) {
				$voterids = explode( '	', $result['voterids'] );
				if ( in_array( $this->user->get_id(), $voterids ) ) {
					return $this->callback->error_msg( '您已投票過了' )->toJSON();
				}
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

			$merged_girls[$key]                = array_merge( $girls[$key], $setting['girls'][$key] );
			$merged_girls[$key]['total_votes'] += $merged_girls[$key]['votes'];
			$merged_girls[$key]['polls'][]     = $merged_girls[$key]['votes'];

			unset( $merged_girls[$key]['votes'] );
		}

		return $merged_girls;
	}

	/**
	 * 詳細資料
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	private function _girls_detail( $setting = array() ) {

		$setting['page'] = ( ! is_null( $setting['page'] ) ) ? $setting['page'] - 1 : 0;
		$setting['limit'] = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 15;

		$setting['offset'] = ( ! is_null( $setting['offset'] ) ) ? $setting['offset'] : $setting['page'] * $setting['limit'];

		// 關聯投票主題,順序為 0氣質系->1萌系->2性感系->3活潑系.
		$girls['阿土'] = array(
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
				'nickname'     => '阿土',
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
			'video' => 'Sq5-jydvyVI',
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
		$girls['王心豬'] = array(
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
			'video' => 'WBIYDUCFngg',
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
				'nickname'     => '賴億珊',
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
			'video' => 'fLmcz_7AHL0',
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
		$girls['堂堂'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl11/p1.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl11/b1.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl11/c1.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl11/d1.jpg',
				),
			),
			'text_fields' => array(
				'fb' => '',
				'nickname'     => '堂堂‏',
				'server'       => '亞洲',
				'role_name'    => '紗娜',
				'role_level'   => '60',
				'class'        => '秘術師',
				'birthday'     => '1991/07/07',
				'city'         => '台中',
				'play_per_day' => '6小時'
			),
			'intro' => '哈嚕，大家好耶！我是堂堂 : D<br />我是可線上遊戲迷呢! 玩遊戲有10年以上的經歷<br />我最近剛玩不久的D3，很喜歡交朋友聊天！透過<br />遊戲認識很多朋友呢^^<br />希望大家能投我一票摟 希望能一起玩D3吧<br />',
			'opinion' => "這是一款男女都可以輕鬆上手的遊戲，畫面動畫優質，技能很華麗！<br />人物角色裝扮還可以隨著染料而變色，對於女生愛打扮來說在好不過了，<br />以往的戰略性遊戲激不起我玩遊戲的興趣這款遊戲，卻打動我的心呢^^<br />自己玩或是跟朋友玩都別有樂趣<br />",
		);
		$girls['Bella'] = array(
			'video' => 'POOzLU9f_KQ',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl12/a1.jpg',
					'/static/img/event/girls_vote_2012/girl12/a2.jpg',
					'/static/img/event/girls_vote_2012/girl12/a3.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl12/b1.jpg',
					'/static/img/event/girls_vote_2012/girl12/b2.jpg',
					'/static/img/event/girls_vote_2012/girl12/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl12/c1.jpg',
					'/static/img/event/girls_vote_2012/girl12/c2.jpg',
					'/static/img/event/girls_vote_2012/girl12/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl12/d1.jpg',
					'/static/img/event/girls_vote_2012/girl12/d2.jpg',
					'/static/img/event/girls_vote_2012/girl12/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb' => 'https://www.facebook.com/NwBella',
				'nickname'     => 'Bella or 彤彤‏',
				'server'       => '亞洲',
				'role_name'    => 'Bellababy',
				'role_level'   => '60',
				'class'        => '秘術師',
				'birthday'     => '10/11',
				'city'         => '台北',
				'play_per_day' => '3小時'
			),
			'intro' => '哈囉大家好我是Bella你也可以叫我彤彤<br />我從小就是個遊戲迷，從紅白機到PS3<br />從GAMEBOY到3DS，電腦遊戲也是從<br />仙劍奇俠傳到暗黑破壞神三無所不玩<br />之前有做過一個遊戲回顧史的APP<br />愕然發現我居然玩過200多款電腦遊戲<br />我都要嚇死了<br />真的是有空的時間我都奉獻給遊戲了<br />雖然我什麼都玩，但什麼都很不精通,都很弱<br />我喜歡玩遊戲的快樂<br />追逐頂尖的磨難就拋在腦後啦<br />開心玩遊戲就好了<br />玩遊戲也帶給了我不一樣的生活<br />成為職業電競的主播<br />讓我工作和休閒都充滿了遊戲~~好開心<br />',
			'opinion' => "暗黑破壞神III,我可是MF寶寶<br />身穿快破三百的打寶裝<br />成為人見人愛的組隊最佳人選<br />躺屍體撿金裝,是我每天必做的活動<br />雖然我沒有玩過二代直接玩三代的<br />但一開始我就一頭栽入了D3練功的世界<br />一心只想趕快60級可以跟著大家去打寶<br />有空的時間甚至可以玩一整天<br />我沒有風騷的走位技巧,也沒有超神的裝備<br />但是我有很多好隊友… 陪著我打屠夫、打歌不林、農A3<br />非常的開心<br />",
		);
		$girls['潘小薰'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl13/a1.jpg',
					'/static/img/event/girls_vote_2012/girl13/a2.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl13/b1.jpg',
					'/static/img/event/girls_vote_2012/girl13/b2.jpg',
					'/static/img/event/girls_vote_2012/girl13/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl13/c1.jpg',
					'/static/img/event/girls_vote_2012/girl13/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl13/d1.jpg',
					'/static/img/event/girls_vote_2012/girl13/d2.jpg',
				),
			),
			'text_fields' => array(
				'fb' => 'http://www.facebook.com/babykaoru',
				'nickname'     => '潘小薰‏',
				'server'       => '亞服',
				'role_name'    => '紗紗',
				'role_level'   => '60',
				'class'        => '巫醫',
				'birthday'     => '10/19',
				'city'         => '新北市',
				'play_per_day' => '有空就在玩'
			),
			'intro' => '嗨!大家好~<br />我是暗黑美少女候選人，小薰。<br />相信認識我的人<br />就知道我是個不折不扣的超級宅女<br />不論什麼類型遊戲都有接觸及嘗試<br />當然我也不會放過睽違12年的大作<br />”暗黑破壞神3”啦!<br />請大家跟我一起支持這款超人氣遊戲吧:D<br />',
			'opinion' => "暗黑破壞神3是一款眾人期盼12年的遊戲<br />而且他是一款百玩不膩的線上遊戲<br />他不像以往的網路遊戲，需要長時間待在遊戲中<br />像我工作結束再回家打D3，都不擔心會有等級被追過的問題。<br />非常適合大家在閒暇時間做的休閒活動!<br />而且操作簡單，老少咸宜呢。<br />",
		);
		$girls['潘恩綺'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl14/a1.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl14/b1.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl14/c1.jpg',
					'/static/img/event/girls_vote_2012/girl14/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl14/d1.jpg',
				),
			),
			'text_fields' => array(
				'fb' => 'https://www.facebook.com/springsweety',
				'nickname'     => '潘恩綺‏',
				'server'       => '美服',
				'role_name'    => '潘恩綺',
				'role_level'   => '41',
				'class'        => '秘術師',
				'birthday'     => '06/11',
				'city'         => 'Taipei',
				'play_per_day' => '晚上不定時'
			),
			'intro' => '大家好~我是潘恩綺Spring<br />不管不管我現在才41級,快一起幫我啦>///<<br />我要當D3 女神啦~~(羞羞)哈<br />還有我的粉絲團在上面有連結<br />投我一票~<br />恩綺給你生活更美麗 ',
			'opinion' => "一開始聽到D3上市了,就一直在想到底有沒有那麼好玩?<br />大家有需要那麼誇張嗎??<br />後來...朋友送給我了D3組,從此我就聖光阿~<br />剛開始摸索覺得好困難,一直接受復活54321...XDD<br />連作夢都會夢到\"幫助我\"這三個字!!!!<br />雖然現在才50級,還沒資格煉獄<br />但~~團結力量大!!快帶我一起練^0^/<br />",
		);
		$girls['初牛奶'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl15/a1.jpg',

				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl15/b1.jpg',

				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl15/c1.jpg',

				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl15/d1.jpg',

				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/milk11731',
				'nickname'     => '初牛奶',
				'server'       => '美服',
				'role_name'    => '初牛奶',
				'role_level'   => '48',
				'class'        => '巫醫',
				'birthday'     => '1989/10/14',
				'city'         => '新北市',
				'play_per_day' => '不一定'
			),
			'intro' => 'HI 囉～大家好 我是初牛奶<br /> 我平常放假時我都會玩暗黑破壞神3<br /> 雖然巫醫很容易被打死<br /> 但是我覺得巫醫比較好玩<br /> 歡迎大家可以跟我一起上線練喔︿︿<br /> ', 'opinion' => "一開始覺得好像不怎麼好玩..沒想到我弟買到了光碟<br /> 辦好了帳號後..我就無聊的去玩了一下..之後我就陷進去了..<br /> 實在是會因為自尊心的問題..覺得被怪物打死很不甘心..<br /> 所以一定先殺死他們才覺得快樂～哈哈～<br /> 也剛好工作上有些不愉快的事情..<br /> 回到家就開電腦上D3開始打怪物..<br /> 也是一種紓壓的管道<br /> ",
		);
		$girls['貝爾卡諾'] = array(
			'video' => 'jWUyY_stnD0',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl16/a1.jpg',
					'/static/img/event/girls_vote_2012/girl16/a2.jpg',
					'/static/img/event/girls_vote_2012/girl16/a3.jpg',
					'/static/img/event/girls_vote_2012/girl16/a4.jpg',
					'/static/img/event/girls_vote_2012/girl16/a5.jpg',
					'/static/img/event/girls_vote_2012/girl16/a6.jpg',
					'/static/img/event/girls_vote_2012/girl16/a7.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl16/b1.jpg',
					'/static/img/event/girls_vote_2012/girl16/b2.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl16/c1.jpg',
					'/static/img/event/girls_vote_2012/girl16/c2.jpg',
					'/static/img/event/girls_vote_2012/girl16/c3.jpg',
					'/static/img/event/girls_vote_2012/girl16/c4.jpg',
					'/static/img/event/girls_vote_2012/girl16/c5.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl16/d1.jpg',
					'/static/img/event/girls_vote_2012/girl16/d2.jpg',
					'/static/img/event/girls_vote_2012/girl16/d3.jpg',
					'/static/img/event/girls_vote_2012/girl16/d4.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/?ref=home#!/babyMiyabi',
				'nickname'     => '貝爾卡諾‏',
				'server'       => '美洲',
				'role_name'    => '詩寇蒂的憤怒',
				'role_level'   => '60',
				'class'        => '秘術師',
				'birthday'     => '9/20',
				'city'         => '台北',
				'play_per_day' => '晚上9點到12點'
			),
			'intro' => '偶爾人來瘋會胡鬧又很聒噪<br /> 動作像個男人粗魯時常不小心就沒形象!<br /> 大剌剌的模樣是我的表現方式<br /> 有時候又喜歡安靜裝沒事,這就是我~smile~XP<br /> 票選開始時，記得頭我一票唷~啾<br /> ',
			'opinion' => "已經好久沒有玩網路遊戲了，在經過華納威秀D3活動的那次<br /> 開始引起我的注意，是什麼遊戲這麼多人!然後臉書上的狀態<br /> 完全被D3洗板 這麼多人瘋狂!到底是甚麼~禁不起慫恿~<br /> 就這樣開啟了我的D3世界哩 ^^<br /> 這次在創遊戲ID的時候，想起之前玩遊戲總是被騷擾，很煩很討厭。<br /> 所以創了比較有個性的ID，也符合我自己大辣辣的真實的個性XP<br /> 遊戲過程中，暗黑的精緻，著實讓人驚艷，而且不用擔心等級問題~<br /> 或是練功比別人慢，完全是讓我自由發揮各種職業的不同玩法~<br /> 一開始的普通，並不太難，呵呵~<br /> 但是到惡夢的時候站著打打打已經不太會過了>”<<br /> 到地獄難度就開始call out 要一直找朋友幫忙了<br /> 迪亞布羅還是靠朋友幫忙打過的~:P 現在煉獄難度都只敢組隊打了...<br /> D3真是不簡單~有難度...<br /> 最最最重要的就是~他的寶物數量~屬性~只要他說第2~沒人敢說第1 :P<br /> 終於知道大家為何會說，這是ㄧ款值得玩的遊戲，連我現在也迷上<br /> 已經無法脫離哩，不過裝備有點貴貴~~哭哭~~<br /> 所以有遇到我的不要嫌我喔><br />",
		);
		$girls['喬寶'] = array(
			'video' => 'd0ITWFnQvUk',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl17/a1.jpg',
					'/static/img/event/girls_vote_2012/girl17/a2.jpg',
					'/static/img/event/girls_vote_2012/girl17/a3.jpg',
					'/static/img/event/girls_vote_2012/girl17/a4.JPG',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl17/b1.jpg',
					'/static/img/event/girls_vote_2012/girl17/b2.JPG',
					'/static/img/event/girls_vote_2012/girl17/b3.jpg',
					'/static/img/event/girls_vote_2012/girl17/b4.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl17/c1.jpg',
					'/static/img/event/girls_vote_2012/girl17/c2.JPG',
					'/static/img/event/girls_vote_2012/girl17/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl17/d1.jpg',
					'/static/img/event/girls_vote_2012/girl17/d2.jpg',
					'/static/img/event/girls_vote_2012/girl17/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/katsuki.tomoyo',
				'nickname'     => '喬寶‏',
				'server'       => '亞洲',
				'role_name'    => '喬寶寶',
				'role_level'   => '60',
				'class'        => '武僧',
				'birthday'     => '12/15',
				'city'         => '台中市',
				'play_per_day' => '4~5小時'
			),
			'intro' => '哈囉，大家好<br /> 我是喬寶，一個熱愛玩遊戲的女生<br /> 嘗試過很多種類型的遊戲<br /> 希望有機會能跟大家成為朋友<br /> 一起在暗黑破壞神3的世界裡分享打寶的樂趣<br /> 也別忘了投我一票唷～＊<br /> ',
			'opinion' => "在因緣際會下，拿到了Ｄ３的體驗版帳號，玩了一個晚上，深深著迷<br /> 原本一開始是玩巫醫，吹箭、丟蜘蛛、招喚殭屍‧‧‧等等技能<br /> 都讓我覺得在這黑黑暗暗的遊戲內增添了幾許ＫＵＳＯ的感覺<br /> 跟朋友一起闖關時，大家都說，只要我一出現，整個畫面就變得好熱鬧（招喚殭屍犬、巨屍）<br /> 好像多了好多夥伴一樣ＸＤ<br /> 朋友們打到的裝備、武器也是彼此給來給去，省了不少錢呢！<br /> 後來，玩遊戲從來沒玩過近戰職業的我，也決定挑戰玩看看武僧<br /> 沒想到一玩就入迷了，就像很多網友說的，拳拳到肉的快感！<br /> 因為是近戰職業，進了煉獄，才發現，是悲劇的開始Ｑ口Ｑ<br /> 於是就開始上網爬文做了非常多的功課，努力的在煉獄第一章打寶、存錢<br /> 終於小武僧長大了，也和朋友們非常努力打死最後的迪亞布羅，當下很感動，也很有成就感<br /> 現在大部分的時間都是和朋友們一起再煉獄歡樂谷打寶<br /> 很喜歡Ｄ３精美的遊戲畫面、動畫、多變的技能配置、玩法<br /> 跟朋友們一起打寶、分享也是我玩Ｄ３最大的樂趣唷！<br /> ",
		);
		$girls['琦琦'] = array(
			'video' => 'S-KWjewUYtA',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/girl18/a1.jpg',
					'/static/img/event/girls_vote_2012/girl18/a2.jpg',
					'/static/img/event/girls_vote_2012/girl18/a3.jpg',
					'/static/img/event/girls_vote_2012/girl18/a4.jpg',
					'/static/img/event/girls_vote_2012/girl18/a5.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/girl18/b1.jpg',
					'/static/img/event/girls_vote_2012/girl18/b2.jpg',
					'/static/img/event/girls_vote_2012/girl18/b3.jpg',
					'/static/img/event/girls_vote_2012/girl18/b4.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/girl18/c1.jpg',
					'/static/img/event/girls_vote_2012/girl18/c2.jpg',
					'/static/img/event/girls_vote_2012/girl18/c3.jpg',
					'/static/img/event/girls_vote_2012/girl18/c4.jpg',
					'/static/img/event/girls_vote_2012/girl18/c5.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/girl18/d1.jpg',
					'/static/img/event/girls_vote_2012/girl18/d2.jpg',
					'/static/img/event/girls_vote_2012/girl18/d3.jpg',
					'/static/img/event/girls_vote_2012/girl18/d4.jpg',
					'/static/img/event/girls_vote_2012/girl18/d5.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/chice1213',
				'nickname'     => '琦琦‏',
				'server'       => '亞服',
				'role_name'    => '上弦的玥',
				'role_level'   => '56',
				'class'        => '秘術師',
				'birthday'     => '1989/3/13',
				'city'         => '台中市',
				'play_per_day' => '3小時左右'
			),
			'intro' => 'HIHI大家好唷:)<br /> 我是ChiChi一個喜歡玩game的女孩兒<br /> 平時是個活潑外向的女生<br /> 休閒就是上網、看電影、聽音樂唱唱歌<br /> 偶爾沉靜自己寫寫創作<br /> 喜歡拍照玩玩相機，出遠門踏青拍拍照留念!<br /> 和朋友一起品嚐美食是最幸福的事:D<br /> 本身很熱衷遠攻系<br /> 不管單機還是online game<br /> 法師與弓手是小妹熱愛的首選之一唷！<br /> 但先前因為半工半讀的關係<br /> 平常玩的時間少，所以到目前還沒封頂>_<<br /> 現在當工作完後整天拖著的疲累回到家<br /> 當然不忘留戀一下暗黑破壞神三~嘻！<br /> ',
			'opinion' => "在D3出來之前，有陣子很懷念小時候玩的D2，<br /> 又跟朋友借來玩，慢慢品嚐遊戲劇情，破完後惆悵了好陣子！<br /> 那時想著，哎唷~怎麼三代還不出來呢！(打滾)<br /> 而玩過二代無數次，在三代整體上非常容易的上手^Q^<br /> 不過三代死亡後的部分較簡單化囉<br /> 只會回到儲存點&修修裝備而已XD<br /> 回味二代還要找角度撿屍體呢哈哈！<br /> 還有卷軸部分也沒有了，三代輕便許多，不需要帶太多哩哩摳摳，<br /> 只要帶著水，穿好裝備就可以出發囉！haha<br /> 之後好不容易到了地獄，劇情看了好幾遍，<br /> 許多小地方讓人覺得很回味又有趣。<br /> 隨著難度漸漸提升，玩秘術師的我技術也要多多加強了！<br /> 還沒爬過文的我玩的差強人意，不過死亡也是種樂趣啊哈哈！^^<br /> ",
		);
		$girls['Patty'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/patty/a1.jpg',
					'/static/img/event/girls_vote_2012/patty/a2.jpg',
					'/static/img/event/girls_vote_2012/patty/a3.jpg',
					'/static/img/event/girls_vote_2012/patty/a4.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/patty/b1.jpg',
					'/static/img/event/girls_vote_2012/patty/b2.jpg',
					'/static/img/event/girls_vote_2012/patty/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/patty/c1.jpg',
					'/static/img/event/girls_vote_2012/patty/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/patty/d1.jpg',
					'/static/img/event/girls_vote_2012/patty/d2.jpg',
					'/static/img/event/girls_vote_2012/patty/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => '',
				'nickname'     => 'Patty',
				'server'       => '亞服',
				'role_name'    => 'PattyRush',
				'role_level'   => '60',
				'class'        => '狩魔獵人',
				'birthday'     => '09/13',
				'city'         => '新北市',
				'play_per_day' => '1~2小時'
			),
			'intro' => 'HI大家好我是Patty~<br />目前是個苦命但是非常愛打電動的上班族<br />在某科技公司擔任程式工程師<br />玩的遊戲有SC2 跟D3 還有LOL<br />個性溫和<br />但是打遊戲輸了我會很生氣?<br />希望大家一起跟我看聖光<br />一起幫海德格',
			'opinion' => "D3是一個很有趣的遊戲，因為等級最高才60級<br />所以不是那種拼命練功的遊戲，重點是放在跟朋友一起打寶物<br />打到好裝的那一瞬間真的是很有成就感<br />D3也有別於一般線上遊戲大家在一起的模式<br />人太多有時候真的很容易起爭執跟衝突<br />不過4人有點少朋友有時候要開好幾團= =<br />總之是一個值得玩的遊戲喔~",
		);
		$girls['Athena'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/Athena/a1.jpg',
					'/static/img/event/girls_vote_2012/Athena/a2.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/Athena/b1.jpg',
					'/static/img/event/girls_vote_2012/Athena/b2.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/Athena/c1.jpg',
					'/static/img/event/girls_vote_2012/Athena/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/Athena/d1.jpg',
					'/static/img/event/girls_vote_2012/Athena/d2.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/pages/Athena-%E9%9B%85%E5%85%B8%E5%A8%9C/187985017905140',
				'nickname'     => 'Athena‏',
				'server'       => '亞洲',
				'role_name'    => 'AthenaRush',
				'role_level'   => '60',
				'class'        => '秘術師',
				'birthday'     => '07/15',
				'city'         => '台北市',
				'play_per_day' => '4小時'
			),
			'intro' => '大家好 我是雅典娜<br /> <br /> 熱愛電玩的我之前曾是Special Force<br /> (射擊遊戲)的電競職業選手<br /> <br /> 隨著D3的推出 也讓我想起國中時停留在D2的懷念<br /> <br /> 希望跟我一樣熱愛電玩朋友 可以投我ㄧ票唷^_^<br /> ',
			'opinion' => "記得D2的時候,還是在國中時期<br /> <br /> 雖然是單機板 但還是可以用區網跟朋友們在網咖連線打牛關。<br /> <br /> 這次D3推出網路版 更是讓我每天晚上不眠不休的跟朋友瘋狂刷到天亮<br /> <br /> 為得就是那掉下來的綠色字體裝備!!!!!!!!!!!!!!!!!!!!<br /> <br /> 而這次的D3讓我對角色扮演更是著迷了<br /> <br /> 動化作得超棒,像在看電影一樣生動<br /> <br /> 解成就也很有趣 資源回收 跟屁蟲等等...<br /> <br /> 如果想玩D3卻不知道知道該玩什麼角色的朋友<br /> <br /> 比較建議大家玩秘術師,因為好上手以外<br /> <br /> 在施放技能的特效也會感到很酷炫~<br /> <br /> 或者可以選擇逃跑 風箏性高的狩魔獵人!<br /> <br /> 總言之 希望還沒體驗過D3的朋友<br /> <br /> 有機會真的要好好體驗一下 <br /> <br /> 因為真得很棒~<br /> ",
		);
		$girls['Flora'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/Flora/a1.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/Flora/b1.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/Flora/c1.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/Flora/d1.jpg',
					'/static/img/event/girls_vote_2012/Flora/d2.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/flora.ju',
				'nickname'     => 'Flora‏',
				'server'       => '美服',
				'role_name'    => '狩魔妹妹',
				'role_level'   => '60',
				'class'        => '狩魔獵人',
				'birthday'     => '1990/12/29',
				'city'         => '新北市',
				'play_per_day' => '五小時'
			),
			'intro' => '各位在聖修亞瑞大陸奮戰的玩家~<br /> 大家好，我是Flora，我很喜歡美式風格的遊戲<br /> 所以從D2我就開始玩這款遊戲啦！<br /> 平常的休閒活動除了跟狗狗玩、養魚偷菜種<br /> 香菇之外，最喜歡就是玩暗黑破壞神喔^口^/<br /> 我的tag是Flora#3921歡迎大家和我一起玩喔~<br /> (雖然都60等了沒人帶玩起來還是很吃力><)<br /> ',
			'opinion' => "心得嘛…只能說痛苦太多，收穫太少(誤，呵呵!<br /> D3從第一章到第四章~從普通到煉獄，真的都沒冷場！<br /> 劇情的架構有比上一代好很多，畫面也是做得很精緻!!<br /> 不過最吸引我的還是他營造的刺激感，各種變態的技能組合，全地圖被追殺>口</，<br /> 聖光阿~你有看到我的修裝費嗎?<br /> 目前是主要攻略巫醫跟DH，前陣子終於拓荒到了彼列面前，結果打到狂暴<囧><br /> 後來在暗盟的網站研究別人的配裝跟技能，也在上面看了很多高手錄製的影片，終於最後僥倖打過~嗚呼^o^/<br /> 歡迎有玩巫醫跟DH的朋友跟我交換心得喔~~<br /> D3真的很棒很好玩，玩不膩!!! ",
		);
		$girls['小熊'] = array(
			'video' => 'NgMDwUtGmeI',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/a1.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/a2.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/a3.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/b1.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/b2.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/b3.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/c1.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/c2.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/d1.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/d2.jpg',
					'/static/img/event/girls_vote_2012/Melinda_Hsiung/d3.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/wowmbaby',
				'nickname'     => '小熊‏',
				'server'       => '美服',
				'role_name'    => '魅靈兒',
				'role_level'   => '60',
				'class'        => '巫醫',
				'birthday'     => '05/04',
				'city'         => '新北市',
				'play_per_day' => '22:00-01:00'
			),
			'intro' => 'YO 大家好我是小熊<br /> 我相當熱愛玩各種線上遊戲及戰略遊戲<br /> 有相當多年玩遊戲的經驗<br /> 也玩過多款暴雪的其他遊戲<br /> 像是魔獸爭霸、星海、魔獸世界。<br /> 暗黑III目前進度玩到煉獄第二章<br /> 可以加我好友一起玩喔！<br /> <br /> 平常除了玩遊戲之外我也喜歡看電影、看書、運動、戶外遊玩吃吃喝喝<br /> 如果大家有好吃或好玩的地點也可以推薦給我唷！<br /> <br />',
			'opinion' => "雖然我沒玩過暗黑I、II，但我是暴雪迷，暗黑III一出就迫不及待去買來玩了，會選擇玩巫醫是因為我很喜歡巫醫的外型！我覺得很帥，雖然有些人覺得很醜XD 可能是我以前玩魔獸世界有玩過不死族術士吧，所以看到巫醫就覺得特別親切(笑) 一開始玩覺得還滿簡單的，等級也衝很快，可是到後面越來越難，無法再自己一個人玩了，只好向朋友求助XD我覺得巫醫的技能都很有趣，學生物的應該會很愛，蜘蛛青蛙通通來，還可以把怪變豬真是很趣味，不過我最愛的還是用熊熊衝撞，因為我是小熊啊，所以要愛用熊熊技能(笑) 我覺得暗黑III最吸引我的地方就是任務與故事劇情的設計，讓玩家可以跟著任務一起了解暗黑破壞神的故事劇情，更能融入各主角之間的愛恨糾葛(?)而且動畫做的真是太棒了，很有看電影的FU，是一大視覺享受，還有還有，莉亞好正>////< 另外，有一個願望是希望暗黑破壞神不要太久才改版啦，不要讓玩家等那麼辛苦！雖然好的遊戲值得等待XD ",
		);
		$girls['雞排妹Ili'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/Ili/a1.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/Ili/b1.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/Ili/c1.jpg',
					'/static/img/event/girls_vote_2012/Ili/c2.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/Ili/d1.jpg',
					'/static/img/event/girls_vote_2012/Ili/d2.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'http://www.facebook.com/Ili0831',
				'nickname'     => '雞排妹Ili‏',
				'server'       => '美洲',
				'role_name'    => 'ili',
				'role_level'   => '60',
				'class'        => '武增',
				'birthday'     => '1993/08/31',
				'city'         => '台北',
				'play_per_day' => '拍攝寫真集之前,每天玩6小時左右,拍完寫真集之後,因有許多後續的工作,就比較沒空玩'
			),
			'intro' => '雞排妹/Ili/鄭佳甄<br /> 身高體重:157/43<br /> 三圍:30E/22/33<br /> <br /> 最近在幹嘛?:出寫真集(十八歲的禮物),八月預購上市,還有雙面人型抱枕喔!<br /> <br /> 個性的優點：善良，喜歡關心周遭的家人及朋友，不愛計較<br /> <br /> 個性的缺點：任性，餓的時候及太熱的時候會開始不耐煩及發脾氣<br /> <br /> 飲食：　最喜歡吃得飽飽然後睡覺～這是我感到幸福的其中一件事，喜歡吃肉、蛤仔湯、粥、起司、糖葫蘆、還有很多。不喜歡吃甜食（巧克力、蛋糕、糖果），怕香菜、紅蘿蔔、魚湯，早餐一定是兩份才會飽，消夜看心情。<br /> <br /> 喜歡的異性：　不要帥，最好有點胖有肚子，愛屋及烏，照顧家人，尊重我的工作，務正業，能負責任，聰明，還要包容我。（條件會不會開太多了哈哈哈）<br /> <br /> 日常生活：　私底下呢，跟大嬸一樣，喜歡穿著寬鬆的衣服跟拖鞋，上工前才肯換上高跟鞋與衣服。不喜歡化妝，幾乎都是素顏戴眼鏡。<br /> <br /> 寵物：　去年１２月領養了一隻流浪貓腫腫，怕腫腫寂寞，今年三月又領養了一隻流浪貓咪醬，寵物的存在對我很重要，有人說交男朋友不會寂寞，不過跟寵物比較起來，寵物會一輩子跟著我。<br /> <br /> 年紀：　ｉｌｉ非常享受青春，由於工作的關係，生活很累卻多彩多姿，每張照片散發著青春，我好愛現在的自己，不想要１９歲，不想變老，珍惜且揮灑青春。<br />',
			'opinion' => "剛上市時，根本買不到，還是託香港的朋友在香港買遊戲包，再給我序號<br /> 其實Ｄ３的等級不會很難生等，如果有朋友一起玩，等級會跑得很快！<br /> 但不能跟太高等的人一起玩，打怪會沒成就感。<br /> <br /> 我記得玩不到２４小時的時間就會有４０等。<br /> 最喜歡的是遊戲畫面，包含地圖、背景、怪物、噴血嘔吐之類的，都很精緻<br /> 雖然無法拉近，但已能滿足視覺。<br /> <br /> 故事情節跟章節動畫也不錯，但是隨著等級提升，會一直看到重複的情節<br /> 就有點膩．拍賣場很有趣，偶爾會遇到有人少打一個零之類的好康。<br /> <br /> ６０等之後是有點懶，ｉｌｉ應該去認真打裝備，<br /> 才不會在煉獄連凱恩都沒看到人就掛了。<br />",
		);
		$girls['寶姬'] = array(
			'video' => '',
			'photos' => array(
				0 => array(
					'/static/img/event/girls_vote_2012/yuwwu/a1.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/a2.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/a3.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/a4.jpg',
				),
				1 => array(
					'/static/img/event/girls_vote_2012/yuwwu/b1.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/b2.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/b3.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/b4.jpg',
				),
				2 => array(
					'/static/img/event/girls_vote_2012/yuwwu/c1.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/c2.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/c3.jpg',
				),
				3 => array(
					'/static/img/event/girls_vote_2012/yuwwu/d1.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/d2.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/d3.jpg',
					'/static/img/event/girls_vote_2012/yuwwu/d4.jpg',
				),
			),
			'text_fields' => array(
				'fb'           => 'https://www.facebook.com/yu.w.wu.3',
				'nickname'     => '寶姬‏',
				'server'       => '亞服',
				'role_name'    => '寶姬',
				'role_level'   => '52',
				'class'        => '武僧',
				'birthday'     => '1986/01/03',
				'city'         => '新北市',
				'play_per_day' => '放假才會上線'
			),
			'intro' => '哈!大家好噢!<br /> 很高興可以參予這次的活動。<br /> 我是一個標準的阿宅，每天不是在看<br /> 小說漫畫就是在玩遊戲。<br /> 為了D3還追了2天2夜的物流車-/-|||。<br /> 晤…反正就是瘋到一個極致((掩面。<br /> 人生介紹完了…我悲催了－”””－<br />',
			'opinion' => "其實一開始玩的主因，是因為我朋友都在玩，所以算是被朋友拉著跑的。ＸＤ<br /> 沒辦法，個人比較偏好線上遊戲，互動比較多<br /> 也可以認識很多新朋友，所以沒玩過暗黑2。<br /> FB上一長串都是D3的消息，整個風靡到不行。<br /> 就因為這股風氣，不知道哪來的衝動。<br /> 我追了兩天兩夜的物流車，跟近百間的店家打交道…。<br /> 最後一個好心的全家店員可能看我像個瘋婆子，<br /> 加之又繞到他的店家三四次使他同情心大發(其實是不耐煩…)<br /> 就幫我直接打電話問其他店家調貨，我才終於得到救贖!(一整個大愛阿!!!)<br /> 原本很期待它的畫面，結果悲劇的發現我電腦全開會跑不動…（（啜泣<br /> <br /> 雖然有一點小遺憾，但是還是玩的很開心J<br /> <br /> 每次上線就在跟朋友過本本、或是玩不同的職業角色、亂喇賽<br /> 不然就假裝很威的帶新手朋友。哈:)我就是喜歡這樣子!!!!!!!<br /> <br />",
		);
		return array_slice( $girls, $setting['offset'], $setting['limit'] );
	}
}
//
