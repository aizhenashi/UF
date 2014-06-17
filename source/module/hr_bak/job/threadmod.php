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

if(empty($_G['uid'])) {
	showmessage(lang('hr/template', 'job_thread_not_exist'), '', '', array('login' => 1));
}

$moderate = $_GET['moderate'];
$_GET['handlekey'] = 'mods';
$_GET['gid'] = intval($_GET['gid']);

if(!empty($moderate) && $_GET['action'] == 'delthread' && $sortid) {
	
	$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;
	$tidsadd = !empty($moderate) ? "WHERE tid='".$moderate[array_rand($moderate)]."'" : '';
	
	if($tidsadd) {
		$thread = DB::fetch_first("SELECT authorid, tid, sortid FROM ".DB::table('hr_'.$modidentifier.'_thread')." $tidsadd");
		
		$sorttoday = 0;
		$membertoday = 0;
		$groupdel = 0;
		
		$sortvalue = DB::fetch_first("SELECT tid, groupid, dateline FROM ".DB::table('hr_sortvalue')."$sortid $tidsadd");
		$usergroup = $_G['hr_usergrouplist'][$sortvalue['groupid']];
		$isgroupadmin = $_G['uid'] && $_G['uid'] == $usergroup['manageuid'] ? 1 : 0;
		
		if(!($ischanneladmin || $isgroupadmin)) {
			showmessage(lang('hr/template', 'job_usergroup_nopur_manage'));
		}
		
		if($sortvalue['groupid'] > 1) {
			$groupdel = 1;
			if(istoday($sortvalue['dateline'])) { //当天帖子
				$sorttoday++;
				$membertoday++;
			}
		}
		
		DB::query("DELETE FROM ".DB::table('hr_'.$modidentifier.'_thread')." $tidsadd");
		DB::query("DELETE FROM ".DB::table('hr_'.$modidentifier.'_applyrs')." $tidsadd");
		DB::query("DELETE FROM ".DB::table('hr_'.$modidentifier.'_forumthread')." $tidsadd");
		DB::query("DELETE FROM ".DB::table('hr_sortoptionvar')." $tidsadd");
		DB::query("DELETE FROM ".DB::table('hr_sortvalue')."$sortid $tidsadd");
		$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_pic')." $tidsadd");
		while($row = DB::fetch($query)) {
			@unlink($_G['setting']['attachdir'].'/hr/'.$row['url']);
			@unlink(DISCUZ_ROOT.'./data/attachment/image/'.$row['aid'].'_140_140_job.jpg');
			@unlink(DISCUZ_ROOT.'./data/attachment/image/'.$row['aid'].'_48_48_job.jpg');
		}
		DB::query("DELETE FROM ".DB::table('hr_'.$modidentifier.'_pic')." $tidsadd");
		
		//更新当前分类发帖数
		DB::query("UPDATE ".DB::table('hr_sort')." SET threads=threads-1, todaythreads=todaythreads-$sorttoday WHERE sortid='$sortid'");

		//更新用户发帖数
		DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_member')." SET threads=threads-1, todaythreads=todaythreads-$membertoday WHERE uid = ".$thread['authorid']);
		
		//更新中介发帖数
		if($groupdel) {
			DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_usergroup')." SET threads=threads-1 WHERE gid=".$sortvalue['groupid']);
		}

		$url = $_GET['uid'] ? $modurl.'?mod=broker&action=my&uid='.$_GET['uid'].'&sortid='.$sortid : $modurl.'?mod=list&sortid='.$sortid;
		showmessage(lang('hr/template', 'job_delete_success'), $url);
	}
	showmessage(lang('hr/template', 'job_lack_args'));
	exit();
} else {

	if(!in_array($_GET['operation'], array('recommend', 'push', 'highlight', 'stick'))) {
		showmessage(lang('hr/template', 'job_lack_args'));
	}
	
	$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;
	if($_GET['operation'] == 'stick' && !$ischanneladmin) {
		showmessage(lang('hr/template', 'job_usergroup_nopur_totop'));
	}

	$thread = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE tid IN(".dimplode($moderate).")");
	$groupid = DB::result_first("SELECT groupid FROM ".DB::table('hr_sortvalue'.$thread['sortid'])." WHERE tid IN(".dimplode($moderate).")");
	
	$usergroup = $_G['hr_usergrouplist'][$groupid];
	$isgroupadmin = $_G['uid'] && $_G['uid'] == $usergroup['manageuid'] ? 1 : 0;
	if(!($ischanneladmin || $isgroupadmin)) {
		showmessage(lang('hr/template', 'job_usergroup_nopur_manage'));
	}

	$remainnum = array();
	if($isgroupadmin) {
		if($_GET['operation'] == 'recommend' && empty($usergroup['allowrecommend'])) {
			showmessage(lang('hr/template', 'job_usergroup_nopur_stick'));
		} elseif($_GET['operation'] == 'push' && empty($usergroup['allowpush'])) {
			showmessage(lang('hr/template', 'job_usergroup_nopur_promote'));
		} elseif($_GET['operation'] == 'highlight' && empty($usergroup['allowhighlight'])) {
			showmessage(lang('hr/template', 'job_usergroup_nopur_highlight'));
		} else {
			$today = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid='$_G[uid]'");
			if($_GET['operation'] == 'recommend' && $today['todayrecommend'] >= $usergroup['recommenddayper'] && !empty($usergroup['recommenddayper'])) {
				showmessage(lang('hr/template', 'job_today_stick_count1').$today[todayrecommend].lang('hr/template', 'job_today_stick_count2'));
			} elseif($_GET['operation'] == 'push' && $today['todaypush'] >= $usergroup['pushdayper'] && !empty($usergroup['pushdayper'])) {
				showmessage(lang('hr/template', 'job_today_promote_count1').$today[todaypush].lang('hr/template', 'job_today_promote_count2'));
			} elseif($_GET['operation'] == 'highlight' && $today['todayhighlight'] >= $usergroup['highlightdayper'] && !empty($usergroup['highlightdayper'])) {
				showmessage(lang('hr/template', 'job_today_highlight_count1').$today[todayhighlight].lang('hr/template', 'job_today_highlight_count2'));
			}
		}

		$remainnum['recommend'] = $usergroup['recommenddayper'] - $today['todayrecommend'];
		$remainnum['push'] = $usergroup['pushdayper'] - $today['todaypush'];
		$remainnum['highlight'] = $usergroup['highlightdayper'] - $today['todayhighlight'];
	}

	$threadlist = array();
	if(!submitcheck('modsubmit')) {
		$query = DB::query("SELECT * FROM ".DB::table('hr_sortvalue')."$sortid WHERE tid IN(".dimplode($moderate).")");
		while($row = DB::fetch($query)) {
			$threadlist[$row['tid']] = $row;
			$checkdigest[$row['digest']] = ' checked="checked"';
			$checkrecommend[$row['recommend']] = ' checked="checked"';
			$checkstick[$row['displayorder']] = ' checked="checked"';
			$string = sprintf('%02d', $row['highlight']);
			$stylestr = sprintf('%03b', $string[0]);
			for($i = 1; $i <= 3; $i++) {
				$stylecheck[$i] = $stylestr[$i - 1] ? 1 : 0;
			}
			$colorcheck = $string[1];
		}
	} else {
		$statussql = $addnumsql = '';
		if($_GET['operation'] == 'recommend') {
			$isrecommend = intval($_GET['isrecommend']);
			$statussql = "recommend='".intval($_GET['isrecommend'])."'";
			$addnumsql = 'todayrecommend=todayrecommend+1';
			$expiration = TIMESTAMP + 86400 * 3;
		} elseif($_GET['operation'] == 'push') {
			$statussql = "dateline='$_G[timestamp]'";
			$addnumsql = 'todaypush=todaypush+1';
		} elseif($_GET['operation'] == 'highlight') {
			$highlight_style = $_GET['highlight_style'];
			$highlight_color = $_GET['highlight_color'];
			$stylebin = '';
			for($i = 1; $i <= 3; $i++) {
				$stylebin .= empty($highlight_style[$i]) ? '0' : '1';
			}

			$highlight_style = bindec($stylebin);
			if($highlight_style < 0 || $highlight_style > 7 || $highlight_color < 0 || $highlight_color > 8) {
				showmessage('undefined_action', NULL);
			}
			$statussql = "highlight='$highlight_style$highlight_color'";
			$addnumsql = 'todayhighlight=todayhighlight+1';
			$expiration = TIMESTAMP + 86400;
		} else {
			$sticklevel = intval($_GET['sticklevel']);
			$statussql = "displayorder='$sticklevel'";
		}

		if($statussql) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET $statussql WHERE tid IN (".dimplode($moderate).")", 'UNBUFFERED');
			foreach($moderate as $tid) {
				DB::query("INSERT INTO ".DB::table('hr_threadmod')." (tid, expiration, action) VALUES ('$tid', '$expiration', '$_GET[operation]')");
			}
		}

		if($addnumsql) {
			DB::query("UPDATE ".DB::table('hr_'.$modidentifier.'_member')." SET $addnumsql WHERE uid='$_G[uid]'", 'UNBUFFERED');
		}
		
		if($_GET['uid']) {
			$url = $modurl.'?mod=broker&action=my&uid='.$_GET['uid'].'&sortid='.$sortid;
		} elseif($_GET['gid']) {
			$url = $modurl.'?mod=agent&action=store&gid='.$_GET['gid'].'&sortid='.$sortid;
		} else {
			$url = $modurl.'?mod=list&sortid='.$sortid;
		}
		showmessage(lang('hr/template', 'job_manage_success'), $url);
	}

	include template('hr/hr_threadmod');
}

?>