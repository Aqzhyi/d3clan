<?php

/**
 *	  [Discuz!] (C)2001-2009 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: connect_check.php 29265 2012-03-31 06:03:26Z yexinhao $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$utilService = Cloud::loadClass('Service_Util');

$op = !empty($_GET['op']) ? $_GET['op'] : '';
if (!in_array($op, array('cookie'))) {
	$connectService->connectAjaxOuputMessage('0', '1');
}

if ($op == 'cookie') {
	loadcache('connect_login_report_date');
	$cookieLogins = C::t('common_setting')->fetch('connect_login_times');
	//note 次数非0 且上次上报日期非今日
	if (dgmdate(TIMESTAMP, 'Y-m-d') != $_G['cache']['connect_login_report_date']) {
		if (!discuz_process::islocked('connect_login_report', 600)) {
			// 如果cookieLogins 是 0 的话，直接返回成功，下次再上报
			$result = $connectService->connectCookieLoginReport($cookieLogins);
			if (isset($result['status']) && $result['status'] == 0) {
				$date = dgmdate(TIMESTAMP, 'Y-m-d');
				C::t('common_setting')->update('connect_login_times', 0);
				C::t('common_setting')->update('connect_login_report_date', $date);
				savecache('connect_login_report_date', $date);
			}
		}
		discuz_process::unlock('connect_login_report');
	}
}

include template('common/header_ajax');
include template('common/footer_ajax');
