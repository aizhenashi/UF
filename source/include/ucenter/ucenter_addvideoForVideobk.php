<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//���id
$bkid = $_GET['bkid'];

//д������
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");


include template('diy:ucenter/videobk_videoadd');

?>