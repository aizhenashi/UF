#!/home/app/php/bin/php
<?php
//	error_reporting(0);
//	header("Content-Type:text/html;charset=utf-8");
	//获取乐视的视频列表


	require_once '/home/app/apache2/htdocs/uestar/source/class/class_lettv.php';
//	require_once $_SERVER['DOCUMENT_ROOT'].'source/class/class_lettv.php';
	$object = new LetvCloudV1();

/*
//temp
	$result = $object->videoList(1,4);
	$result = json_decode($result,'ARRAY');
	echo '<pre>';
	var_dump($result);
	echo '</pre>';
	exit;
*/

	//取出 uestar的视频列表 及其长度
	$l =	mysql_connect('192.168.1.102','sa','www.uestar.net');
//	$l =	mysql_connect('localhost','root','root');
	mysql_select_db('uestar');
	mysql_query('set names utf8');
	$rs = mysql_query("select video_id from pre_original_video order by video_id desc");
	while ($row = mysql_fetch_assoc($rs)){
		$datas[] = $row;
	}

	$counts = count($datas);

	if($datas === NULL){
		$datas = array();
	}

	$deleteArray = '';
	$count1 = 0;
	$temp = 0;
	$temp1 = 0;
	function deleteLetTvVideo($index){		

		global $temp;
		global $temp1;
  		//lettv接口
		global $object;
		//uestar video array 
		global $datas;
		//预删除数组
		global $deletestr;
		//ue 视频数
		global $counts;
		//临时变量
		global $count1;

		$result = $object->videoList($index,1);
		$result = json_decode($result,'ARRAY');

		if($result['data']){
			if($result['data'][0]['video_id'] > 2571166){
				

				deleteLetTvVideo($index+1);
				
				//与uestar 数据进行比较
				if(!in_array(array('video_id'=>$result['data'][0]['video_id']),$datas)){
					if($temp >= 50){
						$temp1++;
						$temp = 0;
					}
					$temp++;
					$t = 't'.$temp1;
					global $$t;
					// 1.加入到删除数组
					${$t} .=  $result['data'][0]['video_id'].'-';

				}else if(($count1 < $counts) && in_array(array('video_id'=>$result['data'][0]['video_id']),$datas)){
					// 2.记录条数 加1
					$count1++;

				}else if($count1 == $counts){
					if($temp >= 50){
						$temp1++;
						$temp = 0;
					}
					$temp++;
					$t = 't'.$temp1;
					global $$t;
					// 1.加入到删除数组
					${$t} .=  $result['data'][0]['video_id'].'-';
				}
				
			}
		}
	}

	deleteLetTvVideo(1);

	$deletestr =  rtrim($deletestr,',');
	for ($i = 0; $i <= $temp1;$i++){
		$t = 't'.$i;
		$object->videoDelBatch(rtrim($$t,'-'));
	}
?>
