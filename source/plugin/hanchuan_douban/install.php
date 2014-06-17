<?php
/**
 *    [¶¹°êµÇÂ¼(install.php)] (C)2012-2099 Powered by º®´¨@°æÈ¨ËùÓÐ¡£
 *    Version: 1.0 
 *    Date: 2013-03-25 12:31 
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$sql = '
DROP TABLE IF EXISTS '.DB::table('plugin_hcdouban').';
CREATE TABLE '.DB::table('plugin_hcdouban').'
(
  `uid` int(11) NOT NULL,  `douban_user_id` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) TYPE=MyISAM;
';
runquery($sql);
$finish = true;