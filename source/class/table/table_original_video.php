<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_member_security.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_original_video extends discuz_table
{
	public function __construct() {
		$this->_table = 'original_video';
		$this->_pk    = 'id';


		require_once libfile('class/lettv');

		parent::__construct();
	}
	
	
	/*
	 *  ����в���һ������
	 */
	public function insertData($data){

		global $_G;
		$data['uid'] = $_G['uid'];
		$data['click'] = 0;
		$data['time'] = time();
	
		DB::insert($this->_table, $data);
		$id = DB::insert_id();
		return $id;
	}
	
	/*
	 * ���ı��е�һ����¼
	 */
	public function updateData($data = '',$where = ''){

		if($data == ''){
			die('error : no $data');
		}
		
		if($where == ''){
			die('error : no $where');
		}
		
		$sql = " update `".DB::table($this->_table)."` set $data where ".$where;
		$rs = DB::query($sql);
		
		return $rs;
	}
	
	/**
	 * ����where ������ȡ ��Ƶ�б�
	 */
	public function getDataForParams($vtype,$priceType,$orderType,$page){
		$where = ' xia = 0';
		
		//���
		if($vtype != 'all' && $vtype != ''){
			$where .= " && `vtype` like '%".$vtype."%'";
		}
		
		//�շ����
		if($priceType == 'all' || $priceType == ''){ //����
			$where .= '';
		}else if($priceType == 'free'){ // ���
			$where .= " && pricetype = 2";
		}else if($priceType == 'prize'){ // �շ�
			$where .= " && pricetype = 1";
		}
		
		//��������
		if($orderType == 'new' || $orderType == ''){
			$order = " order by id desc";
		}else{
			$order = ' order by `click` desc';
		}
		
		$perpage = 20;
		$page = $page ? $page : 1;
		$limit = ($page-1)*$perpage;		
		$sql = "select count(`id`) as tot from `".DB::table($this->_table)."` where $where";
		$data = DB::fetch_first($sql);
		//����
		$tot = $data['tot'];
		
		//����
	 	$sql = "select `id`,`video_id` from `".DB::table($this->_table)."` where ".$where.$order." limit ".$limit.",".$perpage;
	 	$datas = DB::fetch_all($sql);


	 	foreach ($datas as $key=>$data){
	 		$datas[$key] = $this->chuliData($data);
	 	}
	 	
		$return['datas'] = $datas;

		//��ҳ
		$return['page'] = multi($tot, $perpage, $page, $_SERVER['REQUEST_URI']);

		
		return $return;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $datas
	 */
	public function chuliData($data){

		$obj = new LetvCloudV1();		
		$temp = $obj->videoGet($data['video_id']);
		$temp = json_decode($temp,'ARRAY');
		$temp['data']['video_name'] = iconv('UTF-8','GB2312',$temp['data']['video_name']);
		
		$data['lettvdata'] = $temp['data'];
		
		return $data;		
	}

	/**
	 *  ͨ��id ��ӵ����
	 */
	public function addClick($id){
		$sql = "update `".DB::table($this->_table)."` set `click` = `click`+1 where id = '".$id."'";
		DB::query($sql);
	}

	/**
	 * ͨ��where��ȡһ����¼
	 */
	public function getDataForWhere($where = '1',$chuli = false){
		$sql = "select `id`,`uid`,`video_id`,`video_unique`,`vtype`,`pricetype`,`price`,`click`,`time` from `".DB::table($this->_table)."` where 1 = '1' && ".$where;
		$datas = DB::fetch_all($sql);

		if($chuli == true){
			require_once  libfile('class/lettv');
			$object = new LetvCloudV1();
			foreach ($datas as $key=>&$data){
				$temp = $object->videoGet($data['video_id']);
				$temp = json_decode($temp,'ARRAY');
				$temp['data']['video_name'] = iconv('utf-8','gb2312',$temp['data']['video_name']);
				$temp['data']['video_desc'] = iconv('utf-8','gb2312',$temp['data']['video_desc']);
				$data['lettv'] = $temp['data'];

				//�ϴ�ʱ��
				$data['timestring'] = date('Y-m-d',$data['time']);

				//����
				$data['username'] = c::t('common_member')->fetch_all_username_by_uid($data['uid']);
				$data['username'] = $data['username'][$data['uid']];

				//����ַ���
				$tempArray = explode(',',$data['vtype']);
				foreach ($tempArray as $key=>$type){
					$temp = c::t('creation_workstype')->get_data_for_wid($type);
					$typestring .=  $temp['wname'].'��'; 					
				}
				$typestring = rtrim($typestring,'��');
				$data['typestring'] = $typestring;

				//lettv ��Ƶ���Ŵ���
				$data['videohtml'] = $object->videoGetPlayinterface($object->user_unique, $data['lettv']['video_unique'], 'html','',1,976,457);

//				$datas[$key] = $data;

			}	
		}
		
		return $datas;
		
	}
	
	public function getPlayAuth($id){

		
		global $_G;
		$loginUid = $_G['uid'];
		
		//ͨ����Ƶid���ҳ�����Ƶ���շ�����
		//��ȡ��Ƶ���ϴ���
		$datas = $this->getDataForWhere("id = '{$id}'");
		$data = $datas[0];
		
		$pricetype = $data['pricetype'];
		//�����Ƶ ����Ȩ��ֱ��Ϊ���Կ�
		if($pricetype == 2){
						
			$auth = 1;
			
		}else{
		//�շ���Ƶ 	
			if(!$loginUid){ //δ��¼ Ȩ��ֱ��Ϊ0
				$auth = 0;				
			}else if($loginUid == $data['uid']){ //��ǰ��¼���˾�����Ƶ���ϴ��� Ȩ��Ϊ���Կ�				
				$auth = 1; 
			}else{	//��¼�߲��ǹ�����Ƶ����  �蹺��ſ��Թۿ�����Ƶ
				//���ҹ����¼
				$datas =  c::t('creation_purchased')->getDataForWhere("product_id = '{$id}' && product_class = '2' && jiaoyi_statu = '1'");
				
				if($datas){  //�й����¼ �ۿ�Ȩ��Ϊ1
					$auth = 1;
				}else{ //û�й����¼ �ۿ�Ȩ��Ϊ0
					$auth = 0;
				}
			}
		}

		return $auth;
	}

	//��ҳȡ6������
	public function getDataForIndex($vtype,$limit){
		$where = ' xia = 0';
		
		//���
		if($vtype != 'all' && $vtype != ''){
			$where .= " && `vtype` like '%".$vtype."%'";
		}
		
		//����
	 	$sql = "select `id`,`video_id` from `".DB::table($this->_table)."` where xia = 0 and ".$where." order by time desc limit ".$limit;
	 	$datas = DB::fetch_all($sql);


	 	foreach ($datas as $key=>$data){
	 		$datas[$key] = $this->chuliData($data);
	 	}
	 	
		$return['datas'] = $datas;
		
		return $return;
	}
	
	
}

?>