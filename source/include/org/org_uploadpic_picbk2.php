<?php
$bkid = $_GET['bkid'];
//д������
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");

//��ȡ�ð���µ�����ͼƬ
$datas = c::t('home_picbk_pic')->fetchAll("bkid = '{$bkid}'");

//��ȡ����б�
$albumlist = c::t('home_album')->fetchAll("uid = '{$_G['uid']}'");


include template('diy:ucenter/uploadpic_picbk2');