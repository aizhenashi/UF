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

class table_myspace_bankuai extends discuz_table
{
	public function __construct() {

		$this->_table = 'myspace_bankuai';
		$this->_pk    = 'id';

		parent::__construct();
	}

	/**
	 * 表
	 * 添加版块名称
	 * Enter description here ...
	 * @param $data
	 */
	public function insert_myspacebk($data) {
		
		global $_G;
		
		//准备insert 数据
		//版块排序字符串
		$data['bkstring'] = rtrim($data['bkstring'],',');
		//记录生成时间
		$data['time'] = time();
		$data['uid'] = $_G['uid'];
				
		//执行insert 语句
		$rs = DB::insert($this->_table, $data);
		return DB::insert_id();
		
	}
	
	/**
	 * 更改我的版块顺序
	 * @see source/class/discuz/discuz_table::fetch()
	 */	
	public function updatemybkorder($data){
		
		global $_G;
		$datarow = $this->fetch_myspacerow("uid = '{$_G['uid']}'");
		
		if(!$datarow){
			$data['time'] = time();
			$data['uid'] = $_G['uid'];
			
			
			$id = $this->insert_myspacebk($data);

			if(!$id){
				die('errorinsertmyspace_bankuai');
			}
		}else{
			$data['bkstring'] = rtrim($data['bkstring'],',');

			$rs = $this->update("`bkstring` = '{$data['bkstring']}'", " id = '{$datarow['id']}'");
			
			if(!$rs){
				die('errorupdatemyspace_bankuai');
			}
		}
		
	}
	
	/**
	 * 查询一条空间的记录
	 * Enter description here ...
	 * @param unknown_type $where
	 */	
	public function fetch_myspacerow($where = '1') {
		$sql = 'SELECT `id`,`uid`,`bkstring`,`time` FROM `'.DB::table($this->_table).'` WHERE '.$where;
		return DB::fetch_first($sql);
	}
	
	/**
	 * 查询我的所有板块记录
	 * Enter description here ...
	 * @param unknown_type $panel
	 */
	public function get_myspace_bkinfo($centeruid){
		echo $where;
		global $_G;
		$row = $this->fetch_myspacerow("uid = '{$centeruid}'");
				
		if($row['bkstring']){
			$array = explode(',', $row['bkstring']);
		}	
		
		
		
		foreach ($array as $data){
			if($data == 'shuoshuo'){
				$datas[] = array('id'=>'shuoshuo');
				continue;
			}
			$temp = c::t('space_bankuai')->fetch_bk("id = '{$data}'");
			
			
			if($temp['type'] == '1'){
				//从文字版块内容表 调出该版块的文字内容
				$temp1 = c::t('home_bktext_content')->fetchRow("bkid = '{$temp['id']}'");
				$temp['content'] = $temp1['content'];
			}
			
			if($temp['type'] == '2'){
				//图片版块从图片版块 pic 表 查出该版块下所有图片
				$temp['content'] = c::t('home_picbk_pic')->fetchAll("bkid = '{$temp['id']}'");
			}
			
			if($temp['type'] == '3'){
				//视频版块从视频版块视频 表 读取当前版块 的视频
				$temp['content'] = c::t('home_videobk_video')->fetch_one("bkid = '{$temp['id']}'");
			}
			//2014 1-9 增加取音频内容(读取当前版块的音频)
		
			if($temp['type'] == '4'){
				$temp['content'] = c::t('home_voice')->fetch_one("bkid = '{$temp['id']}'");
			}

			$datas[] = $temp;
		}

		if($datas === NULL){
			$datas[] = array('id'=>'shuoshuo');
		}
		
		return $datas;
		
	}
	
	




	public function update($coloum,$where) {
		
		$sql = "update `".DB::table($this->_table)."` set ".$coloum.' where '.$where;
		$rs = DB::query($sql);
		
		return $rs;
	}

}

?>