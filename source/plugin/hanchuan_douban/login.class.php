<?php
/**
 *    [¶¹°êµÇÂ¼(login.class.php)] (C)2012-2099 Powered by º®´¨@°æÈ¨ËùÓÐ¡£
 *    Version: 1.0
 *    Date: 2013-03-25 12:31
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_hanchuan_douban {

	function global_cpnav_extra1()	{

		global $_G;
		if($_G['uid']==0) {
			//return '<a href="plugin.php?id=hanchuan_douban:login" onclick="showWindow(\'douban_window\',this.href)">¶¹°êµÇÂ¼</a>';
			return '<a href="plugin.php?id=hanchuan_douban:login" onclick="showWindow(\'douban_window\',this.href)"><img src="source/plugin/hanchuan_douban/template/login.gif" border="0"></a>';
		}else {
			return '<a href="plugin.php?id=hanchuan_douban:binding" onclick="showWindow(\'douban_window\',this.href)">°ó¶¨¶¹°ê</a>';
		}
	}

}

class plugin_hanchuan_douban_forum extends plugin_hanchuan_douban {
	
}