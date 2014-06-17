<?php
require libfile('class/lettv');
$object = new LetvCloudV1();

//查找所有分类
$vtypeDatas = c::t('creation_workstype')->get_data_for_tid('4');
//视频列表数据
$datas = c::t('original_video')->getDataForParams($_GET['vtype'],$_GET['priceType'],$_GET['orderType'],$_GET['page']);

//temp
//$datas = $object->videoList();
//$datas = json_decode($datas,'ARRAY');

//模板
include template('diy:original/videolist');
?>