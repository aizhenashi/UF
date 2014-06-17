<?php

//空间主人uid
$spaceuid = $centeruid;

//array 表情组 all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();

//array 所有表情
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);

//说说	
	if($spaceuid == $_G['uid']){
		//全部说说
		//获取我的关注列表
		
		$uidarr = c::t('home_follow')->getdata('`followuid`',"uid = '{$spaceuid}'");
		$uidarr[] = array('followuid'=>$spaceuid);
	
		//获取 uid数组
		$uidstr = "";
		foreach ($uidarr as $uid){
			$uidstr .= $uid['followuid'].',';
		}
		$uidstr = rtrim($uidstr,',');
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid in ({$uidstr}) && fid = 0");
	}else{
		//我的说说
		$uidstr = rtrim($uidstr,',');
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid = '{$spaceuid}' && fid = 0");
		
	}	
		
//获取空间版块
	$spacebk = c::t('myspace_bankuai')->get_myspace_bkinfo($spaceuid);

//获取空间留言
	$spacehuifus = c::t('home_space_liuyan')->select_liuyan("spaceuid = '{$spaceuid}' order by id desc limit 10");
//获取右侧数据
	$array = $center->getRightInfo($centeruid,$flag1);

include template('diy:org/index');
