<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$usergroupid = $_G['groupid'];
//根据后台设置判断游客是否可以发信息
$id=DB::query("SELECT id,name FROM ".DB::table('user_type'));
while($t=DB::fetch($id)){
	$u_types[$t['name']]=$t['id'];
}
if(empty($_G['uid'])) {
	showmessage('not_loggedin', 'login.html');
}

if($usergroupid != 22){
	showmessage(lang('hr/template', 'job_usergroup_nopur'),'home.php?mod=ucenter&do=index');
}

 if($usergroupid == 22){
$verify=DB::fetch_first("SELECT verify1 FROM ".DB::table('common_member_verify')."  WHERE uid=".$_G['uid']);

//判断企业是否认证
/*if($verify['verify1']==0)
{
showmessage("您没有认证，不能发布职位",'home.php');
}
*/
} 



//支持DZ中系统用户组中禁止发言/禁止访问/禁止IP
require_once libfile('function/home');
$space = getspace($_G['uid']);
if($space['status'] == -1 || in_array($space['groupid'], array(4, 5, 6))) {
    showmessage(lang('hr/template', 'job_usergroup_nopur')); 
}
//增加DZ的防灌水验证
cknewuser();



require_once libfile('function/hr');
$page = empty($_GET['page'])?1:intval($_GET['page']);
$sortarray = $cityarray = $districtarray = $streetarray = array();
$cityid = intval($_GET['cityid']);
$districtid = intval($_GET['districtid']);
$streetid = intval($_GET['streetid']);
$action=$_GET['action'];
$avatar = hr_uc_avatar($_G['uid']);
$usergrouplist[$usergroupid]['icon'] = $usergrouplist[$usergroupid]['icon'] ? $_G['setting']['attachurl'].'common/'.$usergrouplist[$usergroupid]['icon'] : '';
$usergrouplist[$usergroupid]['postdayper'] = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper']) : '';
$perpage = 10;
$start = ($page-1)*$perpage;


if($do == 'fabuzp'){
	$job_hr = DB::fetch_first("SELECT email,telephone,linkman FROM ".DB::table('hr_recruitment')." WHERE uid=".$_G['uid']);
	$reg_hr = DB::fetch_first("SELECT m.email,m.username,p.telephone FROM ".DB::table('common_member') ." as m left join ".DB::table('common_member_profile')." p on m.uid = p.uid where m.uid =".$_G['uid']);
	$type = $_G['gp_type'];

	if($type == "edit"){
		include_once libfile('function/profile');
		//$company = DB::fetch_first("SELECT cpuser, email, cptell, cpusertell FROM ".DB::table('common_member_profile')." WHERE uid=".$_G['uid']);
		$job_detail = DB::fetch_first("SELECT * FROM ".DB::table('hr_recruitment')." WHERE id=".$_G["gp_jobid"]);
		//print_r($job_detail);
		
		if (submitcheck('profilesubmitbtn')){
			if ( count($company)){
				//DB::query("UPDATE ".DB::table('common_member_profile')." SET cpuser='".$_G['gp_contact']."', email='".$_G['gp_email']."', cptell='".$_G['gp_tel']."'");
			}
			DB::query("UPDATE ".DB::table('hr_recruitment')." SET title='".dhtmlspecialchars(trim($_G['gp_title']))."', number='".$_G['gp_number']."', sex='".$_G['gp_sex']."', endtime=".($_G['gp_endtime'] ? strtotime($_G['gp_endtime']) : '0').", professor='".$_G['gp_sprofessor']."', province='".$_G['gp_birthprovince']."', city='".$_G['gp_birthcity']."', dist='".$_G['gp_birthdistrict']."', community='".$_G['gp_birthcommunity']."', fee='".$_G['gp_fee']."', description='".dhtmlspecialchars(trim($_G['gp_description']))."' ,telephone='".$_G['gp_telephone']."', email='".$_G['gp_email']."', linkman='".$_G['gp_linkman']."' WHERE id=".$_G["gp_jobid"]);

			showmessage('编辑成功', 'home.php?mod=ucenter&do=task');
		}else{
			$action_url = "home.php?mod=ucenter&do=fabuzp&type=edit";

			$elems = array('birthprovince', 'birthcity', 'birthdist', 'birthcommunity');

			$birthcityhtml = showdistrict(array(getDisIdFromValue($job_detail['province']),getDisIdFromValue($job_detail['city']),getDisIdFromValue($job_detail['dist']),getDisIdFromValue($job_detail['community'])), $elems, 'birthcitybox');

			$query = DB::query("SELECT id, name FROM ".DB::table('user_type')." WHERE topid=1");
			$userType_array = array();
			while($row = DB::fetch($query)){
				$p_id = $row['id'];
				$c_query = DB::query("SELECT name FROM ".DB::table('user_type')." WHERE topid=".$p_id);
				$cType_array = array();
				while ( $c_row = DB::fetch($c_query)){
					$cType_array[] = $c_row['name'];
				}
				$row['c_name'] = $cType_array;
				$userType_array[] = $row;
			}
			if($job_detail['professor']){
		
				$topId = DB::fetch_first("SELECT topid FROM ".DB::table('user_type')." WHERE id='".$job_detail['professor']."'");
				//print_r($topId);
				$profess_array = DB::fetch_first("SELECT name,id FROM ".DB::table('user_type')." WHERE id=".$topId['topid']);
				$cprofessor_array = array();
				$cprofessor_query = DB::query("SELECT name,id FROM ".DB::table('user_type')." WHERE topid=".$topId['topid']);
				while($cprofessor_row = DB::fetch($cprofessor_query)){
					$cprofessor_array[] = $cprofessor_row;
				}
			}
			if($job_detail['endtime']){
				$endtime = date("Y-m-d H:i", $job_detail['endtime']);
			}
			//print_r($cprofessor_array);
			include template('diy:org/fabuzp');
		}
	}elseif($type == "del"){
	
			DB::query("DELETE FROM ".DB::table('hr_recruitment')." WHERE id=".$_G["gp_jobid"]);
			showmessage('删除成功', 'home.php?mod=ucenter&do=task');

	}else{
		//发布招聘
		//进行验证，不能为空
		if(!empty($_POST)){
			//$arr['formhash'] = $_POST['formhash'];
			//$arr['jobid'] = $_POST['jobid'];
			$arr['title']=$_POST['title'];
			//$arr['number'] = $_POST['number'];
			$arr['fee'] = $_POST['fee'];
			$arr['method'] = $_POST['method'];
			$arr['sex'] = $_POST['sex'];
			$arr['birthprovince'] = $_POST['birthprovince'];
			$arr['professor'] = $_POST['professor'];
			//$arr['sprofessor'] = $_POST['sprofessor'];
			$arr['description'] = $_POST['description'];
			$arr['linkman'] = $_POST['linkman'];
			$arr['telephone'] = $_POST['telephone'];
			$arr['email'] = $_POST['email'];
			//$arr['endtime'] = $_POST['endtime'];
			//$arr['profilesubmit'] = $_POST['profilesubmit'];
			//$arr['forfilesubmitbtn'] = $_POST['forfilesubmitbtn'];
			if(in_array("",$arr)){
					//var_dump($_POST);
					echo '<script type="text/javascript">alert("请您填写您的详细信息");window.location.href="home.php?mod=ucenter&do=fabuzp";</script>';
					exit();
			}
		}
		
		include_once libfile('function/profile');
		//$company = DB::fetch_first("SELECT cpuser, email, cptell, cpusertell FROM ".DB::table('user_company')." WHERE uid=".$_G['uid']);
		if (submitcheck('profilesubmitbtn')){
			if ( count($company)){
			//	DB::query("UPDATE ".DB::table('user_company')." SET cpuser='".$_G['gp_contact']."', email='".$_G['gp_email']."', cptell='".$_G['gp_tel']."'");
			}
			if($_G['gp_sprofessor']=='0'){
				$_G['gp_professor']=$_G['gp_professor'];
			}else{
				$_G['gp_professor']=$_G['gp_sprofessor'];
			}
			$_G['gp_professor']=$u_types[$_G['gp_professor']];
			DB::query("INSERT INTO ".DB::table('hr_recruitment')." (uid, title, number, method,sex, starttime, endtime, professor, grade, province, city, dist, community, fee, description, type, status, posttime,verify,telephone,email,linkman ) VALUES (".$_G['uid'].", '".$_G["gp_title"]."', '".$_G['gp_number']."', '".$_G["gp_method"]."','".$_G['gp_sex']."' , '".time()."', '".($_G['gp_endtime'] ? strtotime($_G['gp_endtime']) : '0')."', '".$_G['gp_professor']."', '0', '".$_G['gp_birthprovince']."', '".$_G['gp_birthcity']."', '".$_G['gp_birthdistrict']."', '".$_G['gp_birthcommunity']."', '".$_G['gp_fee']."', '".dhtmlspecialchars(trim($_G['gp_description']))."', type, status, ".time()." , '0 '  ,'".$_G['gp_telephone']."', '".$_G['gp_email']."', '".$_G['gp_linkman']."')");

			showmessage('发布成功,请等待管理员审核', 'home.php?mod=ucenter&do=task');
		}else{
			$action_url = "home.php?mod=ucenter&do=fabuzp";
			/*初始地区数据*/
			$birthcityhtml = showdistrict(array(0,0), array('birthprovince', 'birthcity'), 'birthcitybox');
			$query = DB::query("SELECT id, name FROM ".DB::table('user_type')." WHERE topid=1 and id != 66");
			$userType_array = array();
			while($row = DB::fetch($query)){
				$p_id = $row['id'];
				$c_query = DB::query("SELECT name,id FROM ".DB::table('user_type')." WHERE topid=".$p_id);
				$cType_array = array();
				while ( $c_row = DB::fetch($c_query)){
					$cType_array[$c_row['id']] = $c_row['name'];
				}
				$row['c_name'] = $cType_array;
				$userType_array[] = $row;
			}
			//print_r($userType_array);
		}
		//var_dump($action_url);
		include template('diy:org/fabuzp');
	}
	exit;
}

function getDisIdFromValue($value){
	if($value){
		$dis_array = DB::fetch_first("SELECT id FROM ".DB::table("common_district")." WHERE name='".$value."'");
		return $dis_array['id'];
	}else{
		return 0;
	}
}


?>