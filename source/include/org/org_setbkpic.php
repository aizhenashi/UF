<?php

if(!$_GET['bkid']){
	exit('从哪进来的?');
}

$bkid = $_GET['bkid'];
$spaceuid = $_GET['spaceuid'];

//验证该板块是否属于这个空间的主人
$result = c::t('myspace_bankuai')->fetch_myspacerow("bkstring like '%{$bkid}%' && uid = '{$spaceuid}'");
if(!$result){
	die('这版块是你的吗?');
}

//写真名称
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");

//获取该板块下的所有图片
$datas = c::t('home_picbk_pic')->fetchAll("bkid = '{$bkid}'");


if(!$datas){
	//该图片版块下没有图片 跳转到
	//暂时关上
	echo header("Location:/home.php?mod=ucenter&do=uploadpic_picbk&bkid=".$bkid);
}


include template('diy:org/bkpiclist');