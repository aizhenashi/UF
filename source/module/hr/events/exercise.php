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
$uid=$_G['uid'];
//猜姓名程序
if(isset($_POST['submit'])){

	if($_POST['username']==''){
		die('error_username');
	}
	if(!$_POST['picid']){
		die('error_picid');
	}
	
	
	//传过来的照片主人uid
	$picinfo = DB::fetch_first("select * from ".DB::table('home_pic')." where picid = '{$_POST['picid']}'");

	$lasttime=time();
	$photoname = trim($picinfo['username']);
	$username=trim(iconv('UTF-8', 'GB2312',$_POST['username']));
	$success = 0;
	
	if($photoname==$username){

		$success = 1;
	}
	
	DB::query("insert into ".DB::table('topic_image_content(`uid`,`username`,`puid`,`imageid`,`lasttime`,`status`,`daan`)')." values({$_G['uid']},'{$_G['username']}','{$picinfo['uid']}','{$picinfo['picid']}',$lasttime,'{$success}','{$username}')");
	DB::query("update ".DB::table('home_pic')." set click7=click7+1 where picid={$_POST['picid']}");
	
	die('ok');
}

//ajax 取图片
if($_POST['getPicList'] == 'true'){
	$uidListStr = rtrim($_POST['uidListStr'],',');
	$picInfoList = getGuessPicList(1,$uidListStr);

	die(json_encode($picInfoList));
}

//参加活动总人数
$count=DB::fetch_first("select count(distinct uid) as u from ".DB::table('topic_image_content'));
$cou = DB::fetch_first("select count(distinct uid) as c from ".DB::table('home_pic')." where click8=1");
$num=$count['u']+$cou['c'];

//判断是否传过照片参加活动
$p=DB::fetch_first("select picid from ".DB::table('home_pic')." where uid={$_G['uid']} and click8=1");

//获取中奖列表
$zjlist=DB::fetch_all("select `username`,`uid`,`jiangxiang`,`time` from ".DB::table('events_zhongjiang')." order by id desc");

//中奖查询
$myzjlist=DB::fetch_all("select `username`,`uid`,`jiangxiang`,`time` from ".DB::table('events_zhongjiang')." where uid = '{$_G['uid']}' order by id desc");

$startTime = time();
$endTime = mktime(10,0,0,date('m',time()),date('d',time())+1,date('Y',time()));

//猜图照片列表
$picInfoList = getGuessPicList(8);


//查看收货地址
$addressinfo=DB::fetch_all("select `uname`,`telphone`,`mobile`,`paykey`,`address`,`youbian`,`delivertime` from ".DB::table('events_address')." where uid='{$_G['uid']}'");
$addressinfo = $addressinfo[0];

if(!$addressinfo){
	$address = true;
}

//填写收货地址
if($_GET['addressconfirm']){
	$uname = iconv('UTF-8', 'GB2312',$_POST['uname']);
	$_POST['address'] = iconv('UTF-8', 'GB2312',$_POST['address']);	
	$_POST['delivertime'] = iconv('UTF-8', 'GB2312',$_POST['delivertime']);
	
	$addressinfo=DB::fetch_all("select `id` from ".DB::table('events_address')." where uid='{$_G['uid']}'");
	
	if($addressinfo){
		DB::query("update ".DB::table('events_address')." set uname='{$uname}',telphone='{$_POST['tel']}',mobile='{$_POST['mobile']}',paykey='{$_POST['paykey']}',address='{$_POST['address']}',youbian='{$_POST['youbian']}',delivertime='{$_POST['delivertime']}' where uid='{$_G['uid']}'");
	}else{
		DB::query("insert into ".DB::table('events_address(`uid`,`uname`,`telphone`,`mobile`,`paykey`,`address`,`youbian`,`delivertime`,`time`)')." values('{$_G['uid']}','{$uname}','{$_POST['tel']}','{$_POST['mobile']}','{$_POST['paykey']}','{$_POST['address']}','{$_POST['youbian']}','{$_POST['delivertime']}',".time().")");
	}
	
	die('ok');
}

//领奖
if($_POST['lingjiang'] == '1'){
	die('ok');
}

function getGuessPicList($num,$uidListStr = NULL){
	
	global $_G;
	
	//判断是否允许的时间,当前时间-30分钟
	$judgetime=time()-30*60;
	
	
	
		
	//从猜奖记录表取出30分钟内当前登录者猜过的人 liukai add distinct 容错 防止 1,1,1,1 这样的 uid 字符串
	$allpuid=DB::fetch_all("select distinct puid from ".DB::table('topic_image_content')." where lasttime > $judgetime && uid={$_G['uid']}");

	//implode 拼接字符串 使用,连接 例子 1,2,3,4
	if($allpuid){
		foreach($allpuid as $puid){
			$puidStr .= $puid['puid'].',';
		}
		$puidStr = rtrim($puidStr,',');
	
		$puidListWhere = " && uid not in($puidStr)";
	}else{
		$puidListWhere = '';
	}
		
	if($uidListStr){
		$currentUidListWhere = " && uid not in ($uidListStr)";
	}

	//随机 8 个 会员
	$uidList = DB::fetch_all("select distinct uid from ".DB::table('home_pic')." where click8=1 and uid !={$_G['uid']} {$puidListWhere}{$currentUidListWhere} order by rand() limit {$num}");

	//遍历会员 随机取一张图片
	foreach ($uidList as $uid){
		$picinfo = DB::fetch_first("select * from ".DB::table('home_pic')." where uid ={$uid['uid']} && click8 = '1' order by rand() limit 1");
		$picInfoList[] = $picinfo;
	}

	return $picInfoList;

}

include template('diy:events/activity');

?>