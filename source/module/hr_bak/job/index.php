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

$metakeywords = empty($metakeywords) ? $channel['seoinfo']['seokeywords'] : $metakeywords;
$metadescription = empty($metadescription) ? $channel['seoinfo']['seodescription'] : $metadescription;

if($sortlist) {
	$sortids = array();
	foreach($sortlist as $id => $sort) {
		$sortids[] = $id;
	}

	$totalthread = $todaythread = 0;
	$query = DB::query("SELECT threads, todaythreads FROM ".DB::table('hr_sort')." WHERE sortid IN (".dimplode($sortids).")");
	while($sort = DB::fetch($query)) {
		$totalthread += $sort['threads'];
		$todaythread += $sort['todaythreads'];
	}
}


require_once libfile('function/hr');

//最近浏览过
if($threadvisited = getcookie('threadvisited')) {
	$threadvisited = explode(',', $threadvisited);
	$query = DB::query("SELECT tid, subject FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE tid IN (".dimplode($threadvisited).")");
	$visitedlist = array();
	while($subject = DB::fetch($query)) {
		$subject['subject'] = cutstr($subject['subject'], 32);
		$visitedlist[] = $subject;
	}
}

//首页搜索
$searcharea = searchindex($modidentifier);

include template('diy:hr/'.$modidentifier.'_index');