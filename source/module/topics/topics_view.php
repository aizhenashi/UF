<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$topic = $_GET['topic'] ? trim($_GET['topic']) : 0;
//var_dump($_GET);
//var_dump($topic);
$filename=$topic.'.htm';
//echo $filename;
//if(file_exists("template/uestar/topics/".$filename)){
	$val=file_get_contents("template/uestar/topics/".$topic."/index.htm");
	//echo $val;
	$css_zheng_ze = "/<head>([\s\S]*)<\/head>/Ui";
	preg_match_all($css_zheng_ze,$val,$arr);
   $head = $arr[0][0];
	$css_zheng_ze2 = "/<body>([\s\S]*)<\/body>/Ui";
	preg_match_all($css_zheng_ze2,$val,$arr1);
   $htmlbody = $arr1[0][0];
	$htmlbody=str_replace("images/","/template/uestar/topics/$topic/images/",$htmlbody);
	$htmlbody=str_replace("image/","/template/uestar/topics/$topic/image/",$htmlbody);
	$htmlbody=str_replace("js/","/template/uestar/topics/$topic/js/",$htmlbody);
	$htmlbody=str_replace("css/","/template/uestar/topics/$topic/css/",$htmlbody);

	include template('topics/index');
//}else{
	//showmessage('topic_not_exist');
//}
?>