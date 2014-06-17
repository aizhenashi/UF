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

class table_home_space_liuyan extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_space_liuyan';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * �����Ա�����һ����¼
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function insert_liuyan($data) {

		global $_G;
		require_once libfile('function_shuoshuo','function');
		//��content ת�� utf-8 ת gb2312 ��ȥ�ո�
		$data['content'] = defineIconv($data['content']);
		$data['time'] = time();
		$data['uid'] = $_G['uid'];

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
	 * ��ԭcontent
	 * Enter description here ...
	 * @param unknown_type $content
	 */
	public function huanYuanContent($content){

		//��ԭ����
		$content = $this->huanYuanBiaoQing($content);

		return $content;
	}
	
	/**
	 * ��ԭ����
	 */
	public function huanYuanBiaoQing($content){
		//1.ȡ������ ͼƬid ����
		preg_match_all("/\[img:(\d+)\]/", $content, $matches);
		$picArr = $matches[1];
		
		//2.���� ͼƬid ���� ȡ�� ����ͼƬ��ַ
		$biaoqingObj = c::t('liaotian_biaoqing');

		foreach ($picArr as $picid){
			$temp = $biaoqingObj->getBiaoQingForId($picid,'`imgsrc`');
			$imgarr[] = "<img src=\"{$temp}\" />";
		}

		//3. ��[img:num] �滻��Ϊ ������ͼƬ
		$content = str_replace($matches[0],$imgarr,$content);

		return $content;
	}
	
	/**
	 * ͨ��where ��ȡ����
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_liuyan($where = '1'){

		$datas = DB::fetch_all("select `id`,`content`,`uid`,`spaceuid`,`time`,`fuid` from ".DB::table($this->table)." where {$where}");
		
		foreach ($datas as &$data){
			$data['content'] = $this->huanYuanContent($data['content']);
			$data['username'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['uid']}'");
			
			//����б��ظ��˵�uid
			if($data['fuid']){
				$data['funame'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['fuid']}'");
			}			
		}		
		
		return $datas;
	}
	
	public function deleteforWhere($where){
		DB::query("delete from `".DB::table($this->_table)."` where ".$where);
		
	}

}

?>