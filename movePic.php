<?php
	require_once './source/class/class_core.php';
	require_once libfile('class/image');
	$discuz = C::app();
	$discuz->init();

	$image = new image();
	set_time_limit(0);
	ignore_user_abort(true);

	//获取 所有相册id
	$datas = DB::fetch_all("select `picid`,`filepath` from `".DB::table('home_pic')."`  order by picid desc limit 2000,500");

	foreach($datas as $data){

		$filepath = $data['filepath'];

		$temparr = explode('/',$data['filepath']);
		$pic690 = 'album/'.$temparr[0].'/'.$temparr[1].'/690/'.$temparr[2];

		$new_name = DISCUZ_ROOT.'/data/attachment/album/'.$filepath;

		$info = getimagesize($new_name);
		$w = $info[0];
		$h = $info[1];

		echo $pic690;
		echo '<br />';
		
		if($w < 690 && $h < 690){
			$tw = $w;
			$th = $h;
		}else{
			$tw = 690;
			$th = 690;
		}
		
		$image->Thumb($new_name, $pic690, $tw, $th, 1);

 	  	DB::query("update `".DB::table('home_pic')."` set `thumb690` = '{$pic690}' where picid = '{$data['picid']}'");
		$lid = $data['picid'];

	}
	
	
//	echo '最后插入的id : '.$lid;


?>