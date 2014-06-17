<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if(empty($_G['uid']) && !$channel['visitorpost']) {
	showmessage('not_loggedin', '', '', array('login' => 1));
}


if(submitcheck('formhash')){

		$user_type	= array_filter($_G["gp_user_type"]);
		$table = DB::table('user_actor_type');
		DB::query("DELETE FROM `$table` WHERE `$table`.`uid` = $_G[uid]");//修改前删除
		$sql="INSERT INTO  `$table` (`id` ,`uid` ,`typeid`)VALUES";
		$sql_temp="";
		foreach($user_type as $k=>$v) {
			$sql_temp .="(NULL, '$_G[uid]', '$v'),";
		}
		if($sql_temp<>'') {
			$sql .= substr($sql_temp, 0, -1).';';
			DB::query($sql);
		}
	showmessage('修改成功。', 'user.php?mod=occupation');
}


//DB::query("UPDATE ".DB::table('common_member_profile')." SET introduce='$introduce_str', introduceimg='$imgstr'  WHERE uid='$uid' ");
//showmessage('更新成功', 'user.php?mod=url');
//获取演出分类
$query = DB::query("SELECT * FROM `".DB::table('user_actor_type')."` where `uid`='$_G[uid]' ");
$check=array();
while($db = DB::fetch($query)) 
{
	$check[]=$db['typeid'];
}

$query = DB::query("SELECT * FROM ".DB::table('user_type ')." order by displayorder asc ");
$user_types=array();
while($user_type = DB::fetch($query)) 
{
	$user_types[]=$user_type;
	if(in_array($user_type['id'],$check)) {
		$checked[]='checked="true"';
	}else{
		$checked[]='';
	}
}

include template('diy:user/occupation');