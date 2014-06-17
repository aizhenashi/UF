<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$bkid = $_GET['bkid'];

//ะดีๆร๛ณฦ
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");

$data = c::t('home_bktext_content')->fetchRow("bkid = '{$bkid}'");

include template('diy:org/editorbktext');

?>