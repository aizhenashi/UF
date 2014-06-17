<?php
error_reporting(E_ALL &~E_NOTICE);
require libfile('class/lettv');
$object = new LetvCloudV1();

//通过lettv提供的视频id 将标题 ,剧情介绍  发送到lettv
//对title 转码
$object->videoUpdate($_POST['video_id'],iconv('GB2312','UTF-8',$_POST['title']),iconv('GB2312','UTF-8',$_POST['desc']));

//将题材类型,  费用类型, 价格  存在本地
$vtype = implode(',',$_POST['vtype']);

//删除视频id
unset($_POST['id']);
//删除title
unset($_POST['title']);
$_POST['vtype'] = $vtype;
//删除desc
unset($_POST['desc']);
//删除发布提交按钮
unset($_POST['fabu']);

var_dump($data);
$rs = c::t('original_video')->insertData($_POST);


//视频发布成功 跳转到账户中心整合页
showmessage('视频发布成功','/creation.php?do=account');


?>