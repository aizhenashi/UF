<?php

/**
 *   ���˿ռ�  ģ�� ҳ ��action �ַ� 
 *   
 *   1.�ݴ�
 *   û��uid �� �ж��Ƿ��¼
 *   ʧ�� �� showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
 *   ��uid �� �ж� ��Ա�� ��uid �Ƿ����
 *   ʧ�� �� showmessage('space_does_not_exist');
 *   
 *   2.$centeruid ��ֵ
 *   ��uid ��Get uid ��ֵ������$centeruid
 *   ��uid ��$_G['uid'] ��ֵ������$centeruid
 *
 *	  3.�жϴ���$_G['uid']ʱ����ӷ��ʼ�¼
 *   
 *   4.ͨ��$centeruid ȡ������������
 *   ��$centeruid ������Ϣȡ�� �� function
 *    
 */

//�����ҳ����ת
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



require libfile('class/ucenter');

$dos = array(
	'index',
	'info',
	'album', 
	'camer',		//�ռ���Ƶչʾ
	'myaddVideo',		//�ռ���Ƶչʾ�����Ƶ
	'insetmyVideo',		//�����Ƶ�����ݿ�
	'attention',		//��עҳ��
	'funs',				//	��˿ҳ��
	'visitor',		//�ÿͼ�¼
	'shuoshuo',
	'task',			//���������¼
	'fabuzp',		//�������޸Ĺ�������
	'manager',
	'uploadpic',
	'setbkpic',
	'uploadpic_picbk',
	'uploadpic_picbk2',
	'tinfo', //����
	'label', //���˱�ǩ
	'representative', //���˴�����Ʒ
	'relation', //������ϵ��ʽ
	'authentication', //������֤��Ϣ
	'url', //���˸�������
	'privacy',//������˽����
	'accounts',//�����ʺŰ�ȫ
	'email',    //
	'orgtinfo', //�����ʺ�����
	'orglabel',
	'orgauthentication',//������֤��Ϣ
	'orgurl', //������������
	'orgrelation',//������ϵ��ʽ
	'orgblacklist',//����������
	'orgmodpass', //�����޸�����
	'addvideoForVideobk', //��Ƶ��� �����Ƶ html 
	'insvideoForVideobk', //��Ƶ��� �����Ƶ �߼�
	'editorbktexthtml', //�༭�ı�������� html
	'editorbktext', 		//�༭�ı��������
	'invite',		//���������¼
	'mymessage',	//������Ϣ��¼
	'album', //������
	'albumphotos', //�������������Ƭ
	'albumphoto', //������Ƭ����ϸҳ
	'changephoto',		//�޸�ͷ��
	'shuoshuolist',		//˵˵�б�
	'addvoice',        //�����Ƶ��飨html��
	'pushvoice',       //�����Ƶ��� (�߼�)
);


$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

$guid = intval($_GET['uid']);

//var_dump($guid)��
if($do === NULL){
	die('action error');
	//showmessage('action error');
}

//uid
	//���˿ռ�Ȩ�ްѿ�
		//uid �ж�uid �������
		//������ error û�иû�Ա
		//û��uid ���˿ռ�Ȩ�ްѿ�
		//��¼û��½
		//û��½������½ҳ��
//var_dump(empty($guid));
//var_dump($_G['uid']);

if(empty($guid)){//û��$_GET['uid']��ʱ��
		if($_G['uid']){
				$centeruid = intval($_G['uid']);//û��$_GET['uid'],��$_G['uid'],�ж�Ϊ�Լ����Լ�
				$data = DB::fetch_first("select username,groupid from ".DB::table("common_member")." where uid = {$centeruid}");

				$user_info['username'] = $data['username'];
		}else{
				showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
				header("Location:/login.html");				
		}
}else{//��$_GET['uid']��ʱ��

		//�����Ƿ��¼�����ɲ鿴ע���Ա���жϱ��鿴���Ƿ����
		$data = DB::fetch_first("select username,groupid from ".DB::table("common_member")." where uid = $guid");

		if(!$data){
		 	showmessage(lang('hr/template', '�����˲����ڻ���ɾ��'));
		}else{
			$user_info['username'] = $data['username'];
			$centeruid = $guid;
		}
		
}

//�鿴�û���ϵ
/*
if($guid != $_G['uid']){
		 $guanzhu = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = {$_G['uid']} and followuid = $guid");//��Ϊ�գ����û���ע�˱��鿴��
		 $fensi = DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = $guid and followuid = {$_G['uid']}");//��Ϊ�գ��򱻲鿴���Ǹ��û��ķ�˿
		 if(!empty($guanzhu)){
				if(!empty($fensi)){
						$flag2 = 1;//�����ע
				}else{
						$flag2 = 2;//��ǰ�û��Ǳ��鿴�˵ķ�˿
				}
		 }else{
				if(!empty($fensi)){
						$flag2 = 3;//���鿴���ǵ�ǰ�û��ķ�˿
				}else{
						$flag2 = 4;//����û���κι�ϵ
				}
		 }
}
*/

//2.��ȡ��Աgroupid
$group = $data['groupid'];//��Ա��� 21���˻�Ա 22������Ա

//��ӷ��ʼ�¼
//3.�жϴ���$_G['uid']ʱ����ӷ��ʼ�¼


//$user_info = $center->get_user_info($group,$centeruid);


$uid = intval($_G['uid']);
if(!empty($_G['uid']) && $_G['uid'] != $centeruid){//�ڵ�¼״̬���Ҳ����Լ����Լ���״̬�£���ӷ��ʼ�¼
		$id = DB::fetch_first("select id from ".DB::table("home_visitor")." where uid =$centeruid  and vuid = $uid");
		if(empty($id)){
				$time = time();
				$query = DB::query("insert into ".DB::table("home_visitor")."(id,uid,vuid,dateline) values(null,$centeruid,$uid,$time)");
		}else{
				$lasttime = DB::fetch_first("select id,dateline from ".DB::table("home_visitor")." where uid = $centeruid and vuid = $uid order by dateline desc");
				$t_last = getdate($lasttime['dateline']);
				$time = time();
				$t_now = getdate($time);				
				if($t_now['mday'] > $t_last['mday']){
						$query = DB::query("insert into ".DB::table("home_visitor")."(id,uid,vuid,dateline) values(null,$centeruid,$uid,$time)");
				}else{
						$query = DB::query("update ".DB::table("home_visitor")." set dateline = $time where id = {$lasttime['id']}");
				}
		}
}

//��ȡ�û�������Ϣ
//��ȡ�û���Ϣ

$user_info = $center->get_user_info($group,$centeruid);

if(mb_strlen($data['username'],'GB2312')>16){
					$user_info['username'] = mb_substr($data['username'],0,16,'GB2312')."..";
}else{
					$user_info['username'] = $data['username'];
}

$flag = C::t('home_follow')->fetch_status_by_uid_followuid($_G['uid'],$centeruid);//�ж��Ƿ��Ѿ��ӹ�ע


//���������˱�ʶ

//4. �������˱�ʶ


	if(empty($centeruid) || $centeruid == $_G['uid']){
		$flag1 = 1;//�Լ����Լ�
	}else{
		$flag1 = 0;//������
	}
	if($guid){
		$age_year = DB::fetch_first("select birthyear from ".DB::table("common_member_profile")." where uid = $guid");
	}
	//var_dump($age_year["birthyear"]);
	/**
	 *  �ǳƱ�ʶ
	 */
	if($centeruid == $_G['uid']){
		$nickstring = '��';
	}else{				
		if($user_info['xingbie'] == '2'){
			$nickstring = '��';
		}else{
			$nickstring = '��';			
		}
	}
	$count['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_space_liuyan')." where spaceuid = '{$_G['uid']}' and state = 0");
//���� �˲���� ������ͬ���
if($group == 22){
	require_once libfile('org/'.$do,'include');//organization ��֯������
}else{
	require_once libfile('ucenter/'.$do, 'include');//�����û����
}
?>