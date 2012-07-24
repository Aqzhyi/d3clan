<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Model_news extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 首頁輪播
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_circle_loop( $setting = array() ) {

		return array(
			'circle_loop_data' => array(
				array(
					'img'   => '/bbs/data/attachment/forum/201207/17/023955ihu52yhh6ahthaih.jpg',
					'title' => '全台唯一＜暗黑破壞神III＞專題網站『暗盟』, 正式開始營運',
					'descr' => '＜暗黑破壞神III＞的來臨，讓全球玩家為之瘋狂，全球銷量首週更超過630萬套，刷新史上所有電腦遊戲銷量紀錄。以星海爭霸2起家的知名網站星盟電競情報網，為了提供台灣玩家有更多、更專業的遊戲情報，特別以原班人馬，創立了『暗盟電競情報網』，並預計於 7月17日正式開台運作，本網站亦為目前台灣唯一以暗黑破壞神為主題的專門網站，相信一定能因此造福更多喜愛遊戲的玩家。',
					'link'  => '/bbs/forum.php?mod=viewthread&tid=429',
				),
				array(
					'img'   => '/static/img/event/girls_vote_2012/dgirl-home.jpg',
					'title' => '台灣暗盟電競情報網「暗黑美少女D-Girl年度選拔賽賽」開跑',
					'descr' => '【2012年7月17日台北訊】由台灣暗盟電競情報網舉辦，台灣暴雪協辦，Intel主要贊助，知名品牌Kingston、ASRock、ROCCAT、曜越科技、微星電競筆電、BenQ聯合贊助的「暗黑美少女D-Girl年度選拔賽聯賽」，即將於7月底正式開跑！本次競賽最終總獎金高達新台幣十萬元，熱絡的活動勢必引爆整個暑假！',
					'link'  => '/bbs/forum.php?mod=viewthread&tid=430',
				),
				array(
					'img'   => 'http://d3clan.tw/bbs/data/attachment/forum/201207/17/1759589ds3xggerds96xgl.jpg',
					'title' => '微星遊戲筆電《暗黑破壞神III》賽事正式開跑',
					'descr' => '《暗黑破壞神III》的來臨，讓全球玩家為之瘋狂，全球銷量首週更超過630萬套，刷新史上所有電腦遊戲銷量紀錄。今年暑假，微星科技電競遊戲邀請『暗盟電競情報網』攜手合辦『微星盃暗黑激鬥賽』，凡具有《暗黑破壞神III》亞服帳號即可報名，冠軍獎金更高達3萬元整，並還有Intel® Core™ i5-3470處理器、CM STORM Quick Fire Pro電競鍵盤、CM Storm Sirus S 天狼星電競耳機組、A-DATA DashDrive™ Durable HD710 USB 3.0外接式硬碟…等多項電競好禮要送給暗黑高手們。',
					'link'  => '/event/2012-msi',
				),
				array(
					'img'   => 'http://i418.photobucket.com/albums/pp266/vic111567/ASROCKB75/DSC_5875.jpg',
					'title' => 'ASRock B75 Pro3 and Pro3-M 1155-select 平價實用',
					'descr' => 'INTEL 1155腳位目前市面上可以購買到的晶片組，種類繁多除了之前的六系列現在還有小七系列，分別為B75、H77、Z75、Z77，其中B75也就是這次的文章主角是最讓大家訝異的，因為以往INTEL商用晶片組幾乎是沒在一般零售市場出現，不過其實去Intel官網看到此晶片定位在『為小型企業打造』，',
					'link'  => '/bbs/forum.php?mod=viewthread&tid=431',
				),
			),
		);
	}

	/**
	 * 取得新聞資訊流條目
	 *
	 * @param array   $setting [description]
	 * @return [type]          [description]
	 */
	public function get_flow( $setting = array() ) {

		/* (array) */$setting['fid']           = ( ! is_null( $setting['fid'] ) ) ? $setting['fid'] : NULL;
		/* (array) */$setting['typeid']        = ( ! is_null( $setting['typeid'] ) ) ? $setting['typeid'] : NULL;
		/* (array) */$setting['digest']        = ( ! is_null( $setting['digest'] ) ) ? $setting['digest'] : NULL;
		/* (int) */$setting['limit']           = ( ! is_null( $setting['limit'] ) ) ? $setting['limit'] : 15;
		/* (bool) */$setting['exclude_typeid'] = ( ! is_null( $setting['exclude_typeid'] ) ) ? $setting['exclude_typeid'] : FALSE;

		$this->db->select( '*' );
		$this->db->from( 'd3bbs_forum_thread as t' );
		if ( $setting['exclude_typeid'] === FALSE ) $this->db->join( 'd3bbs_forum_threadclass as tc', 't.typeid = tc.typeid' );
		if ( ! is_null( $setting['fid'] ) ) $this->db->where_in( 't.fid', $setting['fid'] );
		if ( ! is_null( $setting['typeid'] ) ) $this->db->where_in( 't.typeid', $setting['typeid'] );
		if ( ! is_null( $setting['digest'] ) ) $this->db->where_in( 't.digest', $setting['digest'] );
		$this->db->where( 't.displayorder !=', '-1' );
		$this->db->order_by( 'tid', 'desc' );
		$this->db->limit( $setting['limit'], 0 );

		return $this->db->get()->result_array();
	}
}
