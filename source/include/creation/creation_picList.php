<?php 
//缩略图3（238*159，列表页显示）

if($_GET['ptype'] != "all" && $_GET['ptype'] != null){
	$where .= " and type = {$_GET['ptype']} ";
	if($_GET['priceType'] != "all" && $_GET['priceType'] != ""){
		if($_GET['priceType'] == "free"){
				$where .= " and price = 0 ";
				if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
				}
		}elseif($_GET['priceType'] == "prize"){
				$where .= " and price > 0 ";
				if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
				}
		}
	}else{
			if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
			}
	
	}
}else{
	if($_GET['priceType'] != "all" && $_GET['priceType'] != ""){
		if($_GET['priceType'] == "free"){
				$where .= " and price = 0 ";
				if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
				}
		}elseif($_GET['priceType'] == "prize"){
				$where .= " and price > 0 ";
				if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
				}
		}
	}else{
			if($_GET['orderType']){
					if($_GET['orderType'] == "hot"){
						$where .= " order by click desc ";
					}elseif($_GET['orderType'] == "new"){
						$where .= " order by lasttime desc ";
					}
			}
	
	}

}

require libfile('class/page');//分页类
$page = empty($_GET['page'])?1:intval($_GET['page']);
//$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];//获取uid
$perpage=24;
$start_limit = ($page - 1) * $perpage;
$start_limit=$start_limit>=0?$start_limit:"0";
$limit=" limit ".$start_limit.",".$perpage ;
if(!empty($where)){
	$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('creation_views')." where xia = 0 ".$where);
}else{
	$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('creation_views')." where xia = 0");
}
$p=new page($sortdata['count'] ,$perpage);
$multipage=$p->show(8);
$allpage=ceil($sortdata['count']/$perpage);
if(!empty($_GET['page'])){
	$prepage=$_GET['page'];
}else{
	$prepage=1;
}

$type_data = DB::query("select wid,wname from ".DB::table("creation_workstype")." where tid = 5");
while($data = DB::fetch($type_data)){
	$type['wid'] = $data['wid'];
	$type['wname'] = $data['wname'];
	$types[] = $type;
}

$pic_data = DB::query("select id,title,url3 from ".DB::table("creation_views")." where xia = 0 ".$where." $limit");
while($data = DB::fetch($pic_data)){
	$pic_info['id'] = $data['id'];
	$pic_info['title'] = $data['title'];
	$pic_info['url3'] = $data['url3'];

	$pic_infos[] = $pic_info; 
}


//var_dump($pic_infos);
include template('creation/picList');
?>