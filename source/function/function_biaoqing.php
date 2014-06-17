<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 *  获取所有表情
 */
function getAllBiaoQIng(){

//array 表情组 all
	$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();
	
//array 所有表情
	$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
	
	return array('group'=>$BiaoQingGroupData,'biaoqing'=>$AllBiaoQingData);
	
}
?>