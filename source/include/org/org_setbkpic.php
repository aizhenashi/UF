<?php

if(!$_GET['bkid']){
	exit('���Ľ�����?');
}

$bkid = $_GET['bkid'];
$spaceuid = $_GET['spaceuid'];

//��֤�ð���Ƿ���������ռ������
$result = c::t('myspace_bankuai')->fetch_myspacerow("bkstring like '%{$bkid}%' && uid = '{$spaceuid}'");
if(!$result){
	die('�����������?');
}

//д������
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");

//��ȡ�ð���µ�����ͼƬ
$datas = c::t('home_picbk_pic')->fetchAll("bkid = '{$bkid}'");


if(!$datas){
	//��ͼƬ�����û��ͼƬ ��ת��
	//��ʱ����
	echo header("Location:/home.php?mod=ucenter&do=uploadpic_picbk&bkid=".$bkid);
}


include template('diy:org/bkpiclist');