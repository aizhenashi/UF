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
	 * ͨ��where���� ��ȡһ������
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetchRow($where = '1'){

		$sql = "select `id`,`bkid`,`picid`,`time`,`thumb1`,`thumb2` from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_first($sql);

		return $result;
	}

	/**
	 * ����һ��ͼƬ��������id�Ĺ���
	 */
	public function insertRow($data){

		$data['time'] = time();
		$result = DB::insert($this->_table, $data);
		if($result){
			return DB::insert_id();
		}
	}

	/**
	 * ͨ��where���� ��ȡһ������
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function fetchAll($where = '1'){

		$sql = "select `id`,`bkid`,`picid`,`time`,`thumb1`,`thumb2` from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_all($sql);
		return $result;

	}
	
	/*
	 * ͨ�� where ���� ��ȡ ����
	 */
	public function getnum($where){

		$sql = "select count(*) as num from `".DB::table($this->_table)."` where ".$where;
		$result = DB::fetch_first($sql);

		return $result['num'];
	}
	
	/**
	 * ͨ��where ɾ����¼
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function delforwhere($where){

		$sql = "delete from `".DB::table($this->_table)."` where ".$where;
		$rs = DB::query($sql);
		return $rs;
	}
	
	
	/*
	 * ��һ��д�������һ��ͼƬ
	 * $bkid ���id
	 * $picid ͼƬid
	 * $filename ������������ͼ��ԴͼƬ
	 */
	public function addPic($bkid,$picid,$filepath){
		
		//$file['filepath']
		//��ȡ�øð���»������ϴ�����ͼƬ
		$num = $this->getnum("bkid = '{$bkid}'");

		if($num < 3){
			//���ԵĻ� �ƶ�ͼƬ
			$new_name = getglobal('setting/attachdir').'./album/'.$filepath;

			require_once libfile('class/image');
			$image = new image();
			$temp = explode('/', $filepath);
			$filename = $temp[count($temp)-1];
			$new_name270 = 'album/'.substr($filepath, 0, strrpos($filepath, '/')+1).'thumb/270'.$filename;
			$new_name600 = 'album/'.substr($filepath, 0, strrpos($filepath, '/')+1).'thumb/600'.$filename;


			$sizearr = getimagesize($new_name);
			//�б�ҳ
			$image->Thumb($new_name, $new_name270, 270, 165, 3);
			//��ҳ��ͼ
			$image->Thumb($new_name, $new_name600, 600, $sizearr[1], 3);
			
			//<3 ��ͼƬ�ƶ���ͼƬ��� ͼƬ����
			$datas['bkid'] = $bkid;
			$datas['thumb1'] = $new_name270;
			$datas['thumb2'] = $new_name600;
			$datas['picid'] = $picid;
			$id = $this->insertRow($datas);
			
			return $id;
		}else{
			//ÿ��ͼƬ��� ���� ������
			return false;
		}
		
	}
	
	/**
	 * ͨ����Ƭid ��ɾ��һ��ͼƬ���
	 * ɾ������ͼ
	 * ɾ�����ݿ��е�Դ��¼
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