<?php

/**
 *   ��� ajax�ύģ�� ҳ ��action �ַ� 
 *   
 *   1.�ݴ�
 *   δ��¼ ֱ�� exit
 *   
 */

//�����ҳ����ת
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/**
 * addpic ���������һ��ͼƬ
 */

$dos = 
array(
	'addpic'
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class picbkMoudle{

	/**
	 * �������� ���һ��ͼƬ
	 */
	public function addpic(){

		$bkid = $_POST['bkid'];  //���id
		$picid = $_POST['picid']; // ͼƬid
		
		//��ȡͼƬԴ·��
		$datas = c::t('home_pic')->fetch_All_by_where("picid = '{$picid}'");

		$id = c::t('home_picbk_pic')->addPic($_GET['bkid'],$datas[0]['picid'],$datas[0]['filepath']);
		if($id){
			$data = c::t('home_picbk_pic')->fetchRow("id = '{$id}'");				
		}

		include template('diy:ucenter/picbk/bkpic');
				
	}
}

$ajaxUcenter = new picbkMoudle();
$ajaxUcenter->$do();
?>