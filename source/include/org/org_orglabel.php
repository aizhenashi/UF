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
//������Ա�����Ϣ
/*
$data=DB::fetch_first("SELECT a.username,b.field3,b.resideprovince,b.residecity,b.telephone,b.field5 FROM ".DB::table('common_member')." as a left join ".DB::table('common_member_profile')." as b on a.uid=b.uid where a.uid=$uid");

if($_POST['orgtinfo']){
	$username=setCharset($_POST['username']);
	$type=setCharset($_POST['orgType']);
	$province=setCharset($_POST['province']);
	$city=setCharset($_POST['city']);
	$telephone=$_POST['telephone'];
	$field5=setCharset($_POST['field5']);
	
	if($_POST['Province']!='0'){
		DB::query("UPDATE ".DB::table('common_member_profile')." SET resideprovince='$province',residecity='$city' WHERE uid=$uid");
	}
	DB::query("UPDATE ".DB::table('common_member_profile')." SET telephone='$telephone',field3='$type',field5='$field5' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('common_member')." SET username='$username' WHERE uid=$uid");
}

$name=array('����','ʵ����','ż����','������','С����','���巶��','�巶��','office lady','���շ���','ŷ������','�պ�ϵ','�Ӿ�ϵ','ɱ����','�׸���','�߸�˧','Ů����','С��̫','����Ԫ','���ŷ�','Ӣ�׷���','�й���','�Բз�','С����','��ͨ����','��������');
for($i=0;$i<count($name);$i++){
	DB::query("INSERT INTO ".DB::table('label_type')." (`name`) values ('$name[$i]')");

}
*/
//���б�ǩ
$label=DB::fetch_all("SELECT id,name FROM ".DB::table('label_type'));
//�ҵı�ǩ
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('common_label')." where uid=$uid");
if($typenums['num']>0){
	$mylabel=DB::fetch_all("select name from ".DB::table('label_type')." as a left join " .DB::table('common_label')." as b on a.id=b.bid where b.uid=$uid");
}

include template('diy:org/orglabel');
?>