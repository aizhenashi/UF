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
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_recruitment')." where uid=$centeruid");
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(7);
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
$query = DB::query("select id,uid,title,province,city,description,posttime from ".DB::table("hr_recruitment")." where uid = $centeruid $limit");

while($job= DB::fetch($query)) {
				$job['date']=date('Y-m-d',$job['posttime']);				
				$job['title'] = $job['title'];
				$job['description']=cutstr($job['description'],204);
				$job['url']="jobs/view_".$job['id'].".html";				
				$job['dizhi'] = $job['province'].'&nbsp;&nbsp;'.$job['city'];
				$jobs[]=$job;
				
		}
$comp_name = DB::fetch_first("select username from ".DB::table('common_member')." where uid = $centeruid and groupid = 22");

include template('diy:org/task');
?>