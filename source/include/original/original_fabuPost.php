<?php
error_reporting(E_ALL &~E_NOTICE);
require libfile('class/lettv');
$object = new LetvCloudV1();

//ͨ��lettv�ṩ����Ƶid ������ ,�������  ���͵�lettv
//��title ת��
$object->videoUpdate($_POST['video_id'],iconv('GB2312','UTF-8',$_POST['title']),iconv('GB2312','UTF-8',$_POST['desc']));

//���������,  ��������, �۸�  ���ڱ���
$vtype = implode(',',$_POST['vtype']);

//ɾ����Ƶid
unset($_POST['id']);
//ɾ��title
unset($_POST['title']);
$_POST['vtype'] = $vtype;
//ɾ��desc
unset($_POST['desc']);
//ɾ�������ύ��ť
unset($_POST['fabu']);

var_dump($data);
$rs = c::t('original_video')->insertData($_POST);


//��Ƶ�����ɹ� ��ת���˻���������ҳ
showmessage('��Ƶ�����ɹ�','/creation.php?do=account');


?>