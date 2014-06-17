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
	 *  向表中插入一条数据
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
	 * 更改表中的一条记录
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
	 * 根据where 参数获取 视频列表
	 */
	public function getDataForParams($vtype,$priceType,$orderType,$page){
		$where = ' xia = 0';
		
		//类别
		if($vtype != 'all' && $vtype != ''){
			$where .= " && `vtype` like '%".$vtype."%'";
		}
		
		//收费类别
		if($priceType == 'all' || $priceType == ''){ //不限
			$where .= '';
		}else if($priceType == 'free'){ // 免费
			$where .= " && pricetype = 2";
		}else if($priceType == 'prize'){ // 收费
			$where .= " && pricetype = 1";
		}
		
		//排序类型
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
		//总数
		$tot = $data['tot'];
		
		//数据
	 	$sql = "select `id`,`video_id` from `".DB::table($this->_table)."` where ".$where.$order." limit ".$limit.",".$perpage;
	 	$datas = DB::fetch_all($sql);


	 	foreach ($datas as $key=>$data){
	 		$datas[$key] = $this->chuliData($data);
	 	}
	 	
		$return['datas'] = $datas;

		//分页
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
	 *  通过id 添加点击率
	 */
	public function addClick($id){
		$sql = "update `".DB::table($this->_table)."` set `click` = `click`+1 where id = '".$id."'";
		DB::query($sql);
	}

	/**
	 * 通过where获取一条记录
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

				//上传时间
				$data['timestring'] = date('Y-m-d',$data['time']);

				//作者
				$data['username'] = c::t('common_member')->fetch_all_username_by_uid($data['uid']);
				$data['username'] = $data['username'][$data['uid']];

				//类别字符串
				$tempArray = explode(',',$data['vtype']);
				foreach ($tempArray as $key=>$type){
					$temp = c::t('creation_workstype')->get_data_for_wid($type);
					$typestring .=  $temp['wname'].'、'; 					
				}
				$typestring = rtrim($typestring,'、');
				$data['typestring'] = $typestring;

				//lettv 视频播放代码
				$data['videohtml'] = $object->videoGetPlayinterface($object->user_unique, $data['lettv']['video_unique'], 'html','',1,976,457);

//				$datas[$key] = $data;

			}	
		}
		
		return $datas;
		
	}
	
	public function getPlayAuth($id){

		
		global $_G;
		$loginUid = $_G['uid'];
		
		//通过视频id查找出该视频的收费类型
		//获取视频的上传者
		$datas = $this->getDataForWhere("id = '{$id}'");
		$data = $datas[0];
		
		$pricetype = $data['pricetype'];
		//免费视频 播放权限直接为可以看
		if($pricetype == 2){
						
			$auth = 1;
			
		}else{
		//收费视频 	
			if(!$loginUid){ //未登录 权限直接为0
				$auth = 0;				
			}else if($loginUid == $data['uid']){ //当前登录的人就是视频的上传者 权限为可以看				
				$auth = 1; 
			}else{	//登录者不是购买视频的人  需购买才可以观看此视频
				//查找购买记录
				$datas =  c::t('creation_purchased')->getDataForWhere("product_id = '{$id}' && product_class = '2' && jiaoyi_statu = '1'");
				
				if($datas){  //有购买记录 观看权限为1
					$auth = 1;
				}else{ //没有购买记录 观看权限为0
					$auth = 0;
				}
			}
		}

		return $auth;
	}

	//首页取6条数据
	public function getDataForIndex($vtype,$limit){
		$where = ' xia = 0';
		
		//类别
		if($vtype != 'all' && $vtype != ''){
			$where .= " && `vtype` like '%".$vtype."%'";
		}
		
		//数据
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