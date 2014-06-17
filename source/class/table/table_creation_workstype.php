<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_member_count.php 29977 2012-05-04 07:14:48Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_creation_workstype extends discuz_table_archive
{
	public function __construct() {

		$this->_table = 'creation_workstype';

		parent::__construct();
	}
	
	/**
	 * 通过tid获取某条记录
	 * Enter description here ...
	 * @param unknown_type $tid
	 */
	public function get_data_for_tid($tid){
		if(!$tid){
			die('error : no tid');
		}
		
		$sql = "select `tid`,`wid`,`wname` from `".DB::table($this->_table)."` where tid = '{$tid}' order by wid asc";
		$datas = DB::fetch_all($sql);
		
		return $datas;
	}
	
	/**
	 * 通过wid获取视频的某条记录
	 */
	public function get_data_for_wid($wid){
		
		$sql = "select `tid`,`wid`,`wname` from `".DB::table($this->_table)."` where tid = '4' && wid = '{$wid}'";
		$data = DB::fetch_first($sql); 
		
		return $data;
	}

}

?>