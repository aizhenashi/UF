<?php
global $_G;
if(empty($_G['uid']))
{
	header("Location:login.html");
}
$uId = $_G['uid'];
$sql="select * from pre_common_article where uid=".$uId." and professor=1 order by time desc";
$myDrama = DB::fetch_all($sql);
$rs = DB::query($sql);
while($row = DB::fetch($rs))
{
	$row['creationTime'] = date('Y-m-d',$row['creationTime']);
	$datas[] = $row;	
}
$myDrama = $datas;
$sql1 = "select * from pre_article_shoucang where uid=".$_G['uid'];
$shoucang = DB::query($sql1);
while($row = DB::fetch($shoucang))
{
    $sql="select * from pre_common_article where articleId=".$row["articleId"];
    $data = DB::query($sql);
    $datas = DB::fetch($data);
    $cang[] = $datas;
}
for($i=0;$i<count($cang);$i++)
{
    $cang[$i]["creationTime"] = date("Y-m-d",$cang[$i]["creationTime"]);
 }
$do2 = $_GET["do2"];
if($do2 == 2)
{
	$articleId = $_GET["articleId"];
	$sql = "delete from pre_article_shoucang where articleId=".$articleId;	
	DB::query($sql);
	echo "<script language='javascript'>";
	echo "window.location='article.php?do=mysc'";
	echo "</script>";
}
if($_GET["do1"] == 1)
{
	global $_G; 
	$articleId = $_GET["articleId"];
	$creationTime = $_GET["creationTime"];
	$writer = $_GET["writer"];
	$title = $_GET["title"];
	$dramaClass = $_GET["dramaClass"];
	$time = time();
	$sql2 = "select * from pre_article_shoucang where articleId=".$articleId." and uid=".$_G["uid"];
	$result = DB::fetch_all($sql2);
	if(!$result)
	{
		if($_G["uid"])
		{
			if($_G["uid"]==18009)
			{
				$sql = "insert into pre_article_shoucang(articleId,creationTime,writer,title,dramaClass,time,uid)values($articleId,'$creationTime','$writer','$title','$dramaClass',$time,$uId)";
				$row = DB::query($sql);
			}else{
				showmessage("你没有权限收藏此剧本",'article.php?do=content&articleId='.$articleId);
			}
			
		}else{
			echo "<script language='javascript'>";
			echo "alert('您还未登录');";
			echo "window.location='article.php?do=datalist';";
			echo "</script>";
		}
		
	}else{
		echo "<script language='javascript'>";
		echo "alert('您已经收藏了此剧本');";
		echo "window.location='article.php?do=datalist';";
		echo "</script>";
	}
	$sql1 = "select articleId from pre_article_shoucang where uid=".$uId;
    $shoucang1 = DB::query($sql1);
    while($row = DB::fetch($shoucang1))
    {
    	$sql="select * from pre_common_article where articleId=".$row["articleId"];
    	$data = DB::query($sql);
    	$datas = DB::fetch($data);
    	$cang[] = $datas;    	
    }
    for($i=0;$i<count($cang);$i++)
    {
    	$cang[$i]["creationTime"] = date("Y-m-d",$cang[$i]["creationTime"]);
    }
    echo "<script language='javascript'>";
	echo "window.location='article.php?do=mysc';";
	echo "</script>";
}
	
include template('diy:article/mysc');
?>