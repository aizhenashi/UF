<?php
require libfile('class/lettv');
$object = new LetvCloudV1();

//����ʼ�1
c::t('original_video')->addClick($_GET['id']);

//������Ƶid��ȡ��lettv�Ǳߵ���Ƶ��Ϣ ����uestar �ڱ�վ����Ϣ
// ����
// ����
// �����
// ����������
// �ϴ�ʱ��
// �������
$data = c::t('original_video')->getDataForWhere("id = '{$_GET['id']}'",true);
$data = $data[0];

//��ȡ�ۿ�Ȩ��
$auth = C::t('original_video')->getPlayAuth($data['id'],$data['pricetype']);

if($auth == 0){
	$jietu = $object->imageGet($data['video_id'], '640_360');
	$jietu = json_decode($jietu,'ARRAY');
}

$jietujson = json_encode($jietu['data']);

include template('diy:original/play');