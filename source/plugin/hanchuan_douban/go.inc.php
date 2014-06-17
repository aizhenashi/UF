<?php
/**
 *    [¶¹°êµÇÂ¼(go.inc.php)] (C)2012-2099 Powered by º®´¨@°æÈ¨ËùÓÐ¡£
 *    Version: 1.0
 *    Date: 2013-03-25 12:31
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require('doubanv2.class.php');
require('config.php');
$o = new DoubanOAuthV2(APIKEY,Secret);
$login_url = $o->getAuthorizeURL(CALLBACK_URL);
$login_url = "https://www.douban.com/service/auth2/auth".$login_url;
header("location:$login_url");