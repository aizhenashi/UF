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
//ת���ַ�
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=$_G['uid'];
//�õ��������б�
$blacklist=DB::fetch_all('SELECT uid,buid,busername,blacktime FROM '.DB::table('common_blacklist')." WHERE uid=$uid");
//��Ӻ�������Ա
if(isset($_POST['orgblacklist'])){
	$busername=setCharset($_POST['busername']);
	//�õ�Ҫ��ӵĺ���������Ϣ
	$bdata=DB::fetch_first("SELECT username,uid FROM ".DB::table('common_member')." WHERE username='$busername'");
	//�ж�Ҫ��ӵ����Ƿ��ں�������
	$black=DB::fetch_first("SELECT buid FROM ".DB::table('common_blacklist')." WHERE uid=$uid and busername='$busername'");
	//����ص���Ϣ����û���ں����������ڱ��в�������
	if(!empty($bdata) && empty($black) && $bdata['uid']!=$uid){
	$time=date("Y-m-d H:i:s");
	DB::insert('common_blacklist',array('uid'=>$uid,
										'buid'=>$bdata['uid'],
										'busername'=>$bdata['username'],
										'blacktime'=>$time));
	//�ڹ�ע����ɾ������
	DB::query("DELETE FROM ".DB::table('home_follow')." WHERE uid=$uid and followuid={$bdata['uid']}");
	DB::query("DELETE FROM ".DB::table('home_follow')." WHERE uid={$bdata['uid']} and followuid=$uid");
		die('1');
	}else{
		die('2');
	}
}

if(isset($_POST['orgmoveblack'])){
	$buid=$_POST['buid'];
	$uid=$_POST['uid'];
	DB::query("DELETE FROM ".DB::table('common_blacklist')." WHERE uid=$uid and buid=$buid");
}
include template('diy:org/orgblacklist');
?>