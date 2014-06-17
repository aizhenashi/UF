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

//根据后台设置判断游客是否可以发信息
if(empty($_G['uid']) && !$channel['visitorpost']) {
	showmessage('not_loggedin', '', '', array('login' => 1));
}

if(!empty($_G['uid']) && empty($hr_usergroup['allowpost'])) {
	showmessage(lang('hr/template', 'job_usergroup_nopur'));
}

//支持DZ中系统用户组中禁止发言/禁止访问/禁止IP
require_once libfile('function/home');
$space = getspace($_G['uid']);
if($space['status'] == -1 || in_array($space['groupid'], array(4, 5, 6))) {
    showmessage(lang('hr/template', 'job_usergroup_nopur')); 
}
//增加DZ的防灌水验证
cknewuser();

$actionarray = array('newthread', 'edit', 'nav');
$action = in_array($_GET['action'], $actionarray) ? $_GET['action'] : '';

if($action != 'edit' && $hr_usergroup['postdayper'] && $_G['hr_member']['todaythreads'] >= $hr_usergroup['postdayper']) {
	showmessage(lang('hr/template', 'job_post_thread_max'));
}

require_once libfile('function/hr');

$sortarray = $cityarray = $districtarray = $streetarray = array();
$cityid = intval($_GET['cityid']);
$districtid = intval($_GET['districtid']);
$streetid = intval($_GET['streetid']);

$avatar = hr_uc_avatar($_G['uid']);
$usergrouplist[$usergroupid]['icon'] = $usergrouplist[$usergroupid]['icon'] ? $_G['setting']['attachurl'].'common/'.$usergrouplist[$usergroupid]['icon'] : '';
$usergrouplist[$usergroupid]['postdayper'] = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper']) : '';
if($usergroupid > 1) {
	$usergrouplist[$usergroupid]['title'] = '<a href="job.php?mod=agent&action=store&gid='.$usergroupid.'">'.$usergrouplist[$usergroupid]['title'].'</a>';
}


$_G['hr_member']['todaythreads'] = intval($_G['hr_member']['todaythreads']);
$_G['hr_member']['serverarea'] = $arealist['district'][$_G['hr_member']['city']][$_G['hr_member']['district']].'&nbsp;'.$arealist['street'][$_G['hr_member']['district']][$_G['hr_member']['street']];
if($_G['hr_member']['serverarea'] == '&nbsp;') {
	$_G['hr_member']['serverarea'] = '';
}
$_G['hr_member']['name'] = $_G['hr_member']['realname'] ? $_G['hr_member']['realname'] : $_G['username'];

$subject = isset($_GET['subject']) ? dhtmlspecialchars(censor(trim($_GET['subject']))) : '';
$subject = !empty($subject) ? str_replace("\t", ' ', $subject) : $subject;
$message = isset($_GET['message']) ? censor(trim($_GET['message'])) : '';

$verifyinfo = showverifyicon($_G['hr_member']['certification']);//用户认证情况

$pullset = DB::fetch_first("SELECT pullset FROM ".DB::table('hr_channel')." WHERE cid='1' ");
$pull = DB::fetch_first("SELECT pullfid, pulltypeid, pullsortid FROM ".DB::table('hr_sort')." WHERE sortid='$sortid' ");

if($action == 'nav') {

	foreach($sortlist as $id => $sort) {
		$sortarray[$id]= $sort['name'];
	}

	foreach($arealist['district'][$cityid] as $aid => $area) {
		$districtarray[$aid]['title'] = $area;
	}

	if(!empty($districtid) && $arealist['street'][$districtid]) {
		foreach($arealist['street'][$districtid] as $aid => $area) {
			$streetarray[$aid]['title'] = $area;
		}
	}
} elseif($action == 'newthread') {

	if(empty($sortid)) {
		showmessage(lang('hr/template', 'job_no_option'));
	}

	if(empty($cityid) && $arealist['city']) {
		showmessage(lang('hr/template', 'job_no_one_option'));
	} elseif(empty($districtid) && $arealist['district'][$cityid]) {
		showmessage(lang('hr/template', 'job_no_two_option'));
	} elseif(empty($streetid) && $arealist['street'][$districtid]) {
		showmessage(lang('hr/template', 'job_no_three_option'));
	}

	loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));
	$_G['hr_optionlist'] = $_G['cache']['hr_option_'.$sortid];
	threadsort_checkoption($sortid);
	$mapcenter = $arealist['city'][$cityid].' '.$arealist['district'][$cityid][$districtid].' '.$arealist['street'][$districtid][$streetid];
	$hr_sort = $sortlist[$sortid];

	$todaythreads = $usergrouplist[$usergroupid]['postdayper'] ? $_G['hr_member']['todaythreads'] : '';
	$todaypostthreads = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper'] - $_G['hr_member']['todaythreads']) : '';

	$imgnum = array();
	$imgnum = array_pad($imgnum, $hr_sort['imgnum'], 0);

	$lastpost = $_G['hr_member']['lastpost'];

	if($_G['timestamp'] - $lastpost < 30) {
		showmessage(lang('hr/template', 'job_post_30second'));
	}

	if(!submitcheck('topicsubmit')) {
		threadsort_optiondata($sortid, $_G['cache']['hr_option_'.$sortid], $_G['cache']['hr_template_'.$sortid], 0, $usergroupid);
	} else {

		if(empty($subject)) {
			showmessage(lang('hr/template', 'job_no_subject'));
		} else if($channel['mapkey'] && empty($_GET['mapposition'])) {
			showmessage(lang('hr/template', 'job_no_mapmark'));
		}

		$today = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid='$_G[uid]'");

		$_G['hr_optiondata'] = threadsort_validator($_GET['typeoption']);
		$_GET['expiration'] = $_GET['expiration'] ? $_G['timestamp'] + intval($_GET['expiration']) : 0;
		$cate_groupid = $_G['hr_member']['groupid'];
		$message = isset($_GET['message']) ? censor(trim($_GET['message'])) : '';

		DB::query("INSERT INTO ".DB::table('hr_'.$modidentifier.'_thread')." (sortid, author, authorid, subject, message, ip)
		VALUES ('$sortid', ' $_G[username]', '$_G[uid]', '$subject', '$message', '$_G[clientip]')");
		$tid = DB::insert_id();

		foreach($_G['hr_optiondata'] as $optionid => $value) {
			$filedname .= $separator.$_G['hr_optionlist'][$optionid]['identifier'];
			$valuelist .= $separator."'$value'";
			$separator = ' ,';

			DB::query("INSERT INTO ".DB::table('hr_sortoptionvar')." (sortid, tid, optionid, value, expiration)
				VALUES ('$sortid', '$tid', '$optionid', '$value', '$_GET[expiration]')");
		}
		if($filedname && $valuelist) {
			DB::query("INSERT INTO ".DB::table('hr_sortvalue').
				"$sortid ($filedname, tid, dateline, expiration, city, district, street, groupid,  mapposition) VALUES ($valuelist, '$tid', '$_G[timestamp]', '$_GET[expiration]', '$cityid', '$districtid', '$streetid', '$cate_groupid', '$_GET[mapposition]')");
		}
		transchannelinfo($modidentifier);
		threadsort_insertfile($tid, $_FILES, $sortid, '', $modidentifier, $channel);
		DB::query("UPDATE ".DB::table('hr_sort')." SET threads=threads+1, todaythreads=todaythreads+1 WHERE sortid='$sortid'");
		DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_member')." SET threads=threads+1, todaythreads=todaythreads+1, lastpost='$_G[timestamp]' WHERE uid='$_G[uid]'");
		if($cate_groupid > 1) {
			DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_usergroup')." SET threads=threads+1 WHERE gid=".$cate_groupid);
		}
		
		$hft = dgmdate($_G['timestamp'], 'Y-m-d H:i');
		require_once libfile('function/feed');
		feed_add('job', '{actor} 于 {datetime} 发布了一条 <a href="job.php?mod=list&sortid={sortid}" target="_blank" >{sortname}</a> 信息。', array('datetime' => $hft, 'sortname' => $sortlist[$sortid][name], 'sortid' => $sortid), '<a href="job.php?mod=view&tid={tid}" target="_blank" >{subject}</a>', array('subject' => $subject, 'tid' => $tid), '','', '');


		if($pullset['pullset'] == 1){
			$pull = DB::fetch_first("SELECT pullfid, pulltypeid, pullsortid FROM ".DB::table('hr_sort')." WHERE sortid='$sortid' ");
			if($pull['pullfid'] !== 0){
					DB::query("INSERT INTO ".DB::table('forum_thread')." (fid, posttableid, readperm, price, typeid, sortid, author, authorid, subject, dateline, lastpost, lastposter, displayorder, digest, special, attachment, moderated, highlight, closed, status, isgroup)VALUES ('$pull[pullfid]', '0', '0', '0', '$pull[pulltypeid]', '$pull[pullsortid]', '$_G[username]', '$_G[uid]', '$subject', '$_G[timestamp]', '$_G[timestamp]', '$_G[username]', '0', '0', '0', '0', '0', '0', '0', '32', '0')");
					$forumtid = DB::insert_id();
					require_once libfile('function/post');
					require_once libfile('function/forum');
					$modname = $channel['title'];
					$forummessage = '[align=center][url='.$_G[siteurl].'job.php][color=gray][该信息来自本站'.$modname.'][/color][/url][/align]
[align=center][job=100%]'.$tid.'[/job][/align]
[align=center][url='.$_G[siteurl].'job.php?mod=view&tid='.$tid.'][img]static/image/job/pull.gif[/img][/url][/align]';
					$postip = $_G['clientip'];
					$pid = insertpost(array('fid' => $pull[pullfid],'tid' => $forumtid,'first' => '1','author' => $_G['username'],'authorid' => $_G['uid'],'subject' => $subject,'dateline' => $_G['timestamp'],'message' => $forummessage,'useip' => $_G['clientip'],'invisible' => '0','anonymous' => '0','usesig' => '0','htmlon' => '0','bbcodeoff' => '0','smileyoff' => '0','parseurloff' => '0','attachment' => '0',));
					$lastpost = "$forumtid\t$subject\t$_G[timestamp]\t$_G[username]";
					DB::query("UPDATE ".DB::table('forum_forum')." SET lastpost='$lastpost', threads=threads+1, posts=posts+1, todayposts=todayposts+1 WHERE fid='$pull[pullfid]'", 'UNBUFFERED');
					DB::query("INSERT INTO ".DB::table('hr_'.$modidentifier.'_forumthread')." (tid, forumtid) VALUES ($tid, $forumtid)");
			}
		}

		showmessage(lang('hr/template', 'job_post_success'), $modurl.'?mod=view&tid='.$tid);
	}

} elseif($action == 'edit') {
	
	$todaythreads = $usergrouplist[$usergroupid]['postdayper'] ? $_G['hr_member']['todaythreads'] : '';
	$todaypostthreads = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper'] - $_G['hr_member']['todaythreads']) : '';
	$thread = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE tid='$tid'");
	$groupid = DB::result_first("SELECT groupid FROM ".DB::table('hr_sortvalue'.$thread['sortid'])." WHERE tid='$tid'");
	
	$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;
	$usergroup = $_G['hr_usergrouplist'][$groupid];
	$isgroupadmin = $_G['uid'] && $_G['uid'] == $usergroup['manageuid'] ? 1 : 0;
	
	if(!($ischanneladmin || $isgroupadmin || $_G['uid'] && $thread['authorid'] == $_G['uid'])) {
		showmessage(lang('hr/template', 'job_no_edit'));
	}

	$tid = $thread['tid'];
	$sortid = $thread['sortid'];
	$message = $thread['message'];

	$sortdata = DB::fetch_first("SELECT tid, attachid, dateline, expiration, displayorder, recommend, groupid, city, district, street, mapposition FROM ".DB::table('hr_sortvalue')."$sortid WHERE tid='$tid'");
	$expiration = $sortdata['expiration'] ? dgmdate($sortdata['expiration'], 'd') : '';

	$districtid = intval($sortdata['district']);
	$streetid = intval($sortdata['street']);
	$cityid = intval($sortdata['city']);

	$mapcenter = $arealist['city'][$cityid].' '.$arealist['district'][$cityid][$districtid].' '.$arealist['street'][$districtid][$streetid];
	$mapposition = empty($sortdata['mapposition']) ? '' : explode(',', $sortdata['mapposition']);

	loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));
	$_G['hr_optionlist'] = $_G['cache']['hr_option_'.$sortid];
	threadsort_checkoption($sortid);
	$hr_sort = $sortlist[$sortid];

	if(!submitcheck('editsubmit')) {
		threadsort_optiondata($sortid, $_G['cache']['hr_option_'.$sortid], $_G['cache']['hr_template_'.$sortid], $tid);

		$attachs = array();
		if($sortdata['attachid']) {
			$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid'");
			while($attach = DB::fetch($query)) {
				$attachs[] = $attach;
			}
		}

		if(count($attachs) < $hr_sort['imgnum']) {
			$imgnum = array();
			$uploadnum = $hr_sort['imgnum'] - count($attachs);
			$imgnum = array_pad($imgnum, $uploadnum, 0);
		}
	} else {
		
		if(empty($subject)) {
			showmessage(lang('hr/template', 'job_no_subject'));
		} else if($channel['mapkey'] && empty($_GET['mapposition'])) {
			showmessage(lang('hr/template', 'job_no_mapmark'));
		}
		
		$_G['hr_optiondata'] = threadsort_validator($_GET['typeoption'], $tid);
		$_GET['expiration'] = $_GET['expiration'] ? ($sortdata['expiration'] ? $sortdata['expiration'] + intval($_GET['expiration']) : $_G['timestamp'] + intval($_GET['expiration'])) : $sortdata['expiration'];

		$sql = $separator = $newaidadd = '';
		foreach($_G['hr_optiondata'] as $optionid => $value) {
			if(($_G['hr_optionlist'][$optionid]['search'] || in_array($_G['hr_optionlist'][$optionid]['type'], array('radio', 'select', 'number'))) && $value) {
				$sql .= $separator.$_G['hr_optionlist'][$optionid]['identifier']."='$value'";
				$separator = ' ,';
			}
			DB::query("UPDATE ".DB::table('hr_sortoptionvar')." SET value='$value', sortid='$sortid', expiration='$_GET[expiration]' WHERE tid='$tid' AND optionid='$optionid'");
		}

		if($sql) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET $sql WHERE tid='$tid'");
		}

		if(!empty($subject) || !empty($message)) {
			$message = censor(trim($_GET['message']));
			DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_thread')." SET subject='$subject', message='$message' WHERE tid='$tid'");
		}

		if($_GET['mapposition']) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET mapposition='$_GET[mapposition]' WHERE tid='$tid'");
		}

		if($_GET['expiration'] !=  $thread['expiration']) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET expiration='$_GET[expiration]' WHERE tid='$tid'");
		}

		if($_FILES) {
			transchannelinfo($modidentifier);
			threadsort_insertfile($tid, $_FILES, $sortid, 1, $modidentifier, $channel);
		} else {
			$newaid = substr($_GET['coverpic'], 4);
			if($newaid != $sortdata['attachid']) {
				DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET attachid='$newaid' WHERE tid='$tid'");
			}
		}

		if($_GET['deleteaids']) {
			$deleteaids = explode(',', $_GET['deleteaids']);
			$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid' AND aid IN(".dimplode($deleteaids).")");
			while($row = DB::fetch($query)) {
				@unlink($_G['setting']['attachdir'].'/hr/'.$row['url']);
				@unlink(DISCUZ_ROOT.'./data/attachment/image/'.$row['aid'].'_140_140_job.jpg');
				@unlink(DISCUZ_ROOT.'./data/attachment/image/'.$row['aid'].'_48_48_job.jpg');
			}
			DB::query("DELETE FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid' AND aid IN(".dimplode($deleteaids).")");
			if(in_array($sortdata['attachid'], $deleteaids)) {
				$newaid = DB::result_first("SELECT aid FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid' LIMIT 1");
				$newaidadd = empty($newaid) ?  ",attachid='".intval($newaid)."'" : '';
			}
			$attachnum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid'");
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET attachnum='".intval($attachnum)."' $newaidadd WHERE tid='$tid'");
		}

		showmessage(lang('hr/template', 'job_update_success'), $modurl.'?mod=view&tid='.$tid.'');
	}
}

include template('diy:hr/hr_post');