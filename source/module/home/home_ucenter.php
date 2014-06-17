<?php

/**
 *   个人空间  模块 页 做action 分发 
 *   
 *   1.容错
 *   没有uid ： 判断是否登录
 *   失败 ： showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
 *   有uid ： 判断 会员表 该uid 是否存在
 *   失败 ： showmessage('space_does_not_exist');
 *   
 *   2.$centeruid 赋值
 *   有uid 将Get uid 赋值到变量$centeruid
 *   无uid 将$_G['uid'] 赋值到变量$centeruid
 *
 *	  3.判断存在$_G['uid']时，添加访问记录
 *   
 *   4.通过$centeruid 取公共布局数据
 *   将$centeruid 公共信息取出 做 function
 *    
 */

//这个是页面跳转
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



require libfile('class/ucenter');

$dos = array(
	'index',
	'info',
	'album', 
	'camer',		//空间视频展示
	'myaddVideo',		//空间视频展示添加视频
	'insetmyVideo',		//添加视频到数据库
	'attention',		//关注页面
	'funs',				//	粉丝页面
	'visitor',		//访客记录
	'shuoshuo',
	'task',			//工作机会记录
	'fabuzp',		//发布，修改工作机会
	'manager',
	'uploadpic',
	'setbkpic',
	'uploadpic_picbk',
	'uploadpic_picbk2',
	'tinfo', //档案
	'label', //个人标签
	'representative', //个人代表作品
	'relation', //个人联系方式
	'authentication', //个人认证信息
	'url', //个人个性域名
	'privacy',//个人隐私设置
	'accounts',//个人帐号安全
	'email',    //
	'orgtinfo', //机构帐号中心
	'orglabel',
	'orgauthentication',//机构认证信息
	'orgurl', //机构个性域名
	'orgrelation',//机构联系方式
	'orgblacklist',//机构黑名单
	'orgmodpass', //机构修改密码
	'addvideoForVideobk', //视频版块 添加视频 html 
	'insvideoForVideobk', //视频版块 添加视频 逻辑
	'editorbktexthtml', //编辑文本版块文字 html
	'editorbktext', 		//编辑文本版块文字
	'invite',		//个人邀请记录
	'mymessage',	//个人消息记录
	'album', //相册相关
	'albumphotos', //该相册下所有照片
	'albumphoto', //单张照片的详细页
	'changephoto',		//修改头像
	'shuoshuolist',		//说说列表
	'addvoice',        //添加音频版块（html）
	'pushvoice',       //添加音频版块 (逻辑)
);


$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

$guid = intval($_GET['uid']);

//var_dump($guid)；
if($do === NULL){
	die('action error');
	//showmessage('action error');
}

//uid
	//他人空间权限把控
		//uid 判断uid 存在与否
		//不存在 error 没有该会员
		//没有uid 个人空间权限把控
		//登录没登陆
		//没登陆跳到登陆页面
//var_dump(empty($guid));
//var_dump($_G['uid']);

if(empty($guid)){//没有$_GET['uid']的时候
		if($_G['uid']){
				$centeruid = intval($_G['uid']);//没有$_GET['uid'],有$_G['uid'],判断为自己看自己
				$data = DB::fetch_first("select username,groupid from ".DB::table("common_member")." where uid = {$centeruid}");

				$user_info['username'] = $data['username'];
		}else{
				showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
				header("Location:/login.html");				
		}
}else{//有$_GET['uid']的时候

		//无论是否登录，均可查看注册会员，判断被查看人是否存在
		$data = DB::fetch_first("select username,groupid from ".DB::table("common_member")." where uid = $guid");

		if(!$data){
		 	showmessage(lang('hr/template', '该艺人不存在或已删除'));
		}else{
			$user_info['username'] = $data['username'];
			$centeruid = $guid;
		}
		
}

//查看用户关系
/*
if($guid != $_G['uid']){
		 $guanzhu = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = {$_G['uid']} and followuid = $guid");//不为空，则用户关注了被查看人
		 $fensi = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = $guid and followuid = {$_G['uid']}");//不为空，则被查看人是该用户的粉丝
		 if(!empty($guanzhu)){
				if(!empty($fensi)){
						$flag2 = 1;//互相关注
				}else{
						$flag2 = 2;//当前用户是被查看人的粉丝
				}
		 }else{
				if(!empty($fensi)){
						$flag2 = 3;//被查看人是当前用户的粉丝
				}else{
						$flag2 = 4;//两人没有任何关系
				}
		 }
}
*/

//2.获取会员groupid
$group = $data['groupid'];//会员类别 21个人会员 22机构会员

//添加访问记录
//3.判断存在$_G['uid']时，添加访问记录


//$user_info = $center->get_user_info($group,$centeruid);


$uid = intval($_G['uid']);
if(!empty($_G['uid']) && $_G['uid'] != $centeruid){//在登录状态，且不是自己看自己的状态下，添加访问记录
		$id = DB::fetch_first("select id from ".DB::table("home_visitor")." where uid =$centeruid  and vuid = $uid");
		if(empty($id)){
				$time = time();
				$query = DB::query("insert into ".DB::table("home_visitor")."(id,uid,vuid,dateline) values(null,$centeruid,$uid,$time)");
		}else{
				$lasttime = DB::fetch_first("select id,dateline from ".DB::table("home_visitor")." where uid = $centeruid and vuid = $uid order by dateline desc");
				$t_last = getdate($lasttime['dateline']);
				$time = time();
				$t_now = getdate($time);				
				if($t_now['mday'] > $t_last['mday']){
						$query = DB::query("insert into ".DB::table("home_visitor")."(id,uid,vuid,dateline) values(null,$centeruid,$uid,$time)");
				}else{
						$query = DB::query("update ".DB::table("home_visitor")." set dateline = $time where id = {$lasttime['id']}");
				}
		}
}

//获取用户基本信息
//获取用户信息

$user_info = $center->get_user_info($group,$centeruid);

if(mb_strlen($data['username'],'GB2312')>16){
					$user_info['username'] = mb_substr($data['username'],0,16,'GB2312')."..";
}else{
					$user_info['username'] = $data['username'];
}

$flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$centeruid);//判断是否已经加关注


//做个人他人标识

//4. 个人他人标识


	if(empty($centeruid) || $centeruid == $_G['uid']){
		$flag1 = 1;//自己看自己
	}else{
		$flag1 = 0;//看别人
	}
	if($guid){
		$age_year = DB::fetch_first("select birthyear from ".DB::table("common_member_profile")." where uid = $guid");
	}
	//var_dump($age_year["birthyear"]);
	/**
	 *  昵称标识
	 */
	if($centeruid == $_G['uid']){
		$nickstring = '我';
	}else{				
		if($user_info['xingbie'] == '2'){
			$nickstring = '她';
		}else{
			$nickstring = '他';			
		}
	}
	$count['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_space_liuyan')." where spaceuid = '{$_G['uid']}' and state = 0");
//根据 人才类别 来进不同入口
if($group == 22){
	require_once libfile('org/'.$do,'include');//organization 组织，机构
}else{
	require_once libfile('ucenter/'.$do, 'include');//个人用户入口
}
?>