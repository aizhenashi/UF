<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//根据后台设置判断游客是否可以发信息
//增加DZ的防灌水验证
cknewuser();
require_once libfile('function/hr');
if(empty($_G['uid'])) {
	showmessage('not_loggedin', 'login.html');
}

$uid=$_G['uid'];
$group= DB::fetch_first("SELECT groupid  FROM ".DB::table('common_member')." WHERE uid='$uid' ");
$group=$group['groupid'];
if($_GET['profilesubmit']=='true'){

//$introduce=$_GET['introduce'];
$introduce_str = serialize($_GET['introduce']);//序列化

$notice="";
$image=array();
$intro = DB::fetch_first("SELECT uid, introduce, introduceimg  FROM ".DB::table('common_member_profile')." WHERE uid='$uid' ");
$imgarrt=unserialize($intro['introduceimg']);

foreach($_FILES['img'] ['tmp_name']as $key=>$value)
{

if ((($_FILES['img'] ['type'][$key] == "image/x-png") || ($_FILES['img'] ['type'][$key] == "image/gif") || ($_FILES['img'] ['type'][$key] == "image/jpeg") || ($_FILES['img'] ['type'][$key] == "image/pjpeg")) && ($_FILES['img'] ['size'][$key] < 2000000))
  {
  if ($_FILES["img"]["error"][$key] == 0)
    {
     
		$fileName = md5(rand()*10000000).'.jpg';
		$month=date('Ym');
		$day=date('d');
		$folder='data/attachment/profile/'.$month;
		if (!file_exists($folder)) {
			@mkdir($folder, 0777);
		}
		$folder .='/'.$day;
		if (!file_exists($folder)) {
			@mkdir($folder, 0777);
		}
		$savepath=$folder."/" . $fileName;
		move_uploaded_file($_FILES["img"]["tmp_name"][$key], $savepath);
		//$image=$savepath;
		@unlink($imgarrt[$key]);
		$image[]=$savepath;
    }
  }
else
  {

	$key2=$key+1;
	$notice.="第".$key2."张图片上传失败，请重新上传。<br />";
	$image[]=$imgarrt[$key];//上传失败的图片采用前一次的图片。

  }
}
$imgstr = serialize($image);
$spaceinfo = isset($_POST['spaceinfo'])?daddslashes(trim($_POST['spaceinfo'])):'';
DB::query("UPDATE ".DB::table('common_member_profile')." SET introduce='$introduce_str',spaceinfo='$spaceinfo', introduceimg='$imgstr'  WHERE uid='$uid' ");
showmessage('更新成功', 'user.php?mod=introduce');
}else
{
			$intro = DB::fetch_first("SELECT uid,spaceinfo, introduce, introduceimg  FROM ".DB::table('common_member_profile')." WHERE uid='$uid' ");
			$introarry=unserialize($intro['introduce']);
			$spaceinfo=$intro['spaceinfo'];
			$imgarrt=unserialize($intro['introduceimg']);
			
}
include template('diy:user/introduce');