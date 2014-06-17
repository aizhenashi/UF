<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_G['uid']) {
	showmessage('您已经登录！');
}

require libfile('function/member');
require libfile('function/mail');
require libfile('class/member');

runhooks();
if(submitcheck('formhash')) {
//print_r($_POST);
loaducenter();

$username = isset($_POST['username'])?daddslashes(trim($_POST['username'])):'';
$password = isset($_POST['password'])?daddslashes(trim($_POST['password'])):'';
$repassword = isset($_POST['repassword'])?daddslashes(trim($_POST['repassword'])):'';
$email = isset($_POST['email'])?daddslashes(trim($_POST['email'])):'';
$user_type=array_filter($_POST["user_type"]);
$type_array =isset($user_type)?$user_type:'';//获取个人职业类型
if($username<>'' and $password<>'' and $repassword==$password) {
if($_G['setting']['regverify']==1) {
	$g=8;//待验证用户组
}else{
	$g=10;//新手上路
}

		$uid = uc_user_register($username, $password, $email);
		if($uid > 0) {
			C::t('common_member')->insert($uid, $username, null, $email, 'Manual Acting', $g, null);
			$type = isset($_POST['type'])?$_POST['type']:'';
			// 插入用户类型
			if(!empty($type_array))
			{
					foreach($type_array  as $value)
					{
						DB::insert('user_actor_type ',array(
						'uid' => $uid,
						'typeid' => $value
					));//插入用户类型表
				}
			}
			if($type=='p') {//个人
				$profession = isset($_POST['profession'])?daddslashes(trim($_POST['profession'])):'';//获取提交的数据
				DB::insert('user_actor',array(
				'uid' => $uid,
				'username' => $username,
				'profession' =>$profession,
				'email' =>$email
				));//插入表
			}else{//机构
				$cpname = isset($_POST['cpname'])?daddslashes(trim($_POST['cpname'])):'';//获取提交的数据
				$cptype = isset($_POST['cptype'])?daddslashes(trim($_POST['cptype'])):'';//获取提交的数据
				$cpuser = isset($_POST['cpuser'])?daddslashes(trim($_POST['cpuser'])):'';//获取提交的数据
				$cptell = isset($_POST['cptell'])?daddslashes(trim($_POST['cptell'])):'';//获取提交的数据
				$cpusertell = isset($_POST['cpusertell'])?daddslashes(trim($_POST['cpusertell'])):'';//获取提交的数据
				$nativeplace = isset($_POST['nativeplace'])?daddslashes(trim($_POST['nativeplace'])):'';//获取提交的数据
				$nativeplace_top = isset($_POST['nativeplace_top'])?daddslashes(trim($_POST['nativeplace_top'])):'';//获取提交的数据
				$nativeplace_son = isset($_POST['nativeplace_son'])?daddslashes(trim($_POST['nativeplace_son'])):'';//获取提交的数据

				DB::insert('user_company',array(
				'uid' => $uid,
				'cpname' => $cpname,
				'cptype' => $cptype,
				'cpuser' => $cpuser,
				'email' =>$email,
				'cptell' => $cptell,
				'cpusertell' => $cpusertell,
				'nativeplace' => $nativeplace,
				'nativeplace_top' => $nativeplace_top,
				'nativeplace_son' => $nativeplace_son
				));//插入表
			}
		}else{
			if($uid == -1) {
						showmessage('profile_username_illegal');
					} elseif($uid == -2) {
						showmessage('profile_username_protect');
					} elseif($uid == -3) {
						showmessage('profile_username_duplicate');
					} elseif($uid == -4) {
						showmessage('profile_email_illegal');
					} elseif($uid == -5) {
						showmessage('profile_email_domain_illegal');
					} elseif($uid == -6) {
						showmessage('profile_email_duplicate');
					} else {
						showmessage('undefined_action');
					}
		}
		$member = getuserbyuid($_GET['uid']);
				setloginstatus(array(
				'uid' => $uid,
				'username' => $member['username'],
				'password' => $member['password'],
				'groupid' => $member['groupid'],
			), 0);
			include_once libfile('function/stat');
			updatestat('register');

				if($_G['setting']['regverify']==1) {//开启邮箱验证
					$idstring = random(6);
					$authstr = $_G['setting']['regverify'] == 1 ? "$_G[timestamp]\t2\t$idstring" : '';
					$authstr = 1 ? "$_G[timestamp]\t2\t$idstring" : '';
					C::t('common_member_field_forum')->update($uid, array('authstr' => $authstr));
					$verifyurl = "{$_G[siteurl]}job.php?mod=activate&amp;uid={$uid}&amp;id=$idstring";
					$email_verify_message = lang('email', 'email_verify_message', array(
						'username' => $username,
						'bbname' => $_G['setting']['bbname'],
						'siteurl' => $_G['siteurl'],
						'url' => $verifyurl
					));
					if(!sendmail("$username <$email>", lang('email', 'email_verify_subject'), $email_verify_message)) {
						runlog('sendmail', "$email sendmail failed.");
					}
				$email_type = explode('@', $email);
				$email_type =$email_type[1];
				switch($email_type)
				{
				case "qq.com":
				$email_url="http://mail.qq.com";
				break; 
				case "163.com":
				$email_url="http://mail.163.com";
				break;
				case "126.com":
				$email_url="http://mail.126.com";
				break;
				case "139.com":
				$email_url="http://mail.139.com";
				break; 
				case "sina.com":
				$email_url="http://mail.sina.com";
				break; 
				case "yahoo.com":
				$email_url="http://mail.sohu.com";
				break; 
				case "gmail.com":
				$email_url="http://ww.gmail.com";
				break;
				case "tom.com":
				$email_url="http://mail.tom.com";
				break; 
				case "hotmail.com":
				$email_url="http://mail.hotmail.com";
				break; 
				default:
				$email_url="";
				
				}
				include template('diy:hr/reg_emali_activate');//邮箱验证
				exit();
				}else{//未开启邮箱验证
					include template('diy:hr/reg_success');//未开验证,直接成功。
				exit();
				}
}else{
showmessage('注册失败，请重试。', 'job.php?mod=reg');
}
}
$type = (isset($_GET['type'])?$_GET['type']:'p')=='p'?'p':'q';

//获取演出分类
$query = DB::query("SELECT * FROM ".DB::table('user_type '));

$user_types=array();
//获取所有分类集合
while($user_type = DB::fetch($query)) 
{
		$user_types[]=$user_type;
}
//print_r($user_types);

include template('hr/reg');
