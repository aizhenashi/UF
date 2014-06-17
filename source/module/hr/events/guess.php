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
$giftarr = arrray();
array_fill('1',$giftarrs);
//$giftarr = array('1','2','2','3','3','3','4','4','4','')
$gifts_arr=array('1','2');//每日礼物
//var_dump($gifts_arr);

//var_dump(count($gifts_arr));
	$date=time()-24*60*60;
	$query=DB::fetch_first("select count(isorder) as num from ".DB::table("topic_image_content")." where lasttime > $date and status = 1 and isorder = 1");
	//var_dump($query);

	//$date=time()-24*60*60;
	//var_dump($date);




for($i=0;$i<count($gifts_arr);$i++){
	
	$date=time()-24*60*60;
	$query=DB::fetch_first("select count(uid) as num from ".DB::table("topic_image_content")." where lasttime > $date and status = 1 and isorder = 1");
	$query=DB::fetch_first("select count(uid) as num from ".DB::table("topic_image_content")." where lasttime > $date and isorder = 0");
//	if(!empty($query['num'])){
	//	exit("抽奖完成");
	//}
	
		if(intval($query['num']) > 0){
							
						$query=DB::query("select uid,lasttime from ".DB::table("topic_image_content")." where lasttime > $date and status = 1 and isorder = 0 ");	
						while($data=DB::fetch($query)){
						$d[]=$data['uid'];
						$t[]=$data['lasttime'];
						}
						$g_arr=array_rand($d);
						$uid=$d[$g_arr];
						$lasttime=$t[$g_arr];
						DB::query("update ".DB::table("topic_image_content")." set isorder = 1 where uid = $uid  and lasttime = $lasttime ");
						$enough_arr[$i]=$uid;
						unset($gifts_arr[$i]);
		}else{
		
			
			
					$query=DB::query("select uid,lasttime from ".DB::table("topic_image_content")." where lasttime > $date and status = 1");
					while($data=DB::fetch($query)){
						$d[]=$data['uid'];
						$t[]=$data['lasttime'];
					}
					$g_arr=array_rand($d);
					$uid=$d[$g_arr];
					$lasttime=$t[$g_arr];
					//var_dump($lasttime);
					//exit;
					DB::query("update ".DB::table("topic_image_content")." set isorder = 1 where uid = $uid  and lasttime = $lasttime ");
					$enough_arr[$i]=$uid;
					unset($gifts_arr[$i]);
			

		}	
}

/*
$date=time()-24*60*60;
$query=DB::query("select uid,lasttime from ".DB::table("topic_image_content")." where lasttime > $date and status = 1");
			while($data=DB::fetch($query)){
				$d[]=$data['uid'];
				$t[]=$data['lasttime'];
			}
			$g_arr=array_rand($d);
			var_dump($g_arr);
			$uid=$d[$g_arr];
			$lasttime=$t[$g_arr];
			var_dump($uid);
			var_dump($lasttime);
			exit;
			DB::query("update ".DB::table("topic_image_content")." set isorder = 1 where uid = $uid and lasttime = $lasttime");
*/
?>