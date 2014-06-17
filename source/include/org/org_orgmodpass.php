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
//×ª»»×Ö·û¼¯
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=$_G['uid'];

if(isset($_POST['modpass'])){
	$salt=DB::fetch_first("SELECT salt FROM ".DB::table('ucenter_members')." WHERE uid={$_G['uid']}");
	$oldpass=md5(md5($_POST['oldpass']).$salt['salt']);
	$newpass=md5(md5($_POST['newpass']).$salt['salt']);
	$pass=DB::fetch_first("SELECT password FROM ".DB::table('ucenter_members')." WHERE uid={$_G['uid']}");
	if($oldpass==$pass['password']){
		DB::query("UPDATE ".DB::table('ucenter_members')." SET password='$newpass' WHERE uid={$_G['uid']}");
		die('1');
	}else{
		die('2');
	}
}


include template('diy:org/orgmodpass');
?>