<?php 
/*
*原创地带首页
*1，视频部分
*2，音乐部分
*3，视觉部分
*4，剧本部分
*
*/
//音乐部分
//1,取出音乐类型（tid = 2）
$music_type_data = DB::query("select tid,wid,wname from ".DB::table("creation_workstype")." where tid = 2 order by wid asc");
while($data = DB::fetch($music_type_data)){
	if(strlen($data['wname'])<=4){
		$music_type['tid'] = $data['tid'];
		$music_type['wid'] = $data['wid'];
		$music_type['wname'] = $data['wname'];

		$music_types[] = $music_type;
	}
}
//if(empty($_POST['mtype'])){
	//$mwid = 1;
//}else{
	//$mwid = intval($_POST['mtype']);
//}
if($_POST['creationtype'] == '2'){
	$mwid = intval($_POST['mtype']);
	$music_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 2 and wid = $mwid");
	$music_info = DB::fetch_all("select m.id,m.uid,m.musicname,m.language,m.style,m.countnum,p.username from ".DB::table("common_music")." as m left join ".DB::table("common_member")." as p on m.uid = p.uid where m.style = $mwid and xia = 0 order by m.id desc limit 10");
	include template("diy:ajax/musicContent");
	exit;
}
$music_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 2 ");
$music_first_data = DB::fetch_all("select m.id,m.uid,m.musicname,m.language,m.style,m.countnum,p.username from ".DB::table("common_music")." as m left join ".DB::table("common_member")." as p on m.uid = p.uid where xia = 0 order by m.id desc limit 10");


//2,取出剧本类型（tid = 1）
$article_type_data = DB::query("select tid,wid,wname from ".DB::table("creation_workstype")." where tid = 1 order by wid asc");
while($data = DB::fetch($article_type_data)){
	$article_type['tid'] = $data['tid'];
	$article_type['wid'] = $data['wid'];
	$article_type['wname'] = $data['wname'];
	$article_types[] = $article_type;
}
//if(empty($_POST['atype'])){
	//$awid = 1;
//}else{
	//$awid = intval($_POST['atype']);
//}
if($_POST['creationtype'] == '1'){
	$awid = intval($_POST['atype']);
	$article_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 1 and wid = $awid");
	$article_info = DB::fetch_all("select articleId,title,content from ".DB::table('common_article')." where dramaClass like '%".$article_type['wname']."%' and xia = 0 limit 6");
	include template("diy:ajax/articleContent");
	exit;
}
$article_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 1");
$article_first_data = DB::fetch_all("select articleId,title,content from ".DB::table('common_article')." where xia = 0 and professor = 0 order by articleId desc limit 6");

//视觉部分，缩略图2（235*155，首页显示）
//3,取出视觉类型（tid = 5）
$pic_type_data = DB::query("select tid,wid,wname from ".DB::table("creation_workstype")." where tid = 5 order by wid asc");
while($data = DB::fetch($pic_type_data)){
	$pic_type['tid'] = $data['tid'];
	$pic_type['wid'] = $data['wid'];
	$pic_type['wname'] = $data['wname'];
	$pic_types[] = $pic_type;
}
//if(empty($_POST['ptype'])){
	//$pwid = 1;
//}else{
	//$pwid = intval($_POST['ptype']);
//}
if($_POST['creationtype'] == '5'){
	$pwid = intval($_POST['ptype']);
	$pic_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 5 and wid = $pwid");
	$pic_info = DB::fetch_all("select id,title,url2 from ".DB::table('creation_views')." where type = $pwid and xia = 0 order by lasttime desc limit 6");
	include template("diy:ajax/picContent");
	exit;
}
$pic_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 5");
$pic_first_data = DB::fetch_all("select id,title,url2 from ".DB::table('creation_views')." where  xia = 0 order by lasttime desc limit 6");

//4,取出视频类型（tid = 4）
require libfile('class/lettv');
$object = new LetvCloudV1();
//if(empty($_POST['vtype'])){
	//$vwid = 1;
//}else{
	//$vwid = intval($_POST['vtype']);
//}
if($_POST['creationtype'] == '4'){
	$vwid = intval($_POST['vtype']);
	$video_type = DB::fetch_first("select wname from ".DB::table("creation_workstype")." where tid = 4 and wid = $vwid");
	//视频列表数据
	$video_datas = c::t('original_video')->getDataForIndex($vwid,12);
	include template("diy:ajax/videoContent");
	exit;
}
//查找所有分类
$vtypeDatas = c::t('creation_workstype')->get_data_for_tid('4');
//视频列表数据
$video_datas = c::t('original_video')->getDataForIndex('',12);
//echo "<pre>";
//var_dump($video_datas['datas']);
//echo "</pre>";
include template('creation/index');
?>