<?php
require libfile('class/lettv');
$object = new LetvCloudV1();

//点击率加1
c::t('original_video')->addClick($_GET['id']);

//根据视频id获取在lettv那边的视频信息 及在uestar 在本站的信息
// 标题
// 分类
// 浏览量
// 发布者姓名
// 上传时间
// 剧情介绍
$data = c::t('original_video')->getDataForWhere("id = '{$_GET['id']}'",true);
$data = $data[0];

//获取观看权限
$auth = C::t('original_video')->getPlayAuth($data['id'],$data['pricetype']);

if($auth == 0){
	$jietu = $object->imageGet($data['video_id'], '640_360');
	$jietu = json_decode($jietu,'ARRAY');
}

$jietujson = json_encode($jietu['data']);

include template('diy:original/play');