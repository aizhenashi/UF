<?php
/**
 *    [¶¹°êµÇÂ¼(config.php)] (C)2012-2099 Powered by º®´¨@°æÈ¨ËùÓÐ¡£
 *    Version: 1.0
 *    Date: 2013-03-25 12:31
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

define('APIKEY',$_G['cache']['plugin']['hanchuan_douban']['apikey']);
define('Secret',$_G['cache']['plugin']['hanchuan_douban']['secret']);
define( "CALLBACK_URL" , $_G['siteurl'].'plugin.php?id=hanchuan_douban:callback' );