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
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_space_liuyan')." where spaceuid = '{$centeruid}'");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}

$spacehuifus = c::t('home_space_liuyan')->select_liuyan("spaceuid = '{$centeruid}' order by id desc");
$query = DB::query("update ".DB::table("home_space_liuyan")." set state = 1 where spaceuid = '{$centeruid}' and state = 0");

//var_dump($spacehuifus);

include template('diy:ucenter/mymessage');
?>