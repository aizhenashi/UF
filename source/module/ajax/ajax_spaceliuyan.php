<?php

/**
 *   像册 ajax提交模块 页 做action 分发 
 *   
 *   1.容错
 *   未登录 直接 exit
 *   
 */

//这个是页面跳转
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'liuyanDel', //删除留言
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class spaceliuyanMoudle{
	
	/**
	 * 留言删除
	 */
	function liuyandel(){

		global $_G;

		$id = $_POST['id'];
		c::t('home_space_liuyan')->deleteforWhere("id = '{$id}'");
		echo 1;
		exit;
	}

}

$ajaxUcenter = new spaceliuyanMoudle();
$ajaxUcenter->$do();
?>