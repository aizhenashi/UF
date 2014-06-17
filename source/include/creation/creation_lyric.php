<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}

include template('creation/musicLyric');
?>