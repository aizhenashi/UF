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



require_once libfile('function/discuzcode');
if(submitcheck('formhash')){
$inviteId=daddslashes($_POST['inviteId']);
$invitetype=daddslashes($_POST['invitetype']);
$content=isset($_POST['content'])?daddslashes(trim(iconv("utf-8","gbk", $_POST['content']))):'';
DB::query("INSERT INTO ".DB::table("user_cooperation")." SET invite_uid=".$_G['uid'].", cooperation_uid=".$inviteId.",cooperation_type=".$invitetype.",post_time=".time().",cooperation_content='".$content."', read_flag='0', agree_flag='0'");

}
$page = empty($_GET['page'])?1:intval($_GET['page']);
$dos = array('index','album','thread','adv','live','adt');
 //print_r($_G);
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';
$uid=$_GET['uid'];
//echo $uid;
$thread = getuserbyuid($_GET['uid'], 1);
 $dburl=DB::fetch_first("SELECT url FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
if(!empty($_GET['praise']))
{
DB::query("update ".DB::table('common_member_profile')." set praise=praise+1 where uid=$uid");
exit();
}

if($thread['uid']> 0){
//print_r($thread);
 //取出职业
 $typenums=DB::fetch_first("SELECT count(*) as num FROM ".DB::table('user_actor_type')." WHERE uid=$uid");
 $typename='';
 if($typenums['num'] > 0)
 {
  $query=DB::query("select name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=$uid");
  
  while($typenames= DB::fetch($query)) {
	
            $typename.=$typenames['name']."&nbsp";
	}
 }
 //个人资料
 	if(!$_G['setting']['preventrefresh'] || $_G['uid'] &&$uid !=$_G['uid']  && $_G['cookie']['viewid'] != 'uid_'.$uid) {
		DB::query("update ".DB::table('common_member_count')." set views=views+1 where uid=$uid");
		//$viewuids[$space['uid']] = $space['uid'];
		dsetcookie('viewid', 'uid_'.$uid);
	}
 $space=DB::fetch_first("SELECT views FROM ".DB::table('common_member_count')." where uid=$uid");
 $userinfo=DB::fetch_first("SELECT * FROM ".DB::table('common_member_profile')." where uid=$uid");
  $userinfo['click']= $space['views'];
 //print_r($userinfo);
 space_merge($thread, 'count');
 space_merge($thread, 'profile');
 space_merge($thread, 'field_home');
 $verify=DB::fetch_first("SELECT * FROM ".DB::table('common_member_verify')." where uid =$uid");
 //print_r($thread);
 //print_r($_G);
 $flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$_GET['uid']);
 //print_R($flag);
 if($thread['groupid']=="21"){ //艺人空间
 $trend=DB::fetch_first("SELECT * FROM ".DB::table('common_member_field_home')." where uid =$uid");
 //print_r($trend);
 $albums=DB::fetch_all("SELECT * FROM ".DB::table('home_album')." where uid =$uid");
 //print_r($albums);
 if($_GET['id']){
 //$album=DB::fetch_all("SELECT * FROM ".DB::table('home_pic')." where albumid =".$_GET['id']);
 $count = C::t('home_pic')->check_albumpic($_GET['id']);
 //echo $count;
    $start = ($page-1)*$perpage;
	$perpage=12;
    $list = array();
	$pricount = 0;
	if($count) {
		$album = DB::fetch_all("SELECT * FROM ".DB::table('home_pic')." where albumid =".$_GET['id']." and uid=$uid  limit $start,$perpage");
		
	}
	$multi = multi($count, $perpage, $page, "job.php?mod=view&uid=$uid"."&do=album&id=".$_GET['id']);
   // print_r($album);
 }
 }
 if($thread['groupid']=="22"){
   $start = ($page-1)*$perpage;
   $perpage=8;
   $countnow=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime > UNIX_TIMESTAMP()");
   $countend=DB::result_first("SELECT count(*)   FROM  ".DB::table('hr_recruitment')."   where   uid=$uid and endtime < UNIX_TIMESTAMP()");
   $countvip=DB::result_first("SELECT count(*)   FROM  ".DB::table('user_cooperation')."   where   invite_uid=$uid and cooperation_type=2");
	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j   where   uid=$uid and verify=1 group by j.id order by j.posttime desc limit	0,8");
	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
    $job['url']="jobs/view_".$job['id'].".html";
    $jobs[]=$job;
	}
if($do=='adv')
{
$method=" method=1 and ";
}
elseif($do=='adt')
{
$method=" method=2 and ";
}else
{
$method=" ";
}
  $nums=DB::fetch_first("SELECT count(*) as num  FROM  ".DB::table('hr_recruitment')."     where  $method  uid=$uid and verify=1 ");
  $count=$nums['num'];
  $query= DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j   where $method  uid=$uid and verify=1 group by j.id order by j.posttime desc limit $start,$perpage ");
  	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
    $job['url']="jobs/view_".$job['id'].".html";
    $job_list[]=$job;
	}
    $jmulti = multi($count, $perpage, $page, "user.php?mod=view&uid=$uid&do=adv");
 }

$introarry=unserialize($userinfo['introduce']);
$imgarrt=unserialize($userinfo['introduceimg']);
$spaceinfo='';
foreach($imgarrt as $key=>$value)
{
$spaceinfo.="<p><img src='".$value."' /></p><pre>".$introarry[$key]."</pre>";

} 
include template('user/view');
}else{
 showmessage(lang('hr/template', '该艺人不存在或已删除'));
}
?>
