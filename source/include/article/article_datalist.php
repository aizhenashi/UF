<?php
require libfile('class/page');
global $_G;
$uid = $_G['uid'];
$perPage = 16;
$page=$_GET["page"];	
$start_limit = ($page - 1) * $perPage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perPage ;
$sql = "select * from pre_common_article where xia=0 and professor=1 order by articleId desc {$limit}";
$sql1 = "select * from pre_common_article where xia=0 and professor=1";
$sortdata['count'] = count((DB::fetch_all($sql1)));
$allpage=ceil($sortdata['count']/$perPage);
if(!empty($_GET['page'])){
	$prepage=$_GET['page'];
    }else{
		$prepage=1;
    }
$url="article.php?do=datalist";
$multi = multi($sortdata['count'], $perPage, $page,$url);
$datalist = DB::fetch_all($sql);
$rs = DB::query($sql);
while($row = DB::fetch($rs))
{
	$row['creationTime'] = date('Y-m-d',$row['creationTime']);
	$row["content"] = substr($row['content'],0,100);
	if(strlen($row["title"])>10){
		$row["title"] = substr($row['title'],0,8)."...";
	}
	$row["content"] = $row["content"]."......";
	$count = strlen($row['writer']);
	if($count>=11){
		$row['writer']  = substr($row['writer'],0,7);
		$row['writer'] = $row['writer']."...";
	}
	$datas[] = $row;	
}
$datalist = $datas;
$introduce = $_GET["introduce"];
if($introduce==1)
{
	$articleId = $_GET["articleId"];
	$sql="update pre_common_article set professor=0 where articleId=".$articleId;
	DB::query($sql);
	echo "<script language='javascript'>";
	echo "window.location='article.php?do=datalist';";
	echo "</script>";
}
include template("article/datalist");
?>