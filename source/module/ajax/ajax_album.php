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
 * getAllPhoto 获取该相册id下所有照片
 */

$dos = 
array(
	'getAllPhoto',
	'getBigpic', //图片详细页 大图显示
	'insertAlbumPinglun', //添加对照片的评论
	'getPingLunContent', //根据照片id 获取评论数及相关评论
	'likepic', //相册对照片的喜欢
	'getOnePinglun', //获取一条回复
	'HuifuPinglunAndRenhtml', //回复评论的同时 回复人 html
	'HuifuLiuyanAndRen', //回复评论的同时 回复人 逻辑
	'getNextPic', //获取下一张图片的picid
	'getprevPic', //获取上一张图片的picid
	'albumRightPicList', //获取图片右边列表
	'delpicforid', //通过照片id 来删除照片
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class albumMoudle{

	/**
	 * 通过相册id获取该相册下所有照片
	 * Enter description here ...
	 */
	public function getAllPhoto(){

		global $_G;

		$datas = c::t('home_pic')->fetch_All_by_where("albumid = '".$_POST['albumid']."' && uid = '{$_G['uid']}'");

		
		include template('diy:album/ajax/albumpiclist');
		
	}
	
	//图片详细页大图显示
	public function getBigpic(){
		
		$datas = c::t('home_pic')->fetch_All_by_where("picid='{$_POST['picid']}'");
		$data = $datas[0];
		$data['dateline'] = date('n-j',$data['dateline']);
		$data['dateline'] = iconv('GB2312', 'UTF-8', str_replace('-', '月', $data['dateline']).'日');
		$data['title'] = iconv( 'GB2312','utf-8', $data['title']);
		//总的评论条数 
		$counts = c::t('home_album_shuoshuo')->counts("picid = '{$_POST['picid']}'");
		$data['pinglunCounts'] = $counts;
		
		//总的喜欢数
		$counts = c::t('home_album_like')->counts("picid = '{$_POST['picid']}'");
		$data['likeCounts'] = $counts;
		$sizeinfo = getimagesize(DISCUZ_ROOT.'data/attachment/'.$data['thumb690']);
		$data['size'] = $sizeinfo;
		die(json_encode($data));
	}
	
	//添加对照片的评论
	public function insertAlbumPinglun(){

		$id = c::t('home_album_shuoshuo')->insert_pinglun($_POST);
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("id = '{$id}'");
		$data = $datas[0];
		include template('diy:album/pinglunhuifu');
	}

	//根据picid来获取相关评论
	public function getPingLunContent(){

		$page = $_GET['page']?$_GET['page'] : 1;
		$pagenum = 8;
		//总的记录条数 用于js分页
		$counts = c::t('home_album_shuoshuo')->counts("picid = '{$_POST['picid']}'");

		$offset = ($page-1)*$pagenum;
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("picid = '{$_POST['picid']}' order by id desc limit {$offset},{$pagenum}");

		include template('diy:album/pinglunhuifus');
		
	}
	
	//谁谁对照片的喜欢
	// 必须登录
	public function likepic(){
		global $_G;
		$uid = $_G['uid'];
		
		if(!$uid){
			die('该操作必须登录');
		}
		$data = c::t('home_album_like')->select_rows("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}'");

		if($data){
			$id = c::t('home_album_like')->deleteForWhere("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}'");

			//删除对该照片喜欢的评论
			c::t('home_album_shuoshuo')->deleteforwhere("picid = '{$_POST['picid']}' && type = '2' && uid = '{$_G['uid']}'");
			$statu = 2;
		}else{
			$id = c::t('home_album_like')->insert_like($_POST);
			//type = 2 对照片的喜欢
			c::t('home_album_shuoshuo')->insert_pinglun($_POST,2);
			$statu = 1;
		}

		echo $statu;
		
	}
	
	/**
	 * 获取一条回复
	 */
	public function getOnePinglun(){
		
		global $_G;
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}' && type = '2'");
		$data = $datas[0];
		
		include template('diy:album/pinglunhuifu');		
		
	}
	
	/**
	 * 回复评论的同时并且回复人
	 * Enter description here ...
	 */
	public function HuifuPinglunAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		
		include template('diy:album/album_liuyanhuifu');
		
	}	
	
	/**
	 * 发布评论并同时回复选中的那个人
	 */
	public function HuifuLiuyanAndRen(){
		
		global $_G;
		$id = c::t('home_album_shuoshuo')->insert_pinglun($_POST);

		if($id){
			$datas = c::t('home_album_shuoshuo')->select_shuoshuo("id = '{$id}'");
			$data = $datas[0];
		}
		
		include template('diy:album/pinglunhuifu');		
				
	}	
	
	/**
	 * 获取下一张图片的picid
	 * $_POST['albumid'] 相册id
	 * $_POST['picid'] 图片id
	 * Enter description here ...
	 */
	public function getNextPic(){
		$datas = c::t('home_pic')->fetch_All_by_where("picid < '{$_POST['picid']}' && albumid = '{$_POST['albumid']}' order by picid desc");
		$data = $datas[0];

		//输出picid
		die($data['picid']);
	}

	/**
	 * 获取上一张图片
	 * $_POST['albumid'] 相册id
	 * $_POST['picid'] 图片id
	 * Enter description here ...
	 */
	public function getprevPic(){
		$datas = c::t('home_pic')->fetch_All_by_where("picid > '{$_POST['picid']}' && albumid = '{$_POST['albumid']}' order by picid asc");
		$data = $datas[0];

		//输出picid
		die($data['picid']);
	}
	
	/**
	 * 右边相册图片列表
	 * 获取 该相册下的九张照片
	 * picid desc
	 */
	public function albumRightPicList(){

		$picid = $_POST['picid'];
		$albumid = $_POST['albumid'];


		//front limit
		$data = DB::fetch_first("select count(`picid`) as tot from `".DB::table('home_pic')."` where picid < '{$picid}' && albumid = '{$albumid}'");
		$bget = $data['tot'];
		$flimit = $bget >= 4 ? 4 : 4+(4-$bget); 

		//该照片的前几张照片 至多取4张 
		$fdatas = DB::fetch_all("select `picid` from `".DB::table('home_pic')."` where picid > '{$picid}' && albumid = '{$albumid}' order by picid asc limit {$flimit}");

		//将自身加入
		$fdatas[] = array('picid'=>$picid);

		//bottom limit 
		$data = DB::fetch_first("select count(`picid`) as tot from `".DB::table('home_pic')."` where picid > '{$picid}' && albumid = '{$albumid}'");
		$fget = $data['tot'];
		$blimit = $fget >= 4 ? 4 : 4+(4-$fget);

		//取后几张图片
		$bdatas = DB::fetch_all("select `picid` from `".DB::table('home_pic')."` where picid < '{$picid}' && albumid = '{$albumid}' order by picid desc limit {$blimit}");

		$datas = array_merge($fdatas,$bdatas);

		$c = count($datas);
		for($i = 0;$i<$c;$i++){
			for($j = 0; $j<$c-$i-1; $j++){

				// desc order
				if((int) $datas[$j]['picid'] < (int) $datas[$j+1]['picid']){
					$temp = $datas[$j];
					$datas[$j] = $datas[$j+1];
					$datas[$j+1] = $temp;
				}
			}
		}

//		db::query("select count(`picid`) from `".DB::table('home_pic')."` where picid > ");

		//获取 照片80缩略图 ,picid
		foreach ($datas as $data){
			$temp = DB::fetch_first("select `picid`,`thumb80` from `".DB::table('home_pic')."` where picid = '{$data['picid']}'");
			$array[] = $temp;
		}

		include template('diy:album/thumbpic');

	}
	
	/**
	 * 通过照片id来删除相关资源
	 * Enter description here ...
	 */
	public function delpicforid(){
		$result = c::t('home_pic')->delPicForPicid($_POST['picid']);
		echo $result;
		exit;

	}

}

$ajaxUcenter = new albumMoudle();
$ajaxUcenter->$do();
?>