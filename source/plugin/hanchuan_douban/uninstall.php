<?php
/**
 *    [�����¼(uninstall.php)] (C)2012-2099 Powered by ����@��Ȩ���С�
 *    Version: 1.0 
 *    Date: 2013-03-25 12:31
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$sql = '
DROP TABLE IF EXISTS '.DB::table('plugin_hcdouban').';'
;
runquery($sql);
$finish = true;