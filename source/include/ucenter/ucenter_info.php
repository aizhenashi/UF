<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
                  
include 'source/module/hr/mec/district.php';
//转换字符集
function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}

$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];

//我的标签
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('common_label')." where uid=$uid");
if($typenums['num']>0){
	$mylabel=DB::fetch_all("select name,a.id from ".DB::table('label_type')." as a left join " .DB::table('common_label')." as b on a.id=b.bid where b.uid=$uid");
}
//我的自定义标签
$diylabel=DB::fetch_all("SELECT id,name,uid FROM ".DB::table('diy_label')." where uid=$uid");
//取得个人信息
$res=DB::query("select * from ".DB::table('common_member_profile')." where uid=$uid");
while($row=DB::fetch($res)){
	$data=$row;	
}
$data['bio'] = str_replace(chr(10),'<br>',$data['bio']);
//验证用户修改的昵称是否重复
if($_GET['action']=='testing'){
	$nname=setCharset($_POST['nname']);
	$name=DB::fetch_first("SELECT username FROM ".DB::table('common_member')." WHERE uid!={$_G['uid']} and username='$nname'");
	if(!empty($name)){
		die('1');
	}else{
		die('2');
	}
}
//邮箱
$em=DB::fetch_all("select email,username from ".DB::table('common_member'). " where uid=$uid");
foreach($em as $m){
	$user=$m;
	
}

//取出艺人职业信息
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('user_actor_type')." where uid=$uid");
if($typenums['num']>0){
	$query=DB::fetch_all("select name from ".DB::table('user_actor_type')." as a left join " .DB::table('user_type')." as b on a.typeid=b.id where a.uid=$uid");
}


//初始化个人信息的可见状态
$row=DB::fetch_first("select uid from ".DB::table('common_member_details')." where uid=$uid");
	if(!$row){
		
		DB::query("INSERT INTO ".DB::table('common_member_details(uid,name,height,weight,bloodtype,birthday,sanwei,nation,birthplace,residence,education,company,works,introduce,school,constellation,filestitle,hair,bodily,shoulder,wingspan,leg,shoe_size,skin,realname,sexual,feeling,zodiac,url,qq,weixin,weibo,telephone,email,broker)')." values($uid,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,1,1,1,1,1)");
	}
//个人信息的可见状态
$int=DB::fetch_all("select * from ".DB::table('common_member_details')." where uid=$uid");
	foreach($int as $a){
		$det=$a;
	}

//修改个人信息
if(isset($_POST['basicInfo'])){
	$gender=$_POST['gender'];
	//$constellation=setCharset($_POST['constellation']);
	$height=$_POST['height'];
	$weight=$_POST['weight'];
	$realname=setCharset($_POST['realname']);
	$username=setCharset($_POST['username']);
	$bust=$_POST['bust'];
	$waistline=$_POST['waistline'];
	$hip=$_POST['hip'];
	$bodily=setCharset($_POST['bodily']);
	$hair=setCharset($_POST['hair']);
	$shoulder=$_POST['shoulder'];
	$wingspan=$_POST['wingspan'];
	$leg=$_POST['leg'];
	$skin=setCharset($_POST['skin']);
	$school=setCharset($_POST['school']);
	$company=setCharset($_POST['company']);
	$province=$em_nativeplaces[$_POST['province']];
	$city=$em_nativeplaces[$_POST['city']];
	$sexual=$_POST['sexual'];
	$feeling=setCharset($_POST['feeling']);
	$field7=setCharset($_POST['field7']);
	$shoe_size=$_POST['shoe_size'];
	$constellation=setCharset($_POST['constellation']);
	$zodiac=setCharset($_POST['zodiac']);
	$bloodtype=strtoupper($_POST['bloodtype']);
	$bio=setCharset($_POST['bio']);
	//$qq=$_POST['qq'];
	$birthyear=substr($_POST['birthday'],0,4);
	$birthmonth=substr($_POST['birthday'],5,2);
	$num=strlen($_POST['birthday']);
	if($num<=8){
		$birthday=substr($_POST['birthday'],-1);
	}else{
		$birthday=substr($_POST['birthday'],-2);
	}
	
	
	$province1=setCharset($_POST['province1']);
	$city1=setCharset($_POST['city1']);
	DB::query("update ".DB::table('common_member_details')." set residence={$_POST['selectres']},constellation={$_POST['selectcon']},height={$_POST['selecthei']},bloodtype={$_POST['selectblo']},birthday={$_POST['selectbir']},realname={$_POST['selectrel']},sanwei={$_POST['selectsan']},weight={$_POST['selectwei']},company={$_POST['selectcom']},hair={$_POST['selecthai']},bodily={$_POST['selectbod']},shoulder={$_POST['selectsho']},wingspan={$_POST['selectwin']},leg={$_POST['selectleg']},shoe_size={$_POST['selectsiz']},skin={$_POST['selectski']},sexual={$_POST['selectsex']},feeling={$_POST['selectfee']},school={$_POST['selectsch']},zodiac={$_POST['selectzod']},nation={$_POST['selectfield7']},url={$_POST['selecturl']},birthplace={$_POST['selectbirplace']} where uid=$uid");
	//DB::query("update ".DB::table('common_member_profile')." set resideprovince='$province',residecity='$city',gender='$gender',constellation='$constellation',height='$height',bloodtype='$bloodtype',bio='$bio',birthyear='$birthyear',birthmonth='$birthmonth',birthday='$birthday' where uid=$uid");
	DB::query("update ".DB::table('common_member')." set username='$username' where uid=$uid");
	DB::query("update ".DB::table('ucenter_members')." set username='$username' where uid=$uid");
	DB::query("update ".DB::table('common_member_profile')." set gender='$gender',height='$height',bloodtype='$bloodtype',bio='$bio',birthyear='$birthyear',birthmonth='$birthmonth',birthday='$birthday',realname='$realname',sexual='$sexual',feeling='$feeling',skin='$skin',shoe_size='$shoe_size',leg='$leg',wingspan='$wingspan',shoulder='$shoulder',bodily='$bodily',hair='$hair',hip='$hip',waistline='$waistline',bust='$bust',company='$company',graduateschool='$school',weight='$weight',constellation='$constellation',constellation='$constellation',zodiac='$zodiac' where uid=$uid");
	if($_POST['province']!=0){
		DB::query("update ".DB::table('common_member_profile')." set resideprovince='$province',residecity='$city' where uid=$uid");	
	}
	if($province1!='0'){
		DB::query("update ".DB::table('common_member_profile')." set birthprovince='$province1',birthcity='$city1' where uid=$uid");	
	}
	
}


//会员代表作品
$works=DB::fetch_all("SELECT uid,workstitle,release_time,intro FROM ".DB::table('common_works')." WHERE uid=$uid");
$oldwork=DB::fetch_first("SELECT field1 FROM ".DB::table('common_member_profile')." where uid=$uid ");

//联系方式
if(isset($_POST['touchType'])){
	//经纪人
	$broker=setCharset($_POST['broker']);
	$tel=$_POST['tel'];
	$email=$_POST['email'];
	$qq=$_POST['qq'];
	$weibo=$_POST['weibo'];
	$weixin=$_POST['weixin'];
	DB::query("update ".DB::table('common_member_profile ')." set broker='$broker',telephone='$tel',qq='$qq',weibo='$weibo',weixin='$weixin' where uid=$uid ");
	DB::query("update ".DB::table('common_member '). " set email='$email' where uid=$uid");
    DB::query("update ".DB::table('common_member_details ')." set broker={$_POST['selectbro']},telephone={$_POST['selecttel']},email={$_POST['selectema']},qq={$_POST['selectqq']},weibo={$_POST['selectweibo']},weixin={$_POST['selectweixin']} where uid=$uid");
	
}



$guid = intval($_GET['uid']);


if(empty($guid) || $guid == $_G['uid']){
		$flag1 = 1;//自己看自己
	}else{
		$flag1 = 0;//看别人
	}
//查看用户之间的关系 
if($guid != $_G['uid']){
		 $guanzhu = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = {$_G['uid']} and followuid = $guid");//不为空，则用户关注了被查看人
		 $fensi = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = $guid and followuid = {$_G['uid']}");//不为空，则被查看人是该用户的粉丝
		 if(!empty($guanzhu)){
				if(!empty($fensi)){
						$flag2 = 1;//互相关注
				}else{
						$flag2 = 2;//当前用户是被查看人的粉丝
				}
		 }else{
				if(!empty($fensi)){
						$flag2 = 3;//被查看人是当前用户的粉丝
				}else{
						$flag2 = 4;//两人没有任何关系
				}
		 }
}
$array = $center->getRightInfo($centeruid,$flag1);

include template('diy:ucenter/info');

?>