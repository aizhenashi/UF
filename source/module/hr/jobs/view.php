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
	showmessage('请您先登陆!','/login.html');	
}

$id=$_GET['id'];
$salary_arry=array('1000'=>'1000以下','2000'=>"1000~2000元",'4000'=>"2000~4000元",'6000'=>"4000~6000元 ","8000"=>"6000~8000元","10000"=>"8000~10000元","15000"=>"10000~15000元","25000"=>"15000~25000 元","25001"=>"25000以上","-1"=>"面议");
$sex_array=array('0'=>'不限','1'=>'男性','2'=>'女性','3'=>'组合');
$method_array=array('0'=>'不限','1'=>'项目合作','2'=>'全职招聘');
$jobinfo = DB::fetch_first("SELECT *   FROM  ".DB::table('hr_recruitment')."   where   id=$id");
$jobinfo['date']=date('Y-m-d',$jobinfo['posttime']);
$jobinfo['endtime']=$jobinfo['endtime']!=0?date('Y-m-d',$jobinfo['endtime']):"未填写";
$professor=$jobinfo['professor'];
$jobinfo['professor']=gettyeidname($jobinfo['professor']);
$uid=$jobinfo['uid'];
$count['now']=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime>UNIX_TIMESTAMP()");
$count['end']=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime<UNIX_TIMESTAMP()");
$count['vip']=DB::result_first("SELECT count(*)   FROM  ".DB::table('user_cooperation')."   where   invite_uid=$uid and cooperation_type=2");
$thisjobcount=DB::result_first("SELECT count(*)   FROM  ".DB::table('user_cooperation')."   where   invite_uid=$uid and cooperation_type=2 and jobid=$id");
 $space=DB::fetch_first("SELECT views FROM ".DB::table('common_member_count')." where uid=$uid");
$userinfo=DB::fetch_first("SELECT  *   FROM  ".DB::table('common_member_profile')."   where   uid=$uid ");
  $userinfo['click']= $space['views'];//空间访问数量转换
$jobinfo['cpname']=getcpname($jobinfo['uid']);
//print_r($jobinfo);
//企业或艺人信息
$thread = getuserbyuid($uid, 1);
//print_r($thread);
space_merge($thread, 'count');
space_merge($thread, 'profile');
space_merge($thread, 'field_home');
//print_r($thread);
 $flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$uid);
//获取其他职位
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
