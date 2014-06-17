<?php
//拍摄地点的数据
$district_data = DB::query("select id,name from ".DB::table("common_district")." where upid = 0");
while($data = DB::fetch($district_data)){
	$district['id'] = $data['id'];
	$district['name'] = $data['name'];
	$districts[] = $district;
}
//分类
$type_data = DB::query("select wid,wname from ".DB::table("creation_workstype")." where tid = 5");
while($data = DB::fetch($type_data)){
	$pic_type['wid'] = $data['wid'];
	$pic_type['wname'] = $data['wname'];

	$pic_types[] = $pic_type;
}
//制缩略图

$viewId = $_GET["id"];
$viewSql = "select * from pre_creation_views where id=".$viewId;
$views = DB::fetch_first($viewSql);
$views['pictime']=date("Y-m-d",$views['pictime']);

//var_dump($views);

//制作缩略图
if($_GET['act'] == 'true')
{
		$upload = new discuz_upload();
		$FILE = $_FILES['upfile'];
		$upload->init($FILE, 'active');
		if($upload->error()) {
			return lang('spacecp', 'lack_of_access_to_upload_file_size');
		}
		if(!$upload->attach['isimage']) {
			return lang('spacecp', 'only_allows_upload_file_types');
		}	
		if(!$upload->attach['isimage']) {
			return lang('spacecp', 'only_allows_upload_file_types');
		}
		$upload->save();
		if($upload->error()) {
			return lang('spacecp', 'mobile_picture_temporary_failure');
		}
		if(!$upload->attach['imageinfo'] || !in_array($upload->attach['imageinfo']['2'], array(1,2,3,6))) {
			@unlink($upload->attach['target']);
			return lang('spacecp', 'only_allows_upload_file_types');
		}	
		//用于缩略图
		$new_name = $upload->attach['target'];
		//var_dump($new_name);
		//制作缩略图
		require_once libfile('class/image');
		$image = new image();
		//生成5个缩略图( 宽分别为370，235，238，559，190)的小图
		$nowdir = dirname($new_name); //原图所在目录
		//拼接缩略图 (宽分别为370，235，238，559，190)所在的目录
		$nowdir370 = dirname($new_name).'/370/';
		$nowdir235 = dirname($new_name).'/235/';
		$nowdir238 = dirname($new_name).'/238/';
		$nowdir559 = dirname($new_name).'/559/';
		$nowdir190 = dirname($new_name).'/190/';
		$arr = explode('/',$new_name);
		var_dump($arr);
		$filename = $arr[count($arr)-1];
		$new_name370 =  $nowdir370.$filename;
		$new_name235 =  $nowdir235.$filename;
		$new_name238 =  $nowdir238.$filename;
		$new_name559 =  $nowdir559.$filename;
		$new_name190 =  $nowdir190.$filename;
		$start370 = strrpos($new_name370,'.','-5');
		$start235 = strrpos($new_name235,'.','-5');
		$start238 = strrpos($new_name238,'.','-5');
		$start559 = strrpos($new_name559,'.','-5');
		$start190 = strrpos($new_name190,'.','-5');
		$new_name370 = substr($new_name370, $start370+2);
		$new_name235 = substr($new_name235, $start235+2);
		$new_name238 = substr($new_name238, $start238+2);
		$new_name559 = substr($new_name559, $start559+2);
		$new_name190 = substr($new_name190, $start190+2);
		$info = getimagesize($new_name);
		
		$size = filesize($new_name);
		//var_dump($size);
		if($info[0] > 370){//缩略图1（370*205，上传页显示）
			$tw370 = 370;
			$th205 = 205;
		}else{
			$tw370 = $info[0];
			$th205 = $info[1];
		}
		if($info[0] > 235){//缩略图2（235*155，首页显示）
			$tw235 = 235;
			$th155 = 155;
		}else{
			$tw235 = $info[0];
			$th155 = $info[1];
		}
		if($info[0] > 238){//缩略图3（238*159，列表页显示）
			$tw238 = 238;
			$th159 = 159;
		}else{
			$tw238 = $info[0];
			$th159 = $info[1];
		}
		if($info[0] > 559){//缩略图4（559*396，详情页显示）
			$tw559 = 559;
			$th396 = 396;
		}else{
			$tw559 = $info[0];
			$th396 = $info[1];
		}	
		if($info[0] > 190){//缩略图5（190*105，搜索结果页）
			$tw190 = 190;
			$th105 = 105;
		}else{
			$tw190 = $info[0];
			$th105 = $info[1];
		}	
		$result370 = $image->Thumb($new_name, $new_name370, $tw370, $th205, 1);
		$result235 = $image->Thumb($new_name, $new_name235, $tw235, $th155, 1);
		$result238 = $image->Thumb($new_name, $new_name238, $tw238, $th159, 1);
		$result559 = $image->Thumb($new_name, $new_name559, $tw559, $th396, 1);
		$result190 = $image->Thumb($new_name, $new_name190, $tw190, $th105, 1);
		//存库的文件
		$filename = $upload->attach['attachment']; //原图的路径
		$temp = explode('/',$filename);
		$tempfilename = array_pop($temp);
		$thumb370 = implode('/',$temp).'/370/'.$tempfilename; //宽 370 的缩略图路径
		$thumb235 = implode('/',$temp).'/235/'.$tempfilename; //宽 235 的缩略图路径
		$thumb238 = implode('/',$temp).'/238/'.$tempfilename; //宽 238 的缩略图路径
		$thumb559 = implode('/',$temp).'/559/'.$tempfilename; //宽 559 的缩略图路径
		$thumb190 = implode('/',$temp).'/190/'.$tempfilename; //宽 190 的缩略图路径
		if($filename){		
			echo "<script>window.parent.document.getElementById('img1').src = '/data/attachment/active/".$thumb370."'</script>";
			echo "<script>window.parent.document.getElementById('picfile').value = '".$filename."'</script>";
			echo "<script>window.parent.document.getElementById('picthumb370').value = '/data/attachment/active/".$thumb370."'</script>";
			echo "<script>window.parent.document.getElementById('picthumb235').value = '/data/attachment/active/".$thumb235."'</script>";
			echo "<script>window.parent.document.getElementById('picthumb238').value = '/data/attachment/active/".$thumb238."'</script>";
			echo "<script>window.parent.document.getElementById('picthumb559').value = '/data/attachment/active/".$thumb559."'</script>";
			echo "<script>window.parent.document.getElementById('picthumb190').value = '/data/attachment/active/".$thumb190."'</script>";
			echo "<script>window.parent.document.getElementById('picwidth').value = '".$info[0]."'</script>";
			echo "<script>window.parent.document.getElementById('picheight').value = '".$info[1]."'</script>";
			echo "<script>window.parent.document.getElementById('picsize').value = '".$size."'</script>";
			echo "<script>window.parent.document.getElementById('picmime').value = '".$info['mime']."'</script>";
		}
}
	$pic370 = $_POST['picthumb370'];//缩略图370
	$pic235 = $_POST['picthumb235'];//缩略图235
	$pic238 = $_POST['picthumb238'];//缩略图238
	$pic559 = $_POST['picthumb559'];//缩略图559
	$pic190 = $_POST['picthumb190'];//缩略图190
	$picfile = $_POST['picfile'];//原图
	$picwidth = $_POST['picwidth'];//宽
	$picheight = $_POST['picheight'];//高
	$picsize = $_POST['picsize'];//图片大小，单位KB
	
	$title = $_POST['title'];//标题
	
	$place_id = $_POST['place'];//城市id,表common_district
	$xiangxidizhi = $_POST['xiangxidizhi'];//详细地址拍摄
	$pictime = strtotime($_POST['pictime']);//拍摄时间
	$check = $_POST['check'];//0是后期处理1是未后期处理
	$type = $_POST['type'];//图片类型
	$mianfei = $_POST['mianfei'];//是否免费
	if(empty($mianfei) && !empty($_POST['price'])){
		$price = $_POST['price'];//价格
	}else{
		$price = 0;
	}
	$lasttime = time();//上传时间
	$mime = $_POST['picmime'];
	//$mime = $mime_type[1];
	//var_dump($mime_type);

	//echo "insert into ".DB::table("creation_views")."(id,uid,title,city,address,pictime,deal,type,price,url1,url2,url3,url4,url5,url,lasttime,size,width,height,xia) values(null,{$_G['uid']},'{$title}',{$place_id},'{$xiangxidizhi}',{$pictime},{$check},{$type},{$price},'{$pic370}','{$pic235}','{$pic238}','{$pic559}','{$pic190}','{$picfile}',{$lasttime},{$picsize},{$picwidth},{$picheight},{$xia})";
	if(!empty($title)){
		$sql = "update pre_creation_views set title='".$title."', city=".$place_id.", address='".$xiangxidizhi."', pictime=".$pictime.", deal=".$check.", type=".$type.", price=".$price.", url1='".$pic370."', url2='".$pic235."', url3='".$pic238."', url4='".$pic559."', url5='".$pic190."', url='".$picfile."', lasttime=".$lasttime.", size='".$picsize."', width=".$picwidth.", height=".$picheight.", mime='".$mime."' where id=".$viewId;
		DB::query($sql); 
		showmessage("修改成功","/creation.php?do=account");
	}
include template("diy:creation/viewedit");
?>