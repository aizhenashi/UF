<?php

/**
 *   说说ajax提交模块 页 做action 分发 
 *
 */

//这个是页面跳转
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'getshuoshuo', //获取说说列表
	'sendshuoshuo', //发送说说
	'getALLpinglun', //获取评论
	'HuifuShuoShuo', //回复说说
	'HuifuShuoShuoAndRenhtml', //回复说说并且回复某人 html
	'HuifuShuoShuoAndRen', //回复说说并且回复某人 逻辑
	'zan', //赞
	'sendspaceliuyan', //发送留言
	'HuifuLiuyanAndRen', //回复留言并且回复人 逻辑
	'HuifuLiuyanAndRenhtml', //回复留言并且回复人 html 
	'playVideo', //播放视频
	'delshuoshuo' //删除我的说说
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	die('action error');
}

class shuoshuoMoudle{

	/**
	 * ajax取说说
	 */
	public function getshuoshuo(){
		
		$centeruid = $_GET['centeruid'];
		global $_G;
		if($_GET['type'] == 'all' || $_GET['type'] == 'guanzhu'){
			$uidarr = c::t('home_follow')->getdata('`followuid`',"uid = '{$centeruid}'");
			if($_GET['type'] == 'all'){				
				$uidarr[] = array('followuid'=>$centeruid);
			}

			if($uidarr){

				$where = "fid = '0'";
				//获取 uid数组
				$uidstr = "";
				foreach ($uidarr as $uid){
					$uidstr .= $uid['followuid'].',';
				}
				$uidstr = rtrim($uidstr,',');
				$where .= " && uid in ({$uidstr})";
			}else{
				$where = 'false';
			}
		}else if($_GET['type'] == 'my'){
			$where = "uid = '{$centeruid}' && fid = '0'";
		}
		//获取说说
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',$where);

		include template('diy:ucenter/shuoshuotem');

	}
	
	/*
	 * 删除说说
	 * */
	public function delshuoshuo(){
		
		global $_G;
		
		DB::query("DELETE FROM ".DB::table('home_shuoshuo')." where id={$_POST['shuoid']}");
	
		include template('diy:ucenter/ajaxshuoshuotem');
		
	}
	
	/**
	 * 发送说说
	 */
	public function sendshuoshuo(){
		
		global $_G;
		//入库
	
		$id = C::t('home_shuoshuo')->insert_shuoshuo($_POST);
		
        	    //同步发送到微博
        	     include_once( 'config.php' );//微博的包含文件
			include_once( 'saetv2.ex.class.php' );//微博的包含文件
			$content = $_POST['content'];
			//pre_weibokey密匙的表
	              $sql = "select * from pre_weibokey where uid=".$_G['uid'];
			
			$weibokey = DB::fetch_all($sql);
			if($weibokey)
			{	
				$c1 = new SaeTClientV2( WB_AKEY , WB_SKEY , $weibokey[0]["weibokey"] );//实例化包含文件的类
				
				$c1 ->update($content);//调用类里的update方法
			}
		
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"id  = '{$id}'");
		
		include template('diy:ucenter/ajaxshuoshuotem');
	}
	

	/**
	 *  ajax 获取评论框 及评论内容
	 * Enter description here ...
	 */
	public function getALLpinglun(){
		global $_G;
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();

		$where = "`fid` = '{$_POST['shuoid']}'" ;
		$Allpinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fuid`,`fid`,`uid`,`content`,`time`',$where);

		include template('diy:ucenter/shuoshuopinglun');
	}
	
	/**
	 * ajax 回复说说
	 */
	public function HuifuShuoShuo(){

		global $_G;
		$id = c::t('home_shuoshuo')->insert_shuoshuo($_POST);
		$pinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fid`,`uid`,`content`,`time`',"id = '{$id}'");
		$pinglun = $pinglun[0];

		include template('diy:ucenter/onepinglun');

	}	
	
	
	/**
	 * ajax 回复某人的留言 但也算是说说的一部分
	 * 1.回复某人
	 * 2.加入说说内容  
	 * Enter description here ...
	 */
	public function HuifuShuoShuoAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		$UNAME = c::t('common_member')->getOneInfo('username',"uid = '{$_POST['fuid']}'");
		
		include template('diy:home/ucenter_shuoshuo2');
	}
	
	/**
	 * ajax 回复某人的评论
	 */
	public function HuifuShuoShuoAndRen(){
		global $_G;
		
		$id = c::t('home_shuoshuo')->insert_shuoshuo($_POST);

		$pinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fid`,`fuid`,`uid`,`content`,`time`',"id = '{$id}'");
		$pinglun = $pinglun[0];
		
		include template('diy:ucenter/onepinglun');
				
	}
	
	/**
	 * ajax 赞
	 * Enter description here ...
	 */
	public function zan(){
		
		global $_G;

		if(!$_G['uid']){
			echo 'nologin';
			exit;
		}
		
		//说说id
		$shuoid = $_POST['shuoshuoid'];		
		
		//获取赞记录
		$data = c::t('home_zan')->select_zan(" uid = '{$_G['uid']}' && shuoshuoid = {$shuoid}");
		$data = $data[0];

		//没有赞记录 添加赞记录  statu=1 
		if($data === NULL){
			$data = $_POST;
			//获取这条说说的发布者
			$temp = c::t('home_shuoshuo')->select_shuoshuo("id = '{$shuoid}'");
			$data['fuid'] = $temp[0]['uid'];
			
			$id = c::t('home_zan')->insert_zan($data);	
			
			echo 'ins';
			
		}else{
		
			//有赞记录  查看  statu 的值
			
			//值 1 update statu = -1 取消赞记录
			//值 -1 update statu = 1 加赞记录
			$result = c::t('home_zan')->updateZan($_G['uid'],$shuoid,$data['statu']);
			
			echo $result;
		}	
	}
	
	/**
	 * 回复留言的同时并且回复人
	 * Enter description here ...
	 */
	public function HuifuLiuyanAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		$UNAME = c::t('common_member')->getOneInfo('username',"uid = '{$_POST['fuid']}'");
		
		include template('diy:home/ucenter_liuyanhuifu');
		
	}
	
	/**
	 * 发布空间留言并同时回复选中的那个人
	 */
	public function HuifuLiuyanAndRen(){

		global $_G;
		//空间留言表最后插入的id
		$id = c::t('home_space_liuyan')->insert_liuyan($_POST);

		if($id){
			$data = c::t('home_space_liuyan')->select_liuyan("id = '{$id}'");
			$huifu = $data[0];
		}
		
		include template('diy:ucenter/onehuifu');		
		
	}	
	
	/**
	 * 发送空间留言
	 * Enter description here ...
	 */
	public function sendspaceliuyan(){

		global $_G;
		
		//空间留言表最后插入的id
		$id = c::t('home_space_liuyan')->insert_liuyan($_POST);

		if($id){
			$data = c::t('home_space_liuyan')->select_liuyan("id = '{$id}'");
			$huifu = $data[0];
		}
		
		include template('diy:ucenter/onehuifu');
				
	}	
	
	/*
	 *  ajax 播放视频
	 */
	public function playVideo(){
		echo "<p class=\"medis_func S_txt3\">
				<a class=\"retract\" href=\"javascript:void(0);\">
				<em class=\"W_ico12 ico_retract\"></em>收起</a>
				<i class=\"W_vline\">|</i>
				<a target=\"_blank\" class=\"show_big\" title=\"{$_POST['title']}\" href=\"{$_POST['reslink']}\">
				<em class=\"W_ico12 ico_showbig\"></em>
				{$_POST['title']}</a>
			</p>
			<div style=\"text-align:center;min-height:18px;\">
				<div>
					<div>
					<embed width=\"440\" height=\"356\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" src=\"{$_POST['flash_address']}\" quality=\"high\" allowfullscreen=\"true\" flashvars=\"playMovie=true&amp;auto=1\" pluginspage=\"http://get.adobe.com/cn/flashplayer/\" style=\"visibility: visible;\" allowscriptaccess=\"never\" id=\"STK_1373958386245112\">
					</div>
				</div>
			</div>";

		exit;
		
	}	
}

$ajaxUcenter = new shuoshuoMoudle();
$ajaxUcenter->$do();
?>