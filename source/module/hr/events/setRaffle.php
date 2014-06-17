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


//统计每个用户的猜姓名次数 获奖次数

$day = explode('-',date('Y-m-d',time()));
$todaystart = mktime(15,30,0,$day[1],$day[2],$day[0]);

if($_GET['history'] == '1'){
	
	$uidOrderGuessNumList = DB::fetch_all("SELECT count( `id` ) AS num,`uid`
FROM ".DB::table('topic_image_content')."
GROUP BY uid");

	//冒泡排序
	for($i = 0;$i < count($uidOrderGuessNumList);$i++){
		for($j=0;$j<count($uidOrderGuessNumList)-$i-1;$j++){
			if((int) $uidOrderGuessNumList[$j]['num'] < (int) $uidOrderGuessNumList[$j+1]['num']){
				$temp = $uidOrderGuessNumList[$j];
				$uidOrderGuessNumList[$j] = $uidOrderGuessNumList[$j+1];
				$uidOrderGuessNumList[$j+1] = $temp;
			}
		}
	}

	$guessList = array();
	foreach ($uidOrderGuessNumList as $array){
		$list = DB::fetch_first("select * from ".DB::table('topic_image_content')." where uid = '{$array['uid']}' order by id desc");

		//猜奖次数
		$temp = DB::fetch_first("select count('*') as num from ".DB::table('topic_image_content')." where uid = '{$array['uid']}'");
		$list['tnum'] = $temp['num'];

		//猜对次数
		$temp = DB::fetch_first("select count('*') as num from ".DB::table('topic_image_content')." where uid = '{$array['uid']}' && status = '1'");
		$list['onum'] = $temp['num'];
		
		//中奖次数
		$temp = DB::fetch_first("select count(`id`) as num from ".DB::table('events_zhongjiang')." where uid = '{$list['uid']}'");
		$list['snum'] = $temp['num'];

		//电话
		$temp  = DB::fetch_first("select `paykey`,`uname`,`mobile` from `".DB::table('events_address')."` where uid = '{$list['uid']}'");
		$list['mobile'] = $temp['mobile'];

		//真实姓名
		$list['uname'] = $temp['uname'];
		
		//支付宝
		$list['paykey'] = $temp['paykey'];
		
		
		$guessList[] =	$list;
		
	}

}else{

	//获取今日中奖会员
	$uidList=DB::fetch_all("select uid from ".DB::table('events_zhongjiang')." where time > '".$todaystart."' ");
	foreach ($uidList as $cuid){
		$str .= $cuid['uid'].',';
	}
	$str = rtrim($str,',');
	if($str){
		$zjwhere = "&& uid not in (".$str.")";
	}
	
	$timearr = explode('-', date('Y-m-d',strtotime("1 days ago")));
	$starttime = mktime(0,0,0,$timearr[1],$timearr[2],$timearr[0]);
	$endtime = $starttime + 24*60*60;
	
	//从抽奖记录表中 获取 昨天 都谁猜了姓名 猜的次数及猜对的次数
	$uidList=DB::fetch_all("select distinct uid from ".DB::table('topic_image_content')." where lasttime > '".$starttime."' && lasttime < '".$endtime."' {$zjwhere}");
	
	foreach ($uidList as $uid){
		$userinfo=DB::fetch_first("select username,uid from ".DB::table('topic_image_content')." where uid = '{$uid['uid']}' && lasttime > '".$starttime."' && lasttime < '".$endtime."'");
		$guessNum=DB::fetch_first("select count('id') as gnum from ".DB::table('topic_image_content')." where uid = '{$uid['uid']}' && lasttime > '".$starttime."' && lasttime < '".$endtime."'");
		$userinfo['gnum'] = $guessNum['gnum'];
		$guessNum=DB::fetch_first("select count('id') as cnum from ".DB::table('topic_image_content')." where uid = '{$uid['uid']}' && status = '1' && lasttime > '".$starttime."' && lasttime < '".$endtime."'");
		$userinfo['cnum'] = $guessNum['cnum'];
		$ad=DB::fetch_first("select uname,mobile from ".DB::table('events_address')." where uid = '{$userinfo['uid']}'");
		$userinfo['mobile'] = $ad['mobile'];
		$userinfo['uname'] = $ad['uname'];

		$userlist[] = $userinfo;
	}
	
}




if($_POST['fj']){
	foreach ($_POST['zj'] as $zjuid){
		$uname=DB::fetch_first("select username from ".DB::table('common_member')." where uid = '{$zjuid}'");
		$uname = $uname['username'];
		DB::query("insert into ".DB::table('events_zhongjiang(`uid`,`jiangxiang`,`username`,`time`)')." values({$zjuid},'{$_POST['jp']}','$uname','".time()."')");
	}
}

include template('diy:events/shengbian');

?>