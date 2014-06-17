<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_imagetype.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_liaotian_biaoqing extends discuz_table
{
	public function __construct() {

		$this->_table = 'liaotian_biaoqing';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * ͨ���������ȡ ��������ͼƬ
	 * @param unknown_type $groupArray  ����������
	 */
	public function getBiaoQingForGroupArray($groupArray){

		foreach($groupArray as $data){
			$array[$data['id']] = $this->getBiaoQingForGroupId($data['id']);			
		}
		
		return $array;
	}
	
	/**
	 * 
	 * ��ȡ������������Ƭ
	 * Enter description here ...
	 * @param unknown_type $Gid
	 */
	public function getBiaoQingForGroupId($Gid){
		return DB::fetch_all("SELECT `id`,`imgsrc` FROM %t WHERE `groupid` =%s order by `id` asc", array($this->_table,$Gid));				
	}
	
	/**
	 * ͨ��id ��ȡ�ֶε�ֵ
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getBiaoQingForId($id,$ziduan){
		return DB::result_first("SELECT {$ziduan} FROM %t WHERE id=%s ", array($this->_table, $id));
	}

}

?>