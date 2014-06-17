<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_activate.php 25756 2011-11-22 02:47:45Z zhangguosheng $
 *      邮件激活
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('NOROBOT', TRUE);
require libfile('function/member');
require libfile('class/member');
if($_GET['uid'] && $_GET['id']) {

	$member = getuserbyuid($_GET['uid']);
	if($member && $member['emailstatus'] == 0) {
		$member = array_merge(C::t('common_member_field_forum')->fetch($member['uid']), $member);
	} else {
		showmessage('activate_illegal', 'index.php');
	}
	list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);

	if($operation == 2 && $idstring == $_GET['id']) {
		
		$uid=$member['uid'];//获取激活用户信息
		if($member['groupid']==22)
		{
		include template('diy:user/reg_company_email_activate');//企业邮箱激活注册成功,提交信息认证。
		}else
		{
		$newgroup = C::t('common_usergroup')->fetch_by_credits($member['credits']);
		C::t('common_member')->update($member['uid'], array('groupid' =>"21", 'emailstatus' => '1'));
		C::t('common_member_field_forum')->update($member['uid'], array('authstr' => ''));
			 setloginstatus(array(
				'uid' => $uid,
				'username' => $member['username'],
				'password' => $member['password'],
				'groupid' =>"21",
			), 0);
			include_once libfile('function/stat');
			updatestat('register');
		include template('diy:user/reg_person_success');
		}
		//showmessage('activate_succeed', 'index.php', array('username' => $member['username']));
	} else {
		showmessage('activate_illegal', 'index.php');
	}

}elseif(submitcheck('formhash')) {
	//print_r($_POST);
$address= isset($_POST['address'])?daddslashes(trim($_POST['address'])):'';
$introduce= isset($_POST['introduce'])?daddslashes(trim($_POST['introduce'])):'';
$image='';
if ((($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 200000))
  {
  if ($_FILES["file"]["error"] > 0)
    {
      include template('diy:user/reg_company_email_activate');//传图失败，重新认证。
	  exit();
    }
  else
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
		move_uploaded_file($_FILES["file"]["tmp_name"], $folder."/" . $fileName);
		$image=$month."/" .$day."/". $fileName;
    }
  }
else
  {
	include template('diy:user/reg_company_email_activate');//传图失败，重新认证。
	exit();
  }
  $member = getuserbyuid($_GET['uid']);
  //DB::update('user_company',array('address' => $address,'introduce' =>$introduce,'image'=>$image),"`uid`='$member[uid]'");//取消机构表数据。
	C::t('common_member_profile')->update($member['uid'], array('address'=>$address,'field5'=>$introduce,'field4'=>$image));//设置用户类型为机构，且输入相关资料。
	$field=serialize(array('field5'=>$introduce,'field4'=>$image));//序列化数据。

	DB::insert('common_member_verify_info',array(//用户审核
											'vid'=>null,
											'uid' => $member['uid'],
											'username' => $member['username'],
											'verifytype' => 0,
											'flag' => 0,
											'field' =>$field,
											'dateline' => time()
										));//插入表

    DB::insert('common_member_verify',array(//用户审核
											'uid'=>$member['uid'],
											'verify1' =>0,
											'verify2' =>0,
											'verify3' =>0,
											'verify4' =>0,
											'verify5' =>0,
											'verify6' =>0,
											'verify7' =>0,
										));//插入表
		//机构激活。		
		$newgroup = C::t('common_usergroup')->fetch_by_credits($member['credits']);
		C::t('common_member')->update($member['uid'], array('groupid' => "22", 'emailstatus' => '1'));
		C::t('common_member_field_forum')->update($member['uid'], array('authstr' => ''));
					 setloginstatus(array(
				'uid' => $uid,
				'username' => $member['username'],
				'password' => $member['password'],
				'groupid' =>"22",
			), 0);
			include_once libfile('function/stat');
			updatestat('register');
include template('diy:user/reg_company_success');
}