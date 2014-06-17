<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: misc_report.php 25246 2011-11-02 03:34:53Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = intval($_GET['uid']);
$name = $_GET['name'];
if(empty($_G['uid'])) {
	showmessage('not_loggedin', null, array(), array('login' => 1));
}
if($_G['groupid']==22&&$_GET['invitetype']==2) {
	showmessage('您是机构会员不能申请职位', null, array(), array('alert' => 'error'));
}
if($_GET['invitetype']==2&&$_GET['jobid']>0) {
$jobinfo=DB::fetch_first("SELECT sex FROM ".DB::table('hr_recruitment')." WHERE id=".$_GET['jobid'] );
//print_r($jobinfo);
if($jobinfo['sex']!=0)
{
$userinfo=DB::fetch_first("SELECT gender FROM ".DB::table('common_member_profile')." WHERE uid=".$_G['uid'] );
if($jobinfo['sex']!=$userinfo['gender'])
{
	showmessage('您的申请不符合工作的性别要求', null, array(), array('alert' => 'error'));
}
}

}
$invite = DB::fetch_first("SELECT * FROM ".DB::table('user_cooperation')." WHERE invite_uid=".$_G['gp_inviteId']." and cooperation_type=1 and cooperation_uid=".$_G['uid'] );
if(!empty($invite)&&$_GET['invitetype']==1)
{
		include template('user/invit3');
	   exit;

}

$invite2 = DB::fetch_first("SELECT * FROM ".DB::table('user_cooperation')." WHERE cooperation_uid=".$_G['uid']." and cooperation_type=2 and jobid='".$_GET['jobid']."'" );

if(!empty($invite2)&&$_GET['invitetype']==2)
{
		include template('user/invit3');
	   exit;

}

if(!submitcheck('reportsubmit')) {
		if($_G['uid'] == $_G['gp_inviteId']){
			showmessage('should_not_invite_your_own');
		}
	}else{
	
	if($_GET['invitetype']==1)
	{
		DB::query("INSERT INTO ".DB::table("user_cooperation")." SET invite_uid=".$_G['gp_inviteId'].", cooperation_uid=".$_G['uid'].",cooperation_type=".$_G['gp_invitetype'].",post_time=".time().",cooperation_content='".dhtmlspecialchars(trim($_G['gp_content']))."', read_flag='0', agree_flag='0'");
          // echo 22222222;
	}
	elseif($_GET['invitetype']==2&&!empty($_GET['jobid']))
	{
	DB::query("INSERT INTO ".DB::table("user_cooperation")." SET invite_uid=".$_G['gp_inviteId'].", cooperation_uid=".$_G['uid'].",cooperation_type=".$_G['gp_invitetype'].",post_time=".time().",cooperation_content='".dhtmlspecialchars(trim($_G['gp_content']))."', read_flag='0', agree_flag='0',jobid='".$_GET['jobid']."'");
	}

	//Send mail.

	require libfile('function/mail');
	$member = getuserbyuid($_G['gp_inviteId']);
	$m = getuserbyuid($_G['uid']);

	//print_r($member);
	
	$email_cooperation_message = lang('email', 'email_cooperation_message'.$_GET['invitetype'], array(
		'name' => $member['username'],
		'm' => $m['username'],
		'cooperation_content' => dhtmlspecialchars(trim($_G['gp_content'])),
		'bbname' => $_G['setting']['bbname'],
		'url' => 'http://www.uestar.net',
	));
	/*
	if(!sendmail("寒川 <admin@huikon.cn>", lang('email', 'email_cooperation_subject'.$_GET['invitetype']), $email_cooperation_message)) {
		runlog('sendmail', "admin@huikon.cn sendmail failed.");
	}
	*/
	if(!sendmail("$member[username] <$member[email]>", lang('email', 'email_verify_subject'), $email_verify_message)) {
		runlog('sendmail', "$member[email] sendmail failed.");
	}
	

		include template('user/invit2');
	   exit;
	}

include template('user/invit');
?>