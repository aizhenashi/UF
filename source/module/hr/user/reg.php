<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
// �жϵ�½��Ա
if($_G['uid']) {
	showmessage('���Ѿ���¼��','home.php?mod=ucenter&do=index');
}

require libfile('function/member');
require libfile('function/mail');
require libfile('class/member');
require_once libfile('function/seccode');
runhooks();
//var_dump($_GET);
$types=$_GET['type'];

if(submitcheck('formhash')) {

	//if(!check_seccode($_POST['seccodeverify'], $_POST['idhash'])){
	//	showmessage('��֤�����');
	//}
	loaducenter();
	$username	= isset($_G['gp_username']) ? daddslashes(trim($_G['gp_username'])) : '';
	$password	= isset($_G['gp_password']) ? daddslashes(trim($_G['gp_password'])) : '';
	$repassword = isset($_G['gp_repassword']) ? daddslashes(trim($_G['gp_repassword'])) : '';
	$email		= isset($_G['gp_email']) ? daddslashes(trim($_G['gp_email'])) : '';
	$fwtk		= isset($_G['gp_fwtk']) ? daddslashes(trim($_G['gp_fwtk'])) : '';
	$gender     = $_POST['gender']; 
	$user_type	= array_filter($_G["gp_user_type"]);
	$type_array = isset($user_type) ? $user_type : '';//��ȡ����ְҵ����
	if( $username != '' && $password != '' && $repassword == $password && $fwtk == "1") {
		if(strlen($username) < 3 || strlen($username) > 60 && !preg_match('/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u',$username)){
			showmessage('profile_username_error');
		}
		if (strlen($password) < 6 || strlen($password) > 30){
			showmessage('profile_password_length');
		}
		
		$uid = uc_user_register($username, $password, $email);//UCע��

		if( $uid > 0) {
			
			
			$type = isset($_POST['type'])?$_POST['type']:'';
			// �����û�����
			if( $type == 'p' ) {
			if(empty($_POST['gender'])){
				DB::query("DELETE FROM ".DB::table('ucenter_members')." where uid=$uid");
				showmessage('ע��ʧ�ܣ���ѡ���Ա�');
				exit;
			}
			C::t('common_member')->insert($uid, $username, null, $email, 'Manual Acting', '21', null);
			//������Ա�ʺ��Զ��ӹ�ע
			$time=time();
			DB::insert('home_follow',array('uid'=>'8925','username'=>'��������С����','followuid'=>$uid,'fusername'=>$username,'bkname'=>'','status'=>'0','mutual'=>'1','dateline'=>$time));
			DB::insert('home_follow',array('uid'=>$uid,'username'=>$username,'followuid'=>'8925','fusername'=>'��������С����','bkname'=>'','status'=>'0','mutual'=>'1','dateline'=>$time));
			
			if(empty($type_array))
			{
				showmessage('ע��ʧ�ܣ���ѡ��ְҵ����');
			}
			if ( count($type_array) > 5){
					showmessage('profile_professor_type');
			}
			if(!empty($type_array))
			{
					
				foreach($type_array  as $value)
				{
					DB::insert('user_actor_type ',array(
														'uid' => $uid,
														'typeid' => $value
													));//�����û����ͱ�
				}
			}
				C::t('common_member_profile')->update($uid, array(
															'type'=>'0'
															));
				//����
				$profession = isset($_G['gp_profession']) ? daddslashes(trim($_G['gp_profession'])) : '';//��ȡ�ύ������
				
				DB::query('update '.DB::table('common_member_profile')." set gender='$gender' where uid=$uid");
				}else{
				C::t('common_member')->insert($uid, $username, null, $email, 'Manual Acting', 22, null);
				//������Ա�ʺ��Զ��ӹ�ע
				$time=time();
				DB::insert('home_follow',array('uid'=>'8925','username'=>'��������С����','followuid'=>$uid,'fusername'=>$username,'bkname'=>'','status'=>'0','mutual'=>'1','dateline'=>$time));
				DB::insert('home_follow',array('uid'=>$uid,'username'=>$username,'followuid'=>'8925','fusername'=>'��������С����','bkname'=>'','status'=>'0','mutual'=>'1','dateline'=>$time));
			
				//����
				//$cpname = isset($_G['gp_cpname']) ? daddslashes(trim($_G['gp_cpname'])) : '';//��ȡ�ύ������
				$cptype = isset($_G['gp_cptype']) ? daddslashes(trim($_G['gp_cptype'])) : '';//��ȡ�ύ������
				$cpuser = isset($_G['gp_cpuser']) ? daddslashes(trim($_G['gp_cpuser'])) : '';//��ȡ�ύ������
				$cptell = isset($_G['gp_cptell']) ? daddslashes(trim($_G['gp_cptell'])) : '';//��ȡ�ύ������
				$cpusertell = isset($_G['gp_cpusertell']) ? daddslashes(trim($_G['gp_cpusertell'])) : '';//��ȡ�ύ������
				$nativeplace = isset($_G['gp_nativeplace']) ? daddslashes(trim($_G['gp_nativeplace'])) : '';//��ȡ�ύ������
				$nativeplace_top = isset($_G['gp_nativeplace_top']) ? daddslashes(trim($_G['gp_nativeplace_top'])) : '';//��ȡ�ύ������
				$nativeplace_son = isset($_G['gp_nativeplace_son']) ? daddslashes(trim($_G['gp_nativeplace_son'])) : '';//��ȡ�ύ������
				if($cpusertell && !preg_match('/^[0-9]{11}$/u',$cpusertell)){
					showmessage('��ϵ���ֻ��������');
				}


				include 'source/module/hr/hr/district.php';
				C::t('common_member_profile')->update($uid, array(
															'resideprovince'=>$em_nativeplaces[$nativeplace_top],
															'residecity'=>$em_nativeplaces[$nativeplace_son],
															'field3'=>$cptype,
															'telephone'=>$cptell,
															'mobile'=>$cpusertell
															));//�����û�����Ϊ������������������ϡ�
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
		$member = getuserbyuid($uid);
		setloginstatus($member,0);
		include_once libfile('function/stat');
		updatestat('register');

		if($_G['setting']['regverify']==1) {//����������֤
			$idstring = random(6);
			$authstr = $_G['setting']['regverify'] == 1 ? "$_G[timestamp]\t2\t$idstring" : '';
			$authstr = 1 ? "$_G[timestamp]\t2\t$idstring" : '';
			C::t('common_member_field_forum')->update($uid, array('authstr' => $authstr));
			$verifyurl = "{$_G[siteurl]}user.php?mod=activate&amp;uid={$uid}&amp;id=$idstring";
			$email_verify_message = lang('email', 'email_verify_message', array(
				'username' => $username,
				'bbname' => $_G['setting']['bbname'],
				'siteurl' => $_G['siteurl'],
				'url' => $verifyurl
			));
			//ע���ʼ�����
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
					$email_url="http://www.gmail.com";
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
				include template('diy:user/reg_emali_activate');//������֤
				exit();
				}else{//δ����������֤
					include template('diy:user/reg_success');//δ����֤,ֱ�ӳɹ���
				exit();
			}
	}else{
		showmessage('ע��ʧ�ܣ������ԡ�', 'user.php?mod=reg');
	}
}else{

//$type = (isset($_GET['type'])?$_GET['type']:'p')=='p'?'p':'q';
//��ȡ�ݳ�����
$query = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
$user_types=array();
//��ȡ���з��༯��
while($user_type = DB::fetch($query)) 
{
	$user_types[]=$user_type;
}
//print_r($user_types);
//��ȡ��Ա����
$member['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member'));
$member['count'] =$member['count'] + 20000;
//��ȡ������Ϣ
$query = DB::query("SELECT * FROM ".DB::table('user_cooperation')." where agree_flag=1 order by id desc limit 0,20");
while($cooperation = DB::fetch($query)) 
{   
    $cooperation['invite_name']=getuserbyuid($cooperation['invite_uid']);
	 $cooperation['cooperation_name']=getuserbyuid($cooperation['cooperation_uid']);
	$cooperations[]=$cooperation;
}
//print_r($cooperations);
include template('user/reg');


}
