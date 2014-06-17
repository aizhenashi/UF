<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//×ª»»×Ö·û¼¯
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$data=DB::fetch_all("SELECT id,uid,workstitle,release_time,intro FROM ".DB::table('common_works')." WHERE uid={$_G['uid']} order by id desc");
if($_POST['works']){
	$worksTitle=setCharset($_POST['worksTitle']);
	$releaseTime=setCharset($_POST['releaseTime']);
	$intro=setCharset($_POST['intro']);
	DB::insert('common_works ',array('uid'=>$_G['uid'],
									'workstitle'=>$worksTitle,
									'release_time'=>$releaseTime,
									'intro'=>$intro
				));
	
}
if($_POST['det']){
	DB::query("DELETE FROM ".DB::table('common_works')." WHERE id={$_POST['wid']}");
}
include template('diy:ucenter/representative');

?>