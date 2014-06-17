<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(submitcheck('formhash') and $_G['uid']){//记录求职/合作
$inviteId=daddslashes($_POST['inviteId']);
$invitetype=daddslashes($_POST['invitetype']);
$content=isset($_POST['content'])?daddslashes(trim(iconv("utf-8","gbk", $_POST['content']))):'';
DB::query("INSERT INTO ".DB::table("user_cooperation")." SET invite_uid=".$_G['uid'].", cooperation_uid=".$inviteId.",cooperation_type=".$invitetype.",post_time=".time().",cooperation_content='".$content."', read_flag='0', agree_flag='0'");

}


include 'district.php';
$optionadd = $filterurladd = $searchsorton = '';
require_once libfile('function/hr');
require libfile('class/page');
$templatearray = $quicksearchlist = array();
$method=$_GET['method'] ?$_GET['method'] :"all";
$salary=$_GET['salary'] ?$_GET['salary'] :"all";
$type=$_GET['type'] ?$_GET['type'] :"all";
$nativeplace=$_GET['nativeplace'] ? intval($_GET['nativeplace']) : '0';
$jobkey=trim($_GET["jobkey"]);
//print_r($_GET);
//$nativeplace_top = $_GET['nativeplace_top'] ? intval($_GET['nativeplace_top']) : '';
//$nativeplace_son = $_GET['nativeplace_son'] ? intval($_GET['nativeplace_son']) : '';

$perpage=10;

// 初始化筛选条件
$job_method=$job_salary=$job_type=array('title'=>'','identifier'=>'','unit'=>'','type'=>'','choices'=>'');

//获取用户类型数据
/*$job_a = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
while($job_type= DB::fetch($job_a)) 
{
if($job_type['rank']==3)
{
	if($job_type['topid']==5||$job_type['topid']==4||$job_type['topid']==3)  //判断是否演出类型
	{
	
		$job_types['choices'][$job_type['id']]=$job_type['name'];
		$job_types['rank'][$job_type['id']]=$job_type['topid'];
		
	}
}
}
*/
//获取用户类型数据
$job_types = MemData::job_tal();
$job_types['identifier']='type';
$job_types['title']='类别';
//增加工作方式筛选数据
$job_method['title']='方式';
$job_method['identifier']='method';
$job_method['choices']=array('1'=>"项目合作",'2'=>"全职招聘");

//增加薪水筛选数据
$job_salary['title']='薪水';
$job_salary['identifier']='salary';
$job_salary['choices']=array('1000'=>"1000以下",'2000'=>"1000~2000",'4000'=>"2000~4000",'6000'=>"4000~6000",'8000'=>"6000~8000",'10000'=>"8000~10000",'15000'=>"10000~15000",'25000'=>"15000~25000",'25001'=>"25000以上");
$checek_method=$job_method['choices'][$method];
$checek_salary=$job_salary['choices'][$salary];
$check_place=$em_nativeplaces[$nativeplace];
if($type!=="all"){
	$user_t = DB::query("SELECT name FROM ".DB::table('user_type ')."where id = $type");
	$check_user_type= DB::fetch($user_t);
}

//组合筛选数据
$quicksearchlist[]=$job_types;
$quicksearchlist[]=$job_salary;
$quicksearchlist[]=$job_method;

//$quicksearchlist[0]=
//print_r($quicksearchlist);
if(count($arealist['nativeplace_son']) == 1) {
	$citysearchlist = '';
	$cityid = array_keys($arealist['nativeplace_son']);
	$cityid = $cityid[0];
} else {
	$citysearchlist = $arealist ? $arealist['nativeplace_son'] : '';
}
$districtsearchlist = $arealist && $cityid ? $arealist['district'][$cityid] : '';
$streetsearchlist = $arealist && $districtid ? $arealist['street'][$districtid] : '';
$page=$_GET["page"];
$start_limit = ($page - 1) * $perpage;

$filteradd = $sortoptionurl = $space = $searchkeyword = '';
$sorturladdarray = $selectadd = $conditionlist = $saveconditionlist = array();
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
			
				$catedisplayadd[$option] .= !in_array($soption, $sfilterfield) ? "_$value" : '';
				
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
				$sorturladdarray[$identifier] .= !in_array($option, array('filter', 'sortid', 'searchoption', $identifier)) ? $value."_" : 'all_';
			}
			$sorturladdarray[$identifier]=substr($sorturladdarray[$identifier],0,strlen($sorturladdarray[$identifier])-1);
		}


		foreach($geturl as $option => $value) {
			$sorturladdarray['nativeplace'] .= !in_array($option, array('filter', 'sortid', 'nativeplace')) ? $value."_" : '0';
			//$sorturladdarray['nativeplace_son'] .= !in_array($option, array('filter', 'sortid', 'nativeplace_son')) ? "&amp;$option=$value" : '';
		}

		$conditionlist['nativeplace']=$em_nativeplaces;
		foreach($geturl as $field => $value) {
			
			if($conditionlist[$field]) {
				$url = "/jobs/";
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
		
		//print_r($saveconditionlist);
	}
}



$conditionsql="";
if(empty($_GET))
{
$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member'));
}
else{

// 增加类别类型
if(!empty($_GET["type"])&&$_GET["type"] !='all')
{

$job_type=$_GET["type"];
$query=DB::query("select name FROM ".DB::table('user_type')." WHERE id = $job_type ");
while($value=DB::fetch($query)){
	$job_name=$value['name'];
}
$conditionsql.=" j.professor=$job_type or u.topid = $job_type or j.title like '%$job_name%' and ";
}
//增加方式选择
if(!empty($_GET["method"])&&$_GET["method"] !='all')
{
$method=$_GET["method"];
$conditionsql.=" j.method =$method or j.method = 0 and ";
}
//增加薪金搜索
if(!empty($_GET["salary"])&&$_GET["salary"] !='all')
{
$salary=$_GET["salary"];
$conditionsql.=" j.fee =$salary  and ";
}
//增加关键词搜索
if(!empty($jobkey))
{

$conditionsql.=" j.title like '%$jobkey%' and  ";
}


//地区条件
if($nativeplace > 0 ) {
	$conditionsql .="( j.province='$em_nativeplaces[$nativeplace]' or j.city='$em_nativeplaces[$nativeplace]'  ) and ";
}
$conditionsql.=" j.verify=1 ";
//echo $conditionsql;

$page = $_G['page'];
$navtitle .= $sortlist[$sortid]['name'].' - 第'.$page.'页 - '.$channel['title'];


//$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on  p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid");
$sortdata['count'] =DB::num_rows(DB::query("SELECT *   FROM  ".DB::table('hr_recruitment')."   j left join   ".DB::table('user_type')." u  on j.professor=u.id  where   $conditionsql  group by j.id "));
//echo "SELECT COUNT(*)  FROM  ".DB::table('common_member')." as m left join ".DB::table('user_actor_type')."  u  on m.uid=u.uid left join ".DB::table('common_member_profile')."  p on p.uid=m.uid left join ".DB::table('user_type')." t on u.typeid=t.id where $conditionsql  group by u.uid";
}

 $start_limit=$start_limit>=0?$start_limit:"0";
 $limit=" limit ".$start_limit.",".$perpage ;
//echo "$modurl?mod=list&filter=$_GET[filter]$catedisplayadd[order]$catedisplayadd[searchoption]";
$salary_arry=array('1000'=>'1000以下','2000'=>"1000~2000元",'4000'=>"2000~4000元",'6000'=>"4000~6000元 ","8000"=>"6000~8000元","10000"=>"8000~10000元","15000"=>"10000~15000元","25000"=>"15000~25000 元","25001"=>"25000以上","-1"=>"面议");
	$query = DB::query("SELECT j.*   FROM  ".DB::table('hr_recruitment')."   j left join   ".DB::table('user_type')." u  on j.professor=u.id  where   $conditionsql  group by j.id order by j.posttime desc $limit");
	
	$jobi=0;
	while($job= DB::fetch($query)) {
	$job['date']=date('Y-m-d',$job['posttime']);
	if(!empty($jobkey))
{
    $job['title']=str_replace($jobkey,"<span style='color:red' >".$jobkey."</span>",$job['title']);
}
$job['description']=cutstr($job['description'],240);
    $job['url']="jobs/view_".$job['id'].".html";
	$job['professor']=gettyeidname($job['professor']);
	$job['cpname']=getcpname($job['uid']);
	$job['invite']=isinvite($_G['uid'],$job['uid']);//用于判断是否被邀请。
	
    $jobs[]=$job;
	}
	$allpage=ceil($sortdata['count']/$perpage);
				if(!empty($_GET['page'])){
					$prepage=$_GET['page'];
				}else{
					$prepage=1;
				}
 $p=new page($sortdata['count'] ,$perpage);
 $multipage=$p->show(8);	
	//print_r($jobs);
//获取推荐机构,排除剧组类型
	$query = DB::query("SELECT p.url,m.uid,m.username  FROM  ".DB::table('common_member')." as m   left join ".DB::table('common_member_profile')."  p on p.uid=m.uid   where m.groupid=22  and p.isavatar=1  and p.field3!='剧组' and m.uid in(8997,8947,8993,8943,9165,9173,8995,164) order by uid desc limit 0,8 ");
	while($user= DB::fetch($query)) {
	$user['url']= !empty($user['url'])? $user['url']:"u_".$user['uid'];
	$jigou[]=$user;
	}	
	
	
	function gettyeidname($id)
	{
	if(is_numeric($id))
	{
	$typename = DB::fetch_first("SELECT  name  FROM ".DB::table('user_type')."  where id=$id ");
	}
	return $typename['name'];
	//print_r($typename);
	}
	
	function getcpname($uid)
	{
       $userinfo= getuserbyuid($uid, 1);
	return $userinfo['username'];
	//print_r($typename);
	}
	function isinvite($invite_uid,$uid) {//判断是否已经邀请 
		if(DB::fetch_first("SELECT * FROM ".DB::table('user_cooperation')." where invite_uid ='$invite_uid' and cooperation_uid='$uid'")) {
			return true;
		}else {
			return false;
		}
	}







$load = 'jobs';//用于模板加载css、js判断。

include template('diy:jobs/index');