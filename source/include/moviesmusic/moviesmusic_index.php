<?php
/*
	影视音乐投票列表页	
			需要查询内容包括：歌曲名，相关影视作品名称，演唱者，所得票数，相关图片

			形式列表展示：
				1，最新获得投票的作品列表（根据最新投票时间排名）
				2，票数排行靠前的作品列表（根据所得票数总和排名）
*/

//最新投票列表

$newmusic_data = DB::query("select musicname,movie,singer,vote,filename from ".DB::table("common_movies_music")." order by time desc limit 9");

//echo "select musicname,movie,singer,vote,pic from ".DB::table("common_movies_music")." order by time limit 10";

while($data = DB::fetch($newmusic_data)){
	$newmusic['musicname'] = $data['musicname'];
	$newmusic['movie'] = $data['movie'];
	$newmusic['singer'] = $data['singer'];
	$newmusic['vote'] = $data['vote'];
	$newmusic['filename'] = $data['filename'];

	$newmusics[] = $newmusic;
}
var_dump($newmusic);

$musicvote_data = DB::query("select musicname,movie,singer,vote,filename from ".DB::table("common_movies_music")." order by vote desc limit 10");

while($data = DB::fetch($musicvote_data)){
	$musicvote['musicname'] = $data['musicname'];
	$musicvote['movie'] = $data['movie'];
	$musicvote['singer'] = $data['singer'];
	$musicvote['vote'] = $data['vote'];
	$musicvote['filename'] = $data['filename'];

	$musicvotes[] = $musicvote;
}

var_dump($musicvote);
include template("moviesmusic/index");
?>