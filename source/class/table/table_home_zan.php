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

class table_home_zan extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_zan';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * 添加赞记录
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function insert_zan($data) {

		global $_G;

		$data['time'] = time();
		$data['uid'] = $_G['uid'];
		$data['statu'] = 1;
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
	 * 查找赞记录
	 */
	function select_zan($where = " 1"){
		
		$data = DB::fetch_all('SELECT `id`,`uid`,`shuoshuoid`,`fuid`,`time`,`statu` FROM '.DB::table($this->_table).' WHERE '.$where);
		
		return $data;
		
	}


	/**
	 * 修改 我赞的哪一条说说
	 */
	function updateZan($uid,$shuoid,$statu){
		
		$mystatu = $statu == '1'? -1: 1;
		$rs = DB::query("update `".DB::table($this->_table)."` set `statu` = '$mystatu' where `uid` = '{$uid}' && `shuoshuoid` = '{$shuoid}' ");
		
		if($statu == '1'){
			return 'del';
		}else if($statu == '-1'){
			return 'ins';
		}	
	}
	
	/**
	 * 获取赞总数
	 */
	function get_zan_count($where = '1'){
		$data = DB::fetch_first('SELECT count(*) as num FROM '.DB::table($this->_table).' WHERE '.$where);
		return $data['num'];		
	}
	
}

?>