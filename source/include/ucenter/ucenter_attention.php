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
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_follow')." where uid=$centeruid");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
/*
$query = DB::query("SELECT followuid FROM ".DB::table('home_follow')." where uid = $centeruid $limit");
while($fid = DB::fetch($query)){
	foreach($fid as $fluid){
	$ismyt=DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = {$_G['uid']} and followuid = $fluid");
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
*/
$arr =  $center->get_user_attention($centeruid,$limit,$_G['uid']);

include template('diy:ucenter/guanzhu');
?>