<?php
define('APPTYPEID', 98);
define('CURSCRIPT', 'hr');

//====================================
// �����ļ����룬 �������������ļ����ܲ���Ҫ
// class_forum.php �� function_forum.php
// �����ʵ����Ҫȷ���Ƿ�����
//====================================

require './source/class/class_core.php';
$discuz = & discuz_core::instance();

//====================================
// ���غ��Ĵ���,����������ļ�������ͬ
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