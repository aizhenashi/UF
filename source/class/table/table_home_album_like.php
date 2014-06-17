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

class table_home_album_like extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_album_like';
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
	public function insert_like($data) {

		global $_G;
		if(!$_G['uid']){
			die('没有uid');
		}
		
		$data['time'] = time();
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
	 * 通过where 获取说说
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_rows($where = '1'){

		$sql = "select `id`,`uid`,`picid`,`time` from ".DB::table($this->table)." where {$where}";
		$datas = DB::fetch_all($sql);

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
	
	/**
	 * 通过where删除记录
	 * Enter description here ...
	 */
	public function deleteForWhere($where){
		
		$sql = "delete from ".DB::table($this->_table)." where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}
}

?>