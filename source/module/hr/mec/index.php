<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $

 * 1�����ղ���������������aaax,bbbb������2������������1������2�������Ǵ��������������Ϊ�գ�����Ϊ�ա�
   2��ƴ��URL��������STRURL, IF����2�Ƿ�Ϊ�գ�����������ѭ������if����1�Ƿ�Ϊ�գ�����������ѭ������
   3�����ݲ���1��2��SQLƴ��
   4\
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'district.php';

$optionadd = $filterurladd = $searchsorton = '';
require_once libfile('function/hr');
require libfile('class/page');


$showpic = intval($_GET['showpic']);
$templatearray = $quicksearchlist = array();
$age=$_GET['age'] ?$_GET['age'] :"all";
//$weight=$_GET['weight'] ?$_GET['weight'] :"all";
$jigou =$_GET['field3'] ?$_GET['field3'] :"all";
$city =$_GET['nativeplace'] ?$_GET['nativeplace'] :"all";
$nativeplace=$_GET['nativeplace'] ? $_GET['nativeplace'] : '0';


//var_dump($nativeplace);

//����ҳ��36
$perpage=36;

// ��ʼ��ɸѡ����
$user_weight=$user_height=$user_age=$user_types=array('title'=>'','identifier'=>'','unit'=>'','type'=>'','choices'=>'');


//���ӻ���ɸѡ����
$user_weight['title']='����';
$user_weight['identifier']='weight';
$user_weight['choices']=array('1'=>"�������",'2'=>"������Ƭ��",'3'=>"����������",'4'=>"ý��ƽ̨",'5'=>"Ͷ�ʻ���",'6'=>"���ݹ�����",'7'=>"����ԺУ",'8'=>"��������",'9'=>"���ֳ������޹�˾",'10'=>"�������޹�˾",'11'=>"��湫˾",'12'=>"���͹�˾",'13'=>"��Ƭ��˾",'14'=>"�ݳ���˾",'15'=>"Ӱ�ӹ�˾",'16'=>"�Ļ���˾",'17'=>"Ӱ��������˾",'18'=>"��ѵ����",'19'=>"����");
//array('1'=>"�������",'2'=>"������Ƭ��",'3'=>"����������",'4'=>"ý��ƽ̨",'5'=>"Ͷ�ʻ���",'6'=>"���ݹ�����",'7'=>'����ԺУ','8'=>'��������','9'=>'���ֳ������޹�˾','10'=>'�������޹�˾'��,'11'=>'��湫˾'��,'12'=>'���͹�˾'��,'13'=>'��Ƭ��˾'��,'14'=>'�ݳ���˾'��,'15'=>'Ӱ�ӹ�˾'��,'16'=>'�Ļ���˾'��,'17'=>'Ӱ��������˾'��,'18'=>'��ѵ����'��,'19'=>'����')


//���ɸѡ����
$quicksearchlist[]=$user_weight;

 if(count($arealist['nativeplace_son']) == 1) {
	$citysearchlist = '';
	$cityid = array_keys($arealist['nativeplace_son']);
	$cityid = $cityid[0];
} else {
	$citysearchlist = $arealist ? $arealist['nativeplace_son'] : '';
}
$districtsearchlist = $arealist && $cityid ? $arealist['district'][$cityid] : '';
//var_dump($districtsearchlist);
$streetsearchlist = $arealist && $districtid ? $arealist['street'][$districtid] : '';
$page=$_GET["page"];	
$start_limit = ($page - 1) * $perpage;

$filteradd = $sortoptionurl = $space = $searchkeyword = '';
$sorturladdarray = $selectadd = $conditionlist = $saveconditionlist = $savedistrictlist = $savestreetlist = $_G['hr_threadlist'] = array();
$filterfield = array( 'page','all');
$_GET['filter'] = isset($_GET['filter']) && in_array($_GET['filter'], $filterfield) ? $_GET['filter'] : 'all';

foreach ($filterfield as $v) {
	$catedisplayadd[$v] = '';
}
//�����ȡ����URL����

if($query_string = $_SERVER['QUERY_STRING']) {
	
	$query_string = substr($query_string, (strpos($query_string, "&") + 1));

	parse_str($query_string, $geturl);

	//var_dump($geturl);

	$geturl = daddslashes($geturl, 1);//��URL��õĲ������黰����
	$geturl=explode('_',$geturl['nativeplace']);
	//var_dump($geturl);
	//$city_id=substr($geturl[4],0,-5);
	if(!empty($geturl[4])){
		$geturl[1]=$geturl[4];
	}
	$city = $geturl[1] == "" ?0:$geturl[1] ;
	$jigou =  $geturl[0] == null?0:$geturl[0] ;
	//var_dump($geturl);

	if($geturl && is_array($geturl)) {
		$selectadd = $geturl[1];
		
		//print_r($filterfield);
		foreach($filterfield as $option) {
			$sfilterfield = array_merge(array('filter', 'sortid', 'searchoption'), $filterfield);
			foreach($geturl as $soption => $value) {
			
				$catedisplayadd[$option] .= !in_array($soption, $sfilterfield) ? "&amp;$soption=$value" : '';
				
			}
			//print_r($catedisplayadd);
		}
//var_dump($quicksearchlist);
		foreach($quicksearchlist as $option) {
			
			$conditionlist[$option['identifier']]['choices'] = $option['choices'];
			$conditionlist[$option['identifier']]['type'] = $option['type'];
			$conditionlist[$option['identifier']]['title'] = $option['title'];
			if($option['unit']) {
				$conditionlist[$option['identifier']]['unit'] = $option['unit'];
			}
		//var_dump($conditionlist);
		
			$identifier = $option['identifier'];
			
			foreach($geturl as $option => $value) {
				
				$sorturladdarray[$identifier] .= !in_array($user_weight['choices']["$option"], array('filter', 'sortid', 'searchoption', $identifier)) ?  $value."_" : 'all_';
				
			}
			
			$sorturladdarray[$identifier]=substr($sorturladdarray[$identifier],0,strlen($sorturladdarray[$identifier])-1);

			//var_dump($sorturladdarray);
		}
		
		$geturl['nativeplace']=$geturl[1];//ȷ����ѡ��ַ
		
		foreach($geturl as $option => $value) {
		//echo $option;
			$sorturladdarray['nativeplace'] .= !in_array($option, array('filter', 'sortid', 'nativeplace')) ? $value."_": '0';
		}
		
		//��ַ����
		$conditionlist['nativeplace']=$em_nativeplaces;
		//var_dump($geturl);
		foreach($geturl as $field => $value) {
			
			if($conditionlist[$field]) {
				$url = "mec.php?mod=index&";
				if($field == 'nativeplace') {
					$savecitylist['title'] = $conditionlist[$field][$value];
					$savecitylist['url'] = $url.$sorturladdarray[$field];
//var_dump($savecitylist);
				}else {
					$saveconditionlist[$field]['title'] = $conditionlist[$field]['choices'][$value].($conditionlist[$field]['type'] != 'range' ? $conditionlist[$field]['unit'] : '');
					$saveconditionlist[$field]['ntitle'] =$conditionlist[$field]['title'].":";
					$saveconditionlist[$field]['url'] = $url.$sorturladdarray[$field].".html";
					$saveconditionlistarray[]=$saveconditionlist[$field]['title'];
				}
			}
		}
		$saveconditionlistarray=array_filter($saveconditionlistarray);
		
	}
}

$page = $_G['page'];
$navtitle .= $sortlist[$sortid]['name'].' - ��'.$page.'ҳ - '.$channel['title'];




$conditionsql="";

$mec_name=$user_weight["choices"][$geturl[0]];//������
$city_name=$em_nativeplaces[$geturl[1]];//������
//var_dump($mec_name);
//var_dump($city_id);
//var_dump($geturl);
//var_dump($city_name);
//ƴ��SQL����
if(empty($_GET))
{
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member'));
}
else{

	//var_dump($user_weight["choices"][$_GET["field3"]]);
	if(!empty($geturl[0])&&$geturl[0] !='all'){
		$conditionsql.=" p.field3 = '$mec_name' and ";
	//$user_weight['choices']=array('1'=>"�������",'2'=>"������Ƭ��",'3'=>"����������",'4'=>"ý��ƽ̨",'5'=>"Ͷ�ʻ���",'6'=>"���ݹ�����",'7'=>"����ԺУ",'8'=>"��������",'9'=>"���ֳ������޹�˾",'10'=>"�������޹�˾",'11'=>"��湫˾",'12'=>"���͹�˾",'13'=>"��Ƭ��˾",'14'=>"�ݳ���˾",'15'=>"Ӱ�ӹ�˾",'16'=>"�Ļ���˾",'17'=>"Ӱ��������˾",'18'=>"��ѵ����",'19'=>"����");
	}

	//var_dump($_GET);

	//��������
	if(!empty($geturl[1])&&$geturl[1] !='all') {
		$conditionsql .=" ( p.resideprovince='$city_name' or p.residecity='$city_name' ) and ";
	}
	$conditionsql.=" m.groupid=22 ";//��������������Ա�û���
	//echo $conditionsql;

	$page = $_G['page'];
	$navtitle .= $sortlist[$sortid]['name'].' - ��'.$page.'ҳ - '.$channel['title'];
		foreach($geturl as $soption => $value) {
			$catedisplayadd['order'] .= !in_array($soption, array('filter', 'sortid', 'orderby', 'ascdesc', 'searchoption')) ? "&amp;$soption=$value" : '';
		}

	//$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on  p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid");
	//��ҳ�ı���$sortdata
	$sortdata['count'] = DB::num_rows(DB::query("SELECT COUNT(*)  FROM  ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')."  p on p.uid=m.uid where $conditionsql  group by m.uid"));
	//echo "SELECT COUNT(*)  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid";
}

//echo $sortdata['count'];
 $start_limit=$start_limit>=0?$start_limit:"0";
 $limit=" limit ".$start_limit.",".$perpage ;
//echo $conditionsql;
//echo "$modurl?mod=list&filter=$_GET[filter]$catedisplayadd[order]$catedisplayadd[searchoption]";
	$query = DB::query("SELECT m.uid,p.url,m.username,COUNT(*) as count  FROM  ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')."  p on p.uid=m.uid where $conditionsql  group by m.uid  order by p.isavatar desc,p.indexsort desc,m.uid desc $limit");
	//echo "SELECT m.uid,p.url,m.username,COUNT(*) as count  FROM  ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')."  p on p.uid=m.uid where $conditionsql  group by m.uid  order by p.isavatar desc,m.uid desc,p.indexsort desc $limit";
	$jobi=0;
	$mmkey = "aabb"; //ģ���¼key
	while($user= DB::fetch($query)) {
$user['encrypt'] = passport_encrypt($user['uid'],$mmkey); //ģ���¼�ַ�������
if(strlen($user['username'])>15){
		$user['username']=cutstr($user['username'],15);
}else{
		$user['username']=$user['username'];
}
$users[$jobi]=$user;
$users[$jobi]['job']=gettyename($user['uid']);
$jobi++;
	}
 $p=new page($sortdata['count'] ,$perpage);
 $multipage=$p->show(8);	
	//print_r($users);
	function gettyename($uid)
	{
	if(is_numeric($uid))
	{
	$query = DB::query("SELECT  field3  FROM ".DB::table('common_member_profile')." where uid=$uid");
	//echo "SELECT  field3  FROM ".DB::table('common_member_profile')." where uid=$uid";
	 //DB::query("SELECT  name  FROM ".DB::table('user_type')." as t left join ".DB::table('user_actor_type')."  u  on t.id=u.typeid  where u.uid=$uid ");
	$$typename='';
	$i=1;
	while($typenames= DB::fetch($query)) {
	
            $typename.=$typenames['field3']."&nbsp";
			$i++;
	}
	}
	//var_dump($typename);
	return $typename;
	//print_r($typename);
	}
	
	
include template('diy:mec/index');

?>