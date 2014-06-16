<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 30442 2012-05-29 06:32:06Z zhangguosheng $
 */


define('APPTYPEID', 1);

define('CURSCRIPT', 'home');

require_once './source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$space = array();

$mod = getgpc('mod');

require_once libfile('ajax/'.$mod, 'module');

?>