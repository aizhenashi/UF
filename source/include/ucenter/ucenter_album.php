<?php
	//��ǰҳ��
	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page=1;
	//ÿҳ��ʾ������
	$perpage = 15;
	
	//��ʼ������
	$start = ($page-1)*$perpage;
	
	//�鿴ĳ�˵����
	$datas = c::t('home_album')->getAlbumList($centeruid,0,$start,$perpage,$page);
	
	include template('diy:ucenter/albumlist');


?>