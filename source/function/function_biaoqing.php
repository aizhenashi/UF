<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 *  ��ȡ���б���
 */
function getAllBiaoQIng(){

//array ������ all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();
	
//array ���б���
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
	return array('group'=>$BiaoQingGroupData,'biaoqing'=>$AllBiaoQingData);
	
}
?>