<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 30442 2012-05-29 06:32:06Z zhangguosheng $
 */

define('APPTYPEID', 1111);
define('CURSCRIPT', '404');
require_once './source/class/class_core.php';
$discuz = & discuz_core::instance();
//====================================
// 加载核心处理,各程序入口文件代码相同
//====================================
$discuz->init();

//=======================
//加载 mod
//===================================
runhooks();
//加载404模板
include template('404');


?>