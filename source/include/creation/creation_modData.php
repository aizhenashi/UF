<?php
global $_G;
if(empty($_G['uid']))
{
	header("Location:login.html");
}
$uid = $_G["uid"];
//取得个人信息
$res=DB::query("select * from ".DB::table('common_member_profile')." where uid=$uid");
while($row=DB::fetch($res)){
	$data=$row;	
}
$data['bio'] = str_replace(chr(10),'<br>',$data['bio']);
//显示用户数据
$sql = "select * from pre_common_account where uid=".$uid;
$result = DB::query($sql);
$row = DB::fetch($result);
if($row)
{
	$userName1 	= $row['userName'];
	$idCode1 	= $row['idCode'];
	$bankName1	= $row['bankName'];
	$bankCode1	= $row['bankCode'];
	$telPhone1	= $row['telPhone'];
	$sex1 		= $row['sex'];
	$address1	= $row['address'];
}
//更新用户数据
if($uid)
{
	if($_GET["do1"]==1){
	$userName = $_POST["userName"];
	$idCode=  $_POST["idCode"];
	$bankName=$_POST["bankName"];
	$bankCode=$_POST["bankCode"];
	$telPhone=$_POST["telPhone"];
	$sex = $_POST["sex"];
	$address=$_POST["address"];
	$updateTime = time();
	if($userName!=null){
				$sql = "select * from pre_common_account where uid=".$uid;
				$result = DB::query($sql);
				$row = DB::fetch($result);
				if($row){
					$sql1 ="update pre_common_account set userName='".$userName."',idCode='".$idCode."',bankName='".$bankName."',bankCode='".$bankCode."',telPhone='".$telPhone."',sex='".$sex."',address='".$address."',updateTime='".$updateTime."' where uid='".$uid."'";
					DB::query($sql1);
					echo "<script language='javascript'>";
					echo "window.location='creation.php?do=account';";
					echo "</script>";
					exit;
				}else{
				$sql="insert into pre_common_account(userName,idCode,bankName,bankCode,telPhone,sex,address,updateTime,uid)values('$userName','$idCode','$bankName','$bankCode','$telPhone','$sex','$address',$updateTime,$uid) ";
				$row = DB::query($sql);
				echo "<script language='javascript'>";
				echo "window.location='creation.php?do=account';";
				echo "</script>";
				exit;
			}		
		}
	}
}

include template('diy:creation/modData');
?>