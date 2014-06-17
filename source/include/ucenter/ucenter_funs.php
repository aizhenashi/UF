<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require libfile('class/page');//分页类
$page = empty($_GET['page'])?1:intval($_GET['page']);
//$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];//获取uid
$perpage=10;
$start_limit = ($page - 1) * $perpage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perpage ;
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_follow')." where followuid=$centeruid");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}


/*
$fansuid = DB::query("SELECT uid FROM ".DB::table('home_follow')." where followuid = $centeruid $limit");
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
*/

$fans =  $center->get_user_funs($centeruid,$limit,$_G['uid']);

include template('diy:ucenter/funs');
?>