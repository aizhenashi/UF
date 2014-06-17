<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require libfile('class/page');//分页类
$page = empty($_GET['page'])?1:intval($_GET['page']);

$perpage=10;
$start_limit = ($page - 1) * $perpage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perpage ;
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_follow')." where uid=$centeruid");
//var_dump($sortdata['count']);
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
$funs =  $center->get_user_funs($centeruid,$limit,$_G['uid']);
//var_dump($funs);

include template('diy:org/fensi');
?>