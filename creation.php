<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */


//====================================
// �����ļ����룬 �������������ļ����ܲ���Ҫ
// class_forum.php �� function_forum.php
// �����ʵ����Ҫȷ���Ƿ�����
//====================================

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
//====================================
$discuz->init();

//=======================
//���� mod
//===================================
//$modarray = array('index', 'drama');
// �ж� $mod �ĺϷ���
define('CURMODULE', $mod);
$pageid = 'creation';
$mod = $_GET['do'] ? $_GET['do']:'index'; 
if(!in_array($mod,array('index','drama','musicupload','musicedit','musicindex','musicview','musicplay','musicpay','dramalist','dramainfo','modData','updateDrama','account','search','lyric','lyricList','lyricInfo','updateLyric','picUpload','picInfo','picList','viewedit'))){
	return;
}

runhooks();
require DISCUZ_ROOT.'./source/include/creation/creation_'.$mod.'.php';


?>