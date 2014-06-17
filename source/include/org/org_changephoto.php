<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(submitcheck('avatarsubmit')) {
	showmessage('do_success', 'cp.php?ac=avatar&quickforward=1');
}

loaducenter();
$uc_avatarflash = uc_avatar($_G['uid'], 'virtual', 0);

if(empty($space['avatarstatus']) && uc_check_avatar($_G['uid'], 'middle')) {
	C::t('common_member')->update($_G['uid'], array('avatarstatus'=>'1'));

	updatecreditbyaction('setavatar');

	manyoulog('user', $_G['uid'], 'update');
}
$reload = intval($_GET['reload']);
$actives = array('avatar' =>' class="a"');


include template('diy:ucenter/changephoto');
?>