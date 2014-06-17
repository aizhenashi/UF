<?php
	//当前相片id
	$picid = $_GET['picid'];
	
	//uid 代表他人看
	//没有uid 代表 自己看
//array 表情组 all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();

//array 所有表情
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
//获取相册id
	$datas = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");
	$data = $datas[0];
	$albumid = $data['albumid'];
	
//相册信息	
	$datas = c::t('home_album')->fetchAll("albumid = '{$albumid}'");
	$albuminfo = $datas[0];

	
	
	include template('diy:ucenter/albumphoto');
?>