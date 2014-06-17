<?php
	//当前页数
	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page=1;
	//每页显示的条数
	$perpage = 15;
	
	//开始的条数
	$start = ($page-1)*$perpage;
	
	//查看某人的相册
	$datas = c::t('home_album')->getAlbumList($centeruid,0,$start,$perpage,$page);
	
	include template('diy:ucenter/albumlist');


?>