<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//影视的查询
if($_POST["ajaxgetcontent1"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
	if($act=="全部")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='影视' order by times desc limit 0,10";	
 	}elseif($act == "正在上演")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='影视' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "即将上演")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='影视' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "优艺官方")
 	{
 		$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='优艺官方' order by times desc limit 0,10";
 	}elseif($act == "国际活动")
 	{
 		$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='国际活动' order by times desc limit 0,10";
 	}elseif($act == "剧院联盟")
 	{
 		$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='剧院联盟' order by times desc limit 0,10";
 	}
	$filmArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent');
	exit;
}
$filmSql = "select * from pre_common_action where fristClassName='影视' order by times desc limit  1,10";
$filmArr = DB::fetch_all($filmSql);
//活动查询
if($_POST["ajaxgetcontent2"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
 	if($act=="全部")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='活动' order by times desc limit 0,10";	
 	}elseif($act == "正在上演")
 	{
 		$now = time();
    	$sql = "select * from pre_common_action where fristClassName='活动' and startime<=$now and $now<=endtime order by times desc limit 0,10";
 	}elseif($act == "即将上演")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='活动' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "优艺官方")
 	{
 		$sql = "select * from pre_common_action where fristClassName='活动' and secondClassName='优艺官方' order by times desc limit 0,10";
 	}elseif($act == "国际活动")
 	{
 		$sql = "select * from pre_common_action where fristClassName='活动' and secondClassName='国际活动' order by times desc limit 0,10";
 	}elseif($act == "剧院联盟")
 	{
 		$sql = "select * from pre_common_action where fristClassName='活动' and secondClassName='剧院联盟' order by times desc limit 0,10";
 	}
	$activeArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent1');
	exit;	
}
$activeSql = "select * from pre_common_action where fristClassName='活动' order by times desc limit 1,10";
$activeArr = DB::fetch_all($activeSql);
//音乐查询
if($_POST["ajaxgetcontent3"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
	if($act=="全部")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='音乐' order by times desc limit 0,10";	
 	}elseif($act == "正在上演")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='音乐' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "即将上演")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='音乐' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "优艺官方")
 	{
 		$sql = "select * from pre_common_action where fristClassName='音乐' and secondClassName='优艺官方' order by times desc limit 0,10";
 	}elseif($act == "国际活动")
 	{
 		$sql = "select * from pre_common_action where fristClassName='音乐' and secondClassName='国际活动' order by times desc limit 0,10";
 	}elseif($act == "剧院联盟")
 	{
 		$sql = "select * from pre_common_action where fristClassName='音乐' and secondClassName='剧院联盟' order by times desc limit 0,10";
 	}
	$musicArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent2');
	exit;	
}
$musicSql = "select * from pre_common_action where fristClassName='音乐' order by times desc limit 1,10";
$musicArr = DB::fetch_all($musicSql);
//展会查询
if($_POST["ajaxgetcontent4"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
 	if($act=="全部")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='展会' order by times desc limit 0,10";	
	}elseif($act == "正在上演")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='展会' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "即将上演")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='展会' and startime>= $now and endtime<=$afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "优艺官方")
 	{
 		$sql = "select * from pre_common_action where fristClassName='展会' and secondClassName='优艺官方' order by times desc limit 0,10";
 	}elseif($act == "国际活动")
 	{
 		$sql = "select * from pre_common_action where fristClassName='展会' and secondClassName='国际活动' order by times desc limit 0,10";
 	}elseif($act == "剧院联盟")
 	{
 		$sql = "select * from pre_common_action where fristClassName='展会' and secondClassName='剧院联盟' order by times desc limit 0,10";
 	}
 	$zhanArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent3');
	exit;	
}
	$zhanSql = "select * from pre_common_action where fristClassName='展会' order by times desc limit 1,10";
	$zhanArr = DB::fetch_all($zhanSql);
//典礼查询
if($_POST["ajaxgetcontent5"] == true)
{
 	$act = $_POST["act"]; 
 	$act = iconv("UTF-8", "gb2312", $act); 
 	if($act=="全部")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='典礼' order by times desc limit 0,10";	
 	}elseif($act == "正在上演")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='典礼' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "即将上演")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='典礼' and startime >= $now and endtime <= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "优艺官方")
 	{
 		$sql = "select * from pre_common_action where fristClassName='典礼' and secondClassName='优艺官方' order by times desc limit 0,10";
 	}elseif($act == "国际活动")
 	{
 		$sql = "select * from pre_common_action where fristClassName='典礼' and secondClassName='国际活动' order by times desc limit 1,10";
 	}elseif($act == "剧院联盟")
 	{
 		$sql = "select * from pre_common_action where fristClassName='典礼' and secondClassName='剧院联盟' order by times desc limit 0,10";
 	}
	$dianArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent4');
	exit;
}
$dianSql = "select * from pre_common_action where fristClassName='典礼' order by times desc  limit 1,10";
$dianArr = DB::fetch_all($dianSql);
//查询一条影视最新的记录显示在首页大图位置
$sql = "select * from pre_common_action where fristClassName='影视' order by times desc limit 0,1";
$lastFilmArr = DB::query($sql);
$lastFilmArr = DB::fetch($lastFilmArr);
//查询一条活动最新的记录显示在首页大图位置
$sql = "select * from pre_common_action where fristClassName='活动' order by times desc limit 0,1";
$lastActiveArr = DB::query($sql);
$lastActiveArr = DB::fetch($lastActiveArr);
//查询一条音乐最新的记录显示在首页大图位置
$sql = "select * from pre_common_action where fristClassName='音乐' order by times desc limit 0,1";
$lastMusicArr = DB::query($sql);
$lastMusicArr = DB::fetch($lastMusicArr);
//查询一条展会最新的记录显示在首页大图位置
$sql = "select * from pre_common_action where fristClassName='展会' order by times desc limit 0,1";
$lastZhanArr = DB::query($sql);
$lastZhanArr = DB::fetch($lastZhanArr);
//查询一条典礼最新的记录显示在首页大图位置
$sql = "select * from pre_common_action where fristClassName='典礼' order by times desc limit 0,1";
$lastDianArr = DB::query($sql);
$lastDianArr = DB::fetch($lastDianArr);
include template('diy:active/search1');
?>