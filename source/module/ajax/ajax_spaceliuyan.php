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

$dos = 
array(
	'liuyanDel', //ɾ������
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class spaceliuyanMoudle{
	
	/**
	 * ����ɾ��
	 */
	function liuyandel(){

		global $_G;

		$id = $_POST['id'];
		c::t('home_space_liuyan')->deleteforWhere("id = '{$id}'");
		echo 1;
		exit;
	}

}

$ajaxUcenter = new spaceliuyanMoudle();
$ajaxUcenter->$do();
?>