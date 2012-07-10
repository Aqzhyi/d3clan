<?php

/**
 *		[Discuz! X] (C)2001-2099 Comsenz Inc.
 *		This is NOT a freeware, use is subject to license terms
 *
 *		$Id: connect.class.php 29471 2012-04-13 06:54:01Z houdelei $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_qqconnect_base {

	// ��������
	public $retryInterval = 60;
	// ����������
	public $retryMax = 5;
	// �������Ե�ʱ��
	public $retryAvaiableTime = 1800;

	function init() {
		global $_G;
		include_once template('qqconnect:module');
		if(!$_G['setting']['connect']['allow'] || $_G['setting']['bbclosed']) {
			return;
		}
		$this->allow = true;
	}

	function common_base() {
		global $_G;

		if(!isset($_G['connect'])) {
			$_G['connect']['url'] = 'http://connect.discuz.qq.com';
			$_G['connect']['api_url'] = 'http://api.discuz.qq.com';
			$_G['connect']['avatar_url'] = 'http://avatar.connect.discuz.qq.com';

			// QZone��������ҳ��URL
			$_G['connect']['qzone_public_share_url'] = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey';
			$_G['connect']['referer'] = !$_G['inajax'] && CURSCRIPT != 'member' ? $_G['basefilename'].($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '') : dreferer();
			// ΢����������Appkey
			$_G['connect']['weibo_public_appkey'] = 'ce7fb946290e4109bdc9175108b6db3a';

			// �°�Connect��¼���ش���ҳ
			$_G['connect']['login_url'] = $_G['siteurl'].'connect.php?mod=login&op=init&referer='.urlencode($_G['connect']['referer'] ? $_G['connect']['referer'] : 'index.php');
			// �°�Connect����Callback����ҳ
			$_G['connect']['callback_url'] = $_G['siteurl'].'connect.php?mod=login&op=callback';
			// ��feed js֪ͨ���ش���ҳ
			$_G['connect']['discuz_new_feed_url'] = $_G['siteurl'].'connect.php?mod=feed&op=new&formhash=' . formhash();
			// ������js֪ͨ���ش���ҳ
			//$_G['connect']['discuz_new_share_url'] = $_G['siteurl'].'connect.php?mod=share&op=new';
			$_G['connect']['discuz_new_share_url'] = $_G['siteurl'].'home.php?mod=spacecp&ac=plugin&id=qqconnect:spacecp&pluginop=new';
			// ������΢����Ļ���������ַ
			$_G['connect']['discuz_sync_tthread_url'] = $_G['siteurl'].'home.php?mod=spacecp&ac=plugin&id=qqconnect:spacecp&pluginop=sync_tthread&formhash=' . formhash();
			// ����QQ�ŵ�¼���ش���ҳ
			$_G['connect']['discuz_change_qq_url'] = $_G['siteurl'].'connect.php?mod=login&op=change';
			// QC��Ȩ���Ӧ��ϵ
			$_G['connect']['auth_fields'] = array(
				'is_user_info' => 1,
				'is_feed' => 2,
			);

			if($_G['uid']) {
				dsetcookie('connect_is_bind', $_G['member']['conisbind'], 31536000);
				if(!$_G['member']['conisbind'] && $_G['cookie']['connect_login']) {
					$_G['cookie']['connect_login'] = 0;
					dsetcookie('connect_login');
				}
			}

			// QQ�����ο͸����û���ΪQQ�ǳ�
			if (!$_G['uid'] && $_G['connectguest']) {
				if ($_G['cookie']['connect_qq_nick']) {
					$_G['member']['username'] = $_G['cookie']['connect_qq_nick'];
				} else {
					$connectGuest = C::t('#qqconnect#common_connect_guest')->fetch($conopenid);
					if ($connectGuest['conqqnick']) {
						$_G['member']['username'] = $connectGuest['conqqnick'];
					}
				}
			}

			if($this->allow && !$_G['uid'] && !defined('IN_MOBILE')) {
				$_G['setting']['pluginhooks']['global_login_text'] = tpl_login_bar();
			}
		}
	}

}

class plugin_qqconnect extends plugin_qqconnect_base {

	var $allow = false;

	function plugin_qqconnect() {
		$this->init();
	}

	function common() {
		$this->common_base();
	}

	function discuzcode($param) {
		global $_G;
		if($param['caller'] == 'discuzcode') {
			$_G['discuzcodemessage'] = preg_replace('/\[wb=(.+?)\](.+?)\[\/wb\]/', '<a href="http://t.qq.com/\\1" target="_blank"><img src="\\2" /></a>', $_G['discuzcodemessage']);
		}
		if($param['caller'] == 'messagecutstr') {
			$_G['discuzcodemessage'] = preg_replace('/\[tthread=(.+?)\](.*?)\[\/tthread\]/', '', $_G['discuzcodemessage']);
		}
	}

	function avatar($param) {
		global $_G;
		if($this->allow) {
			if($_G['basescript'] == 'home' && CURMODULE == 'space' && (!$_GET['do'] || in_array($_GET['do'], array('profile', 'index')))) {
				$avataruid = $_GET['uid'];
			} elseif(CURMODULE == 'viewthread') {
				$avataruid = $_G['uid'];
			} else {
				return;
			}
			list($uid, $size, $returnsrc) = $param['param'];
			if($returnsrc || $size && $size != 'middle' || $uid != $avataruid) {
				return;
			}
			
			$connectUserInfo = array();
			if ($uid) {
				$connectUserInfo = C::t('#qqconnect#common_member_connect')->fetch($uid);
			}
			
			if ($connectUserInfo) {
				if($connectUserInfo['conisqqshow'] && $connectUserInfo['conopenid']) {
					$_G['hookavatar'] = $this->_qqshow_img($connectUserInfo['conopenid']);
				}
			}
		}
	}

	function global_login_extra() {
		if(!$this->allow) {
			return;
		}
		return tpl_global_login_extra();
	}

	function global_usernav_extra1() {
		global $_G;
		if(!$this->allow) {
			return;
		}
		if (!$_G['uid'] && !$_G['connectguest']) {
			return;
		}
		if(!$_G['member']['conisbind']) {
			return tpl_global_usernav_extra1();
		}
	}

	function global_footer() {
		global $_G;

		if(!$this->allow || !empty($_G['inshowmessage'])) {
			return;
		}

		// ҳ����Ҫ���ص�js
		$loadJs = array();

		$connectService = Cloud::loadClass('Service_Connect');

		// ֻ�п���QQ����������Q-share�����ڿ���ҳ���Ի��ʷ�������Ѷ΢��
		if(defined('CURSCRIPT') && CURSCRIPT == 'forum' && defined('CURMODULE') && CURMODULE == 'viewthread'
			&& $_G['setting']['connect']['allow'] && $_G['setting']['connect']['qshare_allow']) {

			$appkey = $_G['setting']['connect']['qshare_appkey'] ? $_G['setting']['connect']['qshare_appkey'] : $_G['connect']['weibo_public_appkey'];

			$qsharejsurl = $_G['siteurl'] . 'static/js/qshare.js';
			$sitename = isset($_G['setting']['bbname']) ? $_G['setting']['bbname'] : '';
			$loadJs['qsharejs'] = array('jsurl' => $qsharejsurl, 'appkey' => $appkey, 'sitename' => $sitename, 'func' => '$C');
			//�Ƶ� tpl��
			//$footerjs .= $connectService->connectLoadQshareJs($appkey);
		}

		if(!empty($_G['cookie']['connect_js_name'])) {
			if($_G['cookie']['connect_js_name'] == 'user_bind') {
				$params = array('openid' => $_G['cookie']['connect_uin']);
				$jsurl = $connectService->connectUserBindJs($params);
				$loadJs['feedjs'] = array('jsurl' => $jsurl);
			} elseif($_G['cookie']['connect_js_name'] == 'feed_resend') {
				$jsurl = $connectService->connectFeedResendJs();
				$loadJs['feedjs'] = array('jsurl' => $jsurl);
			} elseif($_G['cookie']['connect_js_name'] == 'guest_ptlogin') {
				$jsurl = $connectService->connectGuestPtloginJs();
				$loadJs['guestloginjs'] = array('jsurl' => $jsurl);
			}

			dsetcookie('connect_js_name');
			dsetcookie('connect_js_params');
		}

		//note ������0 ���ϴ��ϱ����ڷǽ���
		loadcache('connect_login_report_date');
		if (dgmdate(TIMESTAMP, 'Y-m-d') != $_G['cache']['connect_login_report_date']) {
			$jsurl = $connectService->connectCookieLoginJs();
			$loadJs['cookieloginjs'] = array('jsurl' => $jsurl);
		}

		// cookie ��½�ϱ�/ͳ��
		if ($_G['member']['conisbind']) {
			$connectService->connectMergeMember();
			if($_G['member']['conuinsecret'] && ($_G['cookie']['connect_last_report_time'] != dgmdate(TIMESTAMP, 'Y-m-d'))) {
				$connectService->connectAddCookieLogins();
			}
		}

		return tpl_global_footer($loadJs);
	}

	/*
	 * @brief _allowconnectfeed �������͵��ռ�
	 *
	 * @returns
	 */
	function _allowconnectfeed() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		return $_G['uid'] && $_G['setting']['connect']['allow'] && $_G['setting']['connect']['feed']['allow'] && ($_G['forum']['status'] == 3 && $_G['setting']['connect']['feed']['group'] || $_G['forum']['status'] != 3 && (!$_G['setting']['connect']['feed']['fids'] || in_array($_G['fid'], $_G['setting']['connect']['feed']['fids'])));
	}

	/*
	 * @brief _allowconnectt �������͵�΢��
	 *
	 * @returns
	 */
	function _allowconnectt() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		return $_G['uid'] && $_G['setting']['connect']['allow'] && $_G['setting']['connect']['t']['allow'] && ($_G['forum']['status'] == 3 && $_G['setting']['connect']['t']['group'] || $_G['forum']['status'] != 3 && (!$_G['setting']['connect']['t']['fids'] || in_array($_G['fid'], $_G['setting']['connect']['t']['fids'])));
	}

	/*
	 * @brief _forumdisplay_fastpost_sync_method_output ���ٷ���ͬ����ť
	 *
	 * @returns
	 */
	function _forumdisplay_fastpost_sync_method_output() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		$allowconnectfeed = $this->_allowconnectfeed();
		$allowconnectt = $this->_allowconnectt();
		if($GLOBALS['fastpost'] && ($allowconnectfeed || $allowconnectt)) {
			$connectService = Cloud::loadClass('Service_Connect');
			$connectService->connectMergeMember();
			if ($_G['member']['is_feed']) {
				return tpl_sync_method($allowconnectfeed, $allowconnectt);
			}
		}
	}

	/*
	 * @brief _post_sync_method_output ����ͬ����ť
	 *
	 * @returns
	 */
	function _post_sync_method_output() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		$allowconnectfeed = $this->_allowconnectfeed();
		$allowconnectt = $this->_allowconnectt();
		if(!$_G['inajax'] && ($allowconnectfeed || $allowconnectt) && ($_GET['action'] == 'newthread' || $_GET['action'] == 'edit' && $GLOBALS['isfirstpost'] && $GLOBALS['thread']['displayorder'] == -4)) {
			$connectService = Cloud::loadClass('Service_Connect');
			$connectService->connectMergeMember();
			if ($_G['member']['is_feed']) {
				return tpl_sync_method($allowconnectfeed, $allowconnectt);
			}
		}
	}

	/*
	 * @brief _post_infloat_btn_extra_output ������ͬ����ť
	 *
	 * @returns
	 */
	function _post_infloat_btn_extra_output() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		$allowconnectfeed = $this->_allowconnectfeed();
		$allowconnectt = $this->_allowconnectt();
		if($_G['inajax'] && ($allowconnectfeed || $allowconnectt) && $_GET['action'] == 'newthread') {
			$connectService = Cloud::loadClass('Service_Connect');
			$connectService->connectMergeMember();
			if ($_G['member']['is_feed']) {
				return tpl_infloat_sync_method($allowconnectfeed, $allowconnectt, ' z');
			}
		}
	}

	/*
	 * @brief _post_feedlog_message �������͵��ռ��΢��
	 *
	 * @param $param
	 * (λ��ʾ 1:��Ҫͬ�����ռ䣬2:�����jsͬ���ռ�
	 * 3:��Ҫͬ����΢����4:�����jsͬ��΢��)
	 * @returns
	 */
	function _post_feedlog_message($param) {
		if(!$this->allow) {
			return;
		}
		global $_G;
		// if(empty($_GET['connect_publish_feed']) || $_GET['action'] == 'reply' || substr($param['param'][0], -8) != '_succeed' || $_GET['action'] == 'edit' && !$GLOBALS['isfirstpost'] || !$this->_allowconnectfeed()) {
		$condition1 = $_GET['action'] == 'reply' || substr($param['param'][0], -8) != '_succeed';
		$condition2 = $_GET['action'] == 'edit' && !$GLOBALS['isfirstpost'];
		$condition3 = !$this->_allowconnectfeed() && !$this->_allowconnectt();
		$condition4 = empty($_GET['connect_publish_feed']) && empty($_GET['connect_publish_t']);
		if ($condition1 || $condition2 || $condition3 || $condition4) {
			return false;
		}

		$tid = $param['param'][2]['tid'];

		$thread = C::t('forum_thread')->fetch($tid);
		if ($_GET['connect_publish_feed']) {
			$thread['status'] = setstatus(7, 1, $thread['status']);
		}
		if ($_GET['connect_publish_t']) {
			$thread['status'] = setstatus(8, 1, $thread['status']);
		}
		// $newstatus = 0;
		// if ($_GET['connect_publish_feed'] && $this->_allowconnectfeed()) {
			// $newstatus = setstatus(1, 1, $newstatus);
		// }

		// if ($_GET['connect_publish_t'] && $this->_allowconnectt()) {
			// $newstatus = setstatus(3, 1, $newstatus);
		// }
		C::t('forum_thread')->update($tid, array('status' => $thread['status']));

		$data = array(
			'tid' => $tid,
			'uid' => $_G['uid'],
			'lastpublished' => 0,
			'dateline' => $_G['timestamp'],
			'status' => 0,
		);
		C::t('#qqconnect#connect_feedlog')->insert($data, 0, 1);
	}


	/*
	 * @brief _viewthread_share_method_output �ж����ͬ�����ռ䡢΢����JS
	 *
	 * @returns
	 */
	function _viewthread_share_method_output() {
		global $_G, $postlist, $canonical;
		// �ж��Ƿ���Ҫ���͵��ռ����΢�����ж���������Ҫ��+������޷�����־
		$needFeedStatus = getstatus($_G['forum_thread']['status'], 7);
		$needWeiboStatus = getstatus($_G['forum_thread']['status'], 8);
		// �����ַ
		$_G['connect']['thread_url'] = $_G['siteurl'] . $canonical;

		// ���������������������ַ
		$connectService = Cloud::loadClass('Service_Connect');
		$_G['connect']['qzone_share_url'] = $_G['siteurl'] . 'home.php?mod=spacecp&ac=plugin&id=qqconnect:spacecp&pluginop=share&sh_type=1&thread_id=' . $_G['tid'];
		$_G['connect']['weibo_share_url'] = $_G['siteurl'] . 'home.php?mod=spacecp&ac=plugin&id=qqconnect:spacecp&pluginop=share&sh_type=2&thread_id=' . $_G['tid'];
		$_G['connect']['pengyou_share_url'] = $_G['siteurl'] . 'home.php?mod=spacecp&ac=plugin&id=qqconnect:spacecp&pluginop=share&sh_type=3&thread_id=' . $_G['tid'];
		// ¥��pid
		$_G['connect']['first_post'] = $postlist[$_G['forum_firstpid']];
		$_GET['connect_autoshare'] = !empty($_GET['connect_autoshare']) ? 1 : 0;

		// ����΢��appkey
		$_G['connect']['weibo_appkey'] = $_G['connect']['weibo_public_appkey'];
		if($this->allow && $_G['setting']['connect']['qshare_appkey']) {
			$_G['connect']['weibo_appkey'] = $_G['setting']['connect']['qshare_appkey'];
		}
		// ��������Ĳ������߱��˻���û�а󶨹�ϵֱ�ӽ���
		$condition1 = $_G['uid'] != $_G['forum_thread']['authorid'] || !$_G['member']['conopenid'];
		// �ж��Ƿ�����������
		$condition2 = $_G['forum_thread']['displayorder'] < 0;
		// �����������Сʱ��������ִ�У����ٲ�ѯ
		$condition3 = $_G['timestamp'] - $_G['forum_thread']['dateline'] > $this->retryAvaiableTime;
		// ������������һ������
		if ($condition1 || $condition2 || $condition3) {
			$needFeedStatus = $needWeiboStatus = false;
		}

		// վ�����ͼƬȨ�ޣ�δ��QC����Ȩ�޲鿴ͼƬ���û�
		if (!$_G['member']['conisbind'] && $_G['group']['allowgetimage'] && $_G['thread']['price'] == 0) {
			// debug վ���������ͼƬȨ���ж�
			if (trim($_G['forum']['viewperm'])) {
				$allowViewPermGroupIds = explode("\t", trim($_G['forum']['viewperm']));
			}
			if (trim($_G['forum']['getattachperm'])) {
				$allowViewAttachGroupIds = explode("\t", trim($_G['forum']['getattachperm']));
			}
			$bigWidth = '400';
			$bigHeight = '400';
			$share_images = array();
			foreach ($_G['connect']['first_post']['attachments'] as $attachment) {
				if ($attachment['isimage'] == 0 || $attachment['price'] > 0
					|| $attachment['readperm'] > $_G['group']['readaccess']
					|| ($allowViewPermGroupIds && !in_array($_G['groupid'], $allowViewPermGroupIds))
					|| ($allowViewAttachGroupIds && !in_array($_G['groupid'], $allowViewAttachGroupIds))) {
						continue;
					}
				$key = md5($attachment['aid'].'|'.$bigWidth.'|'.$bigHeight);
				$bigImageURL = $_G['siteurl'] . 'forum.php?mod=image&aid='.$attachment['aid'] . '&size=' . $bigWidth . 'x' . $bigHeight . '&key=' . rawurlencode($key) . '&type=fixnone&nocache=1';
				$share_images[] = urlencode($bigImageURL);
			}
			$_G['connect']['share_images'] = implode('|', $share_images);
		}


		// �������Ҫ���͵��ռ�Ҳ����Ҫ���͵�΢��
		if (!$needFeedStatus && !$needWeiboStatus) {
			return tpl_viewthread_share_method($jsurl);
		}
		// ����ڵ�һҳ��������¥���㣬����¥��������ӿɼ������������ж�����
		if ($_G['page'] == 1 && $_G['forum_firstpid'] && $postlist[$_G['forum_firstpid']]['invisible'] == 0) {
			$feedLog = C::t('#qqconnect#connect_feedlog')->fetch_by_tid($_G['tid']);
			// ���ʹ����ﵽ���ȡ��
			if ($feedLog['publishtimes'] >= $this->retryMax) {
				return tpl_viewthread_share_method($jsurl);
			}
			// �Ѿ����͵��ռ�
			$hadFeedStatus = getstatus($feedLog['status'], 2);
			// �Ѿ����͵�΢��
			$hadWeiboStatus = getstatus($feedLog['status'], 4);

			if (!$hadFeedStatus || !$hadWeiboStatus) {

				// ��ʱȥ�����Ի���
				// �����Ҫ������δ���͹�
				if ($needFeedStatus && !$hadFeedStatus) {
					// �ϴη���ʱ����뵱ǰʱ��С��1���ӣ�������
					if ($_G['timestamp'] - $feedLog['lastpublished'] < 60) {
						$needFeedStatus = false;
					}
				} else {
					// �����������������JS
					$needFeedStatus = false;
				}

				// ��ʱȥ�����Ի���
				// �����Ҫ������δ���͹�
				if($needWeiboStatus && !$hadWeiboStatus) {
					// �ϴη���ʱ����뵱ǰʱ��С��1���ӣ�������
					if ($_G['timestamp'] - $feedLog['lastpublished'] < 60) {
						$needWeiboStatus = false;
					}
				} else {
					// �����������������JS
					$needWeiboStatus = false;
				}
			}


			// ��feed֪ͨ������
			$jsurl = '';
			if($needFeedStatus || $needWeiboStatus) {
				$params = array();
				$params['thread_id'] = $_G['tid'];
				$params['ts'] = TIMESTAMP;
				$params['type'] = bindec(($needWeiboStatus ? '1' : '0').($needFeedStatus ? '1' : '0'));
				$params['sig'] = $connectService->connectGetSig($params, $connectService->connectGetSigKey());

				$utilService = Cloud::loadClass('Service_Util');
				$jsurl = $_G['connect']['discuz_new_feed_url'].'&'.$utilService->httpBuildQuery($params, '', '&');
			}
			$connectService->connectMergeMember();

			return tpl_viewthread_share_method($jsurl);
		}
	}

	function _viewthread_bottom_output() {
		if(!$this->allow) {
			return;
		}
		global $_G, $thread, $rushreply, $postlist, $page;
		$uids = $openids = array();
		foreach($postlist as $pid => $post) {
			if($post['anonymous']) {
				continue;
			}
			if($post['authorid']) {
				$uids[$post['authorid']] = $post['authorid'];
			}
		}
		foreach(C::t('#qqconnect#common_member_connect')->fetch_all($uids) as $connect) {
			if($connect['conisqqshow'] && $connect['conopenid']) {
				$openids[$connect['uid']] = $connect['conopenid'];
			}
		}
		foreach($postlist as $pid => $post) {
			if(getstatus($post['status'], 5)) {
				$matches = array();
				preg_match('/\[tthread=(.+?),(.+?)\](.*?)\[\/tthread\]/', $post['message'], $matches);
				if($matches[1] && $matches[2]) {
					$post['message'] = preg_replace('/\[tthread=(.+?)\](.*?)\[\/tthread\]/', lang('plugin/qqconnect', 'connect_tthread_message', array('username' => $matches[1], 'nick' => $matches[2])), $post['message']);
				}
				$post['authorid'] = 0;
				$post['author'] = lang('plugin/qqconnect', 'connect_tthread_comment');
				$post['avatar'] = $matches[3] ? '<img src="'.$matches[3].'/120'.'">' : '<img src="'.$_G['siteurl'].'/static/image/common/tavatar.gif">';
				$post['groupid'] = '7';
				$postlist[$pid] = $post;
				continue;
			}
			if($post['anonymous']) {
				continue;
			}
			if($openids[$post['authorid']]) {
				$postlist[$pid]['avatar'] = $this->_qqshow_img($openids[$post['authorid']]);
			}
		}

		if($page == 1 && $postlist[$_G['forum_firstpid']]['invisible'] == 0) {
			$jsurl = '';
			//note ������΢������������δ�رգ��Ҳ�����¥�����������ѷ�������Ѷ΢���� todo ΢������
			if(!$_G['cookie']['connect_last_sync_t'] && $_G['uid'] && $_G['setting']['connect']['t']['reply'] && !$thread['closed'] && !$rushreply && getstatus($_G['forum_thread']['status'], 14)) {

				$jsurl = $_G['connect']['discuz_sync_tthread_url'].'&tid='.$thread['tid'];

				// ��һ��JSʱ��һ��ʮ���ӵ�cookie,��ǰ�û�ʮ�����ڲ��ᴥ������JS
				dsetcookie('connect_last_sync_t', 1, 600);
			}

			return tpl_viewthread_bottom($jsurl);
		}
	}

	function _qqshow_img($openid) {
		global $_G;
		return '<img width="120" src="http://open.show.qq.com/cgi-bin/qs_open_snapshot?appid='.$_G['setting']['connectappid'].'&openid='.$openid.'" />';
	}
}

class plugin_qqconnect_member extends plugin_qqconnect {

	function connect_member() {
		global $_G, $seccodecheck, $secqaacheck, $connect_guest;

		if($this->allow) {
			if($_G['uid'] && $_G['member']['conisbind']) {
				dheader('location: '.$_G['siteurl'].'index.php');
			}
			$connect_guest = array();
			if($_G['connectguest'] && (submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('loginsubmit', 1, $seccodestatus))) {
				if(!$_GET['auth_hash']) {
					$_GET['auth_hash'] = $_G['cookie']['con_auth_hash'];
				}
				$conopenid = authcode($_GET['auth_hash']);
				$connect_guest = C::t('#qqconnect#common_connect_guest')->fetch($conopenid);
				if(!$connect_guest) {
					dsetcookie('con_auth_hash');
					showmessage('qqconnect:connect_login_first');
				}
			}
		}
	}

	function logging_member() {
		global $_G;
		if($this->allow && $_G['connectguest'] && $_GET['action'] == 'login') {
			if ($_G['inajax']) {
				showmessage('qqconnect:connectguest_message_complete_or_bind');
			} else {
				dheader('location: '.$_G['siteurl'].'member.php?mod=connect&ac=bind');
			}
		}
	}

	function register_member() {
		global $_G;
		if($this->allow && $_G['connectguest']) {
			if ($_G['inajax']) {
				showmessage('qqconnect:connectguest_message_complete_or_bind');
			} else {
				dheader('location: '.$_G['siteurl'].'member.php?mod=connect');
			}
		}
	}

	function logging_method() {
		if(!$this->allow) {
			return;
		}
		return tpl_login_bar();
	}

	function register_logging_method() {
		if(!$this->allow) {
			return;
		}
		return tpl_login_bar();
	}

	function connect_input_output() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		$_G['setting']['pluginhooks']['register_input'] = tpl_register_input();
	}

	function connect_bottom_output() {
		if(!$this->allow) {
			return;
		}
		global $_G;
		$_G['setting']['pluginhooks']['register_bottom'] = tpl_register_bottom();
	}

}

class plugin_qqconnect_forum extends plugin_qqconnect {

	function index_status_extra() {
		global $_G;
		if(!$this->allow) {
			return;
		}
		if($_G['setting']['connect']['like_allow'] && $_G['setting']['connect']['like_url'] || $_G['setting']['connect']['turl_allow'] && $_G['setting']['connect']['turl_code']) {
			return tpl_index_status_extra();
		}
	}

	function forumdisplay_fastpost_sync_method_output() {
		return $this->_forumdisplay_fastpost_sync_method_output();
	}

	function post_sync_method_output() {
		return $this->_post_sync_method_output();
	}

	function post_infloat_btn_extra_output() {
		return $this->_post_infloat_btn_extra_output();
	}

	function post_feedlog_message($param) {
		return $this->_post_feedlog_message($param);
	}

	function viewthread_share_method_output() {
		return $this->_viewthread_share_method_output();
	}

	function viewthread_bottom_output() {
		return $this->_viewthread_bottom_output();
	}

}

class plugin_qqconnect_group extends plugin_qqconnect {

	function forumdisplay_fastpost_sync_method_output() {
		return $this->_forumdisplay_fastpost_sync_method_output();
	}

	function post_sync_method_output() {
		return $this->_post_sync_method_output();
	}

	function post_infloat_btn_extra_output() {
		return $this->_post_infloat_btn_extra_output();
	}

	function post_feedlog_message($param) {
		return $this->_post_feedlog_message($param);
	}

	function viewthread_share_method_output() {
		return $this->_viewthread_share_method_output();
	}

	function viewthread_bottom_output() {
		return $this->_viewthread_bottom_output();
	}
}

class plugin_qqconnect_home extends plugin_qqconnect {

	function spacecp_profile_bottom() {
		global $_G;

		if(submitcheck('profilesubmit')) {
			$_G['group']['maxsigsize'] = $_G['group']['maxsigsize'] < 200 ? 200 : $_G['group']['maxsigsize'];
			return;
		}
		if($_G['uid'] && $_G['setting']['connect']['allow']) {
			return tpl_spacecp_profile_bottom();
		}

	}
}

class mobileplugin_qqconnect extends plugin_qqconnect_base {

	var $allow = false;

	function mobileplugin_qqconnect() {
		global $_G;
		if(!$_G['setting']['connect']['allow'] || $_G['setting']['bbclosed']) {
			return;
		}
		$this->allow = true;
	}

	function common() {
		$this->common_base();
	}

	function global_footer_mobile() {
		global $_G;

		if(!$this->allow || !empty($_G['inshowmessage'])) {
			return;
		}

		$connectService = Cloud::loadClass('Service_Connect');

		if(!empty($_G['cookie']['connect_js_name'])) {
			if($_G['cookie']['connect_js_name'] == 'guest_ptlogin') {
				$jsurl = $connectService->connectGuestPtloginJs();
				return '<script type="text/javascript">function con_handle_response(response) {
							return response;
						}</script>
						<script type="text/javascript" src="'.$jsurl.'"></script>';
				dsetcookie('connect_js_name');
			}
		}
	}

}