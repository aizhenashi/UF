<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'district.php';// 加载地区数组
$optionadd = $filterurladd = $searchsorton = '';
require_once libfile('function/hr');
require libfile('class/page');
$showpic = intval($_GET['showpic']);
$templatearray = $quicksearchlist = array();
$age=$_GET['age'] ?$_GET['age'] :"all";
$weight=$_GET['weight'] ?$_GET['weight'] :"all";
$height=$_GET['height'] ?$_GET['height'] :"all";
$type=$_GET['user_type'] ?$_GET['user_type'] :"all";
$nativeplace=$_GET['nativeplace'] ? intval($_GET['nativeplace']) : '0';
$sex=$_GET['sex'] ? $_GET['sex'] : 'all';
//$nativeplace_top = $_GET['nativeplace_top'] ? intval($_GET['nativeplace_top']) : '';
//$nativeplace_son = $_GET['nativeplace_son'] ? intval($_GET['nativeplace_son']) : '';

$perpage=36;//每页显示会员数量
//$perpage=3;


// 初始化筛选条件
$user_weight=$user_height=$user_age=$user_types=$user_sex=array('title'=>'','identifier'=>'','unit'=>'','type'=>'','choices'=>'');
//获取用户类型数据


//$user_type =MemData::user_type();

/*$user_a = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
while($user_type= DB::fetch($user_a)) 
{
if($user_type['rank']==3)
{
	if($user_type['topid']==5||$user_type['topid']==4||$user_type['topid']==3)  //判断是否演出类型
	{
		$user_types['choices'][$user_type['id']]=$user_type['name'];
		$user_types['rank'][$user_type['id']]=$user_type['topid'];
	}
}
}*/

//获取用户类型数据
$user_types = MemData::job_tal();
$user_types['identifier']='user_type';
$user_types['title']='类别';

//增加体重筛选数据
$user_weight['title']='体重';
$user_weight['identifier']='weight';
$user_weight['choices']=array('d-40'=>"40KG以下",'40-50'=>"40~50",'50-60'=>"50~60",'60-70'=>"60~70",'70-80'=>"70~80",'80-u'=>"80KG以上");

//增加身高筛选数据
$user_height['title']='身高';
$user_height['identifier']='height';
$user_height['choices']=array('d-150'=>"150以下",'150-155'=>"150~155",'155-160'=>"155~160",'160-165'=>"160~165",'165-170'=>"165~170",'170-175'=>"170~175",'175-180'=>"175~180",'180-u'=>"180以上");
//增加年龄筛选数据
$user_age['title']='年龄';
$user_age['identifier']='age';
$user_age['choices']=array('d-15'=>"15岁以下",'15-20'=>"15~20",'20-25'=>"20~25",'25-30'=>"25~30",'30-35'=>"30~35",'35-u'=>"35岁以上");

$user_sex['title']='性别';
$user_sex['identifier']='sex';
$user_sex['choices']=array('1'=>"男",'2'=>"女");
//获取自定义搜索体重数据
if(!empty($_GET['weight'])&&$_GET['weight']!="all"&&!in_array($_GET['weight'],array_keys($user_weight['choices'])))
{
$weight_str=str_replace("-","~",$_GET['weight']);
$weight_str.="KG";
$user_weight['choices'][$_GET['weight']]=$weight_str;
}
//获取自定义搜索身高数据
if(!empty($_GET['height'])&&$_GET['height']!="all"&&!in_array($_GET['height'],array_keys($user_height['choices'])))
{
$height_str=str_replace("-","~",$_GET['height']);
$user_height['choices'][$_GET['height']]=$height_str;
}
if(!empty($_GET['age'])&&$_GET['age']!="all"&&!in_array($_GET['age'],array_keys($user_age['choices'])))
{
$age_str=str_replace("-","~",$_GET['age']);
$age_str.="岁";
$user_age['choices'][$_GET['age']]=$age_str;
}
//组合筛选数据
$quicksearchlist[]=$user_weight;
$quicksearchlist[]=$user_height;
$quicksearchlist[]=$user_age;
$quicksearchlist[]=$user_types;
$quicksearchlist[]=$user_sex;
//$quicksearchlist[0]=
//print_r($quicksearchlist);
if(count($arealist['nativeplace_son']) == 1) {
	$citysearchlist = '';
	$cityid = array_keys($arealist['nativeplace_son']);
	$cityid = $cityid[0];
} else {
	$citysearchlist = $arealist ? $arealist['nativeplace_son'] : '';
}
$page=$_GET["page"];	
$start_limit = ($page - 1) * $perpage;
$filteradd = $sortoptionurl = $space = $searchkeyword = '';
$sorturladdarray = $selectadd = $conditionlist = $saveconditionlist = $savedistrictlist = $savestreetlist =  array();
$filterfield = array( 'page','all');
$_GET['filter'] = isset($_GET['filter']) && in_array($_GET['filter'], $filterfield) ? $_GET['filter'] : 'all';

foreach ($filterfield as $v) {
	$catedisplayadd[$v] = '';
}
//处理获取到的URL参数

if($query_string = $_SERVER['QUERY_STRING']) {
	$query_string = substr($query_string, (strpos($query_string, "&") + 1));
	parse_str($query_string, $geturl);
	$geturl = daddslashes($geturl, 1);//将URL获得的参数数组话处理
	//print_r($geturl);
	if($geturl && is_array($geturl)) {
		$selectadd = $geturl;
		//print_r($filterfield);
		foreach($filterfield as $option) {
			$sfilterfield = array_merge(array('filter', 'sortid', 'searchoption'), $filterfield);
			foreach($geturl as $soption => $value) {
			
				$catedisplayadd[$option] .= !in_array($soption, $sfilterfield) ? "&amp;$soption=$value" : '';
				
			}
			//print_r($catedisplayadd);
		}
		foreach($quicksearchlist as $option) {
			$conditionlist[$option['identifier']]['choices'] = $option['choices'];
			$conditionlist[$option['identifier']]['type'] = $option['type'];
			$conditionlist[$option['identifier']]['title'] = $option['title'];
			if($option['unit']) {
				$conditionlist[$option['identifier']]['unit'] = $option['unit'];
			}
			$identifier = $option['identifier'];
			foreach($geturl as $option => $value) {
				$sorturladdarray[$identifier] .= !in_array($option, array('list','filter', 'sortid', 'searchoption', $identifier)) ?  $value."_" : 'all_';
				
			}
			$sorturladdarray[$identifier]=substr($sorturladdarray[$identifier],0,strlen($sorturladdarray[$identifier])-1);
			
		}
//print_r($geturl);


		foreach($geturl as $option => $value) {
		//echo $option;
			$sorturladdarray['nativeplace'] .= !in_array($option, array('list','filter', 'sortid', 'nativeplace')) ? $value."_": '0';
			//$sorturladdarray['nativeplace_son'] .= !in_array($option, array('filter', 'sortid', 'nativeplace_son')) ? "&amp;$option=$value" : '';
		}
		//print_r($sorturladdarray);
		$conditionlist['nativeplace']=$em_nativeplaces;
		foreach($geturl as $field => $value) {
			
			if($conditionlist[$field]) {
				$url = "/talents/";
				if($field == 'nativeplace') {
					$savecitylist['title'] = $conditionlist[$field][$value];
					$savecitylist['url'] = $url.$sorturladdarray[$field].".html";
					
					//$savecitylist['title'] = $conditionlist[$field][$value];
					//$savecitylist['url'] = $url.$sorturladdarray[$field];
				} /*elseif($field == 'nativeplace_son') {
					$savedistrictlist['title'] = $conditionlist[$field][$value];
					$savedistrictlist['url'] = $url.$sorturladdarray[$field];
				} */else {
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
$checek_weight=$user_weight['choices'][$weight];
$checek_height=$user_height['choices'][$height];
$check_age=$user_age['choices'][$age];
$check_sex=$user_sex['choices'][$sex];
$check_place=$em_nativeplaces[$nativeplace];
if($type!=="all"){
	$user_t = DB::query("SELECT name FROM ".DB::table('user_type ')."where id = $type");
	$check_user_type= DB::fetch($user_t);
}

// 初始化 搜索条件
$conditionsql="";
if(empty($_GET))
{
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member'));
}
else{
//增加类型筛选
if(!empty($_GET["user_type"])&&$_GET["user_type"] !='all')
{

$user_type=$_GET["user_type"];
$conditionsql.="u.typeid=$user_type or t.topid = $user_type and ";
}
//增加性别筛选
if(!empty($_GET["sex"])&&$_GET["sex"] !='all')
{
$sex=$_GET["sex"];
$conditionsql.="p.gender=$sex and ";
}
//增加身高条件
if(!empty($_GET["height"])&&$_GET["height"] !='all')
{
$height=$_GET["height"];
$heightval=explode("-",$height);
if($heightval[0]=="d")
{
$max_height=$heightval[1];
$conditionsql.=" p.height<=$max_height and ";
}
elseif($heightval[1]=="u")
{
$min_height=$heightval[0];
$conditionsql.=" p.height>=$min_height and ";
}
else
{
$min_height=$heightval[0];
$max_height=$heightval[1];
$conditionsql.=" p.height>$min_height and p.height <$max_height and ";
}
}
//增加体重条件
if(!empty($_GET["weight"])&&$_GET["weight"] !='all')
{
$weight=$_GET["weight"];
$weightval=explode("-",$weight);
if($weightval[0]=="d")
{
$max_weight=$weightval[1];
$conditionsql.=" p.weight<=$max_weight and ";
}
elseif($weightval[1]=="u")
{
$min_weight=$weightval[0];
$conditionsql.="  p.weight>=$min_weight and ";
}
else
{
$min_weight=$weightval[0];
$max_weight=$weightval[1];
$conditionsql.="  p.weight>$min_weight and p.weight <$max_weight and ";
}
}
//增加年龄条件
if(!empty($_GET["age"])&&$_GET["age"] !='all')
{
$age=$_GET["age"];
$ageval=explode("-",$age);
if($ageval[0]=="d")
{
$max_age=$ageval[1];
$conditionsql.=" (YEAR(CURDATE()) -p.birthyear)<=$max_age and ";
}
elseif($ageval[1]=="u")
{
$min_age=$ageval[0];
$conditionsql.="  (YEAR(CURDATE()) -p.birthyear)>=$min_age and ";
}
else
{
$min_age=$ageval[0];
$max_age=$ageval[1];
$conditionsql.=" (YEAR(CURDATE()) -p.birthyear)>$min_age and (YEAR(CURDATE()) -p.birthyear) <$max_age and ";
}
}
//地区条件
if($nativeplace>0) {
	$conditionsql .="( p.resideprovince='$em_nativeplaces[$nativeplace]' or p.residecity='$em_nativeplaces[$nativeplace]'  )  and ";
}
$conditionsql.=" m.groupid=21 and t.topid <>'66' ";//限制搜索艺人会员用户组
//echo $conditionsql;

$page = $_G['page'];
$navtitle .= $sortlist[$sortid]['name'].' - 第'.$page.'页 - '.$channel['title'];
		foreach($geturl as $soption => $value) {
			$catedisplayadd['order'] .= !in_array($soption, array('filter', 'sortid', 'orderby', 'ascdesc', 'searchoption')) ? "&amp;$soption=$value" : '';
		}

//$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on  p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid");
$sortdata['count'] = DB::num_rows(DB::query("SELECT COUNT(*)  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql and p.birthyear<1935 and p.birthyear !=0 or m.uid = 308 group by u.uid"));
//echo "SELECT COUNT(*)  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid";
}
$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
 $start_limit=$start_limit>=0?$start_limit:"0";
 $limit=" limit ".$start_limit.",".$perpage ;
//echo "$modurl?mod=list&filter=$_GET[filter]$catedisplayadd[order]$catedisplayadd[searchoption]";
	$query = DB::query("SELECT p.url,m.uid,m.username FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid   left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql and p.birthyear<1935 and p.birthyear !=0 and m.uid != 473 and m.uid != 1252 or m.uid = 308  group by u.uid  order by  length(url) asc,isavatar desc,uid asc  $limit ");

	$jobi=0;
	$mmkey = "aabb"; //模拟登录key
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"uc_".$user['uid'];

   $user['encrypt'] = passport_encrypt($user['uid'],$mmkey); //模拟登录字符串代码	

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
	$query = DB::query("SELECT  name  FROM ".DB::table('user_type')." as t left join ".DB::table('user_actor_type')."  u  on t.id=u.typeid  where u.uid=$uid ");
	$$typename='';
	$i=1;
	while($typenames= DB::fetch($query)) {
	
            $typename.=$typenames['name']."&nbsp";
			$i++;
	}
	}
	return $typename;
	//print_r($typename);
	}

include template('diy:talents/classic');

?>