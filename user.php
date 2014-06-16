<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// 定义应用 ID
define('APPTYPEID', 98);
define('CURSCRIPT', 'job');

require './source/class/class_core.php';
$discuz = & discuz_core::instance();

$identifier = '98';
$modidentifier = 'user';
$modurl = 'user.php';

$cachelist = array('hr_sortlist_'.$modidentifier, 'hr_arealist_'.$modidentifier, 'hr_channellist', 'hr_usergrouplist_'.$modidentifier, 'diytemplatename', 'blockclass','introduce','url','occupation');
//====================================
// 加载核心处理,各程序入口文件代码相同
//====================================
$discuz->cachelist = $cachelist;
$discuz->init();

//=======================
//加载 mod
//===================================
$modarray = array('reg','activate','post','introduce','url','view','invite','invit','occupation','Simulated_Login','usertype','attention','viewold');
// 判断 $mod 的合法性
$mod = !in_array($_G['mod'], $modarray) ? 'reg' : $_G['mod'];
$navtitle = empty($navtitle) ? $channel['title'] : $navtitle;
define('CURMODULE', $mod);
runhooks();


require DISCUZ_ROOT.'./source/module/hr/'.$modidentifier.'/'.$mod.'.php';

?>