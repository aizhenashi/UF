<?php

/**
 *   说说ajax提交模块 页 做action 分发 
 *
 */

//这个是页面跳转
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'lettvinit', //乐视视频上传初始化
	'getVideoUploadJinDu', //获取说说列表
	'insertVideo', //向优艺网盟视频表插入数据 
	'getLetTvImage', //获取乐视视频的截图 
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	die('action error');
}

class originalMoudle{

	public function getVideoUploadJinDu(){
		require libfile('class/lettv');
		$object = new LetvCloudV1();

		$data = $object->curl($_POST['progress_url']);
		$data = json_decode($data,'ARRAY');
		
		if($data['result']['totalSize'] == '0'){
			echo 'complate';
		}else{
			$percent = ($data['result']['uploadSize']/$data['result']['totalSize'])*100;
			echo ceil($percent).'%';
		}
		
	}	
	
	/**
	 * 乐视视频初始化
	 */
	function lettvinit(){

		//在lettv 创建一个视频
		require_once libfile('class/lettv');
		$object = new LetvCloudV1();

//		$return1 = $object->videoUploadInit('default');
		$return1 = $object->videoUploadInit('default',$_G['clientip']);
		
		die($return1);
		exit;
		
	}
	
	/**
	 * 
	 *	获取乐视视频上传后的视频截图
	 * Enter description here ...
	 */
	
	public function getLetTvImage(){
					
		while(!($jietu = $this->getImage())){
			
		}
		
		$data1['jietu'] = $jietu['img1'];
		
		echo json_encode($data1);
		exit;
		
	}
	
	/**
	 * 获取图片截图
	 * Enter description here ...
	 */	
	public function getImage(){
		require_once libfile('class/lettv');
		$object = new LetvCloudV1();
		$temp =  $object->imageGet($_POST['video_id'],'320_180');
		$jietu = json_decode($temp,'ARRAY');
		return $jietu['data'];
		
	}

}

$ajaxOriginal = new originalMoudle();
$ajaxOriginal->$do();
?>