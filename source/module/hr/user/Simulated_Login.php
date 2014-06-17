<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


require libfile('function/member');
require libfile('function/hr');
require libfile('function/mail');
require libfile('class/member');
require_once libfile('function/seccode');
runhooks();
$key = "aabb"; 
$uid=$_GET['uid'];
$uid = passport_decrypt($_GET['uid'],$key);
$member = getuserbyuid($uid);
setloginstatus($member,0);
//showmessage("模拟登陆成功{$member['username']}", 'home.php');
showmessage("模拟登陆成功{$member['username']}", '/home.php?mod=ucenter&do=index');

//http://www.uestar.cn/user.php?mod=Simulated_Login&uid=1



