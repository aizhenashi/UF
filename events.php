<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// ����Ӧ�� ID
// ����Ӧ�� ID
define('APPTYPEID', 98);
define('CURSCRIPT', 'job');
//echo urldecode('http://http%3a%2f%2fwww.uestar.cn%2fimages%2fbanner_red.jpg/');

//exit;
//====================================
// �����ļ����룬 �������������ļ����ܲ���Ҫ
// class_forum.php �� function_forum.php
// �����ʵ����Ҫȷ���Ƿ�����
//====================================

require './source/class/class_core.php';

$discuz = & discuz_core::instance();
$identifier = '98';
$modurl = 'events.php';
//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
//====================================
$discuz->init();
//=======================
//���� mod
//===================================
$modarray = array('index','exercise','one','two','three','photo_upload','newablum','setRaffle','ac_rule');
// �ж� $mod �ĺϷ���
$mod = !in_array($_G['mod'], $modarray) ? 'index' : $_G['mod'];
define('CURMODULE', $mod);
runhooks();

require DISCUZ_ROOT.'./source/module/hr/events/'.$mod.'.php';

?>