<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: modcp_announcement.php 29236 2012-03-30 05:34:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=$_G['uid'];
//机构会员相关信息

$data=DB::fetch_first("SELECT mobile,cemail,weixin,weibo,qq FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
if(isset($_POST['orgrelation'])){
	$sql = "UPDATE ".DB::table('common_member_profile')." SET `mobile`='{$_POST['mobile']}',";
	$sql .= "cemail='{$_POST['cemail']}',";
	$sql .= "weixin='{$_POST['weixin']}',";
	$sql .= "weibo='{$_POST['weibo']}',";
	$sql .= "qq='{$_POST['qq']}'";
	$sql .= "WHERE uid='$uid'";
	DB::query($sql);
	
}

include template('diy:org/orgrelation');
?>