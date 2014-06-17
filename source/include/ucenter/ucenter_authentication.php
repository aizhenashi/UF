<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'source/module/hr/mec/district.php';
$uid=$_G['uid'];
//转换字符集
function setCharset($str)
{
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
//判断是否认证
//$attu=DB::fetch_all("SELECT ");
//得到所有职业
$query = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
$user_types=array();
while($user_type = DB::fetch($query)) 
{
	$user_types[]=$user_type;
}
//艺人职业
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('user_actor_type')." where uid=$uid");
if($typenums['num']>0)
{
	$query=DB::fetch_all("select name from ".DB::table('user_actor_type')." as a left join " .DB::table('user_type')." as b on a.typeid=b.id where a.uid=$uid");
}


include template('diy:ucenter/authentication');

?>