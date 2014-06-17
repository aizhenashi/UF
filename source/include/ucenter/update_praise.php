<?php
$centeruid = intval($_POST['BNR_SUB_ID']);
if($_COOKIE["zhichi_$centeruid"]){
	exit;
}
$db_host	=	"192.168.1.102";
$db_user	=	"sa";
$db_pass	=	"uestar@uestar";
$db_name	=	"uestar";

mysql_connect($db_host, $db_user, $db_pass) or die('数据库链接失败');
mysql_select_db($db_name) or die('数据库选择失败');
mysql_query("SET NAMES 'utf8';");




$sql = "select praise from pre_common_member_profile where uid = $centeruid";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result) ){
	$praise = $row['praise'];
}
echo $praise;

$update = "update pre_common_member_profile set praise = $praise+1 where uid = $centeruid ";
if(mysql_query($update)){
		setcookie("zhichi_$centeruid",$centeruid);
}
mysql_close();

//echo "update ".DB::table("common_member_profile")." set praise = {$quey['praise']} where uid = $centeruid";
//$sql = DB::query("update ".DB::table("common_member_profile")." set praise = {$quey['praise']} where uid = $centeruid");
//echo $centeruid;
?>