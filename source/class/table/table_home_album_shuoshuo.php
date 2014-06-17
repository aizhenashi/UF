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

class table_home_album_shuoshuo extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_album_shuoshuo';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * ���˵˵
	 * Enter description here ...
	 * @param unknown_type $data
	 * $type 1 ����Ƭ������
	 * $type 2 ����Ƭ��ϲ��
	 * 
	 */
	public function insert_pinglun($data,$type = 1) {

		global $_G;
		if(!$_G['uid']){
			die('û��uid');
		}

		require_once libfile('function_shuoshuo','function');
		//��content ת�� utf-8 ת gb2312 ��ȥ�ո�
		$data['content'] = defineIconv($data['content']);
		$data['time'] = time();
		$data['uid'] = $_G['uid'];
		// 1 ����  2����Ƭ��ϲ��
		$data['type'] = $type;

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
	 * ��ԭϲ���б�
	 * uid $uid
	 */
	public function huanyuanLikeList($uid){
		$datas = c::t('home_album_like')->select_rows("uid = '{$uid}' order by id desc limit 5");
		$piccounts = c::t('home_album_like')->counts("uid = '{$uid}'");
		$arr['piccount'] = $piccounts;
		
		foreach ($datas as $data){
			$picid = $data['picid'];
			$temp = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");
			$temp = $temp[0];
			$arr['picinfo'][] = $temp;
		}
		
		return $arr;

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
	 * ͨ��where ��ȡ˵˵
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_shuoshuo($where = '1',$chuli = true){

		$sql = "select `id`,`content`,`uid`,`picid`,`time`,`fuid`,`type` from ".DB::table($this->table)." where {$where}";
		$datas = DB::fetch_all($sql);

		if($chuli == true){
			foreach ($datas as $key=>$data){

				//�������۵��� �˺�
				$data['username'] = c::t('common_member')->getOneInfo('`username`',"uid = '{$data['uid']}'");
				
				//�������۵��˵��Ա�
				$temp1 = c::t('common_member_profile')->select_rows('gender',"uid = '{$data['uid']}'");
				$data['sex'] = $temp1[0]['gender'];
				$data['sexchinese'] = $data['sex'] == '2' ? '��' : '��';
				
				//����б��ظ��˵�uid
				if($data['fuid']){
					$data['funame'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['fuid']}'");
				}
				
				if($data['type'] == '1'){
					$data['content'] = $this->huanYuanContent($data['content']);
				}else if($data['type'] == '2'){
					$data['likedata'] = $this->huanyuanLikeList($data['uid']);
				}
				$temp[$key] = $data;

			}

			return $temp;
		}
		
		return $datas;
	}
	
	/**
	 * ��ȡ������countͨ��where
	 */
	public function counts($where){
		
		$sql = "select count(`id`) as tot from ".DB::table($this->table)." where {$where}";
		$data = DB::fetch_first($sql);
		return $data['tot'];
	}
	
	
	public function deleteforwhere($where){
		$sql = "delete from `".DB::table($this->_table)."` where ".$where;
		return DB::query($sql);
	}
}

?>