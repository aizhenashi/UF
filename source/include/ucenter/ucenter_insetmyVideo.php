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

require libfile('class_video_pic','class');
//ͨ��Դ���� ����ȡflash_url title sharepic
$videoObj = new Videocatch($videourl);
$videoObj->setVideoinfo();
$videoinfo = $videoObj->videoinfo;

if($videoinfo !== array()){
	//��� ��ȡ��
	//����Ƶ�����Ƶ�� ����һ����Ӧ�ļ�¼  ��ת�����˿ռ���ҳ	
	$datas['title'] = addslashes($videoinfo['title']);
	$datas['sharepic'] = $videoinfo['pic'];
	$datas['flash_address'] = addslashes($videoinfo['flash_address']);
	$datas['time'] = time();
	//echo "insert into ".DB::table('user_addvideo')."(id,flash_address,title,sharepic,time,uid) values(null,'".$datas['flash_address']."','".$datas['title']."','".$datas['sharepic']."',".$datas['time'].",".$_G['uid'].")";
	$rs = DB::query("insert into ".DB::table('user_addvideo')."(id,flash_address,title,sharepic,time,uid) values(null,'".$datas['flash_address']."','".$datas['title']."','".$datas['sharepic']."',".$datas['time'].",".$_G['uid'].")");
	header("Location:/home.php?mod=ucenter&do=camer");
}else{
	header("Location:".$_SERVER['HTTP_REFERER'].'&error=1');
	//��� û��ȡ��
	//error ���ش��� ��֧�ָ���վ ���ص������Ƶҳ http_referre
	
}

	
?>