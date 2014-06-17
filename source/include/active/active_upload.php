<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $_G;
//ECHO $_G['siteroot'];
if(empty($_G['uid'])){
		//showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
		header("Location:/login.html");
}
if(!$_POST){	
	include template('diy:active/upload');
	die();
}
if($_POST['ajaxgetcontent1'] == 'false'){
	echo "不正确";
    exit;
}elseif($_POST['ajaxgetcontent1'] == 'true')
{
	echo "正确";
	exit;
}
if($_POST['ajaxgetcontent2']=='false')
{
	echo "标签不正确";
	exit;
}
//制作缩略图
if($_GET['do1'] == 'uppic')
{
	$upload = new discuz_upload();
	$FILE = $_FILES['upfile'];
	$upload->init($FILE, 'active');
	if($upload->error()) {
		return lang('spacecp', 'lack_of_access_to_upload_file_size');
	}
	if(!$upload->attach['isimage']) {
		return lang('spacecp', 'only_allows_upload_file_types');
	}	
	if(!$upload->attach['isimage']) {
		return lang('spacecp', 'only_allows_upload_file_types');
	}
	$upload->save();
	if($upload->error()) {
		return lang('spacecp', 'mobile_picture_temporary_failure');
	}
	if(!$upload->attach['imageinfo'] || !in_array($upload->attach['imageinfo']['2'], array(1,2,3,6))) {
		@unlink($upload->attach['target']);
		return lang('spacecp', 'only_allows_upload_file_types');
	}	
	//用于缩略图
	$new_name = $upload->attach['target'];
	//制作缩略图
    require_once libfile('class/image');
	$image = new image();
	//生成 w 235,118,256,160的小图
	$nowdir = dirname($new_name); //原图所在目录
	//拼接235，118，256,160所在的目录
    $nowdir235 = dirname($new_name).'/235/';
    $nowdir118 = dirname($new_name).'/118/';
    $nowdir256 = dirname($new_name).'/256/';
    $nowdir160 = dirname($new_name).'/160/';
	$arr = explode('/',$new_name);
	$filename = $arr[count($arr)-1];
    $new_name235 =  $nowdir235.$filename;
    $new_name118 =  $nowdir118.$filename;
	$new_name256 =  $nowdir256.$filename;
	$new_name160 =  $nowdir160.$filename;
	$start235 = strrpos($new_name235,'.','-5');
	$start118 = strrpos($new_name118,'.','-5');
	$start256 = strrpos($new_name256,'.','-5');
	$start160 = strrpos($new_name160,'.','-5');	
	$new_name235 = substr($new_name235, $start235+2);
	$new_name118 = substr($new_name118, $start118+2);
	$new_name256 = substr($new_name256, $start256+2);
	$new_name160 = substr($new_name160, $start160+2);
	$info = getimagesize($new_name);
	if($info[0] > 235){
		$tw235 = 235;
		$th235 = 360;
	}else{
		$tw235 = $info[0];
		$th235 = $info[0];
	}
	if($info[0] > 118){
		$tw118 = 118;
		$th118 = 169;
	}else{
		$tw118 = $info[0];
		$th118 = $info[0];
	}
	if($info[0] > 256){
		$tw256 = 256;
		$th256 = 376;
	}else{
		$tw256 = $info[0];
		$th256 = $info[0];
	}
	if($info[0] > 160){
		$tw160 = 160;
		$th160 = 235;
	}else{
		$tw160 = $info[0];
		$th160 = $info[0];
	}	
	$result235 = $image->Thumb($new_name, $new_name235, $tw235, $th235, 1);
	$result118 = $image->Thumb($new_name, $new_name118, $tw118, $th118, 1);
	$result256 = $image->Thumb($new_name, $new_name256, $tw256, $th256, 1);
	$result160 = $image->Thumb($new_name, $new_name160, $tw160, $th160, 1);
	//存库的文件
	$filename = $upload->attach['attachment']; //原图的路径
	$temp = explode('/',$filename);
	$tempfilename = array_pop($temp);
	$thumb235 = implode('/',$temp).'/235/'.$tempfilename; //宽 235 的缩略图路径
	$thumb118 = implode('/',$temp).'/118/'.$tempfilename; //宽 118 的缩略图路径
	$thumb256 = implode('/',$temp).'/256/'.$tempfilename; //宽 256 的缩略图路径
	$thumb160 = implode('/',$temp).'/160/'.$tempfilename; //宽 160 的缩略图路径
	if($filename){		
		echo "<script>window.parent.document.getElementById('img1').src = '/data/attachment/active/".$thumb235."'</script>";
		echo "<script>window.parent.document.getElementById('picfile').value = '".$filename."'</script>";
		echo "<script>window.parent.document.getElementById('picthumb235').value = '/data/attachment/active/".$thumb235."'</script>";
		echo "<script>window.parent.document.getElementById('picthumb118').value = '/data/attachment/active/".$thumb118."'</script>";
		echo "<script>window.parent.document.getElementById('picthumb160').value = '/data/attachment/active/".$thumb160."'</script>";
		echo "<script>window.parent.document.getElementById('picthumb256').value = '/data/attachment/active/".$thumb256."'</script>";
	}
}
//接收提交的活动详情信息
$picthumb160 = $_POST["picthumb160"];
$picthumb256 = $_POST["picthumb256"];
$picthumb235 = $_POST["picthumb235"];
$picthumb118 = $_POST["picthumb118"];
$imagePath = $_POST["picfile"];
$imagePath  = "/data/attachment/active/".$imagePath;
$fristClass = $_POST["fristClass"];
$secondClass = $_POST["secondClass"];
$title = $_POST["title"];
$startTime = $_POST["startTime"];
$startTime = strtotime($startTime); //timestamp
$endTime = $_POST["endTime"];
$endTime = strtotime($endTime);//timestamp			
$province = $_POST['nativeplace_top']; //省
$area = $_POST['nativeplace_son']; //市
$everyTime = $_POST["everyTime"];
$address = $_POST["address"];
$text = $_POST["renews"];
$telPhone = $_POST["tel"];
$fei = $_POST["fei"];
if($_POST["baomi"]==1)
{
	$baomi = $_POST["baomi"];
}else{
	$baomi = 0;
}
$userName = $_G[member][username];
if($fei == 1){
	$prize = $_POST["prize"];
}else{
	$prize = "免费";
}
$mark = $_POST["mark"];
//判断是否登录如果未登录，不允许发布活动
$sql = "insert into pre_common_action(fristclassname,secondclassname,actiontitle,startime,endtime,eveAction,priAddress,address,dtailAddress,dtail,telPhone,feiType,fei,actMark,imagePath,picthumb118,picthumb235,picthumb256,picthumb160,mimi,username)values('$fristClass','$secondClass','$title','$startTime','$endTime','$everyTime','$province','$area','$address','$text','$telPhone','$fei','$prize','$mark','$imagePath','$picthumb118','$picthumb235','$picthumb256','$picthumb160',$baomi,'$userName')";       
if($fristClass != "请添加" && $fristClass != "" )
{
	if($_G["uid"])
	{
	    $row = DB::query($sql);
	    echo "<script language='javascript'>";
	    echo "window.location='active.php?do=search1';";
	    echo "</script>";
	    exit;
	}else
	{
		
		echo "<script language='javascript'>";
		echo "alert('请登录后发布活动');";
		echo "window.location='active.php?do=search1';";
		echo "</script language='javascript'>";
	    exit;
	}
}
?>