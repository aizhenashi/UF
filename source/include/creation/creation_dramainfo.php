<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}

$aid=$_GET['articleId'];
if($aid){
	DB::query("update ".DB::table('common_article')." set countnum=countnum+1 where articleId=$aid");
}
$info=DB::fetch_first("SELECT * FROM ".DB::table('common_article')." WHERE articleId=$aid");


include template('creation/dramainfo');
?>