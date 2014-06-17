<?php

//空间主人uid
$spaceuid = $centeruid;

//array 表情组 all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();
	
//array 所有表情
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
	$page = $_GET['page'] >0 ? $_GET['page'] : 1;
	$perpage = 10;
	$offset = ($page-1)*$perpage;
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
		
		$count = DB::fetch_first("select count(`id`) as tot from `".DB::table('home_shuoshuo')."` where uid in ({$uidstr}) && fid = 0");
		$count = $count['tot'];
		
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid in ({$uidstr}) && fid = 0",'id desc',"{$offset},{$perpage}");
		
	}else{
		//我的说说
		$count = DB::fetch_first("select count(`id`) as tot from `".DB::table('home_shuoshuo')."` where uid = '{$spaceuid}' && fid = 0");
		$count = $count['tot'];
				
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid = '{$spaceuid}' && fid = 0",'id desc',"{$offset},{$perpage}");
		
	}
	
	$theurl = "/home.php?mod=ucenter&do=shuoshuolist";
	if($_GET['uid']){
		$theurl .= "&uid=".$_GET['uid'];
	}
	
	
	//分页html
	$multi = multi($count, $perpage, $page, $theurl);		

//获取右侧数据

	$array = $center->getRightInfo($centeruid,$flag1);	
	
include template('diy:org/shuoshuolist');
