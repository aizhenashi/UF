<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_picbk_pic extends discuz_table
{

	public function __construct() {

		$this->_table = 'home_picbk_pic';
		$this->_pk = 'id';

		parent::__construct();
	}

	/**
	 * 通过where条件 获取一条数据
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetchRow($where = '1'){

		$sql = "select `id`,`bkid`,`picid`,`time`,`thumb1`,`thumb2` from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_first($sql);

		return $result;
	}

	/**
	 * 插入一条图片版块与相册id的关联
	 */
	public function insertRow($data){

		$data['time'] = time();
		$result = DB::insert($this->_table, $data);
		if($result){
			return DB::insert_id();
		}
	}

	/**
	 * 通过where条件 获取一条数据
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetchAll($where = '1'){

		$sql = "select `id`,`bkid`,`picid`,`time`,`thumb1`,`thumb2` from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_all($sql);
		return $result;

	}
	
	/*
	 * 通过 where 条件 获取 条数
	 */
	public function getnum($where){

		$sql = "select count(*) as num from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_first($sql);

		return $result['num'];
	}
	
	/**
	 * 通过where 删除记录
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function delforwhere($where){

		$sql = "delete from `".DB::table($this->_table)."` where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}
	
	
	/*
	 * 向一个写真版块添加一张图片
	 * $bkid 版块id
	 * $picid 图片id
	 * $filename 用于制作缩略图的源图片
	 */
	public function addPic($bkid,$picid,$filepath){
		
		//$file['filepath']
		//获取该该版块下还可以上传几张图片
		$num = $this->getnum("bkid = '{$bkid}'");

		if($num < 3){
			//可以的话 移动图片
			$new_name = getglobal('setting/attachdir').'./album/'.$filepath;

			require_once libfile('class/image');
			$image = new image();
			$temp = explode('/', $filepath);
			$filename = $temp[count($temp)-1];
			$new_name270 = 'album/'.substr($filepath, 0, strrpos($filepath, '/')+1).'thumb/270'.$filename;
			$new_name600 = 'album/'.substr($filepath, 0, strrpos($filepath, '/')+1).'thumb/600'.$filename;


			$sizearr = getimagesize($new_name);
			//列表页
			$image->Thumb($new_name, $new_name270, 270, 165, 3);
			//首页大图
			$image->Thumb($new_name, $new_name600, 600, $sizearr[1], 3);
			
			//<3 将图片移动至图片版块 图片表内
			$datas['bkid'] = $bkid;
			$datas['thumb1'] = $new_name270;
			$datas['thumb2'] = $new_name600;
			$datas['picid'] = $picid;
			$id = $this->insertRow($datas);
			
			return $id;
		}else{
			//每个图片版块 至多 存三张
			return false;
		}
		
	}
	
	/**
	 * 通过照片id 来删除一个图片版块
	 * 删除缩略图
	 * 删除数据库中的源记录
	 */
	public function delpicBkForPicid($picid){

		$datas = $this->fetchAll("picid = '{$picid}'");

		$root = DISCUZ_ROOT;
		if($datas){
			foreach ($datas as $data){
				$filethumb1 = $root.'/data/attachment/'.$data['thumb1'];
				$filethumb2 = $root.'/data/attachment/'.$data['thumb2'];
				if(file_exists($filethumb1)){
					unlink($filethumb1);
				}

				if(file_exists($filethumb2)){
					unlink($filethumb2);
				}

				DB::query("delete from `".DB::table($this->_table)."` where id = '{$data['id']}'");
			}
		}

	}

}

?>