<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function setCharset($str)
{
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
//得到信息数据
$data=DB::fetch_first("SELECT telephone,cemail,weixin,weibo,qq FROM ".DB::table('common_member_profile')." WHERE uid={$_G['uid']}");
//var_dump($data);
if(isset($_POST['relation'])){
	$mobile=$_POST['mobile'];
	$cemail=setCharset($_POST['cemail']);
	$weixin=setCharset($_POST['weixin']);
	$weibo=setCharset($_POST['weibo']);
	$qq=$_POST['qq'];
	DB::query("UPDATE ".DB::table('common_member_profile')." SET telephone='$mobile',
																cemail='$cemail',
																weixin='$weixin',
																weibo='$weibo',
																qq='$qq' 
																WHERE uid={$_G['uid']}
																			
				");
}
include template('diy:ucenter/relation');

?>