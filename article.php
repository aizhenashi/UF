<?php define('APPTYPEID', 1);
require_once './source/class/class_core.php';
$discuz = C::app();
$discuz->init();
$dos = array('upload','datalist','content','mysc','introduce','search','xia');
$do = in_array($_GET['do'],$dos) ? $_GET['do'] : '';
if($do === ''){
	die('do error');
}
require_once libfile('article/'.$do,'include');
?>