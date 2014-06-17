<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: modcp_announcement.php 29236 2012-03-30 05:34:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=$_G['uid'];
//机构会员相关信息

$url=DB::fetch_first("SELECT url FROM ".DB::table('common_member_profile')." WHERE uid={$_G['uid']}");

if(isset($_POST['modOrgUrl'])){
	$url=$_POST['url'];
	DB::query("UPDATE ".DB::table('common_member_profile')." SET url='$url' WHERE uid={$_G['uid']}");
	
}

include template('diy:org/orgurl');
?>