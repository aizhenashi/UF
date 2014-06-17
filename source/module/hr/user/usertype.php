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
require_once libfile('function/hr');


$query = DB::query("SELECT id, name FROM ".DB::table('user_type')." WHERE topid=1 and id<>66");
			$userType_array = array();
			while($row = DB::fetch($query)){
				$p_id = $row['id'];
				$c_query = DB::query("SELECT name,id FROM ".DB::table('user_type')." WHERE topid=".$p_id." order by displayorder");
				$cType_array = array();
				while ( $c_row = DB::fetch($c_query)){
					$cType_array[$c_row['id']] = $c_row['name'];
				}
				$row['c_name'] = $cType_array;
				$userType_array[] = $row;
			}
//print_r($userType_array);
if(isset($_GET['js'])) {
	echo 'js';
}