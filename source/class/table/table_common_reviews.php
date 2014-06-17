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

class table_common_reviews extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_reviews';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * 向留言表添加一条记录
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function insert_pinglun($data) {

		global $_G;
		require_once libfile('function_shuoshuo','function');
		//将content 转码 utf-8 转 gb2312 并去空格
		$data['articles'] = defineIconv($data['articles']);
		$data['times'] = time();
		$data['uid'] = $_G['uid'];
		
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
	 * 通过where 获取留言
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_pinglun($where = '1'){

		$datas = DB::fetch_all("select `id`,`articles`,`uid`,`actionId`,`times`,`fuid` from ".DB::table($this->table)." where {$where}");

		foreach ($datas as &$data){
			$data['articles'] = $this->huanYuanContent($data['articles']);
			$data['username'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['uid']}'");

			//如果有被回复人的uid
			if($data['fuid']){
				$data['funame'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['fuid']}'");
			}
		}
		
		return $datas;
	}

}

?>