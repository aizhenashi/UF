<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include 'source/module/hr/mec/district.php';
$uid=!empty($_GET['uid'])?$_GET['uid']:$_G['uid'];
//转换字符集
function setCharset($str)
{
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}

//个人信息的可见状态
$int=DB::fetch_all("select * from ".DB::table('common_member_details')." where uid=$uid");
foreach($int as $a)
{
	$det=$a;
}

//验证用户名是否重复
if($_GET['action']=='testing'){
	$nname=setCharset($_POST['nname']);
	$name=DB::fetch_first("SELECT username FROM ".DB::table('common_member')." WHERE uid!={$_G['uid']} and username='$nname'");
	if(!empty($name)){
		die('1');
	}else{
		die('2');
	}
}

//得到职业
$query = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
$user_types=array();
while($user_type = DB::fetch($query)) 
{
	$user_types[]=$user_type;
}
$member=DB::fetch_first("SELECT username,email FROM ".DB::table('common_member')." WHERE uid=$uid");
$data=DB::fetch_first("SELECT * FROM ".DB::table('common_member_profile')." WHERE uid=$uid");

//艺人职业
$typenums=DB::fetch_first("select count(uid) as num from ".DB::table('user_actor_type')." where uid=$uid");
if($typenums['num']>0)
{
	$query=DB::fetch_all("select name from ".DB::table('user_actor_type')." as a left join " .DB::table('user_type')." as b on a.typeid=b.id where a.uid=$uid");
}

if(isset($_POST['tinfo'])){
	$username=setCharset($_POST['username']);
	$realname=setCharset($_POST['trealname']);
	$province=$em_nativeplaces[$_POST['tprovince']];
	$city=$em_nativeplaces[$_POST['tcity']];
	$gender=$_POST['tgender'];
	$sexual=$_POST['tsexual'];
	$feeling=setCharset($_POST['tfeeling']);
	$birthyear=substr($_POST['tbirthday'],0,4);
	$birthmonth=substr($_POST['tbirthday'],5,2);
	$num=strlen($_POST['tbirthday']);
	if($num<=8)
	{
		$birthday=substr($_POST['tbirthday'],-1);
	}else{
		$birthday=substr($_POST['tbirthday'],-2);
	}
	$constellation=setCharset($_POST['tconstellation']);
	$zodiac=setCharset($_POST['tzodiac']);
	$blood=setCharset($_POST['tblood']);
	$height=$_POST['theight'];
	$weight=$_POST['tweight'];
	$bust=$_POST['tbust'];
	$waistline=$_POST['twaistline'];
	$hip=$_POST['thip'];
	$bodily=setCharset($_POST['tbodily']);
	$shoulder=$_POST['tshoulder'];
	$wingspan=$_POST['twingspan'];
	$leg=$_POST['tleg'];
	$shoe_size=$_POST['tshoe_size'];
	$hair=setCharset($_POST['thair']);
	$skin=setCharset($_POST['tskin']);
	$field7=setCharset($_POST['tfield7']);
	$company=setCharset($_POST['tcompany']);
	$school=setCharset($_POST['tschool']);
	$qq=$_POST['tqq'];
	$weixin=$_POST['tweixin'];
	$weibo=$_POST['tweibo'];
	$bio=setCharset($_POST['tbio']);
	$field1=setCharset($_POST['tfield1']);
	$tbirthprovince=setCharset($_POST['tbirthprovince']);
	$tbirthcity=setCharset($_POST['tbirthcity']);
	if(!empty($_POST['type']))
	{
		DB::query("DELETE FROM ".DB::table('user_actor_type')." WHERE uid=$uid");
		$type=explode(",",$_POST['type']);
		foreach($type  as $value)
		{
			DB::insert('user_actor_type ',array('uid' => $uid,'typeid' => $value));
		}
	}
	
	DB::query("UPDATE ".DB::table('common_member')." SET username='$username' WHERE uid=$uid");
	DB::query("UPDATE ".DB::table('ucenter_members')." SET username='$username' WHERE uid=$uid");
	
	if($_POST['tprovince']!='0')
	{
		DB::query("UPDATE ".DB::table('common_member_profile')." SET resideprovince='$province',residecity='$city' WHERE uid=$uid");
	}
	
	if($tbirthprovince!='0'){
		DB::query("UPDATE ".DB::table('common_member_profile')." SET birthprovince='$tbirthprovince',birthcity='$tbirthcity' WHERE uid=$uid");
	}
	
	DB::query("UPDATE ".DB::table('common_member_profile')." SET realname='$realname',
																	    gender='$gender',							
																		sexual='$sexual',	
																		feeling='$feeling',
																		birthday='$birthday',
																		birthmonth='$birthmonth',
																		birthyear='$birthyear',
																		constellation='$constellation',
																		zodiac='$zodiac',
																		bloodtype='$blood',
																		height='$height',
																		weight='$weight',
																		bust='$bust',
																		waistline='$waistline',
																		hip='$hip',
																		bodily='$bodily',
																		wingspan='$wingspan',
																		shoulder='$shoulder',
																		leg='$leg',
																		shoe_size='$shoe_size',
																		hair='$hair',
																		skin='$skin',
																		field7='$field7',
																		graduateschool='$school',
																		company='$company',
																		bio='$bio'
																		 WHERE uid=$uid");

	DB::query("UPDATE ".DB::table('common_member_details')." SET realname={$_POST['tselectRealname']},
																sexual={$_POST['tselectSexual']},
																feeling={$_POST['tselectFeeling']},
																birthday={$_POST['tselectBirthday']},
																constellation={$_POST['tselectConstellation']},
																zodiac={$_POST['tselectZod']},
																bloodtype={$_POST['tselectBlood']},
																height={$_POST['tselectHeight']},
																weight={$_POST['tselectWeight']},
																sanwei={$_POST['tselectSanwei']},
																bodily={$_POST['tselectBodily']},
																shoulder={$_POST['tselectShoulder']},
																school={$_POST['tselectSchool']},
																wingspan={$_POST['tselectWingspan']},
																leg={$_POST['tselectLeg']},
																shoe_size={$_POST['tselectShoe_size']},
																hair={$_POST['tselectHair']},
																skin={$_POST['tselectSkin']},
																nation={$_POST['tselectField7']},
																birthplace={$_POST['tselectBirplace']},
																company={$_POST['tselectCompany']}
																 WHERE uid=$uid"
											);
									 
}

include template('diy:ucenter/tinfo');

?>