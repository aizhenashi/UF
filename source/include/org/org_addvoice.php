<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//���id
$bkid = $_GET['bkid'];
//д������
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");
$mbk = c::t('home_voice')->fetch_one("bkid = {$bkdata['id']}");
//var_dump($mbk);exit;

include template('diy:ucenter/addvoice');

?>