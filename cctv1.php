<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// ����Ӧ�� ID
//define('APPTYPEID', 98);
//define('CURSCRIPT', 'hr');

//====================================
// �����ļ����룬 �������������ļ����ܲ���Ҫ
// class_forum.php �� function_forum.php
// �����ʵ����Ҫȷ���Ƿ�����
//====================================

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$modurl = 'huanlegu.php';
//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
//====================================
$discuz->init();

//=======================
//���� mod
//===================================
$modarray = array('index', 'view');
// �ж� $mod �ĺϷ���
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