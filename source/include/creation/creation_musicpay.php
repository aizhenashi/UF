<?php
	global $_G;
	require_once("api/yinpin/qiniu/rs.php");
	$uid = $_G["uid"];
	$id = $_GET['id'];
	//取音频表数据
	$sql = "SELECT * FROM  `pre_common_music` where `id` = '$id' ;";
	$result = DB::query($sql);
	$row = DB::fetch($result);
	$filename	= $row['filename'];
	$musicname	= $row['musicname'];
	$countnum	= $row['countnum'];
	$lyric		= $row['lyric'];
	$lyric		= str_replace(chr(13),'<br>',$lyric);
	$charge		= $row['charge'];
	$language  	= $row['language'];
	$createtime	= $row['createtime'];
	$createtime	= date('Y-m-d',strtotime($createtime));
	$countnum  	= $row['countnum'];
	$style 		= $row['style'];
	//取音频文件
	$key = $filename;
	$domain = 'uestarroom.qiniudn.com';
	$accessKey = 'wJ7DPFCkCqYiaF1RFf0ASI5XbXTq_sl7VoKkPbtn';
	$secretKey = 'yYa2OLsuho5Gl9Z7dntBysVkLweSZVXJJzkr_TaB';
	Qiniu_SetKeys($accessKey, $secretKey);  
	$baseUrl = Qiniu_RS_MakeBaseUrl($domain, $key);
	$getPolicy = new Qiniu_RS_GetPolicy();
	$privateUrl = $getPolicy->MakeRequest($baseUrl, null);
	//取歌手名
	$get_username = "select `username` from `pre_common_member` where `uid` = '$uid';";
	$get_username = DB::query($get_username);
	$get_username_row = DB::fetch($get_username);
	$username = $get_username_row['username']; 
	//取歌手简介
	$get_bio = "select `bio` from `pre_common_member_profile` where `uid` = '$uid';";
	$get_bio = DB::query($get_bio);
	$get_bio_row = DB::fetch($get_bio);
	$bio = $get_bio_row['bio']; 
	$bio = str_replace(chr(10),'<br>',$bio);
	//取分类名
	$get_style = "select `wname` from `pre_creation_workstype` where `tid` = '2' and `wid` = '$style';";
	$get_style = DB::query($get_style);
	$get_style_row = DB::fetch($get_style);
	$wname = $get_style_row['wname'];
	//取语言名
	$get_language = "select `wname` from `pre_creation_workstype` where `tid` = '3' and `wid` = '$language';";
	$get_language = DB::query($get_language);
	$get_language_row = DB::fetch($get_language);
	$language = $get_language_row['wname']; 
	//取右侧推荐数据
	$rec_detail = array();
	$recom = "select `id`, `musicname`, `uid` from `pre_common_music` where `viewrecommend` = '5' order by `viewrecsort` asc limit 6;";
	$recom_query = DB::query($recom);
	while($recom_row = DB::fetch($recom_query))
	{
		$rec_musicname = $recom_row['musicname'];
		$rec_id = $recom_row['id'];
		$rec_uid = $recom_row['uid'];
		$get_name="select `username` from `pre_common_member` where `uid` = '$rec_uid';";
		$get_name = DB::query($get_name);
		$member_name = DB::fetch($get_name);
		$member_name['rec_musicname'] = $rec_musicname;
		$member_name['user_id'] = $rec_uid;
		$member_name['id'] = $rec_id;
		$rec_detail[] = $member_name;
	}	
	//记录访问量
	DB::query("update `pre_common_music` set `countnum` = `countnum` + 1 where `id` = '$id' ");
	include template("creation/musicpay");
?>