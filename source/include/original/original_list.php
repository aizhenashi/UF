<?php
require libfile('class/lettv');
$object = new LetvCloudV1();

//�������з���
$vtypeDatas = c::t('creation_workstype')->get_data_for_tid('4');
//��Ƶ�б�����
$datas = c::t('original_video')->getDataForParams($_GET['vtype'],$_GET['priceType'],$_GET['orderType'],$_GET['page']);

//temp
//$datas = $object->videoList();
//$datas = json_decode($datas,'ARRAY');

//ģ��
include template('diy:original/videolist');
?>