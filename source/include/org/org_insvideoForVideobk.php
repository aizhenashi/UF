<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_G['uid']){
	die('��¼�� ���');
}

if(!$_POST['submit']){
	die('û���ύ���Ľ�����');
}

//��ȡpost ����
$videourl = $_POST['videourl'];
$bkid = $_POST['bkid'];

require libfile('class_video_pic','class');
//ͨ��Դ���� ����ȡflash_url title sharepic
$videoObj = new Videocatch($videourl);
$videoObj->setVideoinfo();
$videoinfo = $videoObj->videoinfo;

if($videoinfo !== array()){
	//��� ��ȡ��
	//����Ƶ�����Ƶ�� ����һ����Ӧ�ļ�¼  ��ת�����˿ռ���ҳ	
	$datas['title'] = $videoinfo['title'];
	$datas['sharepic'] = $videoinfo['pic'];
	$datas['flash_address'] = $videoinfo['flash_address'];
	$datas['bkid'] = $bkid;
	
	$rs = c::t('home_videobk_video')->insert_video($datas);
	
	header("Location:/home.php?mod=ucenter&do=index");
}else{
	
	header("Location:".$_SERVER['HTTP_REFERER'].'&error=1');
	//��� û��ȡ��
	//error ���ش��� ��֧�ָ���վ ���ص������Ƶҳ http_referre
	
}

	
?>