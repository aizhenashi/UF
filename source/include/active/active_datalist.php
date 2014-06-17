<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require libfile('class/page');
//²éÑ¯
//$searchInfo = $_POST["keyWord"];
 $searchInfo = $_GET["seachkey"];
 $act = $_GET["act"];
if($act!="")
{
	$searchInfo = $_GET["seachkey"];
    $perPage = 12;
	$page=$_GET["page"] ? $_GET['page']:1;	
	$start_limit = ($page - 1) * $perPage;
	$start_limit=$start_limit>=0?$start_limit:"0";
	$limit=" limit ".$start_limit.",".$perPage ;	
	if($act=='all')
	{
		$sql ="select * from pre_common_action where actiontitle like '%{$searchInfo}%'{$limit}";
		$sql1 ="select * from pre_common_action where actiontitle like '%{$searchInfo}%'";
	}else if($act == 'guanzhu')
	{
		$sql ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' order by clickTimes desc{$limit}";
		$sql1 ="select * from pre_common_action where actiontitle like '%{$searchInfo}%'";
	}
	else if($act =='will')
	{ 
		$now = time();
        $afterWeek=time()+7*24*60*60;
		$sql ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' and startime>=$now and startime<=$afterWeek {$limit}";
		$sql1 ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' and startime>=$now and startime<=$afterWeek";
	
	}else if($act == 'lastest')
	{
		$sql ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' order by times desc {$limit}";
		$sql1 ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' order by times";	
		
	}else if($act == "end")
	{
		$now = time();
	    $sql ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' and endtime < $now {$limit}";
	    $sql1 ="select * from pre_common_action where actiontitle like '%{$searchInfo}%' and endtime < $now";
	    
	    
	}
	$searchArr = DB::fetch_all($sql);
	
	$rs = DB::query($sql);
	while($row = DB::fetch($rs)){
	$row['startime'] = date('Y.m.d',$row['startime']);
	$row['endtime'] = date('Y.m.d',$row['endtime']);
	$datas[] = $row;	
	}
	$sortdata['count'] = count((DB::fetch_all($sql1)));	
						
	$searchArr = $datas;
	$url = "/active.php?do=datalist";
	if($_GET['seachkey']){
		
		$url .= "&seachkey=".$_GET['seachkey'];
	}
	//·ÖÒ³html
	$multi = multi($sortdata['count'], $perPage, $page, $url);
	include template('diy:active/datalist');
	exit;
}
	$perPage = 12;
	$page=$_GET["page"] ? $_GET['page']:1;	
	$start_limit = ($page - 1) * $perPage;
	$start_limit=$start_limit>=0?$start_limit:"0";
	$limit=" limit ".$start_limit.",".$perPage ;
	$sql = "select * from pre_common_action where actiontitle like '%{$searchInfo}%'{$limit}";
	$sql1 = "select * from pre_common_action where actiontitle like '%{$searchInfo}%'";
	$searchArr = DB::fetch_all($sql);
	$rs = DB::query($sql);
	while($row = DB::fetch($rs)){
	$row['startime'] = date('Y.m.d',$row['startime']);
	$row['endtime'] = date('Y.m.d',$row['endtime']);
	$datas[] = $row;	
	}
	$sortdata['count'] = count((DB::fetch_all($sql1)));							
	$searchArr = $datas;
	$url = "/active.php?do=datalist";
	if($_GET['seachkey']){
	   
		$url .= "&seachkey=".$_GET['seachkey'];
	}
	//·ÖÒ³html
	$multi = multi($sortdata['count'], $perPage, $page, $url);
	include template('diy:active/datalist');

?>