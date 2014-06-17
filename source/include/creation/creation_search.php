<?php
	$keyword = $_POST["keyword"];
	if($keyword){
		$music_sql = "select * from `pre_common_music` where `musicname` like '%$keyword%' or `lyric` like '%$keyword%' order by `id` desc;";
		$drama_sql = "select * from `pre_common_article` where `title` like '%$keyword%' or `content` like '%$keyword%' order by `articleId` desc;";
	}else{
		$music_sql = "select * from `pre_common_music` where `musicname`='请输入关键词';";
		$drama_sql = "select * from `pre_common_article` where `title` = '请输入关键词';";
	}
	$datalist = DB::fetch_all($music_sql);
	$rs = DB::query($music_sql);
	$i = 0;
	while($row = DB::fetch($rs))
	{
		$get_name="select `username` from `pre_common_member` where `uid` = '".$row['uid']."';";
		$get_name = DB::query($get_name);
		$member_name = DB::fetch($get_name);
		$datalist[$i]['username'] = $member_name['username'];
		$i++;
	}

	$dramalist = DB::fetch_all($drama_sql);

	include template("creation/musicsearch_head");
	if($i>=1){
		include template("creation/musicsearch_music");
	}
	
	if($dramalist){
		include template("creation/musicsearch_drama");
	}
	
	include template("creation/musicsearch_footer");
?>