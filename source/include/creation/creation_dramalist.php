<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//if(!$_G['uid']){
//	header("location:login.html");
//}
//require libfile('class/page');
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}

//得到所有剧本类型的信息
$pname=DB::fetch_all("select tid,wid,wname FROM ".DB::table('creation_workstype')." where tid='1'");
//得到剧本的信息(列表)


if($_POST['type'] == 'ajax'){
	$dramainfo =  getDramainfo();
	include template('creation/dramalist1');
}else{
	$dramainfo =  getDramainfo();
	include template('creation/dramalist');
}
function getDramainfo(){
	$where = ' professor=0 && xia=0 ';
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$theurl = "/creation.php?do=dramalist";
	if($_GET['dramaClass'] && $_GET['dramaClass'] != 'all'){
		$dramaClass=setCharset($_GET['dramaClass']);
		//$where .= " && dramaClass like '%{$_GET['dramaClass']}%'";	
		$where .= " && dramaClass like '%{$dramaClass}%'";
		//$theurl .= "&dramaClass=".$_GET['dramaClass'];
		$theurl .= "&dramaClass=".$dramaClass;
	}
	if($_GET['price'] == 'all' || !$_GET['price']){
		$where .= '';		
	}else if($_GET['price'] == 'price'){
		$where .= ' && price != \'免费\'';
		$theurl .= "&price=price";
	}else if($_GET['price'] == 'free'){
		$where .= ' && price = \'免费\'';
		$theurl .= "&price=free";
	}
	$order = $_GET['order'] == 'hot' ? 'order by countnum desc' : 'order by articleId desc';
	$theurl .= "&order=".$_GET['order'];
	$sql = "select count(*) as tot from `".DB::table('common_article')."` where ".$where;
	$count = DB::fetch_first($sql);
	$count = $count['tot'];
	$multi = multi($count ,21, $page, $theurl);
	$dramainfo['pagehtml'] = $multi;
	$start = ($page-1)*21;	
	$limit = " limit {$start},21";
	$sql = "SELECT uid,articleId,title,writer,time,content,dramaClass FROM ".DB::table('common_article')." where {$where}{$order}{$limit}";
	$dramainfo['data']=DB::fetch_all("SELECT uid,articleId,title,writer,time,content,dramaClass FROM ".DB::table('common_article')." where {$where}{$order}{$limit}");	
	return $dramainfo;
}


?>