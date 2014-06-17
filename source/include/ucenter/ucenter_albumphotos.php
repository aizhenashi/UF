<?php	
	$aid = $_GET['aid'];


	$album = C::t('home_album')->fetch($aid); //相册info		
	
	
	$list = C::t('home_album')->getAlbumAllPhotoList($aid); //该相册下的所有照片

	
	//获取两张相册封面
	$albumdatas = c::t('home_album')->getAlbumList($centeruid,0,0,2,0,false);
	$albumdatas = $albumdatas['data'];

	
	include template('diy:ucenter/albumphotos');
?>