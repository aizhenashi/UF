<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_activate.php 25756 2011-11-22 02:47:45Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);
require libfile('function/member');
require libfile('class/member');
if($_GET['uid'] && $_GET['id']) {

	$member = getuserbyuid($_GET['uid']);
	if($member && $member['groupid'] == 8) {
		$member = array_merge(C::t('common_member_field_forum')->fetch($member['uid']), $member);
	} else {
		showmessage('activate_illegal', 'index.php');
	}
	list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);

	if($operation == 2 && $idstring == $_GET['id']) {
		$newgroup = C::t('common_usergroup')->fetch_by_credits($member['credits']);
		//C::t('common_member')->update($member['uid'], array('groupid' => $newgroup['groupid'], 'emailstatus' => '1'));
		//C::t('common_member_field_forum')->update($member['uid'], array('authstr' => ''));
		//$user_company=C::t('common_member_field_forum')->fetch($member['uid']);
		$uid=$member['uid'];
		$user_company = DB::fetch_first("SELECT * FROM ".DB::table('user_company')." WHERE uid='$uid' ");
				setloginstatus(array(
				'uid' => $uid,
				'username' => $member['username'],
				'password' => $member['password'],
				'groupid' => $member['groupid'],
			), 0);
			include_once libfile('function/stat');
			updatestat('register');
		
		if(!empty($user_company))
		{
		include template('diy:hr/reg_company_email_activate');//企业邮箱激活注册成功,提交信息认证。
		}else
		{
		include template('diy:hr/reg_person_success');
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
if ((($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 20000))
  {
  if ($_FILES["file"]["error"] > 0)
    {
      include template('diy:hr/reg_company_email_activate');//传图失败，重新认证。
	  exit();
    }
  else
    {
		$fileName = md5(rand()*10000000).'.jpg';
		$folder='data/attachment/hr/'.date('Y-m-d');
		if (!file_exists($folder)) {
			@mkdir($folder, 0777);
		}
		move_uploaded_file($_FILES["file"]["tmp_name"], $folder."/" . $fileName);
		$image=$folder."/" . $fileName;
    }
  }
else
  {
	include template('diy:hr/reg_company_email_activate');//传图失败，重新认证。
	exit();
  }
  $member = getuserbyuid($_GET['uid']);
  DB::update('user_company',array('address' => $address,'introduce' =>$introduce,'image'=>$image),"`uid`='$member[uid]'");
include template('diy:hr/reg_company_success');
}