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

require libfile('class_video_pic','class');
//通过源链接 来获取flash_url title sharepic
$videoObj = new Videocatch($videourl);
$videoObj->setVideoinfo();
$videoinfo = $videoObj->videoinfo;

if($videoinfo !== array()){
	//如果 获取到
	//在视频版块视频表 插入一条对应的记录  跳转到个人空间首页	
	$datas['title'] = addslashes($videoinfo['title']);
	$datas['sharepic'] = $videoinfo['pic'];
	$datas['flash_address'] = addslashes($videoinfo['flash_address']);
	$datas['time'] = time();
	//echo "insert into ".DB::table('user_addvideo')."(id,flash_address,title,sharepic,time,uid) values(null,'".$datas['flash_address']."','".$datas['title']."','".$datas['sharepic']."',".$datas['time'].",".$_G['uid'].")";
	$rs = DB::query("insert into ".DB::table('user_addvideo')."(id,flash_address,title,sharepic,time,uid) values(null,'".$datas['flash_address']."','".$datas['title']."','".$datas['sharepic']."',".$datas['time'].",".$_G['uid'].")");
	header("Location:/home.php?mod=ucenter&do=camer");
}else{
	header("Location:".$_SERVER['HTTP_REFERER'].'&error=1');
	//如果 没获取到
	//error 返回错误 不支持该网站 跳回到添加视频页 http_referre
	
}

	
?>