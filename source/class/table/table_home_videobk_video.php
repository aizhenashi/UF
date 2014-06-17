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
		
		//ִ��insert ���
		$rs = DB::insert($this->_table, $datas);
		$id = DB::insert_id();
		return $id;
	}
	
	/**
	 * ͨ��where ���޸�����
	 * Enter description here ...
	 */
	public function updateForWhere($datas,$where){

		$sql = "update `".DB::table($this->_table)."` set title = '{$datas['title']}', sharepic = '{$datas['sharepic']}', flash_address = '{$datas['flash_address']}' where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}
	
	/**
	 * ͨ��where ����ȡһ������
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetch_one($where){

		$sql = "select id,flash_address,title,sharepic,bkid,time from `".DB::table($this->_table)."` where ".$where;
		$data = DB::fetch_first($sql);
		return $data;
	}
	
	/**
	 * ����Ƶ�����Ƶ�����һ����¼
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
	 * ͨ��where ��ɾ������
	 */
	
	function deleteforwhere($where){
		$sql = 'delete from `'.DB::table($this->_table).'` where '.$where;
		DB::query($sql);
	}
}

?>