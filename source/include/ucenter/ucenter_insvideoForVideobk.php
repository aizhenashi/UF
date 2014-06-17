<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_G['uid']){
	die('登录啊 大哥');
}

if(!$_POST['submit']){
	die('没点提交从哪进来的');
}

//获取post 参数
$videourl = $_POST['videourl'];
$bkid = $_POST['bkid'];

require libfile('class_video_pic','class');
//通过源链接 来获取flash_url title sharepic
$videoObj = new Videocatch($videourl);
$videoObj->setVideoinfo();
$videoinfo = $videoObj->videoinfo;

if($videoinfo !== array()){
	//如果 获取到
	//在视频版块视频表 插入一条对应的记录  跳转到个人空间首页	
	$datas['title'] = $videoinfo['title'];
	$datas['sharepic'] = $videoinfo['pic'];
	$datas['flash_address'] = $videoinfo['flash_address'];
	$datas['bkid'] = $bkid;
	
	$rs = c::t('home_videobk_video')->insert_video($datas);
	
	header("Location:/home.php?mod=ucenter&do=index");
}else{
	
	header("Location:".$_SERVER['HTTP_REFERER'].'&error=1');
	//如果 没获取到
	//error 返回错误 不支持该网站 跳回到添加视频页 http_referre
	
}

	
?>