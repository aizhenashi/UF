<?php
	header("Content-type:text/html;charset=utf-8");
	
	require './source/class/class_core.php';
	$discuz = & discuz_core::instance();
	$discuz->init();

	$id = $_GET['pic'];
	$data = DB::fetch_first("select mime,url from ".DB::table("creation_views")." where id = {$id}");
	$t = getimagesize(DISCUZ_ROOT.'data/attachment/active/'.$data['url']);


	$mime = $t[mime];

	header("Content-Type: ".$mime);
	header("Content-Disposition: attachment; filename=xiazaitupian.jpg" );
	readfile(DISCUZ_ROOT."data/attachment/active/".$data['url']);

	
?>