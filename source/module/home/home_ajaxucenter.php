<?php

/**
 *   个人空间  ajax提交模块 页 做action 分发 
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
 *  sendshuoshuo 发送说说
 *  getshuoshuo 获取说说
 *  getALLpinglun 获取说说的所有评论
 *  HuifuShuoShuo 对说说进行评论
 *  HuifuShuoShuoAndRen 回复人的同时 将内容算入说说的一部分
 *  playVideo 播放视频
 *  sendspaceliuyan 发送空间留言
 *  HuifuLiuyanAndRenhtml 回复留言并且回复人
 *  addbankuai 添加版块
 *  changebkOrder 改变版块排序
 *  delbankuaiforid 删除版块通过id
 *  editbkName 编辑版块名称
 *  delpicbkforid 通过id 删除版块pic
 *  addpic_picbk 向图片版块添加一张图片
 */
$dos = 
array(
	'getALLpinglun',
	'HuifuShuoShuo',
	'HuifuShuoShuoAndRenhtml',
	'HuifuShuoShuoAndRen',
	'zan',
	'chuliVideo',
	'playVideo',
	'sendspaceliuyan',
	'HuifuLiuyanAndRenhtml',
	'HuifuLiuyanAndRen',
	'addbankuai',
	'changebkOrder',
	'delbankuaiforid',
	'editbkName',
	'delpicbkforid',
	'delshuoshuo'
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

//1.容错
global $_G;
if(!$_G['uid']){
	echo 'nologin';
	exit;
}


class ajaxucenterMoudle{
	
	/**
	 * ajax 通过源链接 处理视频数据
	 */
	public function chuliVideo(){

		//获取 链接
		$reslink = $_POST['reslink'];

		//将短链接记录存到短链接表
		$shortlink = c::t('video_shortlink')->insert_shortlink($reslink);
		
		if($shortlink){
			//输出  短链接
			die(' '.$shortlink.' ');
		}else{
			// 输出错误 error
			die('error');
		}
	}



	

	
	/**
	 * 添加版块
	 */
	public function addbankuai(){
		
		
		$flag1 = 1;		
		
		//在版块表里添加一条记录
		$id = c::t('space_bankuai')->insert_bankuai($_POST);
		$bkdata = c::t('space_bankuai')->fetch_bk(" id = '$id'");

		include template('diy:ucenter/bk');
				
		//将myspace_bankuai 表 里 我的版块记录修改
		
	}
	
	/**
	 * 改变版块的顺序
	 */
	public function changebkOrder(){
		c::t('myspace_bankuai')->updatemybkorder($_POST);
		exit;
	}

	/**
	 * 通过id 删除版块
	 */
	public function delbankuaiforid(){

		//版块id
		$id = $_POST['id'];
		$bktype = $_POST['bktype'];
		
		//删除版块
		$rs = c::t('space_bankuai')->deletebk("`id` = '{$id}'");
		
		
	}
	
	/**
	 * 编辑版块名称
	 * Enter description here ...
	 */
	public function editbkName(){
		//版块名称转码
		$_POST['bankuainame'] = trim(iconv('utf-8', 'GBK', $_POST['bankuainame']));
		//改变版块名称
		$rs = c::t('space_bankuai')->updateName($_POST['bankuainame'],"`id` = '{$_POST['id']}'");
		if($rs){
			die('1');
		}
	}
	
	/**
	 * 通过bkpicid 来删除这条记录
	 */
	public function delpicbkforid(){

		$rs = c::t('home_picbk_pic')->delforwhere("id = '{$_POST['id']}'");
		
		if($rs){
			die('1');
		}
	}
	
	
}


$ajaxUcenter = new ajaxucenterMoudle();

$ajaxUcenter->$do();
?>