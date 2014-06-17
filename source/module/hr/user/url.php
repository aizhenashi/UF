<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if(empty($_G['uid']) && !$channel['visitorpost']) {
	showmessage('not_loggedin', '', '', array('login' => 1));
}

if(submitcheck('formhash')){
	$url= isset($_POST['url'])?trim($_POST['url']):'';
	if(!ereg("^[0-9a-zA-Z\_]*$",$url) or $url==''){
		showmessage('不能含有除字母、数字、下线线以外的其他任何字符。', 'user.php?mod=url');
	}

	$urldb= DB::fetch_first("SELECT `uid`, `url`  FROM ".DB::table('common_member_profile')." where `url`='".$url."' and `uid`<>'".$_G['uid']."'");
	if($urldb) {
		showmessage('该个性域名已经被占用。', 'user.php?mod=url');
	}else{
		DB::query("UPDATE ".DB::table('common_member_profile')." SET url='$url'  WHERE uid='$_G[uid]'");
	}
	showmessage('设置成功。', 'user.php?mod=url');

}


//DB::query("UPDATE ".DB::table('common_member_profile')." SET introduce='$introduce_str', introduceimg='$imgstr'  WHERE uid='$uid' ");
//showmessage('更新成功', 'user.php?mod=url');
$urldb= DB::fetch_first("SELECT `uid`, `url`  FROM ".DB::table('common_member_profile')." where  uid='".$_G['uid']."'");
include template('diy:user/url');