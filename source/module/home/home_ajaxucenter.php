<?php

/**
 *   ���˿ռ�  ajax�ύģ�� ҳ ��action �ַ� 
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
 *  sendshuoshuo ����˵˵
 *  getshuoshuo ��ȡ˵˵
 *  getALLpinglun ��ȡ˵˵����������
 *  HuifuShuoShuo ��˵˵��������
 *  HuifuShuoShuoAndRen �ظ��˵�ͬʱ ����������˵˵��һ����
 *  playVideo ������Ƶ
 *  sendspaceliuyan ���Ϳռ�����
 *  HuifuLiuyanAndRenhtml �ظ����Բ��һظ���
 *  addbankuai ��Ӱ��
 *  changebkOrder �ı�������
 *  delbankuaiforid ɾ�����ͨ��id
 *  editbkName �༭�������
 *  delpicbkforid ͨ��id ɾ�����pic
 *  addpic_picbk ��ͼƬ������һ��ͼƬ
 */
$dos = 
array(
	'getALLpinglun',
	'HuifuShuoShuo',
	'HuifuShuoShuoAndRenhtml',
	'HuifuShuoShuoAndRen',
	'zan',
	'chuliVideo',
	'playVideo',
	'sendspaceliuyan',
	'HuifuLiuyanAndRenhtml',
	'HuifuLiuyanAndRen',
	'addbankuai',
	'changebkOrder',
	'delbankuaiforid',
	'editbkName',
	'delpicbkforid',
	'delshuoshuo'
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

//1.�ݴ�
global $_G;
if(!$_G['uid']){
	echo 'nologin';
	exit;
}


class ajaxucenterMoudle{
	
	/**
	 * ajax ͨ��Դ���� ������Ƶ����
	 */
	public function chuliVideo(){

		//��ȡ ����
		$reslink = $_POST['reslink'];

		//�������Ӽ�¼�浽�����ӱ�
		$shortlink = c::t('video_shortlink')->insert_shortlink($reslink);
		
		if($shortlink){
			//���  ������
			die(' '.$shortlink.' ');
		}else{
			// ������� error
			die('error');
		}
	}



	

	
	/**
	 * ��Ӱ��
	 */
	public function addbankuai(){
		
		
		$flag1 = 1;		
		
		//�ڰ��������һ����¼
		$id = c::t('space_bankuai')->insert_bankuai($_POST);
		$bkdata = c::t('space_bankuai')->fetch_bk(" id = '$id'");

		include template('diy:ucenter/bk');
				
		//��myspace_bankuai �� �� �ҵİ���¼�޸�
		
	}
	
	/**
	 * �ı����˳��
	 */
	public function changebkOrder(){
		c::t('myspace_bankuai')->updatemybkorder($_POST);
		exit;
	}

	/**
	 * ͨ��id ɾ�����
	 */
	public function delbankuaiforid(){

		//���id
		$id = $_POST['id'];
		$bktype = $_POST['bktype'];
		
		//ɾ�����
		$rs = c::t('space_bankuai')->deletebk("`id` = '{$id}'");
		
		
	}
	
	/**
	 * �༭�������
	 * Enter description here ...
	 */
	public function editbkName(){
		//�������ת��
		$_POST['bankuainame'] = trim(iconv('utf-8', 'GBK', $_POST['bankuainame']));
		//�ı�������
		$rs = c::t('space_bankuai')->updateName($_POST['bankuainame'],"`id` = '{$_POST['id']}'");
		if($rs){
			die('1');
		}
	}
	
	/**
	 * ͨ��bkpicid ��ɾ��������¼
	 */
	public function delpicbkforid(){

		$rs = c::t('home_picbk_pic')->delforwhere("id = '{$_POST['id']}'");
		
		if($rs){
			die('1');
		}
	}
	
	
}


$ajaxUcenter = new ajaxucenterMoudle();

$ajaxUcenter->$do();
?>