<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// 定义应用 ID
//define('APPTYPEID', 98);
//define('CURSCRIPT', 'hr');

//====================================
// 基础文件引入， 其他程序引导文件可能不需要
// class_forum.php 和 function_forum.php
// 请根据实际需要确定是否引入
//====================================

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$modurl = 'huanlegu.php';
//====================================
// 加载核心处理,各程序入口文件代码相同
//====================================
$discuz->init();

//=======================
//加载 mod
//===================================
$modarray = array('index', 'view');
// 判断 $mod 的合法性
//$mod = !in_array($_G['mod'], $modarray) ? 'index' : $_G['mod'];
$mod = 'index';
define('CURMODULE', $mod);
$act = $_GET['act'] ? $_GET['act']:'index'; 
if(!in_array($act,array('index','vlist','photo'))){
	return;
}

//$sortid = intval($_GET['sortid']);
runhooks();

require DISCUZ_ROOT.'./source/module/hr/huanlegu/'.$mod.'.php';

?>