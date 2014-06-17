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

$invite = $center->get_user_invite($centeruid);
//var_dump($invite);
/*
foreach($invite as $date){
	$invite_time[]=getdate($date['ltime']);
	$now[]=getdate(time());
	var_dump($invite_time[]['mday'])；
		var_dump($now[]['mday'])；
}
*/

include template('diy:ucenter/invite');
?>