<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//转换字符集
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}

$uid=$_G['uid'];
//得到黑名单列表
$blacklist=DB::fetch_all('SELECT uid,buid,busername,blacktime FROM '.DB::table('common_blacklist')." WHERE uid=$uid");
//添加黑名单人员
if(isset($_POST['blacklist'])){
	$busername=setCharset($_POST['busername']);
	//得到要添加的黑名单的信息
	$bdata=DB::fetch_first("SELECT username,uid FROM ".DB::table('common_member')." WHERE username='$busername'");
	//判断要添加的人是否己在黑名单中
	$black=DB::fetch_first("SELECT buid FROM ".DB::table('common_blacklist')." WHERE uid=$uid and busername='$busername'");
	//有相关的信息并且没有在黑名单中则在表中插入数据
	if(!empty($bdata) && empty($black) && $bdata['uid']!=$uid){
	$time=date("Y-m-d H:i:s");
	DB::insert('common_blacklist',array('uid'=>$uid,
										'buid'=>$bdata['uid'],
										'busername'=>$bdata['username'],
										'blacktime'=>$time));
	DB::query("DELETE FROM ".DB::table('home_follow')." WHERE uid=$uid and followuid={$bdata['uid']}");
	DB::query("DELETE FROM ".DB::table('home_follow')." WHERE uid={$bdata['uid']} and followuid=$uid");
		die('1');
	}else{
		die('2');
	}
}

if(isset($_POST['moveblack'])){
	$buid=$_POST['buid'];
	$uid=$_POST['uid'];
	DB::query("DELETE FROM ".DB::table('common_blacklist')." WHERE uid=$uid and buid=$buid");
}
include template('diy:ucenter/privacy');

?>