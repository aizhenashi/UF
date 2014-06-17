<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$actionId = $_GET["actionId"];

$sqlClick="update pre_common_action set clickTimes =clickTimes+1 where actionId=$actionId";
$row = DB::query($sqlClick);
global $_G;
$sql ="select * from pre_common_action where actionId=$actionId";
$rs = DB::query($sql);
$activeInfo = DB::fetch($rs);
$actMark = explode(" ",$activeInfo["actMark"]);
$actMark = array_filter($actMark);
$activeInfo['startime'] = date('Y.m.d',$activeInfo['startime']);
$activeInfo['endtime'] = date('Y.m.d',$activeInfo['endtime']);
if($activeInfo['mimi']==1)
{
	$activeInfo['telPhone']="保密";
}
if($_POST['ajaxContent']==true)
{ 
 
  if($_G["uid"])
  {
		$userId=$_G["uid"];
		$sql1= "select * from pre_common_priase where actionId=$actionId and userId=$userId";
		$likeContent = DB::fetch_all($sql1);
		//print_r($likeContent);exit;
		if(!$likeContent){
			$sql = "insert into pre_common_priase(actionId,userId)values($actionId,$userId)";
			$row = DB::query($sql);	
		}
		else
		{
			echo "<script language='javascript'>";
			echo "alert('每个用户只能点击一次');";
			echo "</script>";
			exit;
		}	
  }else
	{
		echo "<script language='javascript'>";
		echo "alert('请登录后点击');";
		echo "</script>";
		exit;
	}
	include template("diy:ajax/active/headImg");
	exit;
}
$sql1 = "select * from pre_common_priase where actionId=$actionId order by times desc limit 0,12";
$likeUser = DB::fetch_all($sql1);
$lastSql = "select * from pre_common_action order by times desc limit 0,2";
$lastArr = DB::fetch_all($lastSql);

//array 表情组 all
$BiaoQingGroupData = c::t('liaotian_biaoqing_group')->getAllBiaoqingGroup();
//array 所有表情
$AllBiaoQingData = c::t('liaotian_biaoqing')->getBiaoQingForGroupArray($BiaoQingGroupData);
$pingluns = c::t('common_reviews')->select_pinglun("actionId = '{$_GET['actionId']}' order by id desc limit 10");	
include template('diy:active/info');
?>