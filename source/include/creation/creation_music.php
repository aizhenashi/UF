<?php
global $_G;
$uid = $_G["uid"];
//上传音频文件
if($_POST){
	$filename = date('YmdHis').$_FILES["selectfile"]["name"];
	move_uploaded_file($_FILES["selectfile"]["tmp_name"],"uploadmusic/" . $_FILES["selectfile"]["name"]);
	
	//上传到七牛空间
	$filepath = str_replace('source\include\creation\creation_music.php','',__file__);
	$filepath = $filepath."uploadmusic/".$_FILES["selectfile"]["name"];
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
	   
	$musicname = $_POST["musicname"];
	$language = $_POST["language"];
	$style = $_POST["style"];
	$lyric = $_POST["lyric"];
	$sql = "INSERT INTO  `uestar`.`pre_common_music` (`id` ,`uid` ,`filename` ,`musicname` ,`language` ,`style` ,`lyric` ,`createtime`)
			VALUES (NULL ,  '$uid',  '$filename',  '$musicname',  '$language',  '$style',  '$lyric', CURRENT_TIMESTAMP);";
	DB::query($sql);
	
}
include template("creation/music");
?>