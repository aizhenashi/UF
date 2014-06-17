<?php
global $_G;
if(empty($_G['uid']))
{
	header("Location:login.html");
}
$uid = $_G['uid'];
$title = $_POST["title"];
$creationTime = $_POST["time"];
$creationTime = strtotime($creationTime);
$content = $_POST["content"];
$bookClass = $_POST["checkbox"];
$writer = $_G[member][username];
if($_GET["do4"]==1)
{	
	$articleId = $_GET["articleId"];
	if($articleId!=undefined)
	{
		$title = $_POST["title"];
		$creationTime = $_POST["time"];
		$creationTime = strtotime($creationTime);
		$content = $_POST["content"];
		$dramaClass = $_POST["checkbox"];
		$txt="";
	    for($i=0;$i<=count($bookClass);$i++)
	    {
	    	$txt.=$bookClass[$i]." ";
	    } 
	    $dramaClass=$txt;
		$sql = "update pre_common_article set title='".$title."' , creationTime='".$creationTime."' , content='".$content."' , dramaClass='".$dramaClass."' where articleId = ".$articleId;
		DB::query($sql);
		echo "<script language='javascript'>";
		echo "window.location='article.php?do=content&articleId=".$articleId."';";
		echo "</script>";
		exit;
	}
}
//$bookClass1= $_POST["bookClass1"];
if($title!=null)
{	
	$txt="";
    for($i=0;$i<=count($bookClass);$i++)
    {
    	$txt.=$bookClass[$i]." ";
    } 
    $dramaClass=$txt;
    $time=date("Y-m-d");
	$sql="insert into pre_common_article(title,writer,creationTime,content,dramaClass,time,uid,professor)values('$title','$writer','$creationTime','$content','$dramaClass','$time',$uid,1)";
	$row=DB::query($sql);
	echo "<script language='javascript'>";
	echo "window.location='article.php?do=datalist';";
	echo "</script>";

}
if($_GET["Mod"]==3)
{
	$articleId = $_GET["artId"];
	$sql="select * from pre_common_article where articleId=".$articleId;
	$rs = DB::query($sql);
	$row = DB::fetch($rs);
	$row["creationTime"] = date("Y-m-d",$row["creationTime"]);
	$str = $row["dramaClass"];
	$str = str_replace(" ","-",$str);
	$scriptchecked= "<script language='javascript'>".
	"var str='".$str."';".
	"var sucai = '".$str."'.split('-');".
	"var i; 
		for(i=0;i<sucai.length;i++)
		{
			var j;
			for(j=1;j<=21;j++)
			{
				var c = document.getElementById('checkbox'+j).value;
				if(sucai[i] == c)
				{
					var agree = document.getElementById('checkbox'+j).checked = true;
				}
			}
		}".
	"</script>";
	include template('diy:article/upload');
}
include template('diy:article/upload');
?>