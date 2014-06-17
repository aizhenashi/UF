<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_lostpasswd.php 31164 2012-07-20 07:50:57Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

$discuz_action = 141;

if(submitcheck('lostpwsubmit')) {
	loaducenter();
	$_GET['email'] = strtolower(trim($_GET['email']));
	if($_GET['username']) {
		list($tmp['uid'], , $tmp['email']) = uc_get_user(addslashes($_GET['username']));
		$tmp['email'] = strtolower(trim($tmp['email']));
		if($_GET['email'] != $tmp['email']) {
			showmessage('getpasswd_account_notmatch');
		}
		$member = getuserbyuid($tmp['uid'], 1);
	} else {
		$emailcount = C::t('common_member')->count_by_email($_GET['email'], 1);
		if(!$emailcount) {
			showmessage('lostpasswd_email_not_exist');
		}
		if($emailcount > 1) {
			showmessage('lostpasswd_many_users_use_email');
		}
		$member = C::t('common_member')->fetch_by_email($_GET['email'], 1);
		list($tmp['uid'], , $tmp['email']) = uc_get_user(addslashes($member['username']));
		$tmp['email'] = strtolower(trim($tmp['email']));
	}
	if(!$member) {
		showmessage('getpasswd_account_notmatch');
	} elseif($member['adminid'] == 1 || $member['adminid'] == 2) {
		showmessage('getpasswd_account_invalid');
	}

	$table_ext = $member['_inarchive'] ? '_archive' : '';
	if($member['email'] != $tmp['email']) {
		C::t('common_member'.$table_ext)->update($tmp['uid'], array('email' => $tmp['email']));
	}

	$idstring = random(6);
	C::t('common_member_field_forum'.$table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
	require_once libfile('function/mail');
	$get_passwd_subject = lang('email', 'get_passwd_subject');
	$get_passwd_message = lang(
		'email',
		'get_passwd_message',
		array(
			'username' => $member['username'],
			'bbname' => $_G['setting']['bbname'],
			'siteurl' => $_G['siteurl'],
			'uid' => $member['uid'],
			'idstring' => $idstring,
			'clientip' => $_G['clientip'],
		)
	);
	if(!sendmail("$_GET[username] <$tmp[email]>", $get_passwd_subject, $get_passwd_message)) {
		runlog('sendmail', "$tmp[email] sendmail failed.");
	}
	//showmessage('getpasswd_send_succeed', $_G['siteurl'], array(), array('showdialog' => 1, 'locationtime' => true));
	$step = 2;
	$email_type = explode('@', $_GET['email']);
			$email_type =$email_type[1];
			switch($email_type)
			{
				case "qq.com":
					$email_url="http://mail.qq.com";
				break; 
				case "163.com":
					$email_url="http://mail.163.com";
				break;
				case "126.com":
					$email_url="http://mail.126.com";
				break;
				case "139.com":
					$email_url="http://mail.139.com";
				break; 
				case "sina.com":
					$email_url="http://mail.sina.com";
				break; 
				case "yahoo.com":
					$email_url="http://mail.sohu.com";
				break; 
				case "gmail.com":
					$email_url="http://www.gmail.com";
				break;
				case "tom.com":
					$email_url="http://mail.tom.com";
				break; 
				case "hotmail.com":
					$email_url="http://mail.hotmail.com";
				break; 
				default:
					$email_url="";
				}
	include template('member/lostpasswd');
}else{
   $step=1;
  include template('member/lostpasswd');
}

?>