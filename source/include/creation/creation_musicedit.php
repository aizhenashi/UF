<?php
	global $_G;
	$uid = $_G["uid"];
	$id = $_GET['id'];
	$product_id = $id; 
	if($id){
		$sql = "SELECT * FROM  `pre_common_music` where `id` = '$id' ;";
		$result = DB::query($sql);
		$row = DB::fetch($result);

		$musicname	= $row['musicname'];
		$recording	= date('Y-m-d',strtotime($row['recording']));
		$style 		= $row['style'];
		$language	= $row['language'];
		$lyric		= $row['lyric'];
		$charge 	= $row['charge'];
		$price 		= "";
		if($charge == 5 ){
			$price 	= $row['price'];
		}
	}
	if($_POST){
		$id = $_POST["product_id"];
		//上传音频文件
		$sql = "UPDATE `pre_common_music` SET ";
		if($_FILES["selectfile"]["name"]){
			$filename = date('YmdHis').date('His').".mp3";
			$_FILES["selectfile"]["name"] = $filename;
			move_uploaded_file($_FILES["selectfile"]["tmp_name"],"uploadmusic/" . $_FILES["selectfile"]["name"]);
			
			//上传到七牛空间
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
			
			$sql = $sql." `filename` =  '$filename', ";
		}
		if($_POST["musicname"]){
			$musicname = $_POST["musicname"];
			$sql = $sql." `musicname` =  '$musicname', ";
		}
		if($_POST["recording"]){
			$recording = $_POST["recording"];
			$sql = $sql." `recording` = '$recording', ";
		}
		if($_POST["language"]){
			$language = $_POST["language"];
			$sql = $sql." `language` = '$language', ";
		}
		if($_POST["style"]){
			$style = $_POST["style"];
			$sql = $sql." `style` =  '$style', ";
		}
		if($_POST["charge"]){
			$price = $_POST["charge"];
			$charge = "5";
			$sql = $sql." `price` =  '$price', `charge` =  '$charge', ";
		}else{
			$charge = "1";
			$sql = $sql." `price` =  '0.00', `charge` =  '$charge', ";
		}
		if($_POST["lyric"]){
			$lyric = $_POST["lyric"];
			$sql = $sql." `lyric` =  '$lyric', ";
		}
		$sql = $sql. "`updatetime` = CURRENT_TIMESTAMP WHERE `id` = $id;";
		DB::query($sql);
		echo "<script language='javascript'>";
		echo "alert('更新成功。');location.href='creation.php?do=account';";
		echo "</script>";
		
	}

	include template("creation/musicedit");
	echo "<script language='javascript'>";
	if($charge==1){
		echo "document.getElementById('mianyi').checked=true;";
	}
	echo "document.getElementById('style').options[".$style."].selected=true;";
	echo "document.getElementById('language').options[".$language."].selected=true;";
	echo "</script>";
?>