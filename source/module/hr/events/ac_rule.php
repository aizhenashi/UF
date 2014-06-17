<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//判断是否传过照片参加活动
$p=DB::fetch_first("select picid from ".DB::table('home_pic')." where uid={$_G['uid']} and click8=1");
include template('diy:events/ac_rule');

?>