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


//验证用户修改的昵称是否重复
if($_GET['action']=='testing'){
	$nname=setCharset($_POST['nname']);
	$name=DB::fetch_first("SELECT username FROM ".DB::table('common_member')." WHERE uid!={$_G['uid']} and username='$nname'");
	if(!empty($name)){
		die('1');
	}else{
		die('2');
	}
}
//机构会员相关信息

$data=DB::fetch_first("SELECT a.username,b.field3,b.resideprovince,b.residecity,b.telephone,b.field5 FROM ".DB::table('common_member')." as a left join ".DB::table('common_member_profile')." as b on a.uid=b.uid where a.uid=$uid");

if($_POST['orgtinfo']){
	$username=setCharset($_POST['username']);
	$type=setCharset($_POST['orgType']);
	$province=setCharset($_POST['province']);
	$city=setCharset($_POST['city']);
	$telephone=$_POST['telephone'];
	$field5=setCharset($_POST['field5']);
	
	if($_POST['Province']!='0'){
		DB::query("UPDATE ".DB::table('common_member_profile')." SET resideprovince='$province',residecity='$city' WHERE uid=$uid");
	}
	DB::query("UPDATE ".DB::table('common_member_profile')." SET telephone='$telephone',field3='$type',field5='$field5' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('common_member')." SET username='$username' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('ucenter_members')." SET username='$username' WHERE uid=$uid");
	
}

include template('diy:org/orgtinfo');
?>