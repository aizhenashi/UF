<?php
global $_G;
require_once("api/yinpin/qiniu/rs.php");
$user = $_G[member][username];
$userId = $_G["uid"];
if(empty($_G['uid']))
{
	header("Location:login.html");
}
//���û�������������Ʒ
$dramasql="select * from pre_common_article where xia=0 and uid=".$userId;
$musicsql="select * from pre_common_music where xia=0 and uid=".$userId;
$lyricsql = "select * from pre_common_music_lyric where xia=0 and uid=".$userId;
$viewsql = "select * from pre_creation_views where xia=0 and uid=".$userId;

$viewRs = DB::query($viewsql);
$musicRs = DB::query($musicsql);
$lyricRs = DB::query($lyricsql);
$publicWorks = DB::fetch_all($dramasql);
//��ȡ�Ӿ�����Ϣ������ӵ�������
while($row = DB::fetch($viewRs))
{
	$data['title'] = $row['title'];
	$data['allPur'] = $row['allPur'];
	$data["articleId"] = $row["id"];
	$data["drama"] = 3;
	$today = time();
	$tod = date("Y-m-d H:i:s",$today);
	$todArr = explode("-",$tod);
	$year = intval($todArr[0]);
	$month = intval($todArr[1]);
	$day = intval($todArr[2]);
	$todStart = strtotime("$year-$month-$day 00:00:00");
	$todEnd = strtotime("$year-$month-$day 23:59:59");
	$sql ="select * from  pre_creation_purchased where createtime>=".$todStart." and createtime<=".$todEnd." and product_id=".$row['id'] ;
	$todPurch = DB::fetch_all($sql);
	$row['todPur'] = count($todPurch);
	$allSql ="select * from  pre_creation_purchased where product_id=".$row['id'];
	
	$allPurch = DB::fetch_all($allSql);
	$row['allPur'] = count($allPurch);
	$data["allPur"]  = $row['allPur'];
	$data["todPur"]  = $row['todPur'];
	$publicWorks[] = $data;	
}
//��ȡ��ʵ���Ϣ������ӵ����鵱��
while($row = DB::fetch($lyricRs))
{
	$data['title'] = $row['title'];
	$data["content"] = $row["content"];
	$data["price"] = $row["price"];
	$data["articleId"] = $row["id"];
	$data["drama"] = $row["lyric"];
	$publicWorks[] = $data;
}
//��ȡ���ֵ���Ϣ������ӵ����鵱��
while($row = DB::fetch($musicRs))
{
	$data['title'] = $row['musicname'];
	$data['creationtime'] = $row['createtime'];
	$data['time'] = $row['updatetime'];
	$data['uid'] = $row['uid'];
	//���ն�����ʱ������
	$today = time();
	$tod = date("Y-m-d H:i:s",$today);
	$todArr = explode("-",$tod);
	$year = intval($todArr[0]);
	$month = intval($todArr[1]);
	$day = intval($todArr[2]);
	$todStart = strtotime("$year-$month-$day 00:00:00");
	$todEnd = strtotime("$year-$month-$day 23:59:59");
	$sql ="select * from  pre_creation_purchased where createtime>=".$todStart." and createtime<=".$todEnd." and product_id=".$row['id'] ;
	$todPurch = DB::fetch_all($sql);
	$row['todPur'] = count($todPurch);
	$data['todPur']= $row['todPur'];
	$data['allPur'] = $row['allPur'];
	$data['articleId'] = $row['id'];
	$data['drama'] = $row['music'];
	$publicWorks[] = $data;
}

//�ҷ�������Ʒ��Ƶ
	$datas = c::t('original_video')->getDataForWhere("uid = '{$_G['uid']}' && fastatu = 1 && xia=0",true);	
	//���� ���ն���
	foreach($datas as &$data){
		//���ն���
		$todaytime = strtotime(date('Y-m-d',time()));
		$sql = "select count('id') as currnum from `".DB::table('creation_purchased')."` where product_id = '{$data['id']}' && product_class = 2 && createtime > '".$todaytime."' && jiaoyi_statu = 1";
		$t = DB::fetch_first($sql);
		$data['currOrder'] = $t['currnum'];
		//ȫ������
		$sql = "select count('id') as tnum from `".DB::table('creation_purchased')."` where product_id = '{$data['id']}' && product_class = 2 && jiaoyi_statu = 1";
		$t = DB::fetch_first($sql);
		$data['tOrder'] = $t['tnum'];
		$data['class'] = 4;
	}	
	$publicWorks = array_merge($publicWorks,$datas);	
//�ҹ���Ķ����б�
$musicsql ="select * from pre_creation_purchased where uid=".$userId." and product_class=1";
$musicpurshered = DB::query($musicsql);
while($row=DB::fetch($musicpurshered))
{
	$sql = "select * from pre_common_music where id=".$row['product_id'];
	$rs = DB::query($sql);
	$purched = DB::fetch($rs);
	$purched["class"] = $purched["music"];
	$purched["name"] = $purched["musicname"];
	$purcheddata[] = $purched;	
}
$viewsql = "select * from pre_creation_purchased where uid=".$userId." and product_class=3";
$viewpurshered = DB::query($viewsql);
while($row = DB::fetch($viewpurshered))
{
	$sql = "select * from pre_creation_views where id=".$row['product_id'];
	$rs = DB::query($sql);
	$purched = DB::fetch($rs);
	$purched["class"] = 3;
	$purched["name"] = $purched["title"];
	$purcheddata[] = $purched;
}

//��Ƶ�����¼
require_once libfile('class/lettv');
$object = new LetvCloudV1();

$videosql = "select `product_id` from pre_creation_purchased where uid='".$userId."' and product_class=2 and jiaoyi_statu=1";
$videodatas = DB::fetch_all($videosql);

foreach($videodatas as $key=>&$data){
	$t = c::t("original_video")->getDataForWhere("id = '{$data['product_id']}'",true);
	$t = $t[0];	
	$t['class'] = 4; 
	$data = $t;
}

$purcheddata = array_merge($purcheddata,$videodatas);	

//�¼���Ʒ����
if($_GET['xia']=='uppic')
{
	$articleId = $_GET["articleId"];
	
	$clas = $_GET['clas'];
	//�¼ܾ籾����
	if($clas==1)
	{
		$dramasql="update pre_common_article set xia=1 where articleId=".$articleId;
		DB::query($dramasql);
	}elseif($clas==2)//�¼����ֹ���
	{		
		$musicsql ="update pre_common_music set xia=1 where id=".$articleId;
		DB::query($musicsql);
		
	}elseif($clas==3)//�¼��Ӿ�����
	{
		$viewsql = "update pre_creation_views set xia=1 where id=".$articleId;
	    DB::query($viewsql);
	}elseif($clas==4)//�¼���Ƶ����
	{
		$viewsql = "update pre_original_video set xia=1 where id=".$articleId;
		DB::query($viewsql);
	}
	elseif($clas==5)//�¼ܸ�ʹ���
	{
		$lyricsql="update pre_common_music_lyric set xia=1 where id=".$articleId;
		DB::query($lyricsql);
	}
	echo "<script language='javascript'>";
	echo "window.location='creation.php?do=account'";
	echo "</script>";
}
//��������
$uppic = $_GET['do1'];
if($uppic == 'uppic')
{
	$clas = $_GET['clas'];
	if($clas ==2)
	{
			$productId = $_GET['productId'];
			$sql ='select * from pre_common_music where id='.$productId;
			$rs = DB::query($sql);
			$row = DB::fetch($rs);
		 	$filename = $row["filename"];
			$key = $filename;
			$domain = 'uestarroom.qiniudn.com';
			$accessKey = 'wJ7DPFCkCqYiaF1RFf0ASI5XbXTq_sl7VoKkPbtn';
			$secretKey = 'yYa2OLsuho5Gl9Z7dntBysVkLweSZVXJJzkr_TaB';
			Qiniu_SetKeys($accessKey, $secretKey);  
			$baseUrl = Qiniu_RS_MakeBaseUrl($domain, $key);
			$getPolicy = new Qiniu_RS_GetPolicy();
			$privateUrl = $getPolicy->MakeRequest($baseUrl, null);
			$arr = explode("?",$privateUrl);
			$privateUrl = $arr[0];
			$filename = basename($privateUrl);
			header("content-type:application/x-msdownload");
			header("content-disposition:attachment;filename={$filename}");
			readfile($privateUrl);
	}elseif($clas==3)
	{
		$productId = $_GET['productId'];
		echo $productId;
		echo "<script language='javascript'>";	
		echo "window.location='creation.php?do=picInfo&id=".$productId."';";
		echo "</script>";
		exit;
	}
}
include template('creation/account');