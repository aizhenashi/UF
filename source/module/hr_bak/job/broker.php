<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/hr');

$actionarray = array('list', 'my', 'setting');
$action = $_GET['action'] && in_array($_GET['action'], $actionarray) ? $_GET['action'] : 'list';

$uid = '';
if($action == 'my') {
	$uid = intval($_GET['uid']);
} elseif($action == 'setting') {
	$uid = $_G['uid'];
}

if(in_array($action, array('my', 'setting'))) {

	if(empty($sortid)) {
		$defaultsortid = array_keys($sortlist);
		$sortid = $defaultsortid[0];
	}
	
	$member = DB::fetch_first("SELECT cm.threads, cm.groupid, cm.city, cm.district, cm.street, cm.verify, cm.certification, cm.tel, cm.address, m.uid, m.username, cm.realname FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
		LEFT JOIN ".DB::table('common_member')." m ON cm.uid=m.uid WHERE cm.uid='$uid'");

	if(empty($member['uid'])) {
		showmessage('not_loggedin', '', '', array('login' => 1));
	}

	require_once libfile('function/hr');

	$usergroupid = $member['groupid'];
	$username = $member['realname'] ? $member['realname'] : $member['username'];
	$avatar = hr_uc_avatar($member['uid']);
	$usergrouplist[$usergroupid]['icon'] = $usergrouplist[$usergroupid]['icon'] ? $_G['setting']['attachurl'].'common/'.$usergrouplist[$usergroupid]['icon'] : '';
	$usergrouplist[$usergroupid]['postdayper'] = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper']) : '';
	if($usergroupid > 1) {
		$usergrouplist[$usergroupid]['title'] = '<a href="'.$modurl.'?mod=agent&action=store&gid='.$usergroupid.'">'.$usergrouplist[$usergroupid]['title'].'</a>';
	}
	$_G['hr_member']['todaythreads'] = intval($_G['hr_member']['todaythreads']);
	$verifyinfo = showverifyicon($member['certification']);
}

if($action == 'list') {

	$navtitle = '代理人 - '.$channel['title'];
	$usergrouplist = $_G['cache']['hr_usergrouplist_'.$modidentifier];

	$shownum = 20;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $shownum;

	$membernum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." m, ".DB::table('hr_'.$modidentifier.'_member')." hm WHERE m.uid=hm.uid AND hm.groupid > 1 AND hm.verify='1'");
	$multipage = multi($membernum, $shownum, $page, "$modurl?mod=broker");

	$memberlist = $topmemberlist = array();
	if($membernum) {
		$query = DB::query("SELECT cm.threads, cm.groupid, cm.tel, cm.address, cm.lastpost, cm.city, cm.district, cm.street, m.uid, m.username, cm.realname FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
			LEFT JOIN ".DB::table('common_member')." m ON cm.uid=m.uid WHERE cm.groupid>'1' AND cm.verify='1' ORDER BY cm.lastpost DESC LIMIT $start_limit, $shownum");
		while($member = DB::fetch($query)) {
			$member['avatar'] = hr_uc_avatar($member['uid']);
			$member['username'] = $member['realname'] ? $member['realname'] : $member['username'];
			$member['usergroup'] = $usergrouplist[$member['groupid']]['title'];
			$member['lastpost'] = $member['lastpost'] ? dgmdate($member['lastpost'], 'd') : 0;
			$member['serverarea'] = $arealist['district'][$member['city']][$member['district']].'&nbsp;'.$arealist['street'][$member['district']][$member['street']];
			if($member['serverarea'] == '&nbsp;') {
				unset($member['serverarea']);
			}
			$memberlist[] = $member;
		}

		$query = DB::query("SELECT cm.threads, m.uid, m.username, cm.realname FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
			LEFT JOIN ".DB::table('common_member')." m ON cm.uid=m.uid WHERE cm.groupid>'1' AND cm.verify='1' ORDER BY cm.threads DESC LIMIT 0, 10");
		while($toplist = DB::fetch($query)) {
			if($toplist['realname']) {
				$toplist['username'] = $toplist['realname'];
			}
			$topmemberlist[] = $toplist;
		}
	}

} elseif($action == 'my') {

	loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));
	$sortoptionarray = $_G['cache']['hr_option_'.$sortid];
	$templatearray = $_G['cache']['hr_template_'.$sortid]['subject'];
	$sortlistarray = $stemplate = $sortexpiration = array();
	$navtitle = $username.' - 代理人 - '.$channel['title'];
	
	$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;

	$_G['hr_threadlist'] = $threadids = $authorids = array();

	$page = $_G['page'];
	$start_limit = ($page - 1) * $_G['tpp'];
	$colorarray = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');

	$_G['hr_threadcount'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE sortid='$sortid' AND authorid='$uid'");
	$multipage = multi($_G['hr_threadcount'], $_G['tpp'], $page, "$modurl?mod=broker&action=my&uid=$uid");

	$query = DB::query("SELECT t.*, ts.* FROM ".DB::table('hr_'.$modidentifier.'_thread')." t
		LEFT JOIN ".DB::table('hr_sortvalue')."$sortid ts ON t.tid=ts.tid
		WHERE t.authorid='$uid' AND t.sortid='$sortid' ORDER BY ts.displayorder DESC, ts.dateline DESC LIMIT $start_limit, $_G[tpp]");
	while($thread = DB::fetch($query)) {
		if($thread['highlight']) {
			$string = sprintf('%02d', $thread['highlight']);
			$stylestr = sprintf('%03b', $string[0]);

			$thread['highlight'] = ' style="';
			$thread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
			$thread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
			$thread['highlight'] .= '"';
		} else {
			$thread['highlight'] = '';
		}
		$threadids[] = $thread['tid'];
		$authorids[] = $thread['authorid'];
		$thread['author'] = $username;
		$thread['usergroup'] = $_G['hr_usergrouplist'][$thread['groupid']];
		$thread['isgroupadmin'] = $_G['uid'] && $_G['uid'] == $thread['usergroup']['manageuid'] ? 1 : 0;
		$_G['hr_threadlist'][$thread['tid']] = $thread;
	}

	if(!empty($threadids)) {
		$sortlistarray = showsorttemplate($sortid, $sortoptionarray, $templatearray, $_G['hr_threadlist'], $threadids, $arealist, $modurl);
		$stemplate = $sortlistarray['template'];
		$sortexpiration = $sortlistarray['expiration'];
	}
	
} elseif($action == 'setting') {

	if(!submitcheck('settingsubmit')) {

		$citylist = $districtlist = $streetlist = '';
		foreach($arealist['city'] as $cityid => $city) {
			$citylist .= '<option value="'.$cityid.'" '.($member['city'] == $cityid ? 'selected="selected"' : '').'>'.$city.'</option>';
		}

		if($member['city']) {
			foreach($arealist['district'][$member['city']] as $districtid => $district) {
				$districtlist .= '<option value="'.$districtid.'" '.($member['district'] == $districtid ? 'selected="selected"' : '').'>'.$district.'</option>';
			}
		}

		if($member['district']) {
			foreach($arealist['street'][$member['district']] as $streetid => $street) {
				$streetlist .= '<option value="'.$streetid.'" '.($member['street'] == $streetid ? 'selected="selected"' : '').'>'.$street.'</option>';
			}
		}

	} else {

		$tel = isset($_GET['tel']) ? dhtmlspecialchars(censor(trim($_GET['tel']))) : '';
		$address = isset($_GET['address']) ? dhtmlspecialchars(censor(trim($_GET['address']))) : '';
		$city = intval($_GET['city']);
		$district = intval($_GET['district']);
		$street = intval($_GET['street']);
		
		if(empty($tel)) {
			showmessage(lang('hr/template', 'msg_broker_tel_nothing'));
		}
		if(!checkarea($city, $district, $street)) {
			showmessage(lang('hr/template', 'msg_broker_area_nothing'));
		}

		$data = array(
			'tel' => $tel,
			'address' => $address,
			'city' => $city,
			'district' => $district,
			'street' => $street
		);
		DB::update('hr_'.$modidentifier.'_member', $data, array('uid' => $_G['uid']));
		showmessage(lang('hr/template', 'msg_broker_update_success'), 'job.php?mod=broker&action=my&uid='.$_G['uid']);
	}

}

$member['street'] = $arealist['street'][$member['district']][$member['street']];
$member['district'] = $arealist['district'][$member['city']][$member['district']];
$member['city'] = $arealist['city'][$member['city']];

include template('diy:hr/'.$modidentifier.'_broker');

?>