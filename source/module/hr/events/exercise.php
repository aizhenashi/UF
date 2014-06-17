<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$uid=$_G['uid'];
//����������
if(isset($_POST['submit'])){

	if($_POST['username']==''){
		die('error_username');
	}
	if(!$_POST['picid']){
		die('error_picid');
	}
	
	
	//����������Ƭ����uid
	$picinfo = DB::fetch_first("select * from ".DB::table('home_pic')." where picid = '{$_POST['picid']}'");

	$lasttime=time();
	$photoname = trim($picinfo['username']);
	$username=trim(iconv('UTF-8', 'GB2312',$_POST['username']));
	$success = 0;
	
	if($photoname==$username){

		$success = 1;
	}
	
	DB::query("insert into ".DB::table('topic_image_content(`uid`,`username`,`puid`,`imageid`,`lasttime`,`status`,`daan`)')." values({$_G['uid']},'{$_G['username']}','{$picinfo['uid']}','{$picinfo['picid']}',$lasttime,'{$success}','{$username}')");
	DB::query("update ".DB::table('home_pic')." set click7=click7+1 where picid={$_POST['picid']}");
	
	die('ok');
}

//ajax ȡͼƬ
if($_POST['getPicList'] == 'true'){
	$uidListStr = rtrim($_POST['uidListStr'],',');
	$picInfoList = getGuessPicList(1,$uidListStr);

	die(json_encode($picInfoList));
}

//�μӻ������
$count=DB::fetch_first("select count(distinct uid) as u from ".DB::table('topic_image_content'));
$cou = DB::fetch_first("select count(distinct uid) as c from ".DB::table('home_pic')." where click8=1");
$num=$count['u']+$cou['c'];

//�ж��Ƿ񴫹���Ƭ�μӻ
$p=DB::fetch_first("select picid from ".DB::table('home_pic')." where uid={$_G['uid']} and click8=1");

//��ȡ�н��б�
$zjlist=DB::fetch_all("select `username`,`uid`,`jiangxiang`,`time` from ".DB::table('events_zhongjiang')." order by id desc");

//�н���ѯ
$myzjlist=DB::fetch_all("select `username`,`uid`,`jiangxiang`,`time` from ".DB::table('events_zhongjiang')." where uid = '{$_G['uid']}' order by id desc");

$startTime = time();
$endTime = mktime(10,0,0,date('m',time()),date('d',time())+1,date('Y',time()));

//��ͼ��Ƭ�б�
$picInfoList = getGuessPicList(8);


//�鿴�ջ���ַ
$addressinfo=DB::fetch_all("select `uname`,`telphone`,`mobile`,`paykey`,`address`,`youbian`,`delivertime` from ".DB::table('events_address')." where uid='{$_G['uid']}'");
$addressinfo = $addressinfo[0];

if(!$addressinfo){
	$address = true;
}

//��д�ջ���ַ
if($_GET['addressconfirm']){
	$uname = iconv('UTF-8', 'GB2312',$_POST['uname']);
	$_POST['address'] = iconv('UTF-8', 'GB2312',$_POST['address']);	
	$_POST['delivertime'] = iconv('UTF-8', 'GB2312',$_POST['delivertime']);
	
	$addressinfo=DB::fetch_all("select `id` from ".DB::table('events_address')." where uid='{$_G['uid']}'");
	
	if($addressinfo){
		DB::query("update ".DB::table('events_address')." set uname='{$uname}',telphone='{$_POST['tel']}',mobile='{$_POST['mobile']}',paykey='{$_POST['paykey']}',address='{$_POST['address']}',youbian='{$_POST['youbian']}',delivertime='{$_POST['delivertime']}' where uid='{$_G['uid']}'");
	}else{
		DB::query("insert into ".DB::table('events_address(`uid`,`uname`,`telphone`,`mobile`,`paykey`,`address`,`youbian`,`delivertime`,`time`)')." values('{$_G['uid']}','{$uname}','{$_POST['tel']}','{$_POST['mobile']}','{$_POST['paykey']}','{$_POST['address']}','{$_POST['youbian']}','{$_POST['delivertime']}',".time().")");
	}
	
	die('ok');
}

//�콱
if($_POST['lingjiang'] == '1'){
	die('ok');
}

function getGuessPicList($num,$uidListStr = NULL){
	
	global $_G;
	
	//�ж��Ƿ������ʱ��,��ǰʱ��-30����
	$judgetime=time()-30*60;
	
	
	
		
	//�Ӳ½���¼��ȡ��30�����ڵ�ǰ��¼�߲¹����� liukai add distinct �ݴ� ��ֹ 1,1,1,1 ������ uid �ַ���
	$allpuid=DB::fetch_all("select distinct puid from ".DB::table('topic_image_content')." where lasttime > $judgetime && uid={$_G['uid']}");

	//implode ƴ���ַ��� ʹ��,���� ���� 1,2,3,4
	if($allpuid){
		foreach($allpuid as $puid){
			$puidStr .= $puid['puid'].',';
		}
		$puidStr = rtrim($puidStr,',');
	
		$puidListWhere = " && uid not in($puidStr)";
	}else{
		$puidListWhere = '';
	}
		
	if($uidListStr){
		$currentUidListWhere = " && uid not in ($uidListStr)";
	}

	//��� 8 �� ��Ա
	$uidList = DB::fetch_all("select distinct uid from ".DB::table('home_pic')." where click8=1 and uid !={$_G['uid']} {$puidListWhere}{$currentUidListWhere} order by rand() limit {$num}");

	//������Ա ���ȡһ��ͼƬ
	foreach ($uidList as $uid){
		$picinfo = DB::fetch_first("select * from ".DB::table('home_pic')." where uid ={$uid['uid']} && click8 = '1' order by rand() limit 1");
		$picInfoList[] = $picinfo;
	}

	return $picInfoList;

}

include template('diy:events/activity');

?>