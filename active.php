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

$flag='active';


$dos = array('index','search1','search2','upload','datalist','position','actSearch','zhanSearch','musicSearch','dianSearch','info','film');

$do = in_array($_GET['do'],$dos) ? $_GET['do'] : '';

if($do === ''){
	die('do error');
}

require_once libfile('active/'.$do, 'include');


?>