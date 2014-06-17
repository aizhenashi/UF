<?php
global $_G;
$uid =  $_G["uid"];
if($_G["uid"])
{
	$articleId = $_GET["articleId"];
	$sql = "select * from pre_common_article where articleId={$articleId}";
	$article = DB::query($sql);
	$article = DB::fetch($article);
	$article["content"] = substr($article["content"],0,300)."......";
	$article["content"] = str_replace(chr(10),'<br>',$article["content"]);
	$article["content"] = str_replace(chr(32),'&nbsp;',$article["content"]);
	$article["creationTime"]= date('Y-m-d',$article['creationTime']); 
	if(in_array($_G["uid"],$article))
	{
		$article = DB::query($sql);
		$article = DB::fetch($article);
		$article["creationTime"]= date('Y-m-d',$article['creationTime']);
		$article["content"] = str_replace(chr(10),'<br>',$article["content"]);
		$article["content"] = str_replace(chr(32),'&nbsp;',$article["content"]);
		include template("article/content");
		exit;
	}
	$arr = array('18009','17199','17694');
	if(in_array($_G["uid"],$arr))
	{
		$article = DB::query($sql);
		$article = DB::fetch($article);
		$article["creationTime"]= date('Y-m-d',$article['creationTime']);
		$article["content"] = str_replace(chr(10),'<br>',$article["content"]);
		$article["content"] = str_replace(chr(32),'&nbsp;',$article["content"]);
		
		include template("article/content");
		exit;
	}
	include template("article/content");
	exit;
}
else{
	showmessage('ÇëµÇÂ¼ºó²é¿´','login.html');
}