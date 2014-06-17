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

class table_liaotian_biaoqing_group extends discuz_table
{
	public function __construct() {

		$this->_table = 'liaotian_biaoqing_group';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 获取所有表情分组
	 * 
	 */
	public function getAllBiaoqingGroup(){
		return DB::fetch_all("SELECT `id`,`addr`,`name` FROM %t order by `order` asc", array($this->_table));
	}

}

?>