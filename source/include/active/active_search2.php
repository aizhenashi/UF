<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
    require libfile('class/page');
    $act = $_GET["act"];
    
    
if($act!="")
{
    $perPage = 12;
    $page=$_GET["page"];	
    $start_limit = ($page - 1) * $perPage;
    $start_limit=$start_limit>=0?$start_limit:"0";
    $limit="limit ".$start_limit.",".$perPage ;
  if($act=="all")
 {
   $sql = "select * from pre_common_action where fristClassName ='影视'{$limit}";
   $sql1 = "select * from pre_common_action where fristClassName ='影视'";	
   
  }elseif($act == "today")
  {
	$now = time();
	$sql = "select * from pre_common_action where fristClassName='影视' and $now >=startime and $now <= endtime {$limit}";
	$sql1 = "select * from pre_common_action where fristClassName='影视' and $now >=startime and $now <= endtime";
  }elseif($act == "week")
  {
	$now = time();
	$mondayTime = strtotime('last MONDAY');
	$sundayTime = strtotime('next SUNDAY');
	$sql = "select * from pre_common_action where fristClassName='影视' and startime>=$mondayTime and startime<=$sundayTime {$limit}";
	$sql1 = "select * from pre_common_action where fristClassName='影视' and startime>=$mondayTime and startime<=$sundayTime";
  }elseif($act == "month")
  { 
 	$now = time();
 	$arr28 = array(2);
 	$arr30 = array(4,6,9,11);
 	$arr31 = array(1,3,5,7,8,10,12);
 	$Month = date("m",$now);
 	
 	if(in_array($Month,$arr28))
 	{
 		$monthDay=28;
 	}elseif(in_array($Month,$arr30))
 	{
 		$monthDay = 30;
 	}elseif(in_array($Month,$arr31))
 	{
 		$monthDay = 31;
 	}
 	
 	$day = date("d",strtotime("today"));
 	$starMonth = strtotime('today')-$day * 24 * 60 * 60;
 	$endMonth = strtotime('today')+($monthDay-$day) * 24 * 60 * 60;
    //echo date("y-m-d h:i:s",$starMonth);
 	
 	$sql = "select * from pre_common_action where fristClassName='影视' and startime>=$starMonth and startime<= $endMonth {$limit}";
 	$sql1 = "select * from pre_common_action where fristClassName='影视' and startime>=$starMonth and startime<= $endMonth ";
   }
   elseif($act == "youyi")
   {
 	$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='优艺官方' {$limit}";
 	$sql1 = "select * from pre_common_action where fristClassName='影视' and secondClassName='优艺官方' ";
   }elseif($act == "guoji")
   {
 	$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='国际活动'{$limit}";
 	$sql1 = "select * from pre_common_action where fristClassName='影视' and secondClassName='国际活动'";
   }elseif($act == "juyuan")
   {
 	$sql = "select * from pre_common_action where fristClassName='影视' and secondClassName='剧院联盟'{$limit}";
 	$sql1 = "select * from pre_common_action where fristClassName='影视' and secondClassName='剧院联盟'";
  }
 	$filmArr = DB::fetch_all($sql);
 	
 	$rs = DB::query($sql);
    while($row = DB::fetch($rs)){
	$row['startime'] = date('Y.m.d',$row['startime']);
	$row['endtime'] = date('Y.m.d',$row['endtime']);
	$datas[] = $row;	
    }
    $sortdata['count'] = count((DB::fetch_all($sql1)));
    $allpage=ceil($sortdata['count']/$perPage);
	if(!empty($_GET['page']))
	{
		$prepage=$_GET['page'];
	}else{
			$prepage=1;
		 }
    $p=new page($sortdata['count'] ,$perPage);
    $multipage=$p->show(8);
    $filmArr = $datas;
    include template('diy:active/search2');
    exit;
}	
    
    $perPage = 12;
    $page=$_GET["page"];	
    $start_limit = ($page - 1) * $perPage;
    $start_limit=$start_limit>=0?$start_limit:"0";
    $limit=" limit ".$start_limit.",".$perPage ;
    $sql = "select * from pre_common_action where fristClassName='影视'{$limit}";
	$filmArr = DB::fetch_all($sql);
	$rs = DB::query($sql);
    while($row = DB::fetch($rs)){
	$row['startime'] = date('Y.m.d',$row['startime']);
	$row['endtime'] = date('Y.m.d',$row['endtime']);
	$datas[] = $row;	
    }
    $filmArr = $datas;
	$sortdata['count'] = count((DB::fetch_all("SELECT *  FROM pre_common_action where fristClassName = '影视'")));
	$allpage=ceil($sortdata['count']/$perPage);
    if(!empty($_GET['page'])){
	   $prepage=$_GET['page'];
	}else{
			$prepage=1;
		 }
    $p=new page($sortdata['count'] ,$perPage);
    $multipage=$p->show(8);
 
	include template('diy:active/search2');

?>