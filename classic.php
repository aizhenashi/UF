<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// ����Ӧ�� ID
define('APPTYPEID', 98);
define('CURSCRIPT', 'hr');

//====================================
// �����ļ����룬 �������������ļ����ܲ���Ҫ
// class_forum.php �� function_forum.php
// �����ʵ����Ҫȷ���Ƿ�����
//====================================

require './source/class/class_core.php';
$discuz = & discuz_core::instance();
$identifier = '98';
$pageid = 'talents';
global $pageid;
$modurl = 'talents.php';
//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
//====================================
$discuz->init();

//=======================
//���� mod
//===================================
$modarray = array('index', 'view','classic');
// �ж� $mod �ĺϷ���
$mod = !in_array($_G['mod'], $modarray) ? 'classic' : $_G['mod'];
define('CURMODULE', $mod);
$sortid = intval($_GET['sortid']);
runhooks();

require DISCUZ_ROOT.'./source/module/hr/talents/'.$mod.'.php';

?>