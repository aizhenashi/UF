<?php

if($_G['uid'] == false){
	die('error no uid');
}

if($_GET['id']){

	//��Ƶid

  	$id = $_GET['id'];

	$data = c::t('original_video')->getDataForWhere("id = '{$id}'",true);
  		
	$data = $data[0];
	
	require_once libfile('class/lettv');
	$object = new LetvCloudV1();
	
	$image = $object->imageGet($data['video_id'], '320_180');
	$image = json_decode($image,'ARRAY');
	$image = $image['data']['img1'];

	
}

//�������з���
$vtypeDatas = c::t('creation_workstype')->get_data_for_tid('4');

//temp del
//$video_id_list = "2641096";
//$object->videoDelBatch($video_id_list);

//temp
/*
$datas = $object->videoList(1,100);
$datas = json_decode($datas,'ARRAY');
foreach ($datas['data'] as $key=>&$data){
	$data['video_name'] = iconv('GB2312','UTF-8',$data['video_name']);
	$data['img'] = '<img src="'.$data['img'].'" />';
	$data['data'][$key] = $data;
}
echo '<pre>';
var_dump($datas['data']);
echo '</pre>';
*/

//����Ƶ���ϴ���ַ  $videoInit['data']['upload_url'];
//��Ƶ���Ȳ�ѯ��ַ  $videoInit['data']['progress_url'];
include template('diy:original/fabu');

?>