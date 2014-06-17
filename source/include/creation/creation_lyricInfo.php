<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}
$id=$_GET['id'];
if($id){
	DB::query("update ".DB::table('common_music_lyric')." set countnum=countnum+1 where id=$id");
}
$info=DB::fetch_first("SELECT title,uid,price,username,content,time,id FROM ".DB::table('common_music_lyric')." WHERE id=$id");
$info['content'] = str_replace(chr(10),'<br>',$info['content']);
include template('creation/musicLyricInfo');
?>