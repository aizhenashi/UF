<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_forumdisplay.php 7610 2010-04-09 01:55:40Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$uid=$_G['uid'];
//����ϴ�����Ƭ��ȡ����
$p=DB::fetch_all("select picid from ".DB::table('home_pic')." where uid={$_G['uid']} and click8=1 limit 0,8");
	foreach($p as $img){
		$arr[]=$img;
	}
$name=DB::fetch_first("select username from ".DB::table('home_pic')." where uid=$uid");
$img=DB::fetch_all("select * from ".DB::table('home_pic')." where uid=$uid");
foreach($img as $photo){
	$ph[]=$photo;
}
//����Ǵ������ѡ��Ƭ
if(isset($_POST['submit'])){
	$picid=$_POST['picid'];
	//���μӻ����Ƭ�Ƿ񳬹�8��
	$total=DB::fetch_first("select count(uid) as t from ".DB::table('home_pic')." where click8=1 and uid=$uid");
	if($total['t']>=8){
		showmessage('���μӻ����Ƭ�����ﵽ������!');
	}else{
		if(is_array($picid)){
			foreach($picid as $pid){
				//ÿ�α�����鿴ѡ����Ƭ�Ƿ񳬹����ֵ
				$total=DB::fetch_first("select count(uid) as t from ".DB::table('home_pic')." where click8=1 and uid=$uid");
				if($total['t']<8){
						DB::query("update ".DB::table('home_pic')." set click8=1 where picid=$pid");	
					}else{
						showmessage('���μӻ����Ƭ�����ﵽ����');
					}
				}
				showmessage('������ѡ����Ƭ��');
		}else{
		DB::query("update ".DB::table('home_pic')." set click8=1 where picid=$picid");
		showmessage('������ѡ����Ƭ��');
		}
		}
	}
include template('diy:events/two');

?>