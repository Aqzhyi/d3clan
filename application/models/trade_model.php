<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Trade_model extends CI_Model {

	function __construct() {
		parent::__construct();

		if ( ! $this->discuzx ) {
			show_error( '請載入 discuzx library.' );
		}
	}

	/**
	 * 建立帖子
	 * @param  array  $file_info [description]
	 * @param  array  $item      [description]
	 * @param  array  $setting   [description]
	 * @return [type]            [description]
	 */
	public function create_good( $file_info = array(), $item = array(), $setting = array() ) {
		if ( ! is_array( $file_info ) OR $file_info['is_success'] !== TRUE ) {
			return FALSE;
		}

		// $this->load->database( 'DiabloIII' );
		$now      = time();
		$fid      = 40; // 論壇-交易專區
		$uid      = $this->discuzx->_G['uid'];
		$username = $this->discuzx->_G['username'];
		$tableid  = rand( 1, 9 );

		// 建立主題
		$this->db->insert( 'd3bbs_forum_thread', array(
				'fid'        => $fid,
				'author'     => $username,
				'authorid'   => $uid,
				'subject'    => '[出售] ',
				'dateline'   => $now,
				'lastpost'   => $now,
				'lastposter' => $username,
				'special'    => 0,
				'status'     => 32,
			) );

		// 返回 tid
		$tid = $this->db->insert_id();

		// 獲取 pid
		$sql = $this->db->query( "SELECT MAX(pid) AS 'pid' FROM `d3bbs_forum_post_tableid` LIMIT 1;" );
		$row = $sql->first_row( 'array' );
		$pid = (int) $row['pid'] +1;

		// 獲取虛擬寶物辨識屬性並存入文章中(必備換行空白)
		$message = "[出售]".
			"[聯繫方式] 站內信".
			"[交易貨幣] 亞服金幣".
			"[價格] 五億".
			"[img]".preg_replace('@.*(data\/attachment\/forum\/\d{6}\/\d{1,2}\/\w+\.\w+)@', '$1', $file_info['full_path'] )."[/img]".
			"
			";
		foreach ($item as $index => $line_txt) {
			$message .= "$line_txt\n";
		}

		// 建立帖子
		$this->db->insert( 'd3bbs_forum_post', array(
				'pid'        => $pid,
				'fid'        => $fid,
				'tid'        => $tid,
				'first'      => 1,
				'author'     => $username,
				'message'    => $message,
				'authorid'   => $uid,
				'attachment' => 2,
				'subject'    => '[出售] ',
				'dateline'   => $now,
				'useip'      => $this->discuzx->_G['clientip'],
				'position'   => 1,
			) );

		// 為帖子建立post分表協調表(必備)
		$this->db->insert( 'd3bbs_forum_post_tableid', array(
				'pid' => $pid,
			) );

		// 建立附件
		$this->db->insert( 'd3bbs_forum_attachment', array(
				'tid'     => $tid,
				'pid'     => $pid,
				'uid'     => $uid,
				'tableid' => $tableid,
			) );

		// 返回附件 aid
		$aid = $this->db->insert_id();

		// 建立附件關聯
		$this->db->insert( "d3bbs_forum_attachment_$tableid", array(
				'aid'        => $aid,
				'tid'        => $tid,
				'pid'        => $pid,
				'uid'        => $uid,
				'dateline'   => $now,
				'filename'   => $file_info['orig_name'],
				'filesize'   => (int) $file_info['file_size'] * 1024,
				'attachment' => date( 'Ym' ) . '/' . date( 'd' ) . '/' . $file_info['file_name'],
				'isimage'    => 1,
				'width'      => $file_info['image_width'],
				'thumb'      => 1,
			) );

		return $tid;
	}

	/**
	 * 創建商品時需按以下順序插入資料表 [建立 出售商品]
	 *  * d3bbs_forum_thread
	 *  * d3bbs_forum_post
	 *  * d3bbs_forum_post_tableid
	 *  * d3bbs_forum_post  // post需至少兩次, cuz商品等於post.
	 *  * d3bbs_forum_post_tableid
	 *  * d3bbs_forum_attachment
	 *  * d3bbs_forum_attachment_{rand(1, 9)}
	 *  * d3bbs_forum_trade
	 *
	 * @param  array  $file_info [description]
	 * @param  array  $item      [description]
	 * @param  array  $setting   [description]
	 * @return [type]            [description]
	 */
	public function create_good_by_trade( $file_info = array(), $item = array(), $setting = array() ) {

		if ( ! is_array( $file_info ) OR $file_info['is_success'] !== TRUE ) {
			return FALSE;
		}

		// $this->load->database( 'DiabloIII' );
		$now      = time();
		$fid      = 40; // 論壇-交易專區
		$uid      = $this->discuzx->_G['uid'];
		$username = $this->discuzx->_G['username'];
		$tableid  = rand( 1, 9 );
		// 順序

		$this->db->insert( 'd3bbs_forum_thread', array(
				'fid'        => $fid,
				'author'     => $username,
				'authorid'   => $uid,
				'subject'    => '[出售] ',
				'dateline'   => $now,
				'lastpost'   => $now,
				'lastposter' => $username,
				'special'    => 2,
			) );

		$tid = $this->db->insert_id();

		$sql = $this->db->query( "SELECT MAX(pid) AS 'pid' FROM `d3bbs_forum_post_tableid` LIMIT 1;" );
		$row = $sql->first_row( 'array' );

		$pid = (int) $row['pid'] +1;

		$this->db->insert( 'd3bbs_forum_post', array(
				'pid'      => $pid,
				'fid'      => $fid,
				'tid'      => $tid,
				'first'    => 1,
				'author'   => $username,
				'authorid' => $uid,
				'subject'  => '[出售] ',
				'dateline' => $now,
				'useip'    => $this->discuzx->_G['clientip'],
				'position' => 1,
			) );

		$this->db->insert( 'd3bbs_forum_post_tableid', array(
				'pid' => $pid,
			) );

		$pid = (int) $pid +1;

		$message = '';
		foreach ($item as $index => $line_txt) {
			$message .= "$line_txt\n";
		}

		$this->db->insert( 'd3bbs_forum_post', array(
				'pid'      => $pid,
				'fid'      => $fid,
				'tid'      => $tid,
				'first'    => 0,
				'author'   => $username,
				'authorid' => $uid,
				'subject'  => '[出售] ',
				'message'  => $message,
				'dateline' => $now,
				'useip'    => $this->discuzx->_G['clientip'],
				'position' => 2,
			) );

		$this->db->insert( 'd3bbs_forum_post_tableid', array(
				'pid' => $pid,
			) );

		$this->db->insert( 'd3bbs_forum_attachment', array(
				'tid'     => $tid,
				'pid'     => $pid,
				'uid'     => $uid,
				'tableid' => $tableid,
			) );

		$aid = $this->db->insert_id();

		$this->db->insert( "d3bbs_forum_attachment_$tableid", array(
				'aid'        => $aid,
				'tid'        => $tid,
				'pid'        => $pid,
				'uid'        => $uid,
				'dateline'   => $now,
				'filename'   => $file_info['orig_name'],
				'filesize'   => (int) $file_info['file_size'] * 1024,
				'attachment' => date( 'Ym' ) . '/' . date( 'd' ) . '/' . $file_info['file_name'],
				'isimage'    => 1,
				'width'      => $file_info['image_width'],
				'thumb'      => 1,
			) );

		$this->db->insert( 'd3bbs_forum_trade', array(
				'pid'        => $pid,
				'tid'        => $tid,
				'seller'     => $username,
				'sellerid'   => $uid,
				'subject'    => '物品名稱',
				'price'      => 0,
				'amount'     => 1,
				'quality'    => 1,
				'locus'      => '亞服',
				'itemtype'   => 1,
				'dateline'   => $now,
				'expiration' => $now + 86400,
				'lastupdate' => $now,
				'costprice'  => 0,
				'credit'     => 50000,
				'costcredit' => 100000,
				'aid'        => $aid,
			) );

		return $tid;
	}

}

//
