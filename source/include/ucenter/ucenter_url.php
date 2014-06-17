<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$url=DB::fetch_first("SELECT url FROM ".DB::table('common_member_profile')." WHERE uid={$_G['uid']}");

if(isset($_POST['modurl'])){
	$url=$_POST['url'];
	DB::query("UPDATE ".DB::table('common_member_profile')." SET url='$url' WHERE uid={$_G['uid']}");
	
}

include template('diy:ucenter/url');

?>