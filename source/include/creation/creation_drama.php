<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}



//得到所有剧本类型的信息
$pname=DB::fetch_all("select wid,wname FROM ".DB::table('creation_workstype')." where tid='1'");



include template('creation/drama');
?>