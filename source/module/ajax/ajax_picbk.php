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

/**
 * addpic 向版块内添加一张图片
 */

$dos = 
array(
	'addpic'
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class picbkMoudle{

	/**
	 * 向这个版块 添加一张图片
	 */
	public function addpic(){

		$bkid = $_POST['bkid'];  //版块id
		$picid = $_POST['picid']; // 图片id
		
		//获取图片源路径
		$datas = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");

		$id = c::t('home_picbk_pic')->addPic($_GET['bkid'],$datas[0]['picid'],$datas[0]['filepath']);
		if($id){
			$data = c::t('home_picbk_pic')->fetchRow("id = '{$id}'");				
		}

		include template('diy:ucenter/picbk/bkpic');
				
	}
}

$ajaxUcenter = new picbkMoudle();
$ajaxUcenter->$do();
?>