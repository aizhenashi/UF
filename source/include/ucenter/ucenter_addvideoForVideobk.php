<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//ฐๆฟ้id
$bkid = $_GET['bkid'];

//ะดีๆร๛ณฦ
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");


include template('diy:ucenter/videobk_videoadd');

?>