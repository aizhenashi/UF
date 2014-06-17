<?php

/**
 *   ��� ajax�ύģ�� ҳ ��action �ַ� 
 *   
 *   1.�ݴ�
 *   δ��¼ ֱ�� exit
 *   
 */

//�����ҳ����ת
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/**
 * getAllPhoto ��ȡ�����id��������Ƭ
 */

$dos = 
array(
	'getAllPhoto',
	'getBigpic', //ͼƬ��ϸҳ ��ͼ��ʾ
	'insertAlbumPinglun', //��Ӷ���Ƭ������
	'getPingLunContent', //������Ƭid ��ȡ���������������
	'likepic', //������Ƭ��ϲ��
	'getOnePinglun', //��ȡһ���ظ�
	'HuifuPinglunAndRenhtml', //�ظ����۵�ͬʱ �ظ��� html
	'HuifuLiuyanAndRen', //�ظ����۵�ͬʱ �ظ��� �߼�
	'getNextPic', //��ȡ��һ��ͼƬ��picid
	'getprevPic', //��ȡ��һ��ͼƬ��picid
	'albumRightPicList', //��ȡͼƬ�ұ��б�
	'delpicforid', //ͨ����Ƭid ��ɾ����Ƭ
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}

class albumMoudle{

	/**
	 * ͨ�����id��ȡ�������������Ƭ
	 * Enter description here ...
	 */
	public function getAllPhoto(){

		global $_G;

		$datas = c::t('home_pic')->fetch_All_by_where("albumid = '".$_POST['albumid']."' && uid = '{$_G['uid']}'");

		
		include template('diy:album/ajax/albumpiclist');
		
	}
	
	//ͼƬ��ϸҳ��ͼ��ʾ
	public function getBigpic(){
		
		$datas = c::t('home_pic')->fetch_All_by_where("picid='{$_POST['picid']}'");
		$data = $datas[0];
		$data['dateline'] = date('n-j',$data['dateline']);
		$data['dateline'] = iconv('GB2312', 'UTF-8', str_replace('-', '��', $data['dateline']).'��');
		$data['title'] = iconv( 'GB2312','utf-8', $data['title']);
		//�ܵ��������� 
		$counts = c::t('home_album_shuoshuo')->counts("picid = '{$_POST['picid']}'");
		$data['pinglunCounts'] = $counts;
		
		//�ܵ�ϲ����
		$counts = c::t('home_album_like')->counts("picid = '{$_POST['picid']}'");
		$data['likeCounts'] = $counts;
		$sizeinfo = getimagesize(DISCUZ_ROOT.'data/attachment/'.$data['thumb690']);
		$data['size'] = $sizeinfo;
		die(json_encode($data));
	}
	
	//��Ӷ���Ƭ������
	public function insertAlbumPinglun(){

		$id = c::t('home_album_shuoshuo')->insert_pinglun($_POST);
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("id = '{$id}'");
		$data = $datas[0];
		include template('diy:album/pinglunhuifu');
	}

	//����picid����ȡ�������
	public function getPingLunContent(){

		$page = $_GET['page']?$_GET['page'] : 1;
		$pagenum = 8;
		//�ܵļ�¼���� ����js��ҳ
		$counts = c::t('home_album_shuoshuo')->counts("picid = '{$_POST['picid']}'");

		$offset = ($page-1)*$pagenum;
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("picid = '{$_POST['picid']}' order by id desc limit {$offset},{$pagenum}");

		include template('diy:album/pinglunhuifus');
		
	}
	
	//˭˭����Ƭ��ϲ��
	// �����¼
	public function likepic(){
		global $_G;
		$uid = $_G['uid'];
		
		if(!$uid){
			die('�ò��������¼');
		}
		$data = c::t('home_album_like')->select_rows("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}'");

		if($data){
			$id = c::t('home_album_like')->deleteForWhere("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}'");

			//ɾ���Ը���Ƭϲ��������
			c::t('home_album_shuoshuo')->deleteforwhere("picid = '{$_POST['picid']}' && type = '2' && uid = '{$_G['uid']}'");
			$statu = 2;
		}else{
			$id = c::t('home_album_like')->insert_like($_POST);
			//type = 2 ����Ƭ��ϲ��
			c::t('home_album_shuoshuo')->insert_pinglun($_POST,2);
			$statu = 1;
		}

		echo $statu;
		
	}
	
	/**
	 * ��ȡһ���ظ�
	 */
	public function getOnePinglun(){
		
		global $_G;
		
		$datas = c::t('home_album_shuoshuo')->select_shuoshuo("picid = '{$_POST['picid']}' && uid = '{$_G['uid']}' && type = '2'");
		$data = $datas[0];
		
		include template('diy:album/pinglunhuifu');		
		
	}
	
	/**
	 * �ظ����۵�ͬʱ���һظ���
	 * Enter description here ...
	 */
	public function HuifuPinglunAndRenhtml(){
		require_once libfile('function_biaoqing','function');
		$biaoqingData = getAllBiaoQIng();
		
		include template('diy:album/album_liuyanhuifu');
		
	}	
	
	/**
	 * �������۲�ͬʱ�ظ�ѡ�е��Ǹ���
	 */
	public function HuifuLiuyanAndRen(){
		
		global $_G;
		$id = c::t('home_album_shuoshuo')->insert_pinglun($_POST);

		if($id){
			$datas = c::t('home_album_shuoshuo')->select_shuoshuo("id = '{$id}'");
			$data = $datas[0];
		}
		
		include template('diy:album/pinglunhuifu');		
				
	}	
	
	/**
	 * ��ȡ��һ��ͼƬ��picid
	 * $_POST['albumid'] ���id
	 * $_POST['picid'] ͼƬid
	 * Enter description here ...
	 */
	public function getNextPic(){
		$datas = c::t('home_pic')->fetch_All_by_where("picid < '{$_POST['picid']}' && albumid = '{$_POST['albumid']}' order by picid desc");
		$data = $datas[0];

		//���picid
		die($data['picid']);
	}

	/**
	 * ��ȡ��һ��ͼƬ
	 * $_POST['albumid'] ���id
	 * $_POST['picid'] ͼƬid
	 * Enter description here ...
	 */
	public function getprevPic(){
		$datas = c::t('home_pic')->fetch_All_by_where("picid > '{$_POST['picid']}' && albumid = '{$_POST['albumid']}' order by picid asc");
		$data = $datas[0];

		//���picid
		die($data['picid']);
	}
	
	/**
	 * �ұ����ͼƬ�б�
	 * ��ȡ ������µľ�����Ƭ
	 * picid desc
	 */
	public function albumRightPicList(){

		$picid = $_POST['picid'];
		$albumid = $_POST['albumid'];


		//front limit
		$data = DB::fetch_first("select count(`picid`) as tot from `".DB::table('home_pic')."` where picid < '{$picid}' && albumid = '{$albumid}'");
		$bget = $data['tot'];
		$flimit = $bget >= 4 ? 4 : 4+(4-$bget); 

		//����Ƭ��ǰ������Ƭ ����ȡ4�� 
		$fdatas = DB::fetch_all("select `picid` from `".DB::table('home_pic')."` where picid > '{$picid}' && albumid = '{$albumid}' order by picid asc limit {$flimit}");

		//���������
		$fdatas[] = array('picid'=>$picid);

		//bottom limit 
		$data = DB::fetch_first("select count(`picid`) as tot from `".DB::table('home_pic')."` where picid > '{$picid}' && albumid = '{$albumid}'");
		$fget = $data['tot'];
		$blimit = $fget >= 4 ? 4 : 4+(4-$fget);

		//ȡ����ͼƬ
		$bdatas = DB::fetch_all("select `picid` from `".DB::table('home_pic')."` where picid < '{$picid}' && albumid = '{$albumid}' order by picid desc limit {$blimit}");

		$datas = array_merge($fdatas,$bdatas);

		$c = count($datas);
		for($i = 0;$i<$c;$i++){
			for($j = 0; $j<$c-$i-1; $j++){

				// desc order
				if((int) $datas[$j]['picid'] < (int) $datas[$j+1]['picid']){
					$temp = $datas[$j];
					$datas[$j] = $datas[$j+1];
					$datas[$j+1] = $temp;
				}
			}
		}

//		db::query("select count(`picid`) from `".DB::table('home_pic')."` where picid > ");

		//��ȡ ��Ƭ80����ͼ ,picid
		foreach ($datas as $data){
			$temp = DB::fetch_first("select `picid`,`thumb80` from `".DB::table('home_pic')."` where picid = '{$data['picid']}'");
			$array[] = $temp;
		}

		include template('diy:album/thumbpic');

	}
	
	/**
	 * ͨ����Ƭid��ɾ�������Դ
	 * Enter description here ...
	 */
	public function delpicforid(){
		$result = c::t('home_pic')->delPicForPicid($_POST['picid']);
		echo $result;
		exit;

	}

}

$ajaxUcenter = new albumMoudle();
$ajaxUcenter->$do();
?>