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
//如果上传过照片，取出来
$p=DB::fetch_all("select picid from ".DB::table('home_pic')." where uid={$_G['uid']} and click8=1 limit 0,8");
	foreach($p as $img){
		$arr[]=$img;
	}
$name=DB::fetch_first("select username from ".DB::table('home_pic')." where uid=$uid");
$img=DB::fetch_all("select * from ".DB::table('home_pic')." where uid=$uid");
foreach($img as $photo){
	$ph[]=$photo;
}
//如果是从相册中选照片
if(isset($_POST['submit'])){
	$picid=$_POST['picid'];
	//看参加活动的照片是否超过8张
	$total=DB::fetch_first("select count(uid) as t from ".DB::table('home_pic')." where click8=1 and uid=$uid");
	if($total['t']>=8){
		showmessage('您参加活动的照片己经达到上限了!');
	}else{
		if(is_array($picid)){
			foreach($picid as $pid){
				//每次遍历后查看选的照片是否超过最大值
				$total=DB::fetch_first("select count(uid) as t from ".DB::table('home_pic')." where click8=1 and uid=$uid");
				if($total['t']<8){
						DB::query("update ".DB::table('home_pic')." set click8=1 where picid=$pid");	
					}else{
						showmessage('您参加活动的照片己经达到上限');
					}
				}
				showmessage('您己经选好照片了');
		}else{
		DB::query("update ".DB::table('home_pic')." set click8=1 where picid=$picid");
		showmessage('您己经选好照片了');
		}
		}
	}
include template('diy:events/two');

?>