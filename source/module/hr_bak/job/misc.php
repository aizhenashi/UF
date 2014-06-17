<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_ajax.php 7091 2010-03-29 02:47:30Z redstone $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('NOROBOT', TRUE);

require_once libfile('function/post');

if($_GET['action'] == 'protectsort') {
	if($_GET['sortvalue']) {
		makevaluepic($_GET['sortvalue']);
	} else {
		$tid = intval($_GET['tid']);
		$optionid = $_GET['optionid'];
		include template('common/header_ajax');
		echo DB::result_first('SELECT value FROM '.DB::table('hr_sortoptionvar')." WHERE tid='$tid' AND optionid='$optionid'");
		include template('common/footer_ajax');
	}
} elseif($_GET['action'] == 'thumb') {
	if(empty($_GET['aid']) || empty($_GET['size']) || empty($_GET['key'])) {
		header('location: '.$_G['siteurl'].'static/image/common/nophotosmall.gif');
		exit;
	}

	$nocache = !empty($_GET['nocache']) ? 1 : 0;
	$aid = intval($_GET['aid']);
	$type = !empty($_GET['type']) ? $_GET['type'] : 'fixwr';
	list($w, $h) = explode('x', $_GET['size']);
	$w = intval($w);
	$h = intval($h);
	$thumbfile = 'image/'.$aid.'_'.$w.'_'.$h.'_job.jpg';
	$identifier = $_GET['identifier'] && in_array($_GET['identifier'], array('job')) ? $identifier : 'job';
	if(!$nocache) {
		if(file_exists(DISCUZ_ROOT.'./data/attachment/'.$thumbfile)) {
			header('location: '.$_G['siteurl'].'data/attachment/'.$thumbfile);
			exit;
		}
	}

	define('NOROBOT', TRUE);

	list($daid, $dw, $dh) = explode("\t", authcode($_GET['key'], 'DECODE', $_G['config']['security']['authkey']));

	if($daid != $aid || $dw != $w || $dh != $h) {
		dheader('location: '.$_G['siteurl'].'static/image/common/nophotosmall.gif');
	}

	if($attach = DB::fetch(DB::query("SELECT url FROM ".DB::table('hr_'.$identifier.'_pic')." WHERE aid='$aid'"))) {
		dheader('Expires: '.gmdate('D, d M Y H:i:s', TIMESTAMP + 3600).' GMT');
		$filename = $_G['setting']['attachdir'].'/hr/'.$attach['url'];

		require_once libfile('class/image');
		$img = new image;
		if($img->Thumb($filename, $thumbfile, $w, $h, $type)) {
			if($nocache) {
				@readfile(DISCUZ_ROOT.'./data/attachment/'.$thumbfile);
				@unlink(DISCUZ_ROOT.'./data/attachment/'.$thumbfile);
			} else {
				dheader('location: '.$_G['siteurl'].'data/attachment/'.$thumbfile);
			}
		} else {
			@readfile($filename);
		}
	}
} elseif($_GET['action'] == 'buyoption') {

	if(empty($_G['uid'])) {
		showmessage(lang('hr/template', 'job_please_login'));
	}

	$tid = intval($_GET['tid']);
	$optionid = intval($_GET['optionid']);
	$buy = unserialize(DB::result_first("SELECT protect FROM ".DB::table('hr_sortoption')." WHERE optionid='$optionid'"));
	$exist = DB::result_first('SELECT tid FROM '.DB::table('hr_payoption')." WHERE uid='$_G[uid]' AND tid='$tid' AND optionid='$optionid'");
	if(getuserprofile('extcredits'.$_G['setting']['creditstransextra'][$buy['credits']['title']]) < $buy['credits']['price']) {
		showmessage(lang('hr/template', 'job_no_integral'));
	} else {
		if(empty($exist)) {
			updatemembercount($_G['uid'], array($_G['setting']['creditstransextra'][$buy['credits']['title']] => -$buy['credits']['price']));
			DB::query("INSERT INTO ".DB::table('hr_payoption')." (tid, uid, optionid, dateline) VALUES ('$tid', '$_G[uid]', '$optionid', '$_G[timestamp]')");
		}
		$optionvalue = DB::result_first('SELECT value FROM '.DB::table('hr_sortoptionvar')." WHERE tid='$tid' AND optionid='$optionid'");
		//showmessage($optionvalue);
		showmessage($optionvalue, 'job.php?mod=view&tid='.$tid, array(), array('showdialog' => true, 'locationtime' => true));
	}
} elseif($_GET['action'] == 'area') {

	loadcache('hr_arealist_'.$_GET['do']);
	$arealist = $_G['cache']['hr_arealist_'.$_GET['do']];

	$selectarealist = '';
	if($_GET['cityid']) {
		$var = 'district';
		$selectarealist = '<option value="0">地区</option>';
		foreach($arealist['district'][$_GET['cityid']] as $districtid => $district) {
			$selectarealist .= '<option value="'.$districtid.'">'.$district.'</option>';
		}
	} elseif($_GET['districtid']) {
		$var = 'street';
		foreach($arealist['street'][$_GET['districtid']] as $streetid => $street) {
			$selectarealist .= '<option value="'.$streetid.'">'.$street.'</option>';
		}
	}

	include template('common/header_ajax');
	include template('hr/ajax_area');
	include template('common/footer_ajax');
	dexit();

} elseif($_GET['action'] == 'applyrs') {

	$applyuid = $_G['uid'];
	if(empty($_G['uid'])) {
		showmessage(lang('hr/template', 'job_please_login'));
	}
	$tid = intval($_GET['tid']);
	$sortid = intval($_GET['sortid']);
	$sortname =  DB::result_first("SELECT name FROM ".DB::table('hr_sort')." WHERE sortid='$sortid'");
	
	$bossuid = DB::result_first("SELECT authorid FROM ".DB::table('hr_job_thread')." WHERE tid='$tid'");
	if($bossuid == $applyuid){
		showmessage('您不能向自己投递简历');
	}
	$exited = DB::result_first("SELECT dateline FROM ".DB::table('hr_job_applyrs')." WHERE tid='$tid' AND uid='$applyuid'");
	if(!empty($exited)){
		showmessage('您已经申请过该职位了');
	}
	$resume = DB::fetch_first("SELECT * FROM ".DB::table('hr_resume')." WHERE uid='$applyuid'");
	if($resume['available'] == '0'){
		showmessage('您的简历处于关闭状态，请先行前往<a href="job.php?mod=resume&action=setting" target="_blank">开启简历</a');
	}elseif($resume['verify'] == '0'){
		showmessage('您的简历尚未通过审核');
	}elseif(empty($resume['id'])){
		showmessage('您尚未设置简历，请先行前往<a href="job.php?mod=resume&action=setting" target="_blank">填写简历</a>');
	}
	DB::query("INSERT INTO ".DB::table('hr_job_applyrs')." (tid, uid, dateline) VALUES ('$tid', '$_G[uid]', '$_G[timestamp]')");
	$msg['msgtitle']="您好，有用户向您的".$sortname."信息投递了简历，<a href='job.php?mod=view&tid=".$tid."' target='_blank'>点击我前往查看信息</a>，或者<a href='job.php?mod=resume&action=view&uid=".$applyuid."' target='_blank'>点我直接看Ta的简历</a>。";
	notification_add($bossuid, 'system', $msg['msgtitle']);
	showmessage('投递简历成功', 'job.php?mod=view&tid='.$tid, array(), array('showdialog' => true, 'locationtime' => true));
	dexit();

}

function makevaluepic($value) {
	Header("Content-type:image/png");
	$im = imagecreate(130, 25);
	$background_color = imagecolorallocatealpha($im, 0, 0, 0, 127);
	$text_color = imagecolorallocate($im, 23, 14, 91);
	imagestring($im, 4, 0, 4, $value, $text_color);
	imagepng($im);
	imagedestroy($im);
}

?>