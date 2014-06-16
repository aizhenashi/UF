<?php 

/*
	require_once './source/class/class_core.php';
	require_once libfile('class/image');
	$discuz = C::app();
	$discuz->init();
	$image = new image();
	set_time_limit(0);
	ignore_user_abort(true);
	
	$datas=DB::fetch_all("SELECT `picid`,uid,filepath FROM ".DB::table('home_pic')." where thumb690=''");

	foreach($datas as $data){
		$pathinfo=explode("/",$data['filepath']);
		$pic200="album/$pathinfo[0]/$pathinfo[1]/94/$pathinfo[2]";
		//$pic80="album/$info[0]/$info[1]/80/$info[2]";
		//$pic550="album/$info[0]/$info[1]/550/$info[2]";
		//$pic690="album/$info[0]/$info[1]/690/$info[2]";
			
	    $new_name = DISCUZ_ROOT.'/data/attachment/album/'.$data['filepath'];
		$sizeinfo = getimagesize($new_name);
		$w = $sizeinfo[0] > 690 ? 690 : $sizeinfo[0];
		$h = $sizeinfo[1];
		$image->Thumb($new_name, $pic200, $w, $h, 3);
		
    	DB::query("update `".DB::table('home_pic')."` set `thumb690` = '{$pic200}' where picid = '{$data['picid']}'");
		
	}

	echo 'ok';
	*/
?>	