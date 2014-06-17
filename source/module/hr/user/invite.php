<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: misc_invite.php 32494 2013-01-29 08:09:58Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/core');
$uid=$_G['uid'];
$page = empty($_GET['page'])?1:intval($_GET['page']);
$perpage = 10;
$start = ($page-1)*$perpage;
if($_G['uid']==0)
{
	showmessage('not_loggedin');

}elseif($_G['gp_action'] == "list"){
if($_G['gp_atype']==1)
{

$condition = " cooperation_uid=$uid ";

	$invite_list = array();
	if ($_G['gp_type']){
		$condition .= " AND cooperation_type = ".$_G['gp_type'];
	}
	if ($_G['gp_read'] == "1"){
		$condition .= " AND read_flag = '1'";
	}elseif($_G['gp_read'] == "0"){
		$condition .= " AND read_flag = '0'";
	}else{
		$condition .= " ";
	}
	$invite_query = DB::query("SELECT c.*, m.uid,m.username, m.email, mp.telephone, mp.mobile FROM ".DB::table('user_cooperation')." c LEFT JOIN ".DB::table('common_member')." m ON c.invite_uid=m.uid LEFT JOIN ".DB::table('common_member_profile')." mp on c.invite_uid=mp.uid WHERE  " .$condition." order by post_time desc");
$ud=0;
	while($invite_row = DB::fetch($invite_query)){

		$invite_list[$ud] = $invite_row;
	   $invite_list[$ud]['jobname']=getjobname($invite_row['jobid']);
		$ud++;
	}

}
else
{


	$condition = " invite_uid=$uid ";

	$invite_list = array();
	if ($_G['gp_type']){
		$condition .= " AND cooperation_type = ".$_G['gp_type'];
	}
	if ($_G['gp_read'] == "1"){
		$condition .= " AND read_flag = '1'";
	}elseif($_G['gp_read'] == "0"){
		$condition .= " AND read_flag = '0'";
	}else{
		$condition .= " ";
	}
	$nums=DB::fetch_first("SELECT count(*) as num FROM ".DB::table('user_cooperation')." c LEFT JOIN ".DB::table('common_member')." m ON c.cooperation_uid=m.uid LEFT JOIN ".DB::table('common_member_profile')." mp on c.cooperation_uid=mp.uid WHERE  " .$condition);
	$count=$nums['num'];
	$invite_query = DB::query("SELECT c.*,m.uid,m.username, m.email, mp.telephone, mp.mobile FROM ".DB::table('user_cooperation')." c LEFT JOIN ".DB::table('common_member')." m ON c.cooperation_uid=m.uid LEFT JOIN ".DB::table('common_member_profile')." mp on c.cooperation_uid=mp.uid WHERE  " .$condition." order by post_time desc");
$ud=0;
	while($invite_row = DB::fetch($invite_query)){
		$invite_list[1] = $invite_row;
		$invite_list[1]['jobname']=getjobname($invite_row['jobid']);
		$ud++;
	}
    $multi = multi($count, $perpage, $page, "user.php?mod=invite&action=list&type=2");
  

}
	include template('diy:user/invite_list');

}elseif($_G['gp_action']){
	DB::query("UPDATE ".DB::table("user_cooperation")." SET ".$_G['gp_action']."_flag='1' WHERE id=".$_G['gp_id']);
	showmessage( '更新成功', "home.php?mod=ucenter&do=invite");
}else{
	if(!submitcheck('invitesubmit')) {
		if($_G['uid'] == $_G['gp_inviteId']){
			showmessage('should_not_invite_your_own');
		}
		include template('user/invite');
	}else{
		DB::query("INSERT INTO ".DB::table("user_cooperation")." SET invite_uid=".$_G['uid'].", cooperation_uid=".$_G['gp_inviteId'].",cooperation_type=".$_G['gp_type'].",post_time=".time().",cooperation_content='".dhtmlspecialchars(trim($_G['gp_content']))."', read_flag='0', agree_flag='0'");

		showmessage(lang('hr/template', 'invite_success'), "test.htm");
	}
}
function getjobname($id)
{
if($id==0)
{ return ;
}
$job = DB::fetch_first("SELECT title FROM ".DB::table('hr_recruitment')." WHERE id=".$id);
return $job['title'];
}
?>