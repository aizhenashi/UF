<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: moderate_member.php 20814 2011-03-04 08:03:12Z liulanbo $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!submitcheck('resumesubmit')) {
	
	$shownum = 50;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $shownum;
	$resumenum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_recruitment')." where verify=0");
	$multipage = multi($resumenum, $shownum, $page, "?action=hr&operation=resume&do=job");
	
	$query = DB::query("SELECT * from ".DB::table('hr_recruitment')." where verify=0 LIMIT $start_limit, $shownum");
		
		
	while($resume = DB::fetch($query)) {
	$mem=getuserbyuid($resume['uid']);
	$resume[username]=$mem['username'];
		$resume['posttime'] = dgmdate($resume['posttime'], 'Y-m-d H:i');
		if($resume['verify'] == 0){
			$verifyresult = $lang['hr_no'];
		}else{
			$verifyresult = $lang['hr_yes'];
		}
		if($resume['available'] == 0){
			$availableresult = $lang['hr_no'];
		}else{
			$availableresult = $lang['hr_yes'];
		}
		if($resume['recommend'] == 0){
			$recommendresult = $lang['hr_no'];
		}else{
			$recommendresult = $lang['hr_yes'];
		}
		if(!empty($resume['title'])) {
			$resumelist .= showtablerow('', array('', 'class="td28"', '', '', ''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$resume[id]]\" value=\"$resume[id]\">",
				" $resume[title] ",
				"$resume[username]",
				"$resume[posttime]",
				"$verifyresult",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"verify[$resume[id]]\" value=\"$resume[id]\">",
				"<a href=\"jobs.php?mod=view&&id=$resume[id]\" target=\"_blank\">$lang[detail]</a>",
			), TRUE);
		}
	}

	echo <<<EOT
EOT;
	shownav('job', '工作管理');
	showsubmenu('工作管理', array(
		array('工作管理', 'hr&operation=resume&do=job'),
		array('工作审核', 'hr&operation=resumeverify&do=job', 1),
	));
	showformheader('hr&operation=resumeverify&type=does&do='.$do);
	showtableheader('menu_hr_resume', 'fixpadding', 'id="resumelist"');
	showsubtitle(array('del', '招聘名称', '发布人员', '发布时间', '是否审核', '通过审核'));
	echo $resumelist;
	showsubmit('resumesubmit', 'submit', 'del', '', $multipage);
	showtablefooter();
	showformfooter();

}  else {

	if($_GET['type'] == 'does') {

		if($ids = dimplode($_GET['delete'])) {
			DB::query("DELETE FROM ".DB::table('hr_recruitment')." WHERE id IN ($ids)");
		}

		if($ids = dimplode($_GET['verify'])) {
			DB::update("hr_recruitment",array('verify' => 1), "id IN ($ids)");
		}
	}
		
		
	cpmsg(cplang('update_success'), 'action=hr&operation=resumeverify&do='.$do, 'succeed');

}

?>