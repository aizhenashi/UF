<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_admincp_member.php 27740 2012-02-13 10:05:22Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_video_shortlink extends discuz_table
{

	public function __construct() {

		$this->_table = 'video_shortlink';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function insert_shortlink($reslink) {

		//ץȡҳ���е�ͼƬ, title, flash_address
		require libfile('class_video_pic','class');
		$Videocatch = new Videocatch($reslink);
		$Videocatch->setVideoinfo();
		$videoinfo = $Videocatch->videoinfo;

		if($videoinfo !== array()){
			//���ɶ�����
			require libfile('class_shortlink','class');
			$shortlinkObj = new shortlink();
			$shortlink = $shortlinkObj->short($reslink);
					
			//׼��insert ����
			$datas = $videoinfo;
			$datas['shortlink'] = $shortlink;
			$datas['reslink'] = $reslink;
			$datas['time'] = time();
	
			//ִ��insert ���
			$rs = DB::insert($this->_table, $datas);
			if($rs){
				return $shortlink;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * ͨ������������ȡ����������Ϣ
	 * Enter description here ...
	 * @param unknown_type $shortlink
	 */
	public function fetch_by_shortlink($shortlink) {

		return DB::fetch_first("SELECT `id`,`shortlink`,`reslink`,`pic`,`title`,`flash_address` FROM `".DB::table($this->_table)."` WHERE shortlink='".$shortlink."'");
	}	
	
}

?>