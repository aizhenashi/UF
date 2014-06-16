<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 30442 2012-05-29 06:32:06Z zhangguosheng $
 */


define('APPTYPEID', 1);

require_once './source/class/class_core.php';

$discuz = C::app();
$discuz->init();

/**
 * list 列表
 * fabu 上传
 * play 播放
 * fabuPost 上传处理
 * buy 购买
 */
$dos = array('list','fabu','play','fabuPost','buy','updatePost');

$do = in_array($_GET['do'],$dos) ? $_GET['do'] : '';

if($do === ''){
	die('do error');
}

require_once libfile('original/'.$do, 'include');

?>