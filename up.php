<?php
require 'source/class/class_core.php';
require_once("source/class/image.class.php");
$discuz = & discuz_core::instance();
$identifier = '98';


$discuz->init();
if($_G['uid']==0)
{
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
    $uid=$_G['uid'];
	

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$images = new Images("file");
if ($_POST['act'] == 'cut'){	
	$image = $_POST['filename'];
	$res = $images->thumb($image,false,1,'jpg');
	if($res !='false')
	{
		
//找到黑色图片的原因 后就打开		
//	unlink($image);
foreach($res as  $key =>$value)
	{

		$path = get_avatar($uid,$key);
		echo $path;
		$dirPath =dirname($path);
		$file =$path;  
		if(!file_exists($dirPath))  
		{  
			mkdir($dirPath, 0755, true);  
		}  
      	$img =file_get_contents($value);  
	//	echo $img;
      	$fp = fopen($file, 'w');  
      	fwrite($fp, $img);  
		fclose($fp);  
//找到黑色图片的原因后 就打开unlink		
//       unlink($value);	
	}
		
	DB::query("update ".DB::table('common_member_profile')." set   isavatar=1  where uid=$uid ");
	}
}elseif(isset($_POST['act']) && $_POST['act'] == "upload"){
	
	$path = $images->move_uploaded();
	$images->thumb($path,false,0,'jpg');							//文件比规定的尺寸大则生成缩略图，小则保持原样
	if($path == false){
		$images->get_errMsg();
	}else{
	$json=array('result'=>'1','size'=>'1','img'=>$path,'w'=>$width,'h'=>$height);
	echo json_encode($json);
    // echo "<script>window.parent.doJcrop($path) </script>";
	 // echo $path;
	  exit();
		//echo "上传成功！<a href='".$path."' target='_blank'>查看</a>";
	}
}

	function get_avatar($uid, $size = 'middle', $type = '') {
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';
	return 'uc_server/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
}