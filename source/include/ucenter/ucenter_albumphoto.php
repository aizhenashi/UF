<?php
	//��ǰ��Ƭid
	$picid = $_GET['picid'];
	
	//uid �������˿�
	//û��uid ���� �Լ���
//array ������ all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();

//array ���б���
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
//��ȡ���id
	$datas = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");
	$data = $datas[0];
	$albumid = $data['albumid'];
	
//�����Ϣ	
	$datas = c::t('home_album')->fetchAll("albumid = '{$albumid}'");
	$albuminfo = $datas[0];

	
	
	include template('diy:ucenter/albumphoto');
?>