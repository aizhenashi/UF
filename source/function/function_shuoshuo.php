<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 *  iconv зЊТы
 */
function defineIconv($content){
	$content = trim(iconv('utf-8', 'GB2312', $content));
	return $content;
}
?>