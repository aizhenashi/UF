<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*引入文件*/
require_once libfile('function/core');
require_once libfile('function/spacecp');
include_once libfile('function/profile');
require_once libfile('function/hr');
require_once libfile('function/discuzcode');
require_once libfile('function/forum');

$metakeywords = empty($metakeywords) ? $channel['seoinfo']['seokeywords'] : $metakeywords;
$metadescription = empty($metadescription) ? $channel['seoinfo']['seodescription'] : $metadescription;

/*初始动态产生时间*/
$hft = dgmdate($_G['timestamp'], 'Y-m-d H:i');

/*初始地区数据*/
$birthcityhtml = showdistrict(array(0,0), array('birthprovince', 'birthcity'), 'birthcitybox');
$residecityhtml = showdistrict(array(0,0, 0, 0), array('resideprovince', 'residecity', 'residedist', 'residecommunity'), 'residecitybox');

/*检查模式许可*/
$actionarray = array('list', 'view', 'setting');
$action = $_GET['action'] && in_array($_GET['action'], $actionarray) ? $_GET['action'] : 'list';

/*检查二级模式许可*/
$oparray = array('basic', 'edu', 'work', 'contect', 'mind', 'avater', 'savebasic', 'saveedu', 'savework', 'savemind', 'saveavater', 'deledu', 'delwork', 'savecontect', 'open', 'promote');
$op = $_GET['op'] && in_array($_GET['op'], $oparray) ? $_GET['op'] : 'basic';

/*初始用户信息，提示游客登录*/
$uid = '';
if($action == 'setting' ) {
	$uid = intval($_G['uid']);
	if(empty($_G['uid'])) {
		showmessage('to_login', '', array(), array('showmsg' => true, 'login' => 1));
	}
} else {
	$uid = intval($_GET['uid']);
}

/*初始建立新的简历*/
if($action == 'setting'){
	$crate = DB::result_first("SELECT * FROM ".DB::table('hr_resume')." WHERE uid='$uid'");
	if(empty($crate)){
		$cratedata['uid'] = $uid;
		$cratedata['available'] = 0;
		$cratedata['cratetime'] = TIMESTAMP;
		$cratedata['updatetime'] = TIMESTAMP;
		$cratenew = DB::insert('hr_resume', $cratedata, 1);
		$cratenewid = DB::insert_id();
		$cratebasicdata['id'] = $cratenewid;
		$cratebasicdata['uid'] = $uid;
		$cratenewbasic = DB::insert('hr_resume_basic', $cratebasicdata, 1);
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 创建了简历，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
	}
}

if(in_array($action, array('view', 'setting'))) {
	$mygid = DB::result_first("SELECT  groupid FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid=".$_G['uid']);
	$member = DB::fetch_first("SELECT cm.threads, cm.groupid, cm.city, cm.district, cm.street, cm.verify, cm.certification, cm.tel, cm.address, m.uid, m.realname, m.gender, m.birthyear, m.birthmonth, m.birthday, m.constellation, m.zodiac, m.telephone, m.mobile, m.birthprovince, m.birthcity, m.birthdist, m.birthcommunity, m.resideprovince, m.residecity, m.residedist, m.residecommunity, m.height, m.weight, m.bloodtype, m.qq, m.telephone, m.mobile, mb.marriage, mb.message, rs.id, rs.available, rs.recommend, rs.cratetime, rs.updatetime, rs.phonesafe, rs.emailsafe, rs.mobilesafe, rs.qqsafe, rs.basic, rs.edu, rs.work, rs.contect, rs.mind, rs.views, rs.avater, rs.verify, mm.email, mm.username FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
		LEFT JOIN ".DB::table('common_member_profile')." m ON cm.uid=m.uid LEFT JOIN ".DB::table('hr_resume')." rs ON cm.uid =rs.uid LEFT JOIN ".DB::table('hr_resume_basic')." mb ON rs.id =mb.id LEFT JOIN ".DB::table('common_member')." mm ON cm.uid =mm.uid WHERE cm.uid='$uid'");
	$rid = $member['id'];
	$member['update'] = dgmdate($member['updatetime'], 'd');
	$member['username'] = $member['username'];
	$member['percent'] = $member['basic']+$member['edu']+$member['work']+$member['contect']+$member['mind'];
	if($member['birthyear']){
	$member['age'] = dgmdate(TIMESTAMP, 'Y')-$member['birthyear'];
	}
	$member['birth'] = getuserprofile('birthprovince');
	if($member['avater']) {
		$valueparse = parse_url($member['avater']);
		
		if(isset($valueparse['host'])) {
			$memberavater = $member['avater'];
		} else {
			$memberavater = $_G['setting']['attachurl'].'hr/'.$member['avater'].'?'.random(6);
		}
		$avaterhtml = '<img src="'.$memberavater.'" />';
	}else{
		$memberavater = $_G['siturl'].'static/image/job/avater.gif';
		$avaterhtml = '<img src="'.$memberavater.'" />';
	}

	$nowy = dgmdate($_G['timestamp'], 'Y');
	$birthyeayhtml = '';
	for ($i=0; $i<100; $i++) {
		$they = $nowy - $i;
		if(empty($_GET['all'])) $selectstr = $they == $member['birthyear']?' selected=\"selected\"':'';
		$birthyeayhtml .= "<option value=\"$they\"$selectstr>$they</option>";
	}
	$birthmonthhtml = '';
	for ($i=1; $i<13; $i++) {
		if(empty($_GET['all'])) $selectstr = $i == $member['birthmonth']?' selected=\"selected\"':'';
		$birthmonthhtml .= "<option value=\"$i\"$selectstr>$i</option>";
	}
	$birthdayhtml = '';
	for ($i=1; $i<29; $i++) {
		if(empty($_GET['all'])) $selectstr = $i == $member['birthday']?' selected=\"selected\"':'';
		$birthdayhtml .= "<option value=\"$i\"$selectstr>$i</option>";
	}
	$bloodhtml = '';
	foreach (array('A','B','O','AB') as $value) {
		if(empty($_GET['all'])) $selectstr = $value == $member['blood']?' selected=\"selected\"':'';
		$bloodhtml .= "<option value=\"$value\"$selectstr>$value</option>";
	}
	
	$usergroupid = $member['groupid'];
	$username = $member['realname'] ? $member['realname'] : $member['username'];
	$avatar = hr_uc_avatar($member['uid']);
	$usergrouplist[$usergroupid]['icon'] = $usergrouplist[$usergroupid]['icon'] ? $_G['setting']['attachurl'].'common/'.$usergrouplist[$usergroupid]['icon'] : '';
	$usergrouplist[$usergroupid]['postdayper'] = $usergrouplist[$usergroupid]['postdayper'] ? intval($usergrouplist[$usergroupid]['postdayper']) : '';
	if($usergroupid > 1) {
		$usergrouplist[$usergroupid]['title'] = '<a href="'.$modurl.'?mod=agent&action=store&gid='.$usergroupid.'">'.$usergrouplist[$usergroupid]['title'].'</a>';
	}
	$_G['hr_member']['todaythreads'] = intval($_G['hr_member']['todaythreads']);
	$verifyinfo = showverifyicon($member['certification']);
}
	$member['messageshow'] = discuzcode($member['message'], 0, 0, 0);

if($action == 'list') {
	$navtitle = '人才简历库 - 第'.$_G['page'].'页 - '.$channel['title'];
	$shownum = 10;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $shownum;

	$resumenum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_resume')." WHERE available = 1");
	$multipage = multi($resumenum, $shownum, $page, "$modurl?mod=resume");

	$resumelist = $topresumelist = array();
	if($resumenum) {
		$query = DB::query("SELECT cm.threads, cm.groupid, cm.city, cm.district, cm.street, cm.verify, cm.certification, cm.tel, cm.address, cmp.uid, cmp.realname, cmp.gender, cmp.birthyear, cmp.birthmonth, cmp.birthday, cmp.constellation, cmp.zodiac, cmp.telephone, cmp.mobile, cmp.birthprovince, cmp.birthcity, cmp.birthdist, cmp.birthcommunity, cmp.resideprovince, cmp.residecity, cmp.residedist, cmp.residecommunity, cmp.height, cmp.weight, cmp.bloodtype, cmp.qq, cmp.telephone, cmp.mobile, mb.marriage, mb.message, rs.id, rs.available, rs.recommend, rs.cratetime, rs.updatetime, rs.phonesafe, rs.emailsafe, rs.mobilesafe, rs.qqsafe, rs.basic, rs.edu, rs.work, rs.contect, rs.mind, rs.views, rs.avater, rs.verify, mm.email, mm.username FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
		LEFT JOIN ".DB::table('common_member_profile')." cmp ON cm.uid=cmp.uid LEFT JOIN ".DB::table('hr_resume')." rs ON cm.uid =rs.uid LEFT JOIN ".DB::table('hr_resume_basic')." mb ON rs.id =mb.id LEFT JOIN ".DB::table('common_member')." mm ON cm.uid =mm.uid WHERE rs.verify='1' AND rs.available = 1 ORDER BY rs.updatetime DESC LIMIT $start_limit, $shownum");
		while($resume = DB::fetch($query)) {
			if($resume['avater']) {
				$valueparse = parse_url($resume['avater']);
		
				if(isset($valueparse['host'])) {
					$resumeavater = $resume['avater'];
				} else {
					$resumeavater = $_G['setting']['attachurl'].'hr/'.$resume['avater'].'?'.random(6);
				}
				$avaterhtml = '<img src="'.$resumeavater.'" />';
			}else{
				$resumeavater = $_G['siturl'].'static/image/job/avater.gif';
				$avaterhtml = '<img src="'.$resumeavater.'" />';
			}
			$resume['avatar'] = $avaterhtml;
			$resume['rid'] = $resume['id'];
			$resume['gender'] = $resume['gender'];
			$resume['views'] = $resume['views'];
			$resume['username'] = $resume['realname'] ? $resume['realname'] : $resume['username'];
			$resume['update'] = dgmdate($resume['updatetime'], 'd');
			if($resume['birthyear']){
			$resume['age'] = dgmdate(TIMESTAMP, 'Y')-$resume['birthyear'];
			}
			$resume['highestedu'] = DB::result_first("SELECT type FROM ".DB::table('hr_resume_edulist')." WHERE rid=$resume[rid] AND uid=$resume[uid] ORDER BY type DESC limit 1");
			$resumelist[] = $resume;
		}

		$query = DB::query("SELECT cm.threads, m.uid, m.username, cm.realname FROM ".DB::table('hr_'.$modidentifier.'_member')." cm
			LEFT JOIN ".DB::table('common_member')." m ON cm.uid=m.uid WHERE cm.groupid>'1' AND cm.verify='1' ORDER BY cm.threads DESC LIMIT 0, 10");
		while($toplist = DB::fetch($query)) {
			if($toplist['realname']) {
				$toplist['username'] = $toplist['realname'];
			}
			$topmemberlist[] = $toplist;
		}
	}
} elseif($action == 'view') {
	$navtitle = $username.'的简历 - 人才简历库 - '.$channel['title'];
	if(empty($member['id'])){
		showmessage(lang('hr/template', 'rs_notavailable'), 'job.php?mod=resume', array(), array('showdialog' => true, 'locationtime' => true));
	}
	if(empty($_G['uid'])) {
		showmessage('to_login', '', array(), array('showmsg' => true, 'login' => 1));
	}
	$viewsup = DB::query("UPDATE ".DB::table('hr_resume')." SET views=views+1 WHERE id=$member[id]");
	//管理员之外的用户当简历未通过或者未开启时禁止浏览，提示简历不可用
	if($uid != $_G['uid']){
		if(($member['available'] == 0 || $member['verify'] == 0) && $_G['adminid'] != 1){
			showmessage(lang('hr/template', 'rs_notavailable'), 'job.php?mod=resume', array(), array('showdialog' => true, 'locationtime' => true));
		}
	}
		$edulist = array();
		$query = DB::query("SELECT * FROM ".DB::table('hr_resume_edulist')." WHERE rid=$rid AND uid=$uid ORDER BY edustart ASC");
		while($edu = DB::fetch($query)) {
			$edulist[$edu['id']]['id'] = $edu['id'];
			$edulist[$edu['id']]['uid'] = $edu['uid'];
			$edulist[$edu['id']]['rid'] = $edu['rid'];
			$edulist[$edu['id']]['edustart'] = dgmdate($edu['edustart'], 'd');
			$edulist[$edu['id']]['eduend'] = dgmdate($edu['eduend'], 'd');
			$edulist[$edu['id']]['school'] = $edu['school'];
			$edulist[$edu['id']]['pro'] = $edu['pro'];
			$edulist[$edu['id']]['type'] = $edu['type'];
		}
		$worklist = array();
		$query = DB::query("SELECT * FROM ".DB::table('hr_resume_worklist')." WHERE rid=$rid AND uid=$uid ORDER BY workstart ASC");
		while($work = DB::fetch($query)) {
			$worklist[$work['id']]['id'] = $work['id'];
			$worklist[$work['id']]['uid'] = $work['uid'];
			$worklist[$work['id']]['rid'] = $work['rid'];
			$worklist[$work['id']]['workstart'] = dgmdate($work['workstart'], 'd');
			$worklist[$work['id']]['workend'] = dgmdate($work['workend'], 'd');
			$worklist[$work['id']]['workcompany'] = $work['workcompany'];
			$worklist[$work['id']]['worktype'] = $work['worktype'];
		}
		$member['highestedu'] = DB::result_first("SELECT type FROM ".DB::table('hr_resume_edulist')." WHERE rid=$rid AND uid=$uid ORDER BY type DESC limit 1");
} elseif($action == 'setting') {
	$navtitle = '简历设置 - 人才简历库 - '.$channel['title'];
	if($op == 'savebasic'){
		if(empty($_GET['realname']) || empty($_GET['gender']) || empty($_GET['birthyear']) || empty($_GET['birthmonth']) || empty($_GET['birthday']) || empty($_GET['height']) || empty($_GET['weight']) || empty($_GET['birthprovince']) || empty($_GET['birthcity'])|| empty($_GET['resideprovince']) || empty($_GET['residecity']) || empty($_GET['marriage'])){
			showmessage(lang('hr/template', 'threadtype_required_invalid'));
		}
		$newdata['realname'] = dhtmlspecialchars($_GET['realname']);
		$newdata['gender'] = intval($_GET['gender']);
		$newdata['birthyear'] = intval($_GET['birthyear']);
		$newdata['birthmonth'] = intval($_GET['birthmonth']);
		$newdata['birthday'] = intval($_GET['birthday']);
		$newdata['height'] = intval($_GET['height']);
		$newdata['weight'] = intval($_GET['weight']);
		if(isset($_GET['birthmonth']) && ($member['birthmonth'] != $_GET['birthmonth'] || $member['birthday'] != $_GET['birthday'])) {
			$newdata['constellation'] = get_constellation($_GET['birthmonth'], $_GET['birthday']);
		}
		if(isset($_GET['birthyear']) && $member['birthyear'] != $_GET['birthyear']) {
			$newdata['zodiac'] = get_zodiac($_GET['birthyear']);
		}
		$newdata['birthprovince'] = dhtmlspecialchars($_GET['birthprovince']);
		$newdata['birthcity'] = dhtmlspecialchars($_GET['birthcity']);
		$newdata['birthdist'] = dhtmlspecialchars($_GET['birthdist']);
		$newdata['birthcommunity'] = dhtmlspecialchars($_GET['birthcommunity']);
		$newdata['resideprovince'] = dhtmlspecialchars($_GET['resideprovince']);
		$newdata['residecity'] = dhtmlspecialchars($_GET['residecity']);
		$newdata['residedist'] = dhtmlspecialchars($_GET['residedist']);
		$newdata['residecommunity'] = dhtmlspecialchars($_GET['residecommunity']);
		$newmarriage = intval($_GET['marriage']);
		DB::update('common_member_profile', $newdata, "uid='$uid'");
		DB::update('hr_resume_basic', array('marriage' => $newmarriage), "id='$member[id]'");
		DB::update('hr_resume', array('basic' => 20 , 'updatetime' => TIMESTAMP), "id='$member[id]'");
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 更新了简历基本信息，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=edu', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'edu'){
		$edulist = array();
		$query = DB::query("SELECT * FROM ".DB::table('hr_resume_edulist')." WHERE rid=$rid AND uid=$uid ORDER BY edustart ASC");
		while($edu = DB::fetch($query)) {
			$edulist[$edu['id']]['id'] = $edu['id'];
			$edulist[$edu['id']]['uid'] = $edu['uid'];
			$edulist[$edu['id']]['rid'] = $edu['rid'];
			$edulist[$edu['id']]['edustart'] = dgmdate($edu['edustart'], 'd');
			$edulist[$edu['id']]['eduend'] = dgmdate($edu['eduend'], 'd');
			$edulist[$edu['id']]['school'] = $edu['school'];
			$edulist[$edu['id']]['pro'] = $edu['pro'];
			$edulist[$edu['id']]['type'] = $edu['type'];
		}
	}elseif($op == 'saveedu'){
		$newdata['school'] = dhtmlspecialchars($_GET['school']);
		$newdata['pro'] = dhtmlspecialchars($_GET['pro']);
		$newdata['rid'] = $rid;
		$newdata['uid'] = $uid;
		$newdata['edustart'] = dmktime($_GET['edustart']);
		$newdata['eduend'] = dmktime($_GET['eduend']);
		$newdata['type'] = intval($_GET['type']);
		DB::insert('hr_resume_edulist', $newdata, 1);
		DB::update('hr_resume', array('edu' => 20 , 'updatetime' => TIMESTAMP), "id='$member[id]'");
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 更新了简历教育信息，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=edu', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'deledu'){
		$eid = intval($_GET['eid']);
		DB::delete('hr_resume_edulist', "id='$eid'");
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=edu', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'work'){
		$worklist = array();
		$query = DB::query("SELECT * FROM ".DB::table('hr_resume_worklist')." WHERE rid=$rid AND uid=$uid ORDER BY workstart ASC");
		while($work = DB::fetch($query)) {
			$worklist[$work['id']]['id'] = $work['id'];
			$worklist[$work['id']]['uid'] = $work['uid'];
			$worklist[$work['id']]['rid'] = $work['rid'];
			$worklist[$work['id']]['workstart'] = dgmdate($work['workstart'], 'd');
			$worklist[$work['id']]['workend'] = dgmdate($work['workend'], 'd');
			$worklist[$work['id']]['workcompany'] = $work['workcompany'];
			$worklist[$work['id']]['worktype'] = $work['worktype'];
		}
	}elseif($op == 'savework'){
		$newdata['workcompany'] = dhtmlspecialchars($_GET['workcompany']);
		$newdata['worktype'] = dhtmlspecialchars($_GET['worktype']);
		$newdata['rid'] = $rid;
		$newdata['uid'] = $uid;
		$newdata['workstart'] = dmktime($_GET['workstart']);
		$newdata['workend'] = dmktime($_GET['workend']);
		DB::insert('hr_resume_worklist', $newdata, 1);
		DB::update('hr_resume', array('work' => 20 , 'updatetime' => TIMESTAMP), "id='$member[id]'");
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 更新了简历工作信息，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=work', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'delwork'){
		$wid = intval($_GET['wid']);
		DB::delete('hr_resume_worklist', "id='$wid'");
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=work', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'contect'){
	}elseif($op == 'savecontect'){
		$newdata['telephone'] = dhtmlspecialchars($_GET['telephone']);
		$newdata['mobile'] = dhtmlspecialchars($_GET['mobile']);
		$newdata['qq'] = dhtmlspecialchars($_GET['qq']);
		DB::update('common_member_profile', $newdata, "uid='$uid'");
		$newdata2['email'] = dhtmlspecialchars($_GET['email']);
		DB::update('common_member', $newdata2, "uid='$uid'");
		$newdata3['emailsafe'] = intval($_GET['emailsafe']);
		$newdata3['phonesafe'] = intval($_GET['phonesafe']);
		$newdata3['mobilesafe'] = intval($_GET['mobilesafe']);
		$newdata3['qqsafe'] = intval($_GET['qqsafe']);
		DB::update('hr_resume', $newdata3, "id='$member[id]'");
		DB::update('hr_resume', array('contect' => 20 , 'updatetime' => TIMESTAMP), "id='$member[id]'");
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 更新了简历联系方式，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=mind', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'savemind'){
		$newdata['message'] = dhtmlspecialchars($_GET['message']);
		DB::update('hr_resume_basic', $newdata, "id='$rid'");
		DB::update('hr_resume', array('mind' => 20 , 'updatetime' => TIMESTAMP), "id='$member[id]'");
		include_once libfile('function/feed');
        feed_add('resume', '{actor} 于 {datetime} 更新了简历技能备注，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
		showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting&op=avater', array(), array('showdialog' => true, 'locationtime' => true));
	}elseif($op == 'saveavater'){
		if(submitcheck('avatersubmit')){
			if($_FILES['avater']) {
				$data = array('extid' => 'resume_'.$uid);
				$_GET['avater'] = upload_avater($data, $_FILES['avater'], '');
			}
			$newdata['avater'] = $_GET['avater'];
			$newdata['updatetime'] = TIMESTAMP;
			DB::update('hr_resume', $newdata, "id='$member[id]'");
			include_once libfile('function/feed');
        	feed_add('resume', '{actor} 于 {datetime} 更新了简历头像，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
			showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting', array(), array('showdialog' => true, 'locationtime' => true));
		}
	}elseif($op == 'open'){
		if($member['basic']==0){
			showmessage(lang('hr/template', 'rs_cantavailable'), 'job.php?mod=resume&action=setting', array(), array('showdialog' => true, 'locationtime' => true));
		}
		if(submitcheck('opensubmit')){
			$newdata['available'] = intval($_GET['available']);
			DB::update('hr_resume', $newdata, "id='$member[id]'");
			include_once libfile('function/feed');
        	feed_add('resume', '{actor} 于 {datetime} 开启了简历，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
			showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting', array(), array('showdialog' => true, 'locationtime' => true));
		}
	}elseif($op == 'promote'){
		if(submitcheck('promotesubmit')){
			$newdata['updatetime'] = TIMESTAMP;
			DB::update('hr_resume', $newdata, "id='$member[id]'");
			include_once libfile('function/feed');
        	feed_add('resume', '{actor} 于 {datetime} 提升了自己的简历，<a href="job.php?mod=resume&action=view&uid='.$uid.'">去看看</a>', array('datetime' => $hft));
			showmessage(lang('hr/template', 'job_update_success'), 'job.php?mod=resume&action=setting', array(), array('showdialog' => true, 'locationtime' => true));
		}
	}

}

function upload_avater(&$data, $file, $type) {
	global $_G;
	$data['extid'] = empty($data['extid']) ? $data['fid'] : $data['extid'];
	if(empty($data['extid'])) return '';

	if($data['status'] == 3 && $_G['setting']['group_imgsizelimit']) {
		$file['size'] > ($_G['setting']['group_imgsizelimit'] * 1024) && showmessage('file_size_overflow', '', array('size' => $_G['setting']['group_imgsizelimit'] * 1024));
	}
	require_once libfile('class/upload');
	$upload = new discuz_upload();
	$uploadtype = $data['status'] == 3 ? 'group' : 'hr';

	if(!$upload->init($file, $uploadtype, $data['extid'], $type)) {
		return false;
	}
	
	if(!$upload->save()) {
		if(!defined('IN_ADMINCP')) {
			showmessage($upload->errormessage());
		} else {
			cpmsg($upload->errormessage(), '', 'error');
		}
	}
	if($data['status'] == 3 && $type == 'icon') {
		require_once libfile('class/image');
		$img = new image;
		$img->Thumb($upload->attach['target'], './'.$uploadtype.'/'.$upload->attach['attachment'], 48, 48, 'fixwr');
	}
	
	return $upload->attach['attachment'];
}

include template('diy:hr/'.$modidentifier.'_resume');

?>