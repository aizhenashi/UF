<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$bkid = $_POST['bkid'];

if(!$bkid){
	die('error attack');
}

$data = c::t('home_bktext_content')->fetchRow("bkid = '$bkid'");

if($data){ //编辑数据
	$rs = c::t('home_bktext_content')->updateforwhere("bkid = '{$bkid}'",$_POST['content']);	
	$data = c::t('home_bktext_content')->fetchRow("bkid = '$bkid'");

	$rs = true;
}else{ //添加数据
	unset($_POST['submit']);
	$id = c::t('home_bktext_content')->insert_row($_POST);
	if($id){
		$rs = true;
	}
}



if($rs === true){
	header("Location:/home.php?mod=ucenter&do=index");
	exit;
}else{
	header("Location:".$_SERVER['HTTP_REFERER']);
	exit;
}


?>