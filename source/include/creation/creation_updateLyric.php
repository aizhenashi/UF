<?php
$mod = $_GET["mod"];
if($mod == 5)
{
	$lyricId = $_GET["id"];
	$sql = "select * from pre_common_music_lyric where id=".$lyricId;
	$lyric = DB::fetch_first($sql);
	include template('creation/updateLyric');
	exit;
}
include template('creation/updateLyric');
?>