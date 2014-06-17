<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z zoewho $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid = intval($_GET['gid']);
$actionarray = array('list', 'store', 'application');
$action = $_GET['action'] && in_array($_GET['action'], $actionarray) ? $_GET['action'] : 'list';

$districtlist = array();
if($arealist) {
	foreach($arealist['district'] as $districtarray) {
		foreach($districtarray as $did => $district) {
			$districtlist[$did] = $district;
		}
	}
}

require_once libfile('function/hr');

$_G['hr_threadlist'] = $threadids = $memberlist = array();
$isgroupadmin = 0;

if($action == 'list') {
	$navtitle = '劳务中介 - '.$channel['title'];
	$usergrouplist = $topgrouplist = array();
	
	//中介公司排行
	$query = DB::query("SELECT gid, title, type, threads FROM ".DB::table('hr_'.$modidentifier.'_usergroup')." WHERE verify='1' ORDER BY threads DESC LIMIT 0, 10");
	while($topgroup = DB::fetch($query)) {
		if($topgroup['type'] == 'intermediary') {
			$topgrouplist[$topgroup['gid']]['title'] = $topgroup['title'];
			$topgrouplist[$topgroup['gid']]['threads'] = $topgroup['threads'];
		}
	}
	
	//中介公司列表
	$shownum = 12;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $shownum;

	$groupnum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$modidentifier.'_usergroup')." WHERE type='intermediary' AND verify='1'");
	$multipage = multi($groupnum, $shownum, $page, "$modurl?mod=agent");
	
	$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_usergroup')." WHERE type='intermediary' AND verify='1' ORDER BY displayorder DESC LIMIT $start_limit, $shownum");
	while($group = DB::fetch($query)) {
		$usergrouplist[$group['gid']]['banner'] = $group['banner'] ? get_logoimg($group['banner']) : './static/image/job/noupload.gif';
		$usergrouplist[$group['gid']]['title'] = $group['title'];
		$usergrouplist[$group['gid']]['type'] = $group['type'];
	}

	include template('diy:hr/'.$modidentifier.'_agent:'.$gid);

} elseif($action == 'store') {

	$usergroup = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_usergroup')." WHERE gid='$gid'");
	$usergroup['banner'] = $usergroup['banner'] ? get_logoimg($usergroup['banner']) : './static/image/job/noupload.gif';
	$navtitle = $usergroup['title'].' - 劳务中介 - '.$channel['title'];

	$query = DB::query("SELECT cm.threads, m.uid, m.username, cm.realname FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
			LEFT JOIN ".DB::table('common_member')." m ON cm.uid=m.uid
			WHERE cm.groupid='$gid' AND cm.verify='1' ORDER BY cm.threads DESC LIMIT 5");
	while($member = DB::fetch($query)) {
		$memberlist[$member['uid']]['username'] = $member['realname'] ? $member['realname'] : $member['username'];
		$memberlist[$member['uid']]['avatar'] = hr_uc_avatar($member['uid'], 'small');
		$memberlist[$member['uid']]['threads'] = $member['threads'];
	}
	$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;
	$isgroupadmin = $_G['uid'] && $_G['uid'] == $usergroup['manageuid'] ? 1 : 0;

	if(empty($sortid)) {
		$defaultsortid = array_keys($sortlist);
		$sortid = $defaultsortid[0];
	}

	loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));
	$sortoptionarray = $_G['cache']['hr_option_'.$sortid];

	$templatearray = $_G['cache']['hr_template_'.$sortid]['subject'];
	$rtemplatearray = $_G['cache']['hr_template_'.$sortid]['recommend'];
	$recommendlist = recommendsort($sortid, $sortoptionarray, $gid, $rtemplatearray, $districtlist, $modurl);

	$page = $_G['page'];
	$start_limit = ($page - 1) * $_G['tpp'];

	$sortcondition['orderby'] = 'dateline';
	$sortcondition['ascdesc'] = 'DESC';

	$selectadd = array('groupid' => $gid);
	$sortdata = sortsearch($sortid, $sortoptionarray, $_GET['searchoption'], $selectadd, $sortcondition, $start_limit, $_G['tpp']);
	$tidsadd = $sortdata['tids'] ? "tid IN (".dimplode($sortdata['tids']).")" : '';
	$_G['hr_threadcount'] = $sortdata['count'];

	$multipage = multi($_G['hr_threadcount'], $_G['tpp'], $page, "$modurl?mod=agent&action=store&sortid=$sortid&gid=$gid&cid=$cid");

	$_G['hr_threadlist'] = $sortdata['datalist'];

	$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_thread')." ".($tidsadd ? 'WHERE '.$tidsadd : '')."");
	$authorids = array();
	while($thread = DB::fetch($query)) {
		$_G['hr_threadlist'][$thread['tid']]['subject'] .= $thread['subject'];
		$_G['hr_threadlist'][$thread['tid']]['author'] .= $thread['author'];
		$_G['hr_threadlist'][$thread['tid']]['authorid'] .= $thread['authorid'];
		$authorids[] = $thread['authorid'];
		$threadids[] = $thread['tid'];
	}
	
	$query = DB::query("SELECT uid, realname FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid IN (".dimplode($authorids).")");
	while($author = DB::fetch($query)) {
		foreach($_G['hr_threadlist'] as $tid => $thread) {
			if($author['realname'] && $thread['authorid'] == $author['uid']) {
				$_G['hr_threadlist'][$tid]['author'] = $author['realname'];
			}
		}
	}
	
	$sortlistarray = showsorttemplate($sortid, $sortoptionarray, $templatearray, $_G['hr_threadlist'], $threadids, $arealist, $modurl);
	$stemplate = $sortlistarray['template'];
	$sortexpiration = $sortlistarray['expiration'];

	include template('diy:hr/'.$modidentifier.'_agent:'.$gid);

} elseif($action == 'application') {
	if(empty($_G['uid'])) {
		showmessage(lang('hr/template', 'job_please_login'), '', '', array('login' => 1));
	}

	if(!submitcheck('applicationsubmit')) {

		//检查用户目前所属中介组
		$userinfo = DB::fetch_first("SELECT groupid, verify, realname, tel, address, city, district, street FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid=".$_G['uid']);
		if($userinfo['groupid'] && $userinfo['groupid'] > 1) {
			$current_title = $_G['cache']['hr_usergrouplist_'.$modidentifier][$userinfo['groupid']]['title'];
		}

		$grouplist = array();
		foreach($_G['cache']['hr_usergrouplist_'.$modidentifier] as $gid => $group) {
			if($group[type] == 'intermediary'){
			if($gid > 1) {
				if($userinfo['groupid'] == $gid) {
					$group['selected'] = "selected";
				}
				$grouplist[] = $group;
			}
			}
		}

		$citylist = $districtlist = $streetlist = '';
		foreach($arealist['city'] as $cityid => $city) {
			$citylist .= '<option value="'.$cityid.'" '.($userinfo['city'] == $cityid ? 'selected="selected"' : '').'>'.$city.'</option>';
		}

		if($userinfo['city']) {
			foreach($arealist['district'][$userinfo['city']] as $districtid => $district) {
				$districtlist .= '<option value="'.$districtid.'" '.($userinfo['district'] == $districtid ? 'selected="selected"' : '').'>'.$district.'</option>';
			}
		}

		if($userinfo['district']) {
			foreach($arealist['street'][$userinfo['district']] as $streetid => $street) {
				$streetlist .= '<option value="'.$streetid.'" '.($userinfo['street'] == $streetid ? 'selected="selected"' : '').'>'.$street.'</option>';
			}
		}

	} else {

		$realname = isset($_GET['realname']) ? dhtmlspecialchars(censor(trim($_GET['realname']))) : '';
		$newagent = isset($_GET['newagent']) ? dhtmlspecialchars(censor(trim($_GET['newagent']))) : '';
		$groupid = empty($newagent) ? intval($_GET['usergroup']) : 0;
		$tel = isset($_GET['tel']) ? dhtmlspecialchars(censor(trim($_GET['tel']))) : '';
		$address = isset($_GET['address']) ? dhtmlspecialchars(censor(trim($_GET['address']))) : '';
		$city = intval($_GET['city']);
		$district = intval($_GET['district']);
		$street = intval($_GET['street']);
		$newgroupid = 0;
		
		//得到管理员id
		$adminids = array_keys($channel['managegid']);
		$query = DB::query("SELECT hm.uid FROM ".DB::table('hr_'.$modidentifier.'_member').' hm 
					LEFT JOIN '.DB::table('common_member').' m 
					ON hm.uid = m.uid 
					WHERE m.groupid IN ('.dimplode($adminids).') 
					OR m.extgroupids IN ('.dimplode($adminids).')');
		
		$adminids = array();
		while($admin = DB::fetch($query)) {
			$adminids[] = $admin['uid'];
		}

		if(empty($realname)) {
			showmessage(lang('hr/template', 'msg_broker_realname_nothing'));
		}
		if(empty($tel)) {
			showmessage(lang('hr/template', 'msg_broker_tel_nothing'));
		}
		if(empty($newagent) && $groupid == 0) {
			showmessage(lang('hr/template', 'msg_broker_company_nothing'));
		}
		if(!checkarea($city, $district, $street)) {
			showmessage(lang('hr/template', 'msg_broker_area_nothing'));
		}

		if($newagent) {
			$data = array(
				'type' => 'intermediary',
				'title' => $newagent,
				'cid' => 1,
				'verify' => 0,
				'manageuid' => $_G['uid']
			);
			$newgroupid = DB::insert('hr_'.$modidentifier.'_usergroup', $data, true);
			
			//给管理员发信息
			$msgtitle = $realname.'申请创建中介公司“'.$newagent.'”，请到后台审核';
			foreach($adminids as $touid) {
				notification_add($touid, 'system', $msgtitle);
			}
		} else {
			$newagent = $_G['cache']['hr_usergrouplist_'.$modidentifier][$groupid]['title'];
		}

		$groupid = $newgroupid ? $newgroupid : $groupid;
		$data = array(
			'groupid' => $groupid,
			'realname' => $realname,
			'tel' => $tel,
			'address' => $address,
			'verify' => 0,
			'city' => $city,
			'district' => $district,
			'street' => $street
		);
		DB::update('hr_'.$modidentifier.'_member', $data, 'uid='.$_G['uid']);
		
		//给管理员发消息
		$msgtitle = $realname.'申请加入“'.$_G['cache']['hr_usergrouplist_'.$modidentifier][$groupid]['title'].'”，请到后台审核';
		foreach($adminids as $touid) {
			notification_add($touid, 'system', $msgtitle);
		}
		
		showmessage(lang('hr/template', 'msg_broker_info_mod'), $modurl);

	}

	include template('diy:hr/'.$modidentifier.'_application');

}

?>