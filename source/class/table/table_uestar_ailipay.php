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

class table_uestar_ailipay extends discuz_table
{
	public function __construct() {

		$this->_table = 'uestar_ailipay';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * ��uestar ֧�������׼�¼�����һ����¼
	 */
	public function insertdata($data){

		return DB::insert($this->_table, $data);		
	}
	
	/**
	 * ��֧�������׼�¼���ȡһ����¼
	 */
	public function getDataForWhere($where){
		$sql = "select `product_id`,`class_id`,`orderno`,`statu`,`price`,`uid` from `".DB::table($this->_table)."` where ".$where;
		$data = DB::fetch_first($sql);

		return $data;
	}
	
	/**
	 * �޸�һ�����׼�¼ͨ��������¼
	 */
	public function updateStatuForWhere($orderno){

		$sql = "update `".DB::table($this->_table)."` set statu = '1' where orderno = '".$orderno."'";
		return DB::query($sql);
	}

}

?>