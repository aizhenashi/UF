<?php

$db_host	=	"192.168.1.102";
$db_user	=	"sa";
$db_pass	=	"uestar@uestar";
$db_name	=	"uestar";

mysql_connect($db_host, $db_user, $db_pass) or die('数据库链接失败');
mysql_select_db($db_name) or dir('?数据库选择失败');
mysql_query("SET NAMES 'gbk';");


$name = $_POST["name"];
$shouji = $_POST["shouji"];
$shenggao = $_POST["shenggao"];
$xw = $_POST["xw"];
$yw = $_POST["yw"];
$tw = $_POST["tw"];
$tz = $_POST["tz"];
$time = time();
//echo 'insert into pre_hotmen_content(id,name,shouji,shenggao,xw,yw,tw,tz,time) values(null,"'.$name.'","'.$shouji.'","'.$shenggao.'","'.$xw.'","'.$yw.'","'.$tw.'","'.$tz.'","'.$time.'")'
$sql = 'insert into pre_hotmen_content(id,name,shouji,shenggao,xw,yw,tw,tz,time) values(null,"'.$name.'","'.$shouji.'","'.$shenggao.'","'.$xw.'","'.$yw.'","'.$tw.'","'.$tz.'","'.$time.'")';
$result=mysql_query($sql);

		if($result){
			echo "<script language='javascript'>";
			echo "alert('报名成功，请等待我们与您联系');";
			echo "window.location='/topics/show/hotmen.html';";
			echo "</script language='javascript'>";
			exit;
		}else{
			echo "<script language='javascript'>";
			echo "alert('报名操作失败，请重新填写资料');";
			echo "window.location='/topics/show/hotmen.html';";
			echo "</script language='javascript'>";
			exit;
		}

?>