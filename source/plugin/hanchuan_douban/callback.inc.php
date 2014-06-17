<?php
/**
 *    [豆瓣登录(callback.php)] (C)2012-2099 Powered by 寒川@版权所有。
 *    Version: 1.0
 *    Date: 2013-03-25 12:31
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require('doubanv2.class.php');
require('config.php');

$o = new DoubanOAuthV2(APIKEY,Secret);
if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {

	}
}

if ($token) {

	require_once libfile('function/member');
	$referer = dreferer();
	if($_G['uid']>0) {//绑定
	
		$sql="select * from `".DB::table('plugin_hcdouban')."` where uid='$_G[uid]' or `douban_user_id`='$token[douban_user_id]'";
		$query = DB::query($sql);
		$n = DB::num_rows($query);
		if($n==1)//改绑
		{
			$sql="UPDATE `".DB::table('plugin_hcdouban')."` SET `douban_user_id`='$token[douban_user_id]' where `uid`='$_G[uid]'";
			DB::query($sql);
		}elseif($n==0) {//新绑
			$sql="INSERT INTO `".DB::table('plugin_hcdouban')."` (`uid`,`douban_user_id`) values('$_G[uid]','$token[douban_user_id]');";
			DB::query($sql);
		}elseif($n>=2){
			showmessage('该豆瓣账号已经绑定过其他用户，无法再次绑定。');
		}

			showmessage('豆瓣用户绑定成功。',$referer?$referer:'./');
	}else{
		$sql="select * from `".DB::table('plugin_hcdouban')."` where douban_user_id='$token[douban_user_id]'";
		$query = DB::query($sql);
		$n = DB::num_rows($query);
		if($n>0)//登录
		{
			$user = DB::fetch($query);
			
			$member = getuserbyuid($user['uid'],1);
			//print_r($member);


			$cookietime = 1296000;
			setloginstatus($member, $cookietime);
	
			$param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle']);
			

			//showmessage('login_succeed', $referer?$referer:'./', $param, array('showdialog' => 1, 'locationtime' => true, 'extrajs' => $ucsynlogin));
			showmessage('login_succeed', $referer?$referer:'./', $param, array('showdialog' => 1, 'locationtime' => true));
	
		}else {//注册
			$username = iconv("UTF-8","GB2312",$token['douban_user_name']);
			include template('hanchuan_douban:reg');//reg
		}
	}

}else{
	header('location:plugin.php?id=hanchuan_douban:login');
}