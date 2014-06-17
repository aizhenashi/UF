<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(empty($_POST['type'])){
	$flag = 1;
}

if($_GET['type'] != 'all'){
	$topics_data1 = DB::query("select id,title,url,banner from ".DB::table('common_topics')." where type = '".$_GET['type']."' order by id desc");
	while($data = DB::fetch($topics_data1)){
		$data1['id'] = $data['id'];
		$data1['title'] = $data['title'];
		$data1['url'] = $data['url'];
		$data1['banner'] = $data['banner'];
	
		$datas1[] = $data1;
	}
}else{
	$t1 = DB::fetch_all("select id,title,url,banner from ".DB::table('common_topics')." where type = '1' order by id desc limit 2");
	$t2 = DB::fetch_all("select id,title,url,banner from ".DB::table('common_topics')." where type = '2' order by id desc limit 2");
	$t3 = DB::fetch_all("select id,title,url,banner from ".DB::table('common_topics')." where type = '3' order by id desc limit 2");
	$t4 = DB::fetch_all("select id,title,url,banner from ".DB::table('common_topics')." where type = '4' order by id desc limit 2");

	$datas1 = array_merge($t1,$t2,$t3,$t4);

}	


include template('topics/list');
?>