<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require libfile('class/page');
global $_G;
if($_G["uid"]==18032)
{
	$searchkey = $_GET["searchkey"];
	if($searchkey != "")
	{
		$perPage = 12;
		$page=$_GET["page"] ? $_GET['page']:1;	
		$start_limit = ($page - 1) * $perPage;
		$start_limit=$start_limit>=0?$start_limit:"0";
		$limit=" limit ".$start_limit.",".$perPage ;
		$sql = "select * from pre_common_article where title like '%{$searchkey}%'  and xia=0 order by introTime desc {$limit}";
		$sql2 = "select * from pre_common_article where title like '%{$searchkey}%' and xia=0";
		$sortdata['count'] = count((DB::fetch_all($sql2)));	
		$searchArr = DB::query($sql);
		while ($row = DB::fetch($searchArr))
		{
			$row["introTime"] = date("Y-m-d",$row["introTime"]);
			$sql1 = "select * from pre_common_member where uid=".$row["uid"];
			$rs = DB::query($sql1);
			$row1 = DB::fetch($rs);
			$row["email"] = $row1["email"];
			$data[] = $row;
		}
		$searchArr = $data;
		$url = "article.php?do=search&searchkey=".$searchkey;
		$multi = multi($sortdata['count'], $perPage, $page,$url);
		include template("article/search");
		exit;
	}
	$perPage = 12;
	$page=$_GET["page"] ? $_GET['page']:1;	
	$start_limit = ($page - 1) * $perPage;
	$start_limit=$start_limit>=0?$start_limit:"0";
	$limit=" limit ".$start_limit.",".$perPage ;
	$sql = "select * from pre_common_article where introduce=1 and xia=0 order by introTime desc {$limit}";
	$sql2 = "select * from pre_common_article where introduce=1 and xia=0";
	$sortdata['count'] = count((DB::fetch_all($sql2)));	
	$searchArr = DB::query($sql);
	while ($row = DB::fetch($searchArr))
	{
		$row["introTime"] = date("Y-m-d",$row["introTime"]);
		$sql1 = "select * from pre_common_member where uid=".$row["uid"];
		$rs = DB::query($sql1);
		$row1 = DB::fetch($rs);
		$row["email"] = $row1["email"];
		$data[] = $row;
	}
	$searchArr = $data;
	$url="article.php?do=search";
	$multi = multi($sortdata['count'], $perPage, $page,$url);
	include template("article/search");
}
?>