<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_viewthread.php 7253 2010-03-31 09:27:33Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!$_G['uid']){
	showmessage('�����ȵ�½!','/login.html');	
}

$id=$_GET['id'];
$salary_arry=array('1000'=>'1000����','2000'=>"1000~2000Ԫ",'4000'=>"2000~4000Ԫ",'6000'=>"4000~6000Ԫ ","8000"=>"6000~8000Ԫ","10000"=>"8000~10000Ԫ","15000"=>"10000~15000Ԫ","25000"=>"15000~25000 Ԫ","25001"=>"25000����","-1"=>"����");
$sex_array=array('0'=>'����','1'=>'����','2'=>'Ů��','3'=>'���');
$method_array=array('0'=>'����','1'=>'��Ŀ����','2'=>'ȫְ��Ƹ');
$jobinfo = DB::fetch_first("SELECT *   FROM  ".DB::table('hr_recruitment')."   where   id=$id");
$jobinfo['date']=date('Y-m-d',$jobinfo['posttime']);
$jobinfo['endtime']=$jobinfo['endtime']!=0?date('Y-m-d',$jobinfo['endtime']):"δ��д";
$professor=$jobinfo['professor'];
$jobinfo['professor']=gettyeidname($jobinfo['professor']);
$uid=$jobinfo['uid'];
$count['now']=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime>UNIX_TIMESTAMP()");
$count['end']=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime<UNIX_TIMESTAMP()");
$count['vip']=DB::result_first("SELECT count(*)   FROM  ".DB::table('user_cooperation')."   where   invite_uid=$uid and cooperation_type=2");
$thisjobcount=DB::result_first("SELECT count(*)   FROM  ".DB::table('user_cooperation')."   where   invite_uid=$uid and cooperation_type=2 and jobid=$id");
 $space=DB::fetch_first("SELECT views FROM ".DB::table('common_member_count')." where uid=$uid");
$userinfo=DB::fetch_first("SELECT  *   FROM  ".DB::table('common_member_profile')."   where   uid=$uid ");
  $userinfo['click']= $space['views'];//�ռ��������ת��
$jobinfo['cpname']=getcpname($jobinfo['uid']);
//print_r($jobinfo);
//��ҵ��������Ϣ
$thread = getuserbyuid($uid, 1);
//print_r($thread);
space_merge($thread, 'count');
space_merge($thread, 'profile');
space_merge($thread, 'field_home');
//print_r($thread);
 $flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$uid);
//��ȡ����ְλ
if(strlen($thread['username'])>36){
		$thread['username']=cutstr($thread['username'],34);
}
$query = DB::query("SELECT *   FROM  ".DB::table('hr_recruitment')."    where  professor='$professor' order by posttime desc limit 0,8");
	while($job= DB::fetch($query)) {
	$job['url']="jobs/view_".$job['id'].".html";
    $likejobs[]=$job;
	}
	function gettyeidname($id)
	{
	if(is_numeric($id))
	{
	$typename = DB::fetch_first("SELECT  name  FROM ".DB::table('user_type')."  where id=$id ");
	}
	return $typename['name'];
	//print_r($typename);
	}
	
	function getcpname($uid)
	{
	if(is_numeric($uid))
	{
	$cpname= DB::fetch_first("SELECT  field2  FROM ".DB::table('common_member_profile')."  where uid=$uid ");
	}
	return $cpname['field2'];
	//print_r($typename);
	}
include template('diy:jobs/view');

?>
