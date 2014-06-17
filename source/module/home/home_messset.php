<?php 	

$uid = $_G['uid'];

$user_a = DB::query("SELECT * FROM ".DB::table('common_member_details')." WHERE uid = ".$uid);
	while($user = DB::fetch($user_a)){
		$arr = $user;
	}
	
if($_POST['submit']){
	$row = DB::fetch_first("select name from ".DB::table('common_member_details'). " where uid= ".$uid);
	if(!$row){
	DB::query("INSERT INTO ".DB::table('common_member_details(uid,name,telephone,sex,height,weight,bloodtype,birthday,meas,nation,birthplace,residence,education,company,works,introduce,school)')." values({$_G['uid']},{$_POST['realname']},{$_POST['telephone']},{$_POST['sex']},{$_POST['height']},{$_POST['weight']},{$_POST['bloodtype']},{$_POST['birthday']},{$_POST['meas']},{$_POST['nation']},{$_POST['birthplace']},{$_POST['residence']},{$_POST['education']},{$_POST['company']},{$_POST['works']},{$_POST['introduce']},{$_POST['school']})");
	}
	DB::query("UPDATE ".DB::table('common_member_details')." SET name='".$_G['gp_realname']."', telephone='".$_G['gp_telephone']."', sex='".$_G['gp_sex']."', bloodtype='".$_G['gp_bloodtype']."', birthday= '".$_G['gp_birthday']."', height = '".$_G['gp_height']."', weight = '".$_G['gp_weight']."' ,meas = '".$_G['gp_meas']."', nation = '".$_G['gp_nation']."', birthplace = '".$_G['gp_birthplace']."', residence = '".$_G['gp_residence']."',education = '".$_G['gp_education']."', school='".$_G['gp_school']."',company='".$_G['gp_company']."', introduce = '".$_G['gp_introduce']."',works = '".$_POST['works']."' WHERE uid=".$_G['uid']);     
	showmessage('ÉèÖÃ³É¹¦');
}
include template("home/spacecp_messset");
?>