<?php
$bkid = $_GET['bkid'];
//д������
$bkdata = c::t('space_bankuai')->fetch_bk("id = '{$bkid}'");


include template('diy:ucenter/uploadpic_picbk');