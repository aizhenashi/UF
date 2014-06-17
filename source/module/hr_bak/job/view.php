<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_viewthread.php 7253 2010-03-31 09:27:33Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/discuzcode');

$thread = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE tid='$tid'");
$sortid = $thread['sortid'];

if(empty($sortid)) {
	showmessage(lang('hr/template', 'job_thread_not_exist'));
}

//$isgroupadmin = !empty($channel['managegid'][$_G['groupid']]) ? 1 : 0;
//$usergroup = $hr_usergroup;
//$managepr = $isgroupadmin || $usergroup['allowpush'] || $usergroup['allowrecommend'] || $usergroup['allowhighlight'];

loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));
$sortoptionarray = $_G['cache']['hr_option_'.$sortid];
$templatearray = $_G['cache']['hr_template_'.$sortid]['viewthread'];
$ntemplatearray = $_G['cache']['hr_template_'.$sortid]['neighborhood'];

$sortdata = DB::fetch_first("SELECT tid, attachid, dateline, expiration, displayorder, highlight, recommend, groupid, city, district, street, mapposition FROM ".DB::table('hr_sortvalue')."$sortid WHERE tid='$tid'");
$districtid = $sortdata['district'];
$streetid = $sortdata['street'];
$cityid = $sortdata['city'];

$ischanneladmin = (!empty($channel['managegid'][$_G['groupid']]) || !empty($channel['managegid'][$_G['member']['extgroupids']])) ? 1 : 0;
$usergroup = $_G['hr_usergrouplist'][$sortdata['groupid']];
$isgroupadmin = $_G['uid'] && $_G['uid'] == $usergroup['manageuid'] ? 1 : 0;

$mapposition = empty($sortdata['mapposition']) ? '' : explode(',', $sortdata['mapposition']);

if(empty($thread) || empty($sortdata)) {
	showmessage(lang('hr/template', 'job_info_not_exist'));
}

if($sortdata['highlight'] || $sortdata['recommend']) {
	if($sortdata['highlight']) {
		$highlight = DB::fetch_first("SELECT expiration FROM ".DB::table('hr_threadmod')." WHERE tid='$tid' AND action='highlight' ORDER BY expiration DESC LIMIT 1");
		if(TIMESTAMP > $highlight['expiration'] && !empty($highlight['expiration'])) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET highlight='0' WHERE tid='$tid'", 'UNBUFFERED');
		}
	}

	if($sortdata['recommend']) {
		$recommend = DB::fetch_first("SELECT expiration FROM ".DB::table('hr_threadmod')." WHERE tid='$tid' AND action='recommend' ORDER BY expiration DESC LIMIT 1");
		if(TIMESTAMP > $recommend['expiration'] && !empty($recommend['expiration'])) {
			DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET recommend='0' WHERE tid='$tid'", 'UNBUFFERED');
		}
	}
}

$navigation = "<em>&rsaquo;</em><a href=\"$modurl?mod=list&amp;cid=$cid&amp;sortid=$sortid\">".$sortlist[$sortid]['name']."</a> ";
$navigation .= $arealist['city'][$cityid] ? "<em>&rsaquo;</em><a href=\"$modurl?mod=list&amp;cid=$cid&amp;sortid=$sortid&amp;filter=all&amp;city=$cityid\">".$arealist['city'][$cityid]."</a> " : '';
$navigation .= $arealist['district'][$cityid][$districtid] ? "<em>&rsaquo;</em><a href=\"$modurl?mod=list&amp;cid=$cid&amp;sortid=$sortid&amp;filter=all&amp;city=$cityid&amp;district=$districtid\">".$arealist['district'][$cityid][$districtid]."</a> " : '';
$navigation .= $arealist['street'][$districtid][$streetid] ? "<em>&rsaquo;</em><a href=\"$modurl?mod=list&amp;cid=$cid&amp;sortid=$sortid&amp;filter=all&amp;city=$cityid&amp;district=$districtid&amp;street=$streetid\">".$arealist['street'][$districtid][$streetid]."</a> " : '';

$navtitle = $arealist['city'][$cityid].$arealist['district'][$cityid][$districtid].$arealist['street'][$districtid][$streetid].$thread['subject'].' - '.$sortlist[$sortid]['name'].' - '.$channel['title'];
$metakeywords = empty($metakeywords) ? $sortlist[$sortid]['keywords'] : $metakeywords;
$metadescription = empty($metadescription) ? $thread['subject'] : $metadescription;

require_once libfile('function/hr');

$threadsortshow = threadsortshow($thread['tid'], $sortoptionarray, $templatearray, $thread['authorid'], $sortdata['groupid']);
$thread['avatar'] = hr_uc_avatar($thread['authorid']);
$thread['dateline'] = dgmdate($sortdata['dateline'], 'd');
$thread['message'] = discuzcode($thread['message'],0 ,0 ,0);

$member = DB::fetch_first("SELECT threads, groupid, certification, city, district, street, realname FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid=".$thread['authorid']);

$member['street'] = $arealist['street'][$member['district']][$member['street']];
$member['district'] = $arealist['district'][$member['city']][$member['district']];
$member['city'] = $arealist['city'][$member['city']];
$thread['author'] = $member['realname'] ? $member['realname'] : $thread['author'];

$verifyinfo = showverifyicon($member['certification']);

$groupid = $member['groupid'];
if($usergrouplist[$groupid]['type'] == 'intermediary') {
	$usergrouptitle = $usergrouplist[$groupid]['title'] ? "<a href=\"$modurl?mod=agent&action=store&amp;gid=$groupid&amp;cid=$cid&amp;sortid=$sortid\">".$usergrouplist[$groupid]['title']."</a>" : '';
	$usergroupicon = $usergrouplist[$groupid]['icon'] ? "<a href=\"$modurl?mod=agent&action=store&amp;gid=$groupid&amp;cid=$cid&amp;sortid=$sortid\"><img src=\"".$_G['setting']['attachurl'].'common/'.$usergrouplist[$groupid]['icon']."\"></a>" : '';
	$usergrouptype = 'intermediary';
} elseif($usergrouplist[$groupid]['type'] == 'company') {
	$usergrouptitle = $usergrouplist[$groupid]['title'] ? "<a href=\"$modurl?mod=agent&action=store&amp;gid=$groupid&amp;cid=$cid&amp;sortid=$sortid\">".$usergrouplist[$groupid]['title']."</a>" : '';
	$usergroupicon = $usergrouplist[$groupid]['icon'] ? "<a href=\"$modurl?mod=agent&action=store&amp;gid=$groupid&amp;cid=$cid&amp;sortid=$sortid\"><img src=\"".$_G['setting']['attachurl'].'common/'.$usergrouplist[$groupid]['icon']."\"></a>" : '';
	$usergrouptype = 'company';
} else {
	$usergrouptitle = $usergrouplist[$groupid]['title'] ? $usergrouplist[$groupid]['title'] : '';
	$usergroupicon = $usergrouplist[$groupid]['icon'] ? "<img src=\"".$_G['setting']['attachurl'].'common/'.$usergrouplist[$groupid]['icon']."\">" : '';
}

visitedsetcookie($thread['tid']);
$neighborhoodlist = neighborhood($thread['tid'], $sortid, $cityid, $districtid, $streetid, $sortoptionarray, $ntemplatearray, $modurl);

$applyrslist = array();
	$query = DB::query("SELECT uid FROM ".DB::table('hr_'.$modidentifier.'_applyrs')." WHERE tid='$thread[tid]' ORDER BY dateline DESC limit 10");
	while($applyrs = DB::fetch($query)) {
		$applyrslist[] = $applyrs;
	}

$piclist = array();
if($sortdata['attachid']) {
	$query = DB::query("SELECT url FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$thread[tid]' ORDER BY dateline");
	while($pic = DB::fetch($query)) {
		$piclist[] = 'hr/'.$pic['url'];
	}
}
$pic['picnum'] = count($piclist);

$pull = DB::fetch_first("SELECT pullfid, pulltypeid, pullsortid FROM ".DB::table('hr_sort')." WHERE sortid='$sortid' ");
$forumtid = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_forumthread')." WHERE tid='$tid'");
$common_url = "forum.php?mod=viewthread&tid=$forumtid[forumtid]";
$form_url = "forum.php?mod=post&action=reply&fid=$pull[pullfid]&tid=$forumtid[forumtid]&replysubmit=yes&infloat=yes&handlekey=fastpost";

			$posttable = getposttablebytid($forumtid['forumtid']);
			$article['commentnum'] = getcount($posttable, array('tid'=>$forumtid['forumtid'], 'first'=>'0'));
			
				$query = DB::query("SELECT pid, first, authorid AS uid, author AS username, dateline, message, smileyoff, bbcodeoff, htmlon, attachment, status
					FROM ".DB::table('forum_post')." WHERE tid='$forumtid[forumtid]' AND first='0' ORDER BY dateline DESC LIMIT 0,20");
				$attachpids = -1;
				$attachtags = array();
				$_G['group']['allowgetattach'] = 1;
				while ($value = DB::fetch($query)) {
					if($value['status'] == '0') {
						$value['message'] = discuzcode($value['message'], $value['smileyoff'], $value['bbcodeoff'], $value['htmlon']);
						$value['cid'] = $value['pid'];
						$commentlist[$value['pid']] = $value;
						if($value['attachment']) {
							$attachpids .= ",$value[pid]";
							if(preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $value['message'], $matchaids)) {
								$attachtags[$value['pid']] = $matchaids[1];
							}
						}
					}
				}

				if($attachpids != '-1') {
					require_once libfile('function/attachment');
					parseattach($attachpids, $attachtags, $commentlist);
				}

if($_GET['iniframe'] == 1){
	include template('diy:hr/'.$modidentifier.'_view_iframe');
}else{
	include template('diy:hr/'.$modidentifier.'_view');
}
?>
