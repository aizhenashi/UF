<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_admincp_member.php 27740 2012-02-13 10:05:22Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_videobk_video extends discuz_table
{

	public function __construct() {

		$this->_table = 'home_videobk_video';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function insert_data($datas) {
		
		$datas['time'] = time();
		
		//执行insert 语句
		$rs = DB::insert($this->_table, $datas);
		$id = DB::insert_id();
		return $id;
	}
	
	/**
	 * 通过where 来修改数据
	 * Enter description here ...
	 */
	public function updateForWhere($datas,$where){

		$sql = "update `".DB::table($this->_table)."` set title = '{$datas['title']}', sharepic = '{$datas['sharepic']}', flash_address = '{$datas['flash_address']}' where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}
	
	/**
	 * 通过where 来获取一条数据
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetch_one($where){

		$sql = "select id,flash_address,title,sharepic,bkid,time from `".DB::table($this->_table)."` where ".$where;
		$data = DB::fetch_first($sql);
		return $data;
	}
	
	/**
	 * 向视频板块视频表添加一条记录
	 * Enter description here ...
	 * @param unknown_type $datas
	 */
	public function insert_video($datas){
		$data = $this->fetch_one("bkid = '{$datas['bkid']}'");
		if($data){
			$rs = $this->updateForWhere($datas,"bkid = '{$datas['bkid']}'");

			return $rs;
		}else{
			$id = $this->insert_data($datas);
		}
		return $id;
	}

	/**
	 * 通过where 来删除数据
	 */
	
	function deleteforwhere($where){
		$sql = 'delete from `'.DB::table($this->_table).'` where '.$where;
		DB::query($sql);
	}
}

?>