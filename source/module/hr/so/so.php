<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'district.php';
$optionadd = $filterurladd = $searchsorton = '';
require_once libfile('function/hr');
require libfile('class/page');
$page = empty($_GET['page'])?1:intval($_GET['page']);
$fid=intval($_GET['fid']);
$list=array();
$num=0;
//var_dump($_GET);
//获取判断字符
$arr=array_keys($_GET);
$key=intval(substr($arr[0],0,1));

//var_dump($key);
if($_GET){
//将搜索关键词加入到数据库中
if($key == 0){
	$keyword=trim($_GET['inputString']);
}else{
	$keyword=trim($_GET['2jobkey']);
}
 $keyinfo=DB::fetch_first("SELECT * FROM ".DB::table('hr_search_keywords')." where keyword='$keyword' ");
  if(!empty($keyinfo)){
		DB::query("update ".DB::table('hr_search_keywords')." set count=count+1 where keyword='$keyword' ");
  }else{
		DB::insert('hr_search_keywords ',array('keyword' => $keyword,'count' => '1'));//插入用户类型表
  }
  
   $query=DB::query("SELECT keyword FROM ".DB::table('hr_search_keywords')."  order by count desc limit 0,5");
   
  while($topkeyword= DB::fetch($query)) {
      $topkeywords[]=$topkeyword;
	}
 
 
 $nums=DB::fetch_first("SELECT COUNT(*) AS num FROM ".DB::table('portal_article_title')." where title like '%".$keyword."%'");
 $num=$nums['num'];
 $allnum=$num;
 if($num){
  //$query=DB::query("SELECT * FROM ".DB::table('portal_article_title')." where title like '%".$keyword."%' limit $start,$perpage");
		$query=DB::query("SELECT * FROM ".DB::table('portal_article_title')." where title like '%".$keyword."%' limit 0,10");
		while($new= DB::fetch($query)) {
				$new['title']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$new['title']);
				$new['summary']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$new['summary']);
				$news[]=$new;
		}
		$multi = multi($num, $perpage, $page, "/so.php?mod=so");
 }
 $info=DB::fetch_first("SELECT *  FROM ".DB::table('common_member')." where username = '".$keyword."' and groupid > 1");
 if($info['uid']){
		$allnum++;
		$thread = getuserbyuid($info['uid'], 1);
		space_merge($thread, 'count');
		space_merge($thread, 'profile');
		space_merge($thread, 'field_home');
		 //print_r($thread);
		$typenums=DB::fetch_first("SELECT count(*) as num FROM ".DB::table('user_actor_type')." WHERE uid=".$info['uid']);
		$typename='';
		if($typenums['num'] > 0){
				$query=DB::query("select b.name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=".$info['uid']);
  
				 while($typenames= DB::fetch($query)) {
						$typename.=$typenames['name']."&nbsp;";
				}
		}
 }




if($key == 0){
 //搜索所有的艺人和机构
	 if($fid == 0){
		 //分页
		 $perpage=10;
		$start_limit = ($page - 1) * $perpage;
		$start_limit=$start_limit>=0?$start_limit:"0";
		$limit=" limit ".$start_limit.",".$perpage ;
		$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." where username like '%".$keyword."%'");
		$allpage=ceil($sortdata['count']/$perpage);
		if(!empty($_GET['page'])){
		$prepage=$_GET['page'];
		}else{
		$prepage=1;
		}
		$p=new page($sortdata['count'] ,$perpage);
		$multipage=$p->show(8);


			$mmkey = "aabb"; //模拟登录key
			$query=DB::query("SELECT m.uid,m.username,m.groupid,p.spaceinfo,p.resideprovince,p.residecity,p.bio,p.field5,p.gender FROM ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as p on p.uid =m.uid where m.username like '%".$keyword."%' and m.groupid > 1 order by m.uid desc $limit");
				while($mem= DB::fetch($query)) {
						$typename_asd='';
						$query_asd=DB::query("select b.name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=".$mem['uid']);
						while($typenames_asd= DB::fetch($query_asd)) {	
								$typename_asd.=$typenames_asd['name']."&nbsp;";
						}
						$mem['username']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['username']);//#2e7dca
						$mem['spaceinfo']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['spaceinfo']);
						$mem['encrypt'] = passport_encrypt($mem['uid'],$mmkey); //模拟登录字符串代码
						$mem['typename']=$typename_asd;
						$flag[$mem['uid']] = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$mem['uid']);
						$attentions=DB::query("SELECT COUNT(username) as num FROM ".DB::table('home_follow')." where uid =".$mem['uid']);
						while($attention_num=DB::fetch($attentions)){
								$attention[$mem['uid']]=$attention_num['num'];
						}
						$fans=DB::query("SELECT COUNT(fusername) as num FROM ".DB::table('home_follow')." where followuid =".$mem['uid']);
						while($fans_num=DB::fetch($fans)){
								$fan[$mem['uid']]=$fans_num['num'];
						}
						$mems[]=$mem;
				}
				$num_tongji=count($mems);
				//var_dump($num_tongji);
	}elseif($fid == 21){
			$mmkey = "aabb"; //模拟登录key
			
				$perpage=10;
				$start_limit = ($page - 1) * $perpage;
				$start_limit=$start_limit>=0?$start_limit:"0";
				$limit=" limit ".$start_limit.",".$perpage ;
				$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." where groupid = 21 and username like '%".$keyword."%'");
				$p=new page($sortdata['count'] ,$perpage);
				$multipage=$p->show(8);
				$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
			$query=DB::query("SELECT m.uid,m.username,m.groupid,p.spaceinfo,p.resideprovince,p.residecity,p.bio,p.field5,p.gender FROM ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as p on p.uid =m.uid where m.username like '%".$keyword."%' and m.groupid = 21 order by m.uid desc $limit");
				while($mem= DB::fetch($query)) {
						$typename_asd='';
						$query_asd=DB::query("select b.name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=".$mem['uid']);
						while($typenames_asd= DB::fetch($query_asd)) {	
								$typename_asd.=$typenames_asd['name']."&nbsp;";
						}
						$mem['username']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['username']);//#2e7dca
						$mem['spaceinfo']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['spaceinfo']);
						$mem['encrypt'] = passport_encrypt($mem['uid'],$mmkey); //模拟登录字符串代码
						$mem['typename']=$typename_asd;
						$flag[$mem['uid']] = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$mem['uid']);
						$attentions=DB::query("SELECT COUNT(username) as num FROM ".DB::table('home_follow')." where uid =".$mem['uid']);
						while($attention_num=DB::fetch($attentions)){
								$attention[$mem['uid']]=$attention_num['num'];
						}
						$fans=DB::query("SELECT COUNT(fusername) as num FROM ".DB::table('home_follow')." where followuid =".$mem['uid']);
						while($fans_num=DB::fetch($fans)){
								$fan[$mem['uid']]=$fans_num['num'];
						}
						
						$mems[]=$mem;
				}
				$num_tongji=count($mems);
	}elseif($fid == 22){
			$mmkey = "aabb"; //模拟登录key

				$perpage=10;
				$start_limit = ($page - 1) * $perpage;
				$start_limit=$start_limit>=0?$start_limit:"0";
				$limit=" limit ".$start_limit.",".$perpage ;
				$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." where groupid = 22 and username like '%".$keyword."%'");
				$p=new page($sortdata['count'] ,$perpage);
				$multipage=$p->show(8);
				$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
			$query=DB::query("SELECT m.uid,m.username,m.groupid,p.spaceinfo,p.resideprovince,p.residecity,p.bio,p.field5,p.gender FROM ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as p on p.uid =m.uid where m.username like '%".$keyword."%' and m.groupid = 22 order by m.uid desc $limit");
				while($mem= DB::fetch($query)) {
						$typename_asd='';
						$query_asd=DB::query("select b.name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=".$mem['uid']);
						while($typenames_asd= DB::fetch($query_asd)) {	
								$typename_asd.=$typenames_asd['name']."&nbsp;";
						}
						$mem['username']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['username']);//#2e7dca
						$mem['spaceinfo']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$mem['spaceinfo']);
						$mem['encrypt'] = passport_encrypt($mem['uid'],$mmkey); //模拟登录字符串代码
						$mem['typename']=$typename_asd;
						$flag[$mem['uid']] = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$mem['uid']);
						$attentions=DB::query("SELECT COUNT(username) as num FROM ".DB::table('home_follow')." where uid =".$mem['uid']);
						while($attention_num=DB::fetch($attentions)){
								$attention[$mem['uid']]=$attention_num['num'];
						}
						$fans=DB::query("SELECT COUNT(fusername) as num FROM ".DB::table('home_follow')." where followuid =".$mem['uid']);
						while($fans_num=DB::fetch($fans)){
								$fan[$mem['uid']]=$fans_num['num'];
						}
						
						$mems[]=$mem;
				}
				$num_tongji=count($mems);
	}
}else{

				$salary_arry=array('1000'=>'1000以下','2000'=>"1000~2000元",'4000'=>"2000~4000元",'6000'=>"4000~6000元 ","8000"=>"6000~8000元","10000"=>"8000~10000元","15000"=>"10000~15000元","25000"=>"15000~25000 元","25001"=>"25000以上","-1"=>"面议",'0'=>"面议");
				$perpage=10;
				$start_limit = ($page - 1) * $perpage;
				$start_limit=$start_limit>=0?$start_limit:"0";
				$limit=" limit ".$start_limit.",".$perpage ;
				$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_recruitment')." where title like '%".$keyword."%' or description like '%".$keyword."%'");
				$p=new page($sortdata['count'] ,$perpage);
				$multipage=$p->show(8);
				$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j left join   ".DB::table('user_type')." u  on j.professor=u.id  where j.title like '%".$keyword."%' or j.description like '%".$keyword."%' order by j.posttime desc $limit");
	
	
		while($job= DB::fetch($query)) {
				$job['date']=date('Y-m-d',$job['posttime']);
				$job['title']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$job['title']);
				$job['description']=str_replace($keyword,"<span style='color:red' >".$keyword."</span>",$job['description']);
				
				$job['description']=cutstr($job['description'],204);
				$job['url']="jobs/view_".$job['id'].".html";
				$job['professor']=gettyeidname($job['professor']);
				$job['cpname']=getcpname($job['uid']);
				$job['invite']=isinvite($_G['uid'],$job['uid']);//用于判断是否被邀请。
				$jobs[]=$job;
				
		}
		$num_tongji=count($jobs);
}

}

function gettyeidname($id){
		if(is_numeric($id)){
			$typename = DB::fetch_first("SELECT  name  FROM ".DB::table('user_type')."  where id=$id ");
		}
		return $typename['name'];
	//print_r($typename);
	}
	
	function getcpname($uid){
       $userinfo= getuserbyuid($uid, 1);
		return $userinfo['username'];
	//print_r($typename);
	}
	function isinvite($invite_uid,$uid) {//判断是否已经邀请 
		if(DB::fetch_first("SELECT * FROM ".DB::table('user_cooperation')." where invite_uid ='$invite_uid' and cooperation_uid='$uid'")) {
			return true;
		}else {
			return false;
		}
	}

include template('so/view');
?>