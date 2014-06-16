<?php
define('APPTYPEID', 98);
define('CURSCRIPT', 'hr');

//====================================
// 基础文件引入， 其他程序引导文件可能不需要
// class_forum.php 和 function_forum.php
// 请根据实际需要确定是否引入
//====================================

require './source/class/class_core.php';
$discuz = & discuz_core::instance();

//====================================
// 加载核心处理,各程序入口文件代码相同
//====================================

$discuz->init();
/*if(){

}else{

}*/

global $_G;
//var_dump($_G);
$count['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_space_liuyan')." where spaceuid = '{$_G['uid']}' and state = 0");


include template("common/hr_header");

?>