<?php

/**
 *   ˵˵ajax�ύģ�� ҳ ��action �ַ� 
 *
 */

//�����ҳ����ת
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'lettvinit', //������Ƶ�ϴ���ʼ��
	'getVideoUploadJinDu', //��ȡ˵˵�б�
	'insertVideo', //������������Ƶ��������� 
	'getLetTvImage', //��ȡ������Ƶ�Ľ�ͼ 
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
	 * ������Ƶ��ʼ��
	 */
	function lettvinit(){

		//��lettv ����һ����Ƶ
		require_once libfile('class/lettv');
		$object = new LetvCloudV1();

//		$return1 = $object->videoUploadInit('default');
		$return1 = $object->videoUploadInit('default',$_G['clientip']);
		
		die($return1);
		exit;
		
	}
	
	/**
	 * 
	 *	��ȡ������Ƶ�ϴ������Ƶ��ͼ
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
	 * ��ȡͼƬ��ͼ
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