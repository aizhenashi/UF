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

class table_home_pic extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_pic';
		$this->_pk    = 'picid';

		parent::__construct();
	}

	public function update_click($picid, $clickid, $incclick) {
		$clickid = intval($clickid);
		if($clickid < 1 || $clickid > 8 || empty($picid) || empty($incclick)) {
			return false;
		}
		return DB::query('UPDATE %t SET click'.$clickid.' = click'.$clickid.'+\'%d\' WHERE picid = %d', array($this->_table, $incclick, $picid));
	}
	public function update_hot($picid, $num = 1) {
		return DB::query('UPDATE %t SET hot=hot+\'%d\' WHERE picid=%d', array($this->_table, $num, $picid));
	}
	public function update_sharetimes($picid, $num = 1) {
		return DB::query('UPDATE %t SET sharetimes=sharetimes+\'%d\' WHERE picid=%d', array($this->_table, $num, $picid));
	}
	public function fetch_all_by_uid($uids, $start = 0, $limit = 0, $picids = 0) {
		if(empty($uids)) {
			return array();
		}
		$picidsql = $picids ? DB::field('picid', $picids).' AND ' : '';
		return DB::fetch_all("SELECT * FROM %t WHERE $picidsql ".DB::field('uid', $uids).DB::limit($start, $limit), array($this->_table));
	}
	public function update_for_uid($uids, $picids, $data) {
		if(!empty($data) && is_array($data)) {
			return DB::update($this->_table, $data, DB::field('picid', $picids).' AND '.DB::field('uid', $uids));
		}
		return 0;
	}
	public function fetch_all_by_albumid($albumids, $start = 0, $limit = 0, $picids = 0, $orderbypicid = 0, $orderbydateline = 0, $uid = 0, $count = false) {
		$albumids = $albumids < 0 ? 0 : $albumids;
		$picidsql = $picids ? DB::field('picid', $picids).' AND ' : '';
		if($orderbypicid) {
			$ordersql = 'ORDER BY picid DESC ';
		} elseif($orderbydateline) {
			$ordersql = 'ORDER BY dateline DESC ';
		}
		$uidsql = $uid ? ' AND '.DB::field('uid', $uid) : '';
		if ($count) {
			return DB::result_first("SELECT COUNT(*) FROM %t WHERE $picidsql ".DB::field('albumid', $albumids)." $uidsql", array($this->_table));
		} else {
			return DB::fetch_all("SELECT * FROM %t WHERE $picidsql ".DB::field('albumid', $albumids)." $uidsql $ordersql".DB::limit($start, $limit), array($this->_table));
		}
	}
	public function update_for_albumid($albumid, $data) {
		if(!empty($data) && is_array($data)) {
			return DB::update($this->_table, $data, DB::field('albumid', $albumid));
		}
		return 0;
	}
	public function delete_by_uid($uids) {
		return DB::query("DELETE FROM %t WHERE ".DB::field('uid', $uids), array($this->_table));
	}
	public function delete_by_albumid($albumids) {
		return DB::query("DELETE FROM %t WHERE ".DB::field('albumid', $albumids), array($this->_table));
	}
	public function fetch_all_by_sql($where = '1', $orderby = '', $start = 0, $limit = 0, $count = 0, $joinalbum = 1) {
		if(!$where) {
			$where = '1';
		}
		if($count) {
			return DB::result_first("SELECT count(*) FROM ".DB::table($this->_table)." p WHERE %i", array($where));
		}
		return DB::fetch_all("SELECT ".($joinalbum ? 'a.*, ' : '')."p.* FROM ".DB::table($this->_table)." p ".($joinalbum ? "LEFT JOIN ".DB::table('home_album')." a USING(albumid)" : '')." WHERE %i ".($orderby ? "ORDER BY $orderby " : '').DB::limit($start, $limit), array($where));
	}
	public function fetch_albumpic($albumid, $uid) {
		return DB::fetch_first("SELECT filepath, thumb FROM %t WHERE albumid=%d AND uid=%d ORDER BY thumb DESC, dateline DESC LIMIT 0,1", array($this->_table, $albumid, $uid));
	}
	public function check_albumpic($albumid, $status = NULL, $uid = 0) {

		$sql = is_numeric($albumid) ? DB::field('albumid', $albumid) : '';
		$sql .= $uid ? ($sql ? ' AND ' : '').DB::field('uid', $uid) : '';
		$sql .= $status === NULL ? '' : ($sql ? ' AND ' : '').DB::field('status', $status);

		return DB::result_first("SELECT COUNT(*) FROM %t WHERE $sql", array($this->_table));
	}
	public function count_size_by_uid($uid) {
		return DB::result_first("SELECT SUM(size) FROM %t WHERE uid=%d", array($this->_table, $uid));
	}
	public function fetch_by_id_idtype($id) {
		if(!$id) {
			return false;
		}
		return DB::fetch_first('SELECT * FROM %t WHERE %i', array($this->_table, DB::field('picid', $id)));
	}
	public function update_dateline_by_id_idtype_uid($id, $idtype, $dateline, $uid) {
		if(empty($id) || empty($idtype) || empty($dateline) || empty($uid)) {
			return false;
		}
		return DB::update($this->_table, array('dateline' => intval($dateline)), array($idtype => intval($id), 'uid' => intval($uid)));
	}

	/**
	 * 通过where条件获取多条数据
	 */
	public function fetch_All_by_where($where = 1){
		$sql = "select `picid`,`albumid`,`uid`,`dateline`,`title`,`filepath`,`thumb`,`thumb70`,`thumb440`,`thumb200`,`thumb80`,`thumb550`,`thumb690` from `".DB::table($this->_table)."` where ".$where;	
		$datas = DB::fetch_all($sql);

		return $datas;
	}
	
	/**
	 * 通过where条件获取条数
	 */
	public function counts($where = 1){
		$sql = "select count(`picid`) as tot from `".DB::table($this->_table)."` where ".$where;
		$data = DB::fetch_first($sql);

		return $data['tot'];
	}
	
	/**
	 * 通过 照片id 来
	 * 删除相册里的照片
	 */
	public function delPicForPicid($picid){		
		
		//查找图片版块 一并删除
		$data = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");
		$data = $data[0];

		$dir_sep = DIRECTORY_SEPARATOR  ;
		$dir = DISCUZ_ROOT.'data'.$dir_sep.'attachment'.$dir_sep;

		//70 delete
		if($data['thumb70']){
			$file70 = $dir.$data['thumb70'];
			if(file_exists($file70)){
				unlink($dir.$data['thumb70']);	
			}			
		}

		// 440 delete
		if($data['thumb440']){
			$file440 = $dir.$data['thumb440'];
			if(file_exists($file440)){
				unlink($file440);
			}
		}

		//200 delete
		if($data['thumb200']){
			$file200 = $dir.$data['thumb200'];
			if(file_exists($file200)){
				unlink($file200);
			}
		}

		//80 delete
		if($data['thumb80']){
			$file80 = $dir.$data['thumb80'];
			if(file_exists($file80)){
				unlink($file80);
			}
		}

		//550 delete
		if($data['thumb550']){
			$file550 = $dir.$data['thumb550'];
			if(file_exists($file550)){
				unlink($file550);
			}
		}

		//690 delete
		if($data['thumb690']){
			$file690 = $dir.$data['thumb690'];
			if(file_exists($file690)){
				unlink($file690);
			}
		}

		//删除图片版块设置 设置的这张图片
		c::t('home_picbk_pic')->delpicBkForPicid($_POST['picid']);

		//删除该记录
		if($_POST['picid']){
			DB::query("delete from `".DB::table($this->_table)."` where picid = '{$_POST['picid']}'");
		}
		
		//更改相册信息
		require_once libfile('function/spacecp');
		album_update_pic($data['albumid']);
		

		return 1;
	}
	

}

?>