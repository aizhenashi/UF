<?php
global $_G;
$uid = $_G["uid"];
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}
if($_POST){
	//上传音频文件
	$filename = date('YmdHis').date('His').".mp3";
	$_FILES["selectfile"]["name"] = $filename;
	$musicname = $_POST["musicname"];
	$recording = $_POST["recording"];
	$language = $_POST["language"];
	$style = $_POST["style"];
	$lyric = $_POST["lyric"];
	if($_POST["charge"]){
		$price = $_POST["charge"];
		$charge = "5";
	}else{
		$charge = "1";
	}
	
	move_uploaded_file($_FILES["selectfile"]["tmp_name"],"uploadmusic/" . $_FILES["selectfile"]["name"]);
	
	//上传到七牛空间
	//$filepath = str_replace('source\include\creation\creation_musicupload.php','',__file__);
	$filepath = "/home/app/apache2/htdocs/uestar/uploadmusic/".$_FILES["selectfile"]["name"];	
	require_once("api/yinpin/qiniu/io.php");
	require_once("api/yinpin/qiniu/rs.php");

	$bucket = "uestarroom";
	$key1 = $filename;
	$accessKey = 'wJ7DPFCkCqYiaF1RFf0ASI5XbXTq_sl7VoKkPbtn';
	$secretKey = 'yYa2OLsuho5Gl9Z7dntBysVkLweSZVXJJzkr_TaB';

	Qiniu_SetKeys($accessKey, $secretKey);
	$putPolicy = new Qiniu_RS_PutPolicy($bucket);
	$upToken = $putPolicy->Token(null);
	$putExtra = new Qiniu_PutExtra();
	$putExtra->Crc32 = 1;
	list($ret, $err) = Qiniu_PutFile($upToken, $key1, $filepath, $putExtra);
    //上传到七牛空间完成
	   

	$sql = "INSERT INTO  `uestar`.`pre_common_music` (`id` ,`uid` ,`filename` ,`recording` ,`musicname` ,`language` ,`style` ,`lyric` ,`charge` ,`price` ,`createtime`)
			VALUES (NULL ,  '$uid',  '$filename', '$recording', '$musicname',  '$language',  '$style', '$lyric', '$charge', '$price', CURRENT_TIMESTAMP);";
	DB::query($sql);
	echo "<script language='javascript'>";
	echo "alert('上传成功，请继续发布其它作品。');location.href='creation.php?do=musicupload';";
	echo "</script>";
	
}
include template("creation/musicupload");
?>