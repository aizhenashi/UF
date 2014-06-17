<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_click.php 25246 2011-11-02 03:34:53Z zhangguosheng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$time=strtotime('2013-08-15 0:0:0');
$num=DB::fetch_first("SELECT COUNT(uid) as n FROM ".DB::table('common_member')." WHERE regdate>$time");
include template('diy:admin/performance');

?>