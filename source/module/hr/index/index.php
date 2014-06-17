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

$uid=$_G['uid']?$_G['uid']:'0';
$jobcount = DB::result_first("SELECT count(*) FROM ".DB::table('hr_recruitment ')." where uid=$uid and verify=1 ");
$uhcount = DB::result_first("SELECT count(*) FROM ".DB::table('home_doing ')." where uid=$uid  ");
$invitcount= DB::result_first("SELECT count(*) FROM ".DB::table('user_cooperation ')." where cooperation_uid=$uid or invite_uid=$uid  and cooperation_type=1 ");
$invitejobcount= DB::result_first("SELECT count(*) FROM ".DB::table('user_cooperation ')." where cooperation_uid=$uid and cooperation_type=2 ");


//获取热门关键词
   $query=DB::query("SELECT keyword FROM ".DB::table('hr_search_keywords')."  order by count desc limit 0,5");   
  while($topkeyword= DB::fetch($query)) {
      $topkeywords[]=$topkeyword;
	}
//获取全部会员，优艺新势力
	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid=m.uid where m.groupid=21 and p.isavatar=1 and m.uid=18958 or m.uid=18051 or m.uid=18049 or m.uid=18655 or m.uid=17742 group by u.uid order by p.indexsort asc limit 0,5");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users[$jobi]=$user;
    $users[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	}
	
//获取全部会员，优艺最活力



 	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join  ".DB::table('home_pic')." h on h.uid=m.uid left join ".DB::table('common_member_count')." c on c.uid=m.uid  left join ".DB::table('home_docomment ')."  d on d.uid=m.uid left join ".DB::table('home_doing')." o  on o.uid =m.uid  left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21 and p.isavatar=1 and m.uid=17580 or m.uid=16094 or m.uid=9172 or m.uid=8935 or m.uid=8934 group by u.uid order by p.indexsort asc limit 0,5");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_2[$jobi]=$user;
    $users_2[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	} 
	
	
	

	//print_r($userall_3);
//获取影视类会员，优艺新势力
		$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,t.topid,v.verify6, COUNT(*) as count  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21 and p.isavatar=1 and m.uid in(9036,9037,9039,9033,9020) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users3[$jobi]=$user;
    $users3[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	}
//获取影视会员，优艺最给力


  	$query = DB::query("SELECT m.`uid`,m.`username`,p.`url`,c.`views`,p.`praise`, c.`follower`,v.verify6 FROM ".DB::table('common_member')."  m left join  ".DB::table('common_member_profile')." p  on m.uid=p.uid left join   ".DB::table('common_member_count')." c on p.uid=c.uid  left join ".DB::table('user_actor_type')."  u on u.uid= m.uid left join  ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21 and p.isavatar=1 and m.uid in(9148,17580,17637,2060,7968) and   c.`uid`=p.`uid` group by m.uid order by p.uid asc LIMIT 5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_3_1[$jobi]=$user;
    $users_3_1[$jobi]['job']=gettyename($user['uid']);
	//print_r( $user_1_3[$jobi]);
    $jobi++;
	}  
	
//获取影视会员，优艺最活力



 	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join  ".DB::table('home_pic')." h on h.uid=m.uid left join ".DB::table('common_member_count')." c on c.uid=m.uid  left join ".DB::table('home_docomment ')."  d on d.uid=m.uid left join ".DB::table('home_doing')." o  on o.uid =m.uid  left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21  and p.isavatar=1 and m.uid in(9172,17641,9110,17808,9056) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_3_2[$jobi]=$user;
    $users_3_2[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	} 
//获取音乐类会员，优艺新势力
	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,t.topid,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21  and t.topid=4 and p.isavatar=1 and m.uid in(17217,9113,17582,11639,17598) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users4[$jobi]=$user;
   $users4[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	}

	//获取音乐会员，优艺最给力


  	$query = DB::query("SELECT m.`uid`,m.`username`,p.`url`,c.`views`,p.`praise`, c.`follower`,v.verify6 FROM ".DB::table('common_member')."  m left join  ".DB::table('common_member_profile')." p  on m.uid=p.uid left join   ".DB::table('common_member_count')." c on p.uid=c.uid  left join ".DB::table('user_actor_type')."  u on u.uid= m.uid left join  ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21 and t.topid=4 and p.isavatar=1 and  m.uid in(8933,8935,9103,8934,9038) and   c.`uid`=p.`uid` group by m.uid order by p.uid asc LIMIT 5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_4_1[$jobi]=$user;
    $users_4_1[$jobi]['job']=gettyename($user['uid']);
	//print_r( $user_1_3[$jobi]);
    $jobi++;
	}  
	
	//获取音乐会员，优艺最活力



 	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join  ".DB::table('home_pic')." h on h.uid=m.uid left join ".DB::table('common_member_count')." c on c.uid=m.uid  left join ".DB::table('home_docomment ')."  d on d.uid=m.uid left join ".DB::table('home_doing')." o  on o.uid =m.uid  left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21  and t.topid=4 and p.isavatar=1 and m.uid in(17118,11702,9075,17555,17560) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_4_2[$jobi]=$user;
    $users_4_2[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	} 
//获取演出类会员，优艺新势力
	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,t.topid,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21   and p.isavatar=1 and m.uid in(17537,17211,9169,9134,9131) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users5[$jobi]=$user;
    $users5[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	}

	//获取演出类会员，优艺最给力


  	$query = DB::query("SELECT m.`uid`,m.`username`,p.`url`,c.`views`,p.`praise`, c.`follower`,v.verify6 FROM ".DB::table('common_member')."  m left join  ".DB::table('common_member_profile')." p  on m.uid=p.uid left join   ".DB::table('common_member_count')." c on p.uid=c.uid  left join ".DB::table('user_actor_type')."  u on u.uid= m.uid left join  ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21  and   c.`uid`=p.`uid` and p.isavatar=1 and m.uid in(17775,17810,17754,17753,17646)  group by m.uid order by p.uid asc LIMIT 5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_5_1[$jobi]=$user;
    $users_5_1[$jobi]['job']=gettyename($user['uid']);
	//print_r( $user_1_3[$jobi]);
    $jobi++;
	}  	
	//获取演出类会员，优艺最活力



 	$query = DB::query("SELECT p.url,m.uid,t.name,m.username,p.praise,v.verify6  FROM  ".DB::table('common_member')." as m left join  ".DB::table('home_pic')." h on h.uid=m.uid left join ".DB::table('common_member_count')." c on c.uid=m.uid  left join ".DB::table('home_docomment ')."  d on d.uid=m.uid left join ".DB::table('home_doing')." o  on o.uid =m.uid  left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id left join ".DB::table('common_member_verify')." v on v.uid = m.uid where m.groupid=21   and p.isavatar=1 and m.uid in(17813,17642,17632,17553,17540) group by u.uid  order by p.uid asc limit 0,5 ");
	$jobi=0;
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
    $users_5_2[$jobi]=$user;
    $users_5_2[$jobi]['job']=gettyename($user['uid']);
    $jobi++;
	} 	
//获取影视类工作机会
/*
//echo avatar(8893,'small',true,true,true);
	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j   left join ".DB::table('user_type')." t on j.professor=t.id where  t.topid=3 and verify=1  group by j.id order by j.posttime desc limit 0,6");
	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
	if(!empty($jobkey))
{
    $job['title']=str_replace($jobkey,"<span style='color:red' >".$jobkey."</span>",$job['title']);
}
    $job['url']="jobs/view_".$job['id'].".html";
	$job['professor']=gettyename($job['professor']);
	$job['cpname']=getcpname($job['uid']);
    $jobs3[]=$job;
	}
	

//获取音乐类工作机会

	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j   left join ".DB::table('user_type')." t on j.professor=t.id where  t.topid=4  and verify=1  group by j.id order by j.posttime desc limit 0,6");
	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
	if(!empty($jobkey))
{
    $job['title']=str_replace($jobkey,"<span style='color:red' >".$jobkey."</span>",$job['title']);
}
    $job['url']="jobs/view_".$job['id'].".html";
	$job['professor']=gettyename($job['professor']);
	$job['cpname']=getcpname($job['uid']);
    $jobs4[]=$job;
	}

//获取演出类工作机会
	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j   left join ".DB::table('user_type')." t on j.professor=t.id where  t.topid=5 and verify=1  group by j.id order by j.posttime desc limit 0,6");
	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
	if(!empty($jobkey))
{
    $job['title']=str_replace($jobkey,"<span style='color:red' >".$jobkey."</span>",$job['title']);
}
    $job['url']="jobs/view_".$job['id'].".html";
	$job['professor']=gettyename($job['professor']);
	$job['cpname']=getcpname($job['uid']);
    $jobs5[]=$job;
	}
	*/
//获取推荐机构,排除剧组类型
	$query = DB::query("SELECT p.url,m.uid,m.username  FROM  ".DB::table('common_member')." as m   left join ".DB::table('common_member_profile')."  p on p.uid=m.uid   where m.groupid=22  and p.isavatar=1  and p.field3!='剧组' and m.uid in(8997,8993,11715,8995,9149) order by p.indexsort desc limit 0,6 ");
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
	$jigou[]=$user;
	}

	
	
		function getcpname($uid)
	{
       $userinfo= getuserbyuid($uid, 1);
	return $userinfo['username'];
	//print_r($typename);
	}

	function gettyename($uid)
	{
	
	if(is_numeric($uid))
	{
	$query = DB::query("SELECT  name  FROM ".DB::table('user_type')." as t left join ".DB::table('user_actor_type')."  u  on t.id=u.typeid  where u.uid=$uid group by u.id order by displayorder asc");
	
	$typename='';
	$i=1;
	while($typenames= DB::fetch($query)) {
	
            $typename.=$typenames['name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$i++;
	}
	}
	return $typename;
	//print_r($typename);
	}

include template('diy:index/index');