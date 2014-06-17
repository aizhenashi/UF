<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_pic.php 31180 2012-07-24 03:51:03Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_creation_purchased extends discuz_table
{
	public function __construct() {

		$this->_table = 'creation_purchased';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function insertdata($data){
		
		//该记录的添加时间
		$data['createtime'] = time();
		
		$id = DB::insert($this->_table, $data,true);
		return $id;
	}
	
	/**
	 * 通过where条件来获取数据
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function getDataForWhere($where){
		$sql = "select id, product_id , uid, price, product_class from `".DB::table($this->_table)."` where ".$where;
		$datas = DB::fetch_all($sql);
		return $datas;
		
	}
	

}

?>