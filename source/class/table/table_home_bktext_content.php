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

class table_home_bktext_content extends discuz_table
{

	public function __construct() {

		$this->_table = 'home_bktext_content';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function insert_row($datas) {

		//׼��insert ����
		$datas['time'] = time();

		$datas['content'] = htmlentities($datas['content'],ENT_QUOTES,'GB2312');
		//ִ��insert ���
		$rs = DB::insert($this->_table, $datas);
		if($rs){
			return DB::insert_id();
		}

	}

	/**
	 * ͨ��where ��ȡ ��������
	 */	
	public function fetchRow($where){	
		$sql = "select `id`,`bkid`,`content`,`time` from `".DB::table($this->_table)."` where ".$where;
		
		$data =	DB::fetch_first($sql);
		if($data){
			$data['content'] = html_entity_decode($data['content'],ENT_QUOTES,'GB2312');		
			
		}
		return $data;
		
	}

	/**
	 * ͨ��where ���޸� content
	 */
	public function updateforwhere($where,$content){
		$content = htmlentities($content,ENT_QUOTES,'GB2312');
		
		$sql = "update `".DB::table($this->_table)."` set content = '{$content}' where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}

	/**
	 * ͨ��where��ɾ��
	 */
	public function deleteforwhere($where){
		$sql = 'delete from `'.DB::table($this->_table).'` where '.$where;
		$rs = DB::query($sql);
		return $rs;
	}
	
}

?>