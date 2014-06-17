<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: modcp_announcement.php 29236 2012-03-30 05:34:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function setCharset($str){
	$data=iconv('UTF-8','GB2312',$str);
	return $data;
}
$uid=$_G['uid'];
//var_dump($_G['cache']);exit;
//看是否认证
$attu=DB::fetch_first("SELECT uid,flag FROM ".DB::table('common_member_verify_info')." WHERE uid=$uid");
$abc=DB::fetch_first("select verify1 from ".DB::table('common_member_verify')." where uid=$uid");

var_dump($attu);
echo '<br/>';
var_dump($abc);exit;
//var_dump($attu);
//机构会员相关信息
$data=DB::fetch_first("SELECT field3 FROM ".DB::table('common_member_profile')." WHERE uid=$uid");
if(!$attu){
	
	if(isset($_POST['submit_btn'])){
			$upload=new discuz_upload();
			$FILE=$_FILES['myfile'];
			$upload->init($FILE,'profile');
			if($upload->error()){
				return lang('spacep','lack_of_access_to_upload_file_size');
			}
			if(!$upload->attach['isimage']){
				return lang('spacep','only_allows_upload_file_types');
			}
			$upload->save();
			if($upload->error()) {
				return lang('spacecp', 'mobile_picture_temporary_failure');
			}
			if(!$upload->attach['imageinfo'] || !in_array($upload->attach['imageinfo']['2'], array(1,2,3,6))) {
				@unlink($upload->attach['target']);
				return lang('spacecp', 'only_allows_upload_file_types');
			}
			//用于缩略图
			//$new_name=$upload->attach['target'];
			//生成缩略图
			//$image=new image();
			//$nowdir=dirname($new_name);
			//$arr=explode('/',$new_name);
			//$filename=$arr[count($arr)-1];
			$filename=$upload->attach['attachment'];
			//var_dump($filename);
			$setverify = array(
				'uid' => $_G['uid'],
				'username' => $_G['username'],
				'verifytype' => '1',
				'field' => serialize($filename),
				'dateline' => $_G['timestamp']
			);

		C::t('common_member_verify_info')->insert($setverify);	
		
			$verify=array('uid'=>$_G['uid'],
						  'verify'=>'1'
			);
		C::t('common_member_verify')->insert($verify);		
	}
	
	

	
}
include template('diy:org/orgauthentication');
?>