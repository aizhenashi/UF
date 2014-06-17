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

//自己的照片总曝光数
$data=DB::fetch_all("select click7 from ".DB::table('home_pic')." where click8=1 and uid=$uid");
foreach($data as $num){
	$m+=$num['click7'];
}
$total=DB::fetch_first("select count(uid) as t from ".DB::table('home_pic')." where click8=1 and uid=$uid");

//取出自己参加活动的照片
$ph= DB::query("select * from ".DB::table('home_pic'). " where click8=1 and uid=$uid limit 0,8");
while($photo=DB::fetch($ph)){
	$img[]=$photo;
}

$imgnum = count($img);
include template('diy:events/three');

?>