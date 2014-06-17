<?php

/**
 * liukaiadd 2013.5.28
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 * 获取欢乐谷相关资讯
 */
$wheresql = 'catid IN (\'2\')'; //select 里面 option 的id
$ordersql = 'ORDER BY aid DESC';
$start = 0 ;  //从第几条开始
$perpage = 10; //每页显示的条数
$zixundata = C::t('portal_article_title')->fetch_all_by_sql($wheresql, $ordersql, $start, $perpage);
$zixunhtml = getZixunList($zixundata);

/*
 *  处理资讯数据
 *  return html 
 */
function getZixunList($array){

	$html = '<ul>';
	
	foreach ($array as $val){
	    $html .= '<li><a href="/news/'.$val['aid'].'.html" target="_blank">'.$val['title'].'</a></li>';
	}

    $html .= '</ul>';
		
	return $html;
}


/*
 * 评委照片 选手照片 
 */
$arrayPingWei = array(
	array('pic'=>'/images/huanlegu/pingwei611.jpg','url'=>'/huanlegu.php?act=photo'), // 13.6.1 zu1
	array('pic'=>'/images/huanlegu/changjing2.jpg','url'=>'/huanlegu.php?act=photo'),
	array('pic'=>'/images/huanlegu/pingwei612.jpg','url'=>'/huanlegu.php?act=photo'), //zu2
	array('pic'=>'/images/huanlegu/changjing4.jpg','url'=>'/huanlegu.php?act=photo'),
	array('pic'=>'/images/huanlegu/62pingwei1.jpg','url'=>'/huanlegu.php?act=photo'), // 13.6.2 zu3
	array('pic'=>'/images/huanlegu/changjing5.jpg','url'=>'/huanlegu.php?act=photo'),
	array('pic'=>'/images/huanlegu/62pingwei2.jpg','url'=>'/huanlegu.php?act=photo'), // zu4
	array('pic'=>'/images/huanlegu/changjing7.jpg','url'=>'/huanlegu.php?act=photo'),
	array('pic'=>'/images/huanlegu/pingwei611.jpg','url'=>'/huanlegu.php?act=photo'), // 13.6.1 zu5
	array('pic'=>'/images/huanlegu/changjing2.jpg','url'=>'/huanlegu.php?act=photo'),
	array('pic'=>'/images/huanlegu/pingwei612.jpg','url'=>'/huanlegu.php?act=photo'), //zu6
	array('pic'=>'/images/huanlegu/changjing4.jpg','url'=>'/huanlegu.php?act=photo')
//	array('pic'=>'/images/huanlegu/rater5.jpg','url'=>'/news/209.html'), // 3组
//	array('pic'=>'/images/huanlegu/rater6.jpg','url'=>'/news/210.html')	
);
$jsonPingWei = json_encode($arrayPingWei);

/**
 * 选手照片
 */

$arrayXuanShou = array(
	array( //1组选手
		array('pic'=>'/images/huanlegu/xuanshou611.jpg','url'=>'/news/201.html','title'=>iconv('gb2312','utf-8','周晓洁')),
		array('pic'=>'/images/huanlegu/xuanshou612.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','周洪志')),
		array('pic'=>'/images/huanlegu/xuanshou613.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','腾圆圆')),
		array('pic'=>'/images/huanlegu/xuanshou614.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','千雨涵')),
		array('pic'=>'/images/huanlegu/xuanshou615.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','黄如佳')),
		array('pic'=>'/images/huanlegu/xuanshou616.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','黄鹏'))			
	),
	array( //2组选手
		array('pic'=>'/images/huanlegu/62xuanshou1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','杨凯凯')),
		array('pic'=>'/images/huanlegu/62xuanshou2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','王志辉')),
		array('pic'=>'/images/huanlegu/62xuanshou3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','沈紫薇')),
		array('pic'=>'/images/huanlegu/62xuanshou4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','李钰')),
		array('pic'=>'/images/huanlegu/62xuanshou5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','贾楠')),
		array('pic'=>'/images/huanlegu/62xuanshou6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','丁岳峰'))
	),
	array( //6.10组选手
		array('pic'=>'/images/huanlegu/610-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.11组选手
		array('pic'=>'/images/huanlegu/611-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.12组选手
		array('pic'=>'/images/huanlegu/612-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.15组选手
		array('pic'=>'/images/huanlegu/615-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	)


/*
	array( //2组选手
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html'),
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html'),
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html'),
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html'),
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html'),
		array('pic'=>'/images/huanlegu/player3.jpg','url'=>'/news/207.html')
	)	
*/
);

$jsonXuanShou = json_encode($arrayXuanShou);
//照片展示页
if($_GET['act']=='photo'){
		/*$page=$page = empty($_GET['page'])?1:intval($_GET['page']);
		$perpage=15;//每页显示多少条
		$start_limit = ($page - 1) * $perpage;
		$start_limit=$start_limit>=0?$start_limit:"0";
		$limit=" limit ".$start_limit.",".$perpage ;
		$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_pic')." where uid=11568 and albumid=280");
		$p=new page($sortdata['count'] ,$perpage);
		$multipage=$p->show(8);
		$allpage=ceil($sortdata['count']/$perpage);
		if(!empty($_GET['page'])){*/
		$page=$page = empty($_GET['page'])?1:intval($_GET['page']);
		$perpage=15;//每页显示多少条
		$start_limit = ($page - 1) * $perpage;
		$start_limit=$start_limit>=0?$start_limit:"0";
		$limit=" limit ".$start_limit.",".$perpage ;
		$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_pic')." where uid=11568 and albumid=280");
		$p=new page($sortdata['count'] ,$perpage);
		$multipage=$p->show(8);
		$allpage=ceil($sortdata['count']/$perpage);
						if(!empty($_GET['page'])){
	
							$prepage=$_GET['page'];
						}else{
							$prepage=1;
						}
		$data=MemData::huanlegu();
	}
	
	/*
		视频列表排序关键词6.28
	*/
	$list=$_GET['list'];

include template('diy:huanlegu/'.$act);

?>