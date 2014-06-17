<?php
	global $_G;
       $user_id = $_G['uid'];
	require_once("api/yinpin/qiniu/rs.php");
	$id = $_GET['id'];
        $product_id = $id;
	//取音频表数据
	$sql = "SELECT * FROM  `pre_common_music` where `id` = '$id' ;";
	$result = DB::query($sql);
	$row = DB::fetch($result);
       $uid = $row['uid'];
	$filename	= $row['filename'];
	$musicname	= $row['musicname'];
	$countnum	= $row['countnum'];
	$lyric		= $row['lyric'];
	$lyric		= str_replace(chr(13),'<br>',$lyric);
	$charge		= $row['charge'];
	$product_price	= $row['price'];	
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
	//取商户订单号
	$WIDout_trade_no = date('YmdHis')."_".$user_id."_".$id."_1";
	//取歌手名
	$get_username = "select `username` from `pre_common_member` where `uid` = '$uid';";
	$get_username = DB::query($get_username);
	$get_username_row = DB::fetch($get_username);
	$username = $get_username_row['username']; 
	//取支付状态
	$get_purchase = "select `id` from `pre_creation_purchased` where `uid` = '$user_id' and `product_id` = '$id' and `product_class` = '1' order by `id` desc limit 1;";
	$get_purchase = DB::query($get_purchase);
	$get_purchase_row = DB::fetch($get_purchase);
	$purchase_id = $get_purchase_row['id']; 
	if($purchase_id>0){
		$purchase_id = "5";
	}else{
		$purchase_id = "1";
	}
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
	/*取右侧推荐数据 --开始*/
	//同一分类，同一用户，取全部，除当前ID，最后随机取2个。
	$own_id = array();
	$sql = "select `id` from `pre_common_music` where `style` = '$style' and `uid` = '$uid' and `id` != '$id'; ";
	$query = DB::query($sql);
	while($row = DB::fetch($query)){
		$own_id[] = $row['id'];
	}
	$array_count = count($own_id);
	if($array_count == 0){
		$own_id_length = 0;
		$own_id = "";
	}
	if($array_count == 1){
		$own_id_length = 1;
		$own_id = $own_id[0];
	}
	if($array_count >= 2){
		$own_id_key = array_rand($own_id, 2);
		$own_id_length = 2;
		$own_id = $own_id[$own_id_key[0]].",".$own_id[$own_id_key[1]];
	}
	//同一分类，不同用户，最热取20个，随机取6 - own_id_length个
	$other_id_length = 6 - $own_id_length;
	$other_id = array();
	$sql = "select `id` from `pre_common_music` where `style` = '$style' and `uid` != '$uid' order by `countnum` desc limit 20; ";
	$query = DB::query($sql);
	while($row = DB::fetch($query)){
		$other_id[] = $row['id'];
	}
	$count_other_id = count($other_id);
	if($count_other_id>=$other_id_length){
		$other_id_key = array_rand($other_id, $other_id_length);
	}else{
		$i = 0;
		foreach ($other_id as $id_value) {
			$other_id_key[] = $i;
			$i++;
		}
	}
	foreach ($other_id_key as $value) {
		$other_all_id = $other_all_id.",".$other_id[$value];
	}
	$other_all_id = substr($other_all_id,1,strlen($other_all_id));
	$id_group = "";
	if($own_id_length>=1 && $other_all_id >=1){
		$id_group = $own_id.",".$other_all_id;
	}else{
		$id_group = $other_all_id;
	}
	
	$rec_detail = array();
	if($id_group){
		$recom = "select `id`, `musicname`, `uid` from `pre_common_music` where `id` in ($id_group) order by find_in_set(id,'$id_group') ;";
	}else{
		$recom = "select `id`, `musicname`, `uid` from `pre_common_music` where `style` = '$style' and `uid` != '$uid' order by `countnum` desc limit 6;";
	}
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
	/*取右侧推荐数据 --结束*/	
	//记录访问量
	DB::query("update `pre_common_music` set `countnum` = `countnum` + 1 where `id` = '$id' ");
	include template("creation/musicview");
?>