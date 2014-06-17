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

//所有标签
$label=DB::fetch_all("SELECT id,name FROM ".DB::table('label_type')." limit 25");
//我的系统标签
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('common_label')." where uid=$uid");
if($typenums['num']>0){
	$mylabel=DB::fetch_all("select name,a.id from ".DB::table('label_type')." as a left join " .DB::table('common_label')." as b on a.id=b.bid where b.uid=$uid");
}
//我的自定义标签
$diylabel=DB::fetch_all("SELECT id,name,uid FROM ".DB::table('diy_label')." where uid=$uid");
//添加自定义标签
if($_POST['addlabel']){
	$labelname=setCharset($_POST['addLabelName']);
	DB::insert('diy_label',array('uid'=>$uid,'name'=>$labelname));
}
//添加标签
if(isset($_POST['label'])){
	$typename=setCharset($_POST['text']);
	$id=DB::fetch_first("SELECT id FROM ".DB::table('label_type')." WHERE name='$typename'");
	DB::insert('common_label',array('uid'=>$uid,'bid'=>$id['id']));
}
//删除标签
if(isset($_POST['moveLabel'])){
	if($_POST['typeid']==1){
		$lid=$_POST['lid'];
		DB::query("DELETE FROM ".DB::table('common_label')." WHERE uid=$uid and bid=$lid");
	}
	if($_POST['typeid']==2 && $_POST['labelname']){
		$diyname=setCharset($_POST['labelname']);
		DB::query("DELETE FROM ".DB::table('diy_label')." where uid=$uid and name='$diyname'");
	}
}
include template('diy:ucenter/label');
?>