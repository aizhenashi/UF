<?php

/**
 * liukaiadd 2013.5.28
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*
 * ��ȡ���ֹ������Ѷ
 */
$wheresql = 'catid IN (\'2\')'; //select ���� option ��id
$ordersql = 'ORDER BY aid DESC';
$start = 0 ;  //�ӵڼ�����ʼ
$perpage = 10; //ÿҳ��ʾ������
$zixundata = C::t('portal_article_title')->fetch_all_by_sql($wheresql, $ordersql, $start, $perpage);
$zixunhtml = getZixunList($zixundata);

/*
 *  ������Ѷ����
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
 * ��ί��Ƭ ѡ����Ƭ 
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
//	array('pic'=>'/images/huanlegu/rater5.jpg','url'=>'/news/209.html'), // 3��
//	array('pic'=>'/images/huanlegu/rater6.jpg','url'=>'/news/210.html')	
);
$jsonPingWei = json_encode($arrayPingWei);

/**
 * ѡ����Ƭ
 */

$arrayXuanShou = array(
	array( //1��ѡ��
		array('pic'=>'/images/huanlegu/xuanshou611.jpg','url'=>'/news/201.html','title'=>iconv('gb2312','utf-8','������')),
		array('pic'=>'/images/huanlegu/xuanshou612.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','�ܺ�־')),
		array('pic'=>'/images/huanlegu/xuanshou613.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','��ԲԲ')),
		array('pic'=>'/images/huanlegu/xuanshou614.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','ǧ�꺭')),
		array('pic'=>'/images/huanlegu/xuanshou615.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','�����')),
		array('pic'=>'/images/huanlegu/xuanshou616.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','����'))			
	),
	array( //2��ѡ��
		array('pic'=>'/images/huanlegu/62xuanshou1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','���')),
		array('pic'=>'/images/huanlegu/62xuanshou2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','��־��')),
		array('pic'=>'/images/huanlegu/62xuanshou3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','����ޱ')),
		array('pic'=>'/images/huanlegu/62xuanshou4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','����')),
		array('pic'=>'/images/huanlegu/62xuanshou5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','���')),
		array('pic'=>'/images/huanlegu/62xuanshou6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','������'))
	),
	array( //6.10��ѡ��
		array('pic'=>'/images/huanlegu/610-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/610-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.11��ѡ��
		array('pic'=>'/images/huanlegu/611-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/611-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.12��ѡ��
		array('pic'=>'/images/huanlegu/612-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/612-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	),
	array( //6.15��ѡ��
		array('pic'=>'/images/huanlegu/615-1.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-2.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-3.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-4.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-5.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8','')),
		array('pic'=>'/images/huanlegu/615-6.jpg','url'=>'/news/207.html','title'=>iconv('gb2312','utf-8',''))
	)


/*
	array( //2��ѡ��
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
//��Ƭչʾҳ
if($_GET['act']=='photo'){
		/*$page=$page = empty($_GET['page'])?1:intval($_GET['page']);
		$perpage=15;//ÿҳ��ʾ������
		$start_limit = ($page - 1) * $perpage;
		$start_limit=$start_limit>=0?$start_limit:"0";
		$limit=" limit ".$start_limit.",".$perpage ;
		$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_pic')." where uid=11568 and albumid=280");
		$p=new page($sortdata['count'] ,$perpage);
		$multipage=$p->show(8);
		$allpage=ceil($sortdata['count']/$perpage);
		if(!empty($_GET['page'])){*/
		$page=$page = empty($_GET['page'])?1:intval($_GET['page']);
		$perpage=15;//ÿҳ��ʾ������
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
		��Ƶ�б�����ؼ���6.28
	*/
	$list=$_GET['list'];

include template('diy:huanlegu/'.$act);

?>