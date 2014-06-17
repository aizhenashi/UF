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
$page = empty($_GET['page'])?1:intval($_GET['page']);
$dos = array('index','album','thread','adv','live','adt');
 //print_r($_G);
$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';
$uid=$_GET['uid'];
//echo $uid;
$fuid=intval($uid);
$mainid=intval($_G['uid']);
 $panduan=DB::fetch_first("SELECT fusername FROM ".DB::table('home_friend')." WHERE uid=$mainid and fuid=$fuid");
$thread = getuserbyuid($_GET['uid'], 1);
 $dburl=DB::fetch_first("SELECT url FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
 //空间视频展示
 $video=DB::fetch_first("select videostatus from ".DB::table("common_member")." where uid=$uid");
 $video['videostatus']=trim($video['videostatus']);
$video_keys=explode(" ",$video['videostatus']);
 //$video['videostatus']=intval($video['videostatus']);
 $video_arr=array(null,'<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=11e4918dad&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><br><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-杨凯凯</h3>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=184cd305a0&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><br><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-周晓洁</h3>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=804870139b&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><br><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-滕圆圆</h3>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=d41c3a5c81&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><br><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-李钰</h3>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=be144a80cc&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><br><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-沈紫薇</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=5503015504&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-丁岳峰</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=dcea37f871&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-王志辉</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=07decaadd2&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-千雨涵</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=d4c70a8721&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-黄如佳</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=85dc93ecd0&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-王源泽</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=e26d71a0ab&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-陈凡</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=87e9b573d0&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-王馨楠</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=e193ae1f78&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-刘显扬</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=bc0871bf80&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-李亚泽</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=3790d65c0b&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-徐艺铷</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=ca5405f7e0&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-姜皓中</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=8d551299bd&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-曹朝</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=1c4344d945&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-谭颖</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=38f0ee8eaf&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-朱维鑫</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=0813b88ade&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-孙菲</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=4016b14188&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-周子琪</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=f98c37a627&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-刘志远</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=4a8ce883a5&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-秦博</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=99b9072f7a&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-吴林泽</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=5cda4e04ac&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-张丽新</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=8a9593a3f3&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-陈丽绅</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=e86598d3e1&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-滕圆圆</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=1c02297705&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-杨凯凯</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=5679e28a8b&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-李钰</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=944d44b250&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-黄茹佳</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=2489040edb&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-王源泽</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="640" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=2a92f6815d&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-王杰</h3></b>','<embed src="http://yuntv.letv.com/bcloud.swf" allowFullScreen="true" quality="high"  width="640" height="480" align="middle" allowScriptAccess="always" flashvars="uu=664805680f&vu=c05b561dd2&auto_play=0&width=640&height=480" type="application/x-shockwave-flash"></embed><b><h3 style="margin-top: 20px;font-size:18px;">优艺网盟-欢乐谷-艺能新星-栾帅</h3></b>');
 //艺人空间赞
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
include template('user/view');
}else{
 showmessage(lang('hr/template', '该艺人不存在或已删除'));
}
?>
