<?php

//�ռ�����uid
$spaceuid = $centeruid;

//array ������ all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();
	
//array ���б���
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
	if($spaceuid == $_G['uid']){
		//ȫ��˵˵
		//��ȡ�ҵĹ�ע�б�
		
		$uidarr = c::t('home_follow')->getdata('`followuid`',"uid = '{$spaceuid}'");
		$uidarr[] = array('followuid'=>$spaceuid);
	
		//��ȡ uid����
		$uidstr = "";
		foreach ($uidarr as $uid){
			$uidstr .= $uid['followuid'].',';
		}
		$uidstr = rtrim($uidstr,',');
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid in ({$uidstr}) && fid = 0");
	}else{
		//�ҵ�˵˵
		$uidstr = rtrim($uidstr,',');
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"uid = '{$spaceuid}' && fid = 0");
		
	}
	
//��ȡ�ռ���
	$spacebk = c::t('myspace_bankuai')->get_myspace_bkinfo($spaceuid);


//��ȡ�ռ�����
	$spacehuifus = c::t('home_space_liuyan')->select_liuyan("spaceuid = '{$spaceuid}' order by id desc limit 10");	
	//var_dump($spacehuifus);
//��ȡ�Ҳ�����

	$array = $center->getRightInfo($centeruid,$flag1);	

	//var_dump($array['pic_info']);
	
include template('diy:ucenter/index');
