<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_feed.php 28335 2012-02-28 04:37:47Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_album_shuoshuo extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_album_shuoshuo';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * 添加说说
	 * Enter description here ...
	 * @param unknown_type $data
	 * $type 1 对照片的评论
	 * $type 2 对照片的喜欢
	 * 
	 */
	public function insert_pinglun($data,$type = 1) {

		global $_G;
		if(!$_G['uid']){
			die('没有uid');
		}

		require_once libfile('function_shuoshuo','function');
		//将content 转码 utf-8 转 gb2312 并去空格
		$data['content'] = defineIconv($data['content']);
		$data['time'] = time();
		$data['uid'] = $_G['uid'];
		// 1 评论  2对照片的喜欢
		$data['type'] = $type;

		if(!empty($data) && is_array($data)) {
			$rs = DB::insert($this->_table, $data, false, true);
			if($rs == '1'){
				$id = DB::insert_id();				
			}
			return $id;
		}
		return 0;
	}	
	
	/**
	 * 还原content
	 * Enter description here ...
	 * @param unknown_type $content
	 */
	public function huanYuanContent($content){
		
		
		//还原表情
		$content = $this->huanYuanBiaoQing($content);
		
		return $content;
	}
	
	/**
	 * 还原喜欢列表
	 * uid $uid
	 */
	public function huanyuanLikeList($uid){
		$datas = c::t('home_album_like')->select_rows("uid = '{$uid}' order by id desc limit 5");
		$piccounts = c::t('home_album_like')->counts("uid = '{$uid}'");
		$arr['piccount'] = $piccounts;
		
		foreach ($datas as $data){
			$picid = $data['picid'];
			$temp = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");
			$temp = $temp[0];
			$arr['picinfo'][] = $temp;
		}
		
		return $arr;

	}

	/**
	 * 还原表情
	 */
	public function huanYuanBiaoQing($content){
		//1.取出所有 图片id 数组
		preg_match_all("/\[img:(\d+)\]/", $content, $matches);
		$picArr = $matches[1];
		
		//2.按照 图片id 数组 取出 所有图片地址
		$biaoqingObj = c::t('liaotian_biaoqing');

		foreach ($picArr as $picid){
			$temp = $biaoqingObj->getBiaoQingForId($picid,'`imgsrc`');
			$imgarr[] = "<img src=\"{$temp}\" />";
		}

		//3. 将[img:num] 替换成为 真正的图片
		$content = str_replace($matches[0],$imgarr,$content);

		return $content;
	}

	/**
	 * 通过where 获取说说
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_shuoshuo($where = '1',$chuli = true){

		$sql = "select `id`,`content`,`uid`,`picid`,`time`,`fuid`,`type` from ".DB::table($this->table)." where {$where}";
		$datas = DB::fetch_all($sql);

		if($chuli == true){
			foreach ($datas as $key=>$data){

				//发布评论的人 账号
				$data['username'] = c::t('common_member')->getOneInfo('`username`',"uid = '{$data['uid']}'");
				
				//发布评论的人的性别
				$temp1 = c::t('common_member_profile')->select_rows('gender',"uid = '{$data['uid']}'");
				$data['sex'] = $temp1[0]['gender'];
				$data['sexchinese'] = $data['sex'] == '2' ? '她' : '他';
				
				//如果有被回复人的uid
				if($data['fuid']){
					$data['funame'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['fuid']}'");
				}
				
				if($data['type'] == '1'){
					$data['content'] = $this->huanYuanContent($data['content']);
				}else if($data['type'] == '2'){
					$data['likedata'] = $this->huanyuanLikeList($data['uid']);
				}
				$temp[$key] = $data;

			}

			return $temp;
		}
		
		return $datas;
	}
	
	/**
	 * 获取总条数count通过where
	 */
	public function counts($where){
		
		$sql = "select count(`id`) as tot from ".DB::table($this->table)." where {$where}";
		$data = DB::fetch_first($sql);
		return $data['tot'];
	}
	
	
	public function deleteforwhere($where){
		$sql = "delete from `".DB::table($this->_table)."` where ".$where;
		return DB::query($sql);
	}
}

?>