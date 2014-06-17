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
$guid = intval($_GET['uid']);
if(empty($guid)){
		$flag1 = 1;//自己看自己
	}else{
		$flag1 = 0;//看别人
	}

//查看用户之间的关系 
if($guid != $_G['uid']){
		 $guanzhu = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = {$_G['uid']} and followuid = $guid");//不为空，则用户关注了被查看人
		 $fensi = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = $guid and followuid = {$_G['uid']}");//不为空，则被查看人是该用户的粉丝
		 if(!empty($guanzhu)){
				if(!empty($fensi)){
						$flag2 = 1;//互相关注
				}else{
						$flag2 = 2;//当前用户是被查看人的粉丝
				}
		 }else{
				if(!empty($fensi)){
						$flag2 = 3;//被查看人是当前用户的粉丝
				}else{
						$flag2 = 4;//两人没有任何关系
				}
		 }
}


//转换字符集
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];
//机构会员相关信息

$data=DB::fetch_first("SELECT a.username,a.uid,a.email,b.url,b.field3,b.resideprovince,b.residecity,b.field5,b.broker,b.telephone FROM ".DB::table('common_member')." as a left join ".DB::table('common_member_profile')." as b on a.uid=b.uid where a.uid=$uid");
$data['url']=!empty($data['url'])?$data['url']:'u_'.$data['uid'];
$data['field5'] = str_replace(chr(10),'<br>',$data['field5']);
//推荐会员
$att=DB::query("SELECT b.username,b.uid,a.url FROM ".DB::table('common_member_profile'). " as a left join ".DB::table('common_member'). " as b on a.uid=b.uid where a.isavatar=1 and a.uid!=$uid order by b.regdate desc limit 0,5");
	while($attinfo=DB::fetch($att)){
		$attinfo['url']=!empty($attinfo['url'])?$attinfo['url']:'u_'.$attinfo['uid'];
		$info[]=$attinfo;
	}
	
if($_POST['base']){
	$username=setCharset($_POST['username']);
	$type=setCharset($_POST['cpType']);
	$province=setCharset($_POST['province']);
	$city=setCharset($_POST['city']);
	//DB::query("UPDATE ".DB::table('common_details')." SET broker={$_POST['selectbro']},email={$_POST['selectema']},telephone={$_POST['selecttel']} where uid=$uid");
	DB::query("UPDATE ".DB::table('common_member')." SET username='$username' where uid=$uid");
	DB::query("UPDATE ".DB::table('ucenter_members')." SET username='$username' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('common_member_profile')." SET field3='$type' where uid=$uid");
	if($_POST['province']!='0'){
		DB::query("UPDATE ".DB::table('common_member_profile')." SET resideprovince='$province',residecity='$city' WHERE uid=$uid");
	}
}	
	
if($_POST['lianxi']){
	$broker=setCharset($_POST['broker']);
	$telephone=$_POST['telephone'];
	$email=$_POST['email'];
	DB::query("UPDATE ".DB::table('common_member')." SET email='$email' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('common_member_profile')." SET broker='$broker',telephone='$telephone' WHERE uid=$uid");
		
}
	
if($_POST['bcjieshao']){
	$field5=setCharset($_POST['jieshao']);
	DB::query("UPDATE ".DB::table('common_member_profile')." SET field5='$field5' WHERE uid=$uid");
}

//看修改的用户是否重复
if($_GET['action']=='testing'){
	$nname=setCharset($_POST['nname']);
	$name=DB::fetch_first("SELECT username FROM ".DB::table('common_member')." WHERE uid!={$_G['uid']} and username='$nname'");
	if(!empty($name)){
		die('1');
	}else{
		die('2');
	}
}

include template('diy:org/info');
?>