<?php

/**
 *	  [Discuz!] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: connect_feed.php 29265 2012-03-31 06:03:26Z yexinhao $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

// formhash ����
if (trim($_GET['formhash']) != formhash()) {
	exit('Access Denied');
}

$params = $_GET;
$op = !empty($_GET['op']) ? $_GET['op'] : '';
if (!in_array($op, array('new'))) {
	$connectService->connectJsOutputMessage('', 'undefined_action', 1);
}

// debug ����id
$tid = trim(intval($_GET['thread_id']));
if (empty($tid)) {
	$connectService->connectJsOutputMessage('', 'connect_thread_id_miss', 1);
}

// debug ��feed����
if ($op == 'new') {

	$connectService->connectMergeMember();

	//$posttable = getposttablebytid($tid);
	//$post = DB::fetch_first("SELECT * FROM ".DB::table($posttable)." WHERE tid = '$tid' AND first='1' AND invisible='0'");
	$post = C::t('forum_post')->fetch_threadpost_by_tid_invisible($tid, 0);
//	$thread = DB::fetch_first("SELECT * FROM ".DB::table('forum_thread')." WHERE tid = '$tid' AND displayorder >= 0");
	$thread = C::t('forum_thread')->fetch_by_tid_displayorder($tid, 0);
	$feedlog = C::t('#qqconnect#connect_feedlog')->fetch_by_tid($thread['tid']);
    // �����ǰ�û������߲���ͬһ����
    if ($_G['uid'] != $thread['authorid']) {
		$connectService->connectJsOutputMessage('', 'connect_about', 2);
    }
    // ���û����Ҫ���͵ı�ǣ�������
    if (!getstatus($thread['status'], 7) && !getstatus($thread['status'], 8)) {
		$connectService->connectJsOutputMessage('', 'connect_about', 2);
    }

	// ���ʹ������� 5 �Σ����뷢��ʱ�䳬�����Сʱ�����ٷ���
	if ($feedlog['publishtimes'] >= 5 || $_G['timestamp'] - $thread['dateline'] > 1800) {
		$connectService->connectJsOutputMessage('', 'connect_about', 2);
    }

	// debug feed������ 1 QZone��2 t��3 QZone + t
	$f_type = trim(dintval($_GET['type']));

	$html_content = $connectService->connectParseBbcode($post['message'], $thread['fid'], $post['pid'], $post['htmlon'], $attach_images);

	if($_G['setting']['rewritestatus'] && in_array('forum_viewthread', $_G['setting']['rewritestatus'])) {
		$url = rewriteoutput('forum_viewthread', 1, $_G['siteurl'], $tid);
	} else {
		$url = $_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid;
	}

	$qzone_params = array(
		'title' => $thread['subject'],
		'url' => $url,
		'summary' => $html_content,
		'nswb' => '1', // ���Զ�ͬ����΢��
	);

	$t_params = array(
		'content' => $thread['subject'].' '.$url,
	);

	// debug ע����˵���ͼƬ������ͼƬ����ֻ��ǰһ��
	if($attach_images && is_array($attach_images)) {
		$attach_image = array_shift($attach_images);
		$qzone_params['images'] = $attach_image['big'];
		$t_params['pic'] = $attach_image['path'];
		$t_params['remote'] = $attach_image['remote'];
	}

	$connectOAuthClient = Cloud::loadClass('Service_Client_ConnectOAuth');
	$feed_succ = $weibo_succ = false;
	// ���͵��ռ�
	if(getstatus($f_type, 1)) {
		try {
			$response = $connectOAuthClient->connectAddShare($_G['member']['conopenid'], $_G['member']['conuin'], $_G['member']['conuinsecret'], $qzone_params);

			$f_type = setstatus(1, 0, $f_type);
			// ���ش�����ȷ
			if ($response['ret'] == 0) {
				$feed_succ = true;
			}
		} catch(Exception $e) {
			if($e->getCode()) {
				$f_type = setstatus(1, 0, $f_type);
				$shareErrorCode = $e->getCode();
			}
			$feed_succ = false;
		}
	}
	// ���͵�΢��
	if(getstatus($f_type, 2)) {
		try {
			if ($t_params['pic']) {
				$method = 'connectAddPicT';
			} else {
				$method = 'connectAddT';
			}

			$response = $connectOAuthClient->$method($_G['member']['conopenid'], $_G['member']['conuin'], $_G['member']['conuinsecret'], $t_params);
			// ���͵�΢���ɹ�����΢��id�� ���뵽΢�������ı���
			if($response['data']['id']) {
				if($_G['setting']['connect']['t']['reply'] && $thread['tid'] && !$thread['closed'] && !getstatus($thread['status'], 3) && empty($_G['forum']['replyperm'])) {
					$memberConnect = C::t('#qqconnect#common_member_connect')->fetch($thread['authorid']);
					$conopenid = $memberConnect['conopenid'];
					// $conopenid = DB::result_first("SELECT conopenid FROM ".DB::table('common_member_connect')." WHERE uid='".$thread['authorid']."'");
					//DB::insert('connect_tthreadlog', array(
					C::t('#qqconnect#connect_tthreadlog')->insert(array(
						'twid' => $response['data']['id'],
						'tid' => $tid,
						'conopenid' => $conopenid,
						'pagetime' => 0,
						'lasttwid' => '0',
						'nexttime' => $_G['timestamp'] + 30 * 60,
						'updatetime' => 0,//note ������ʱ��
						'dateline' => $_G['timestamp'],
					));
				}
			}

			$f_type = setstatus(2, 0, $f_type);
			// ���ش�����ȷ
			if ($response['ret'] == 0) {
				$weibo_succ = true;
			}
		} catch(Exception $e) {
			if($e->getCode()) {
				$f_type = setstatus(2, 0, $f_type);
				$weiboErrorCode = $e->getCode();
			}
			$weibo_succ = false;
		}
	}

	// ���ͳɹ����޸� thread ���״̬λ
	$thread_status = $thread['status'];
	$feedlog_status = $feedlog['status'];
	if ($feed_succ) {
		$thread_status = setstatus(7, 0, $thread_status);
		// �����ͱ��
		$feedlog_status = setstatus(2, 1, $feedlog_status);
		// ����Ҫ���ͱ��
		$feedlog_status = setstatus(1, 0, $feedlog_status);
	}
	if ($weibo_succ) {
		$thread_status = setstatus(8, 0, $thread_status);
		// ��������͵�΢��
		$thread_status = setstatus(14, 1, $thread_status);
		// �����ͱ��
		$feedlog_status = setstatus(4, 1, $feedlog_status);
		// ����Ҫ���ͱ��
		$feedlog_status = setstatus(3, 0, $feedlog_status);
	}
	// ���ͳɹ������±�
	if ($feed_succ || $weibo_succ) {
		C::t('#qqconnect#connect_feedlog')->update_by_tid($thread['tid'],
			array(
				'status' => $feedlog_status,
				'lastpublished' => $_G['timestamp'],
				'publishtimes' => $feedlog['publishtimes'] + 1,
			));
		C::t('forum_thread')->update($thread['tid'], array('status' => $thread_status));
	}

	// debug �����д������ʧ�ܾ�������
	if(!$shareErrorCode && !$weiboErrorCode) {
		$connectService->connectJsOutputMessage(lang('plugin/qqconnect', 'connect_feed_sync_success'), '', 0);
	} else {
		// // debug TODO ���Դ���ҲҪ���ƣ�������������
		// if($f_type > 0) {
			// dsetcookie('connect_js_name', 'feed_resend');
			// dsetcookie('connect_js_params', base64_encode(serialize(array('type' => $f_type, 'thread_id' => $tid, 'ts' => TIMESTAMP))), 86400);
		// }
		// ����ʧ�ܣ����� feedlog ��
		C::t('#qqconnect#connect_feedlog')->update_by_tid($thread['tid'],
			array(
				'lastpublished' => $_G['timestamp'],
				'publishtimes' => $feedlog['publishtimes'] + 1,
			));
		$connectService->connectJsOutputMessage('', '', $shareErrorCode.'|'.$weiboErrorCode);
	}

}
