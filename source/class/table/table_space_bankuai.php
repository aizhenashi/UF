<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_admincp_session.php 27803 2012-02-15 02:39:36Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_space_bankuai extends discuz_table
{
	public function __construct() {

		$this->_table = 'space_bankuai';
		$this->_pk    = 'id';

		parent::__construct();
	}

	/**
	 * 添加版块名称
	 * Enter description here ...
	 * @param $data
	 */
	public function insert_bankuai($data) {

		global $_G;
		
		if(!$_G['uid']){
			die('没有登录uid');
		}
		
		$data['uid'] = $_G['uid'];
		//准备insert 数据
		//版块名称
		$data['bankuainame'] = iconv('utf-8', 'gbk', $data['bankuainame']);
		//记录生成时间
		$data['time'] = time();

		//执行insert 语句
		$rs = DB::insert($this->_table, $data);
		return DB::insert_id();
		
	}	
	
	/**
	 * 通过where条件 获取一条 版块记录
	 * @see source/class/discuz/discuz_table::fetch()
	 */
	public function fetch_bk($where = '1') {

		$sql = 'SELECT `id`,`bankuainame`,`type`,`time` FROM '.DB::table($this->_table).' WHERE '.$where;
		return DB::fetch_first($sql);
	}

	public function fetch_all_by_panel($panel) {
		return DB::fetch_all('SELECT * FROM %t WHERE panel=%d', array($this->_table, $panel), 'uid');
	}

	/**
	 * 通过where删除记录
	 * @see source/class/discuz/discuz_table::delete()
	 */
	public function delete($where = 1) {

	 	$sql = 'DELETE FROM `'.DB::table($this->_table).'` WHERE '.$where;
		DB::query($sql);

	}
	
	public function deletebk($bkid,$bktype = ''){
		if($bktype === ''){
			//从版块表 查找数据type
		}
		
		$rs = $this->delete("`id` = '{$bkid}'");
		
		if ($bktype == '1'){ //文字
			c::t('home_bktext_content')->deleteforwhere("bkid = '{$bkid}'");
		}else if($bktype == '2'){ //图片 
			c::t('home_picbk_pic')->delforwhere("bkid = '{$bkid}'");
		}else if($bktype == '3'){ //视频
			c::t('home_videobk_video')->deleteforwhere("bkid = '{$bkid}'");
		}
		
		return true;
		
	}

	public function updateName($bkname,$where = '') {
		if($where == ''){
			return false;
		}
		
		$sql = "update `".DB::table($this->_table)."` set `bankuainame` = '{$bkname}' where ".$where;
		DB::query($sql);
		
		return 1;

	}

}

?>