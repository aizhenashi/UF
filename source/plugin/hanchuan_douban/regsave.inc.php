<?php
/**
 *    [豆瓣登录(regsave.php)] (C)2012-2099 Powered by 寒川@版权所有。
 *    Version: 1.0
 *    Date: 2013-03-25 12:31
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require libfile('function/member');
require libfile('class/member');
$username	= isset($_POST['username']) ? daddslashes(trim($_POST['username'])) : '';
$password	= isset($_POST['password']) ? daddslashes(trim($_POST['password'])) : '';
$email	= isset($_POST['email']) ? daddslashes(trim($_POST['email'])) : '';
$userid	= isset($_POST['userid']) ? daddslashes(trim($_POST['userid'])) : '';
$groupid = isset($_POST['groupid']) ? daddslashes(trim($_POST['groupid'])) : '0';

if(submitcheck('formhash') and $username<>'' and $password<>'' and $email<>'' and $userid<>'') {
	loaducenter();
	$uid = uc_user_register($username, $password, $email);
	if( $uid > 0) {
	
		$sql = "UPDATE `".DB::table('common_member'). "` SET `groupid`=".$groupid."  WHERE `uid`=".$uid;
		DB::query($sql);
		C::t('common_member')->insert($uid, $username, null, $email, 'Manual Acting', $groupid, null);
		$member = getuserbyuid($uid);
		setloginstatus($member,0);
		$sql="INSERT INTO `".DB::table('plugin_hcdouban')."` (`uid`,`douban_user_id`) values('$uid','$userid');";
		DB::query($sql);
		showmessage('注册成功','index.php');
	}else{
		if($uid == -1) {
				showmessage('profile_username_illegal');
			} elseif($uid == -2) {
				showmessage('profile_username_protect');
			} elseif($uid == -3) {
				showmessage('profile_username_duplicate');
			} elseif($uid == -4) {
				showmessage('profile_email_illegal');
			} elseif($uid == -5) {
				showmessage('profile_email_domain_illegal');
			} elseif($uid == -6) {
				showmessage('profile_email_duplicate');
			} else {
				showmessage('undefined_action');
			}
	}
}else{
	showmessage('参数错误，请重试。','plugin.php?id=hanchuan_douban:login');
}
