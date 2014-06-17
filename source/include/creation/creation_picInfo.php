<?php 
//缩略图4（559*396，详情页显示）
$id = $_GET['id'];//图片ID

$pic_data = DB::query("select id,title,city,address,pictime,deal,type,price,click,url4,url,size,width,height,mime from ".DB::table("creation_views")." where id = $id and xia = 0");
while($data = DB::fetch($pic_data)){
		$pic_info['id'] = $data['id'];
		$pic_info['title'] = $data['title'];
		//$pic_info['city'] = $data['city'];
		
		$city = DB::fetch_first("select name from ".DB::table("common_district")." where id = {$data['city']}");
		$pic_info['city'] = $city['name']; 
		$pic_info['address'] = $data['address'];
		$pic_info['pictime'] = date("Y-m-d",$data['pictime']);
		$pic_info['deal'] = $data['deal'];
		//$pic_info['type'] = $data['type'];
		$type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 5 and wid = {$data['type']}");
		$pic_info['type'] = $type['wname'];
		$pic_info['price'] = $data['price'];
		$pic_info['click'] = ($data['click']+1);
		$pic_info['url4'] = $data['url4'];
		$pic_info['url'] = $data['url'];
		$pic_info['size'] = round(($data['size']/1024),2);
		
		$pic_info['width'] = $data['width'];
		$pic_info['height'] = $data['height'];
		$pic_info['mime'] = $data['mime'];

		
}
$click = DB::query("update ".DB::table("creation_views")." set click = {$pic_info['click']} where id = $id");

//creation_purchased交易成功记录表

$flag = DB::fetch_first("select id from ".DB::table("creation_purchased")." where uid = {$_G['uid']} and product_id = {$id} and product_class = 3");

//var_dump($pic_info);

include template('creation/picInfo');
?>