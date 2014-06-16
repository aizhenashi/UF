<?php
if(empty($_POST["dbpath"])||empty($_POST["dbid"])||empty($_POST["dblen"])){
	$message = iconv("utf-8","gb2312","请填写数据库路径或者起始ID或者步长");
	exit($message);
} 

// 定义应用 ID
define('APPTYPEID', 98);
define('CURSCRIPT', 'job');

require './source/class/class_core.php';
$discuz = & discuz_core::instance();




$identifier = '98';
$modidentifier = 'user';
$modurl = 'user.php';

$cachelist = array('hr_sortlist_'.$modidentifier, 'hr_arealist_'.$modidentifier, 'hr_channellist', 'hr_usergrouplist_'.$modidentifier, 'diytemplatename', 'blockclass');
//====================================
// 加载核心处理,各程序入口文件代码相同
//====================================
$discuz->cachelist = $cachelist;
$discuz->init();

//=======================
//加载 mod
//===================================
$modarray = array('index' ,'reg','activate');
// 判断 $mod 的合法性
$mod = !in_array($_G['mod'], $modarray) ? 'index' : $_G['mod'];
$navtitle = empty($navtitle) ? $channel['title'] : $navtitle;
define('CURMODULE', $mod);
runhooks();
 

require DISCUZ_ROOT.'./source/module/hr/'.$modidentifier.'/dbto.php';

 ?>