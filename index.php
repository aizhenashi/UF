<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: hr.php 7024 2010-03-28 06:39:41Z cnteacher $
 */

// ����Ӧ�� ID
define('APPTYPEID', 98);
define('CURSCRIPT', 'job');

require './source/class/class_core.php';
$discuz = & discuz_core::instance();



$pageid='index';
global $pageid;


//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
//====================================
$discuz->init();

//=======================
//���� mod
//===================================
$modarray = array('index', 'list', 'view', 'post', 'misc', 'broker', 'company', 'threadmod', 'agent', 'resume','reg','activate');
// �ж� $mod �ĺϷ���
$mod = !in_array($_G['mod'], $modarray) ? 'index' : $_G['mod'];







define('CURMODULE', $mod);
runhooks();
$url = substr($_SERVER["REQUEST_URI"],1);
if(ereg("^[0-9a-zA-Z\_]*$",$url) and $url<>''){

			$useruid= DB::fetch_first("SELECT `uid`, `url`  FROM ".DB::table('common_member_profile')." where `url`='".$url."'");
			if(!empty($useruid['uid']))
			{
			$_GET['uid']=$useruid['uid'];
			require DISCUZ_ROOT.'./source/module/hr/user/view.php';
			exit();
			}
			else
			{
			header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
			include('404.php');
			exit();
			
			}
		//	header('Location: '.$_G['siteurl'].'home.php?uid='.$useruid['uid']);
	}


require DISCUZ_ROOT.'./source/module/hr/index/index.php';

?>