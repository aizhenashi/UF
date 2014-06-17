<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$optionadd = $filterurladd = $searchsorton = '';
require_once libfile('function/hr');
require libfile('class/page');
//$queryString = $_GET['queryString']; 
$queryString = $_POST['queryString'];
$queryString=addslashes(iconv("UTF-8","GB2312",$queryString)); 
//echo $queryString;
if(strlen($queryString) >0) { 
//echo 1111111;
 $query=DB::query("select * FROM ".DB::table('common_member')." WHERE username LIKE '%".$queryString."%' and groupid > 1 limit 0,5 ");
 //echo "select * FROM ".DB::table('common_member_profile')." WHERE realname LIKE '%".$queryString."%'";
  while($value= DB::fetch($query)) {
  $name=$value['username'];
  $uid=$value['uid'];
  echo '<li ><a href="/home.php?mod=ucenter&do=index&uid='.$uid.'">'.$name.'</a></li>'; 
  }

}

?>