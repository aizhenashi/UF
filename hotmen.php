<?php

$db_host	=	"192.168.1.102";
$db_user	=	"sa";
$db_pass	=	"uestar@uestar";
$db_name	=	"uestar";

mysql_connect($db_host, $db_user, $db_pass) or die('���ݿ�����ʧ��');
mysql_select_db($db_name) or dir('?���ݿ�ѡ��ʧ��');
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
			echo "alert('�����ɹ�����ȴ�����������ϵ');";
			echo "window.location='/topics/show/hotmen.html';";
			echo "</script language='javascript'>";
			exit;
		}else{
			echo "<script language='javascript'>";
			echo "alert('��������ʧ�ܣ���������д����');";
			echo "window.location='/topics/show/hotmen.html';";
			echo "</script language='javascript'>";
			exit;
		}

?>