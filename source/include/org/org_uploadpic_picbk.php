<?php
$bkid = $_GET['bkid'];
//Ð´ÕæÃû³Æ
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");


include template('diy:ucenter/uploadpic_picbk');