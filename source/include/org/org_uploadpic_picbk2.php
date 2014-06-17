<?php
$bkid = $_GET['bkid'];
//写真名称
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");

//获取该板块下的所有图片
$datas = c::t('home_picbk_pic')->fetchAll("bkid = '{$bkid}'");

//获取相册列表
$albumlist = c::t('home_album')->fetchAll("uid = '{$_G['uid']}'");


include template('diy:ucenter/uploadpic_picbk2');