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
require libfile('class/page');
$page = empty($_GET['page'])?1:intval($_GET['page']);
$dos = array('index','album','thread','adv','live','adt');
 //print_r($_G);
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';
$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];
//echo $uid;
$fuid=intval($uid);
$mainid=intval($_G['uid']);
 $panduan=DB::fetch_first("SELECT fusername FROM ".DB::table('home_friend')." WHERE uid=$mainid and fuid=$fuid");
$thread = getuserbyuid($_GET['uid'], 1);
 $dburl=DB::fetch_first("SELECT url FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
 $dbsex=DB::fetch_first("SELECT gender FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
 //艺人空间赞
if(!empty($_GET['praise']))
{
DB::query("update ".DB::table('common_member_profile')." set praise=praise+1 where uid=$uid");
exit();
}


//关注

if($_GET['action']=='attention'){
//分页
$perpage=10;
$start_limit = ($page - 1) * $perpage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perpage ;
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_follow')." where uid=$uid");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
$followuid = DB::query("SELECT followuid FROM ".DB::table('home_follow')." where uid = $uid $limit");
while($fid = DB::fetch($followuid)){
	foreach($fid as $fluid){
	$ismyt=DB::fetch_first("SELECT fusername  FROM ".DB::table(home_follow)." where uid={$_G['uid']} and followuid=$fluid");
	$finfo = DB::query("SELECT m.username,m.groupid,p.birthprovince,p.field5,p.bio,p.birthcity,p.url,p.uid,p.gender FROM ".DB::table('common_member'). " as m left join ".DB::table('common_member_profile')." as p on m.uid=p.uid where m.uid=$fluid");
		while($attinfo=DB::fetch($finfo)){
			$attinfo['url']=!empty($attinfo['url'])?$attinfo['url']:"u_".$attinfo['uid'];
			$attnum['numt'] = DB::fetch_first("SELECT count(followuid) as numt FROM ".DB::table('home_follow')." where uid = {$attinfo['uid']}");
				foreach($attnum['numt'] as $numt){
				}
			$attinfo['numt']=$numt;
			$attnum['numf'] = DB::fetch_first("SELECT count(uid) as numf FROM ".DB::table('home_follow')." where followuid={$attinfo['uid']}");
				foreach($attnum['numf'] as $numf){	
				}
			$attinfo['ismyt']=$ismyt;
			$attinfo['numf']=$numf;
			if(strlen($attinfo['field5'])>120){
				$attinfo['field5']=cutstr($attinfo['field5'],120)."...";
			}else{
				$attinfo['field5']=$attinfo['field5'];
			}
			if(strlen($attinfo['bio'])>120){
				$attinfo['bio']=cutstr($attinfo['bio'],120)."...";
			}else{
				$attinfo['bio']=$attinfo['bio'];
			}
			$arr[]=$attinfo;
			$action='attention';
		}
	}
}
}else if($_GET['action']=='fans'){
//粉丝
//分页
$perpage=10;
$start_limit = ($page - 1) * $perpage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perpage ;
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_follow')." where followuid=$uid");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
$fansuid = DB::query("SELECT uid FROM ".DB::table('home_follow')." where followuid = $uid $limit");
while($faid = DB::fetch($fansuid)){
	foreach($faid as $fanid){
	$ismyt=DB::fetch_first("SELECT fusername  FROM ".DB::table(home_follow)." where uid={$_G['uid']} and followuid=$fanid");
	$fansinfo1 = DB::query("SELECT m.username,m.groupid,p.birthprovince,p.field5,p.bio,p.birthcity,p.url,p.uid,p.gender FROM ".DB::table('common_member'). " as m left join ".DB::table('common_member_profile')." as p on m.uid=p.uid where m.uid=$fanid");
		while($fansinfo=DB::fetch($fansinfo1)){
			$fansinfo['url']=!empty($fansinfo['url'])?$fansinfo['url']:"u_".$fansinfo['uid'];
			$attnum['numf'] = DB::fetch_first("SELECT count(uid) as numt FROM ".DB::table('home_follow')." where followuid={$fansinfo['uid']}");
			foreach($attnum['numf'] as $numf){
			
			}
			$attnum['numt'] = DB::fetch_first("SELECT count(followuid) as numf FROM ".DB::table('home_follow')." where uid={$fansinfo['uid']}");
			foreach($attnum['numt'] as $numt){
			
			}
			$fansinfo['ismyt']=$ismyt;
			$fansinfo['numt']=$numt;
			$fansinfo['numf']=$numf;
			if(strlen($attinfo['field5'])>120){
				$attinfo['field5']=cutstr($attinfo['field5'],120)."...";
			}else{
				$attinfo['field5']=$attinfo['field5'];
			}
			if(strlen($attinfo['bio'])>120){
				$attinfo['bio']=cutstr($attinfo['bio'],120)."...";
			}else{
				$attinfo['bio']=$attinfo['bio'];
			}
			$fans[]=$fansinfo;
			$action='fans';
		}
	}
}
}

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
 
 //判断信息是否隐藏
 $userid = $userinfo['uid'];
 $userinfo_pri = DB::fetch_first("SELECT * FROM ".DB::table('common_member_details')." where uid=$userid");
 
  
  
  $userinfo['click']= $space['views'];
  if(strlen($userinfo['bio'])>120){
		$userinfo['pre_jieshao']=cutstr($userinfo['bio'],120)."...";
  }else{
		$userinfo['pre_jieshao']=$userinfo['bio'];
  }
 //echo '<pre>';
 //print_r($userinfo);
 space_merge($thread, 'count');
 space_merge($thread, 'profile');
 space_merge($thread, 'field_home');
  if(strlen($thread['field5'])>120){
		$thread['mec_jieshao']=cutstr($thread['field5'],120)."...";
  }else{
		$thread['mec_jieshao']=$thread['field5'];
  }
 $verify=DB::fetch_first("SELECT * FROM ".DB::table('common_member_verify')." where uid =$uid");
 //print_r($thread);
 //print_r($_G);
 $flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$_GET['uid']);
 //print_R($flag);
 if($thread['groupid']=="21"){ //艺人空间
 $trend=DB::fetch_first("SELECT * FROM ".DB::table('common_member_field_home')." where uid =$uid");
 //print_r($trend);
 $albums=DB::fetch_all("SELECT * FROM ".DB::table('home_album')." where uid =$uid");//获取相册数来那个
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
if(!empty($imgarrt)){
foreach($imgarrt as $key=>$value)
{
$spaceinfo.="<p><img src='".$value."' /></p><pre>".$introarry[$key]."</pre>";

}
}else{
	foreach($introarry as $val){
	$spaceinfo.="<pre>".$val."</pre>";
	}
}

include template('user/attention');


?>