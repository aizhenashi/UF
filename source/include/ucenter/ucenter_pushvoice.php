<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_POST['submit']){
	die('û�ύ��ô�����ģ�');
}
if(!$_G['uid']){
	die('��ôû��½��!');
}

if($_FILES){
	$filetype = $_FILES['selectfile']['type'];
	$maxsize = 8388608;
	$filesize = $_FILES['selectfile']['size'];
	if($filetype != 'audio/mpeg'){
		echo "<script>alert('���ϴ�MP3��ʽ�������ļ�');location.href='home.php?mod=ucenter&do=index';</script>";
	}
	if($filesize > $maxsize){
		echo "<script>alert('�ļ�̫��,���ϴ�С��8M���ļ�');location.href='home.php?mod=ucenter&do=index';</script>"; 
	}
	require_once("api/yinpin/qiniu/io.php");
	require_once("api/yinpin/qiniu/rs.php");

	$filename = $_G['uid'].'_'.date('YmdHis').'.mp3';
	$filepath = $_FILES["selectfile"]["tmp_name"];
	$bucket = "uestarroom";
	$key1 = $filename;
	$accessKey = 'wJ7DPFCkCqYiaF1RFf0ASI5XbXTq_sl7VoKkPbtn';
	$secretKey = 'yYa2OLsuho5Gl9Z7dntBysVkLweSZVXJJzkr_TaB';

	//�õ���Ƶ�Ĳ��ŵ�ַ
	$domain = 'uestarroom.qiniudn.com';
	Qiniu_SetKeys($accessKey, $secretKey);  
	$baseUrl = Qiniu_RS_MakeBaseUrl($domain, $key1);
	$getPolicy = new Qiniu_RS_GetPolicy();
	$privateUrl = $getPolicy->MakeRequest($baseUrl, null);
	//���ID���жϴ˰���Ƿ�������
	$bkid = $_POST['bkid'];
	$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");
	$mbk = c::t('home_voice')->fetch_one("bkid = {$bkdata['id']}");
	$time = time();
	$uid = $_G['uid'];
	$title = $_POST['musicname'];
	$path = $filename;


	//�ϴ�����ţ�ռ�
	//Qiniu_SetKeys($accessKey, $secretKey);
	$putPolicy = new Qiniu_RS_PutPolicy($bucket);
	$upToken = $putPolicy->Token(null);
	$putExtra = new Qiniu_PutExtra();
	$putExtra->Crc32 = 1;
	list($ret, $err) = Qiniu_PutFile($upToken, $key1, $filepath, $putExtra);
	//�ϴ�����ţ�ռ����
	//����˰��û������,д�����ݿ�
	if(empty($mbk)){
		DB::insert('home_voice',array('uid'=>$uid,'title'=>$title,'path'=>$path,'time'=>$time,'bkid'=>$bkid,'privateUrl'=>$privateUrl));
	}else{
		DB::query("UPDATE ".DB::table('home_voice')." SET `title`='$title',`path`='$filename',`time`='$time',`privateUrl`='$privateUrl' WHERE bkid='$bkid'");	
	}

	echo "<script>alert('�ϴ��ɹ���');location.href='home.php?mod=ucenter&do=index';</script>";
}




?>