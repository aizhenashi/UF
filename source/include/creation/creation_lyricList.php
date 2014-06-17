<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}
/*1.找出我的所有关注
 * 2.查看是否在我的关注表中
 * 3.如果在，则跳出本次循环，否则插入我的关注表中
 * 
 * */
//己注册所有会员
/*
$uid=DB::fetch_all("SELECT uid,username FROM ".DB::table('ucenter_members')." where uid!=8925");
//我的关注会员
$mfuid=DB::fetch_all("SELECT followuid,fusername FROM ".DB::table('home_follow')." WHERE uid=8925");
$time=time();

$t1 = 
array(
	array('uid'=>1,'username'=>'铝合金'),
	array('uid'=>2,'username'=>'铝合金2'),
	array('uid'=>3,'username'=>'铝合金3'),
	array('uid'=>4,'username'=>'铝合金4'),
	array('uid'=>5,'username'=>'铝合金5'),
	array('uid'=>6,'username'=>'铝合金6'),
	array('uid'=>7,'username'=>'铝合金7'),
	array('uid'=>8,'username'=>'铝合金8')			
);

$t2 = 
array(
	array('fuid'=>11,'fusername'=>'铝合金11'),
	array('fuid'=>2,'fusername'=>'铝合金2'),
	array('fuid'=>17,'fusername'=>'铝合金7'),
	array('fuid'=>8,'fusername'=>'铝合金8')			
);

getChaJi($t1,$t2,$t);
getChaJi($t2,$t1,$t);


function getChaJi($a1,$a2,&$array){
	foreach ($a1 as $k=>$data){
		if(!in_array($data,$a2)){
			$array[] = $data;
		}		
	}
}


echo '<pre>';
var_dump($t);
echo '</pre>';
exit;
foreach($mfuid as $u){
	for($i=0;$i<=count($uid);$i++){
		//if($u['followuid']!=$uid[$i]['uid']){
		if(!in_array($uid[$i]['uid'],$u)){
			var_dump($uid[$i]) ;
			var_dump($u);
			exit;
			DB::insert('home_follow',array('uid'=>'8925','username'=>'优艺网盟小秘书','followuid'=>$uid[$i]['uid'],'fusername'=>$uid[$i]['username'],'bkname'=>'','status'=>'0','mutual'=>'1','dateline'=>$time));
			//echo $i.'<br/>'; 
		}else{
			continue;
		}
	}
}
exit;
*/
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}

function listData(){
	$price=setCharset($_GET['price']);
	$best=setCharset($_GET['best']);
	$page=$_GET['page']?$_GET['page']:1;
	$url="creation.php?do=lyricList";
	$where=" 1 and xia=0 ";
	if($price=='free'){
		$where.=" && price='免费'";
		$url.="&price=free";
	}else if($price=='all'){
		$where.="";
		$url.="&price=all";
	}else if($price=='price'){
		$where.=" && price!='免费'";
		$url.="&price=price";
	}
	$order=" order by id desc ";
	if($best=="hot"){
		$order=" order by countnum desc ";
	}
	if($best=="new"){
		$order=" order by id desc ";
	}
	//$where .=$order;
	$url.="&order=".$best;
	$count=DB::fetch_first("SELECT count(id) as num FROM ".DB::table('common_music_lyric')." where $where");
	$data['multi'] = multi($count['num'] ,15, $page, $url);
	$start=($page-1)*15;
	$limit=" limit $start,15";
	$data['info']=DB::fetch_all("SELECT id,title,price FROM ".DB::table('common_music_lyric')." where {$where}{$order}{$limit}");
	return $data;
}
if($_POST['data']=='li'){
	$data=listData();
	include template('creation/musicLyricAjax');
}else{
	$data=listData();
	include template('creation/musicLyricList');
}


?>