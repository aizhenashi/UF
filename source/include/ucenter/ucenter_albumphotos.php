<?php	
	$aid = $_GET['aid'];


	$album = C::t('home_album')->fetch($aid); //���info		
	
	
	$list = C::t('home_album')->getAlbumAllPhotoList($aid); //������µ�������Ƭ

	
	//��ȡ����������
	$albumdatas = c::t('home_album')->getAlbumList($centeruid,0,0,2,0,false);
	$albumdatas = $albumdatas['data'];

	
	include template('diy:ucenter/albumphotos');
?>