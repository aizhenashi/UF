<?php

/**
 *   ˵˵ajax�ύģ�� ҳ ��action �ַ� 
 *
 */

//�����ҳ����ת
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'getshuoshuo', //��ȡ˵˵�б�
	'sendshuoshuo', //����˵˵
	'getALLpinglun', //��ȡ����
	'HuifuShuoShuo', //�ظ�˵˵
	'HuifuShuoShuoAndRenhtml', //�ظ�˵˵���һظ�ĳ�� html
	'HuifuShuoShuoAndRen', //�ظ�˵˵���һظ�ĳ�� �߼�
	'zan', //��
	'sendspaceliuyan', //��������
	'HuifuLiuyanAndRen', //�ظ����Բ��һظ��� �߼�
	'HuifuLiuyanAndRenhtml', //�ظ����Բ��һظ��� html 
	'playVideo', //������Ƶ
	'delshuoshuo' //ɾ���ҵ�˵˵
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	die('action error');
}

class shuoshuoMoudle{

	/**
	 * ajaxȡ˵˵
	 */
	public function getshuoshuo(){
		
		$centeruid = $_GET['centeruid'];
		global $_G;
		if($_GET['type'] == 'all' || $_GET['type'] == 'guanzhu'){
			$uidarr = c::t('home_follow')->getdata('`followuid`',"uid = '{$centeruid}'");
			if($_GET['type'] == 'all'){				
				$uidarr[] = array('followuid'=>$centeruid);
			}

			if($uidarr){

				$where = "fid = '0'";
				//��ȡ uid����
				$uidstr = "";
				foreach ($uidarr as $uid){
					$uidstr .= $uid['followuid'].',';
				}
				$uidstr = rtrim($uidstr,',');
				$where .= " && uid in ({$uidstr})";
			}else{
				$where = 'false';
			}
		}else if($_GET['type'] == 'my'){
			$where = "uid = '{$centeruid}' && fid = '0'";
		}
		//��ȡ˵˵
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',$where);

		include template('diy:ucenter/shuoshuotem');

	}
	
	/*
	 * ɾ��˵˵
	 * */
	public function delshuoshuo(){
		
		global $_G;
		
		DB::query("DELETE FROM ".DB::table('home_shuoshuo')." where id={$_POST['shuoid']}");
	
		include template('diy:ucenter/ajaxshuoshuotem');
		
	}
	
	/**
	 * ����˵˵
	 */
	public function sendshuoshuo(){
		
		global $_G;
		//���
	
		$id = C::t('home_shuoshuo')->insert_shuoshuo($_POST);
		
        	    //ͬ�����͵�΢��
        	     include_once( 'config.php' );//΢���İ����ļ�
			include_once( 'saetv2.ex.class.php' );//΢���İ����ļ�
			$content = $_POST['content'];
			//pre_weibokey�ܳ׵ı�
	              $sql = "select * from pre_weibokey where uid=".$_G['uid'];
			
			$weibokey = DB::fetch_all($sql);
			if($weibokey)
			{	
				$c1 = new SaeTClientV2( WB_AKEY , WB_SKEY , $weibokey[0]["weibokey"] );//ʵ���������ļ�����
				
				$c1 ->update($content);//���������update����
			}
		
		$AllShuoshuo = c::t('home_shuoshuo')->getShuoshuo('`id`,`uid`,`content`,`time`',"id  = '{$id}'");
		
		include template('diy:ucenter/ajaxshuoshuotem');
	}
	

	/**
	 *  ajax ��ȡ���ۿ� ����������
	 * Enter description here ...
	 */
	public function getALLpinglun(){
		global $_G;
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();

		$where = "`fid` = '{$_POST['shuoid']}'" ;
		$Allpinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fuid`,`fid`,`uid`,`content`,`time`',$where);

		include template('diy:ucenter/shuoshuopinglun');
	}
	
	/**
	 * ajax �ظ�˵˵
	 */
	public function HuifuShuoShuo(){

		global $_G;
		$id = c::t('home_shuoshuo')->insert_shuoshuo($_POST);
		$pinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fid`,`uid`,`content`,`time`',"id = '{$id}'");
		$pinglun = $pinglun[0];

		include template('diy:ucenter/onepinglun');

	}	
	
	
	/**
	 * ajax �ظ�ĳ�˵����� ��Ҳ����˵˵��һ����
	 * 1.�ظ�ĳ��
	 * 2.����˵˵����  
	 * Enter description here ...
	 */
	public function HuifuShuoShuoAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		$UNAME = c::t('common_member')->getOneInfo('username',"uid = '{$_POST['fuid']}'");
		
		include template('diy:home/ucenter_shuoshuo2');
	}
	
	/**
	 * ajax �ظ�ĳ�˵�����
	 */
	public function HuifuShuoShuoAndRen(){
		global $_G;
		
		$id = c::t('home_shuoshuo')->insert_shuoshuo($_POST);

		$pinglun = c::t('home_shuoshuo')->getShuoshuo('`id`,`fid`,`fuid`,`uid`,`content`,`time`',"id = '{$id}'");
		$pinglun = $pinglun[0];
		
		include template('diy:ucenter/onepinglun');
				
	}
	
	/**
	 * ajax ��
	 * Enter description here ...
	 */
	public function zan(){
		
		global $_G;

		if(!$_G['uid']){
			echo 'nologin';
			exit;
		}
		
		//˵˵id
		$shuoid = $_POST['shuoshuoid'];		
		
		//��ȡ�޼�¼
		$data = c::t('home_zan')->select_zan(" uid = '{$_G['uid']}' && shuoshuoid = {$shuoid}");
		$data = $data[0];

		//û���޼�¼ ����޼�¼  statu=1 
		if($data === NULL){
			$data = $_POST;
			//��ȡ����˵˵�ķ�����
			$temp = c::t('home_shuoshuo')->select_shuoshuo("id = '{$shuoid}'");
			$data['fuid'] = $temp[0]['uid'];
			
			$id = c::t('home_zan')->insert_zan($data);	
			
			echo 'ins';
			
		}else{
		
			//���޼�¼  �鿴  statu ��ֵ
			
			//ֵ 1 update statu = -1 ȡ���޼�¼
			//ֵ -1 update statu = 1 ���޼�¼
			$result = c::t('home_zan')->updateZan($_G['uid'],$shuoid,$data['statu']);
			
			echo $result;
		}	
	}
	
	/**
	 * �ظ����Ե�ͬʱ���һظ���
	 * Enter description here ...
	 */
	public function HuifuLiuyanAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		$UNAME = c::t('common_member')->getOneInfo('username',"uid = '{$_POST['fuid']}'");
		
		include template('diy:home/ucenter_liuyanhuifu');
		
	}
	
	/**
	 * �����ռ����Բ�ͬʱ�ظ�ѡ�е��Ǹ���
	 */
	public function HuifuLiuyanAndRen(){

		global $_G;
		//�ռ����Ա��������id
		$id = c::t('home_space_liuyan')->insert_liuyan($_POST);

		if($id){
			$data = c::t('home_space_liuyan')->select_liuyan("id = '{$id}'");
			$huifu = $data[0];
		}
		
		include template('diy:ucenter/onehuifu');		
		
	}	
	
	/**
	 * ���Ϳռ�����
	 * Enter description here ...
	 */
	public function sendspaceliuyan(){

		global $_G;
		
		//�ռ����Ա��������id
		$id = c::t('home_space_liuyan')->insert_liuyan($_POST);

		if($id){
			$data = c::t('home_space_liuyan')->select_liuyan("id = '{$id}'");
			$huifu = $data[0];
		}
		
		include template('diy:ucenter/onehuifu');
				
	}	
	
	/*
	 *  ajax ������Ƶ
	 */
	public function playVideo(){
		echo "<p class=\"medis_func S_txt3\">
				<a class=\"retract\" href=\"javascript:void(0);\">
				<em class=\"W_ico12 ico_retract\"></em>����</a>
				<i class=\"W_vline\">|</i>
				<a target=\"_blank\" class=\"show_big\" title=\"{$_POST['title']}\" href=\"{$_POST['reslink']}\">
				<em class=\"W_ico12 ico_showbig\"></em>
				{$_POST['title']}</a>
			</p>
			<div style=\"text-align:center;min-height:18px;\">
				<div>
					<div>
					<embed width=\"440\" height=\"356\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" src=\"{$_POST['flash_address']}\" quality=\"high\" allowfullscreen=\"true\" flashvars=\"playMovie=true&amp;auto=1\" pluginspage=\"http://get.adobe.com/cn/flashplayer/\" style=\"visibility: visible;\" allowscriptaccess=\"never\" id=\"STK_1373958386245112\">
					</div>
				</div>
			</div>";

		exit;
		
	}	
}

$ajaxUcenter = new shuoshuoMoudle();
$ajaxUcenter->$do();
?>