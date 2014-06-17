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
	
	$shownum = 20;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $shownum;
	$atype=!empty($_GET['atype'])?$_GET['atype']:'1';
	$resumenum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('user_cooperation')." where cooperation_type='$atype' ");
	$multipage = multi($resumenum, $shownum, $page, "?action=hr&operation=invite&atype=$atype&do=job");
	
	$query = DB::query("SELECT * FROM ".DB::table('user_cooperation')."  where cooperation_type='$atype'  LIMIT $start_limit, $shownum");
		
	while($resume = DB::fetch($query)) {
	//print_r($resume);
	$cooperation=getuserbyuid($resume['cooperation_uid']);
	$invite=getuserbyuid($resume['invite_uid']);
	$resume[co_username]=$cooperation['username'];
	$resume[invite_username]=$invite['username'];
	
		$resume['post_time'] = dgmdate($resume['post_time'], 'Y-m-d H:i');
		if($resume['read_flag'] == 0){
			$readresult = $lang['hr_no'];
		}else{
			$readresult = $lang['hr_yes'];
		}
		if($resume['agree_flag'] == 0){
			$agreeresult = $lang['hr_no'];
		}else{
			$agreeresult = $lang['hr_yes'];
		}
		if($resume['recommend'] == 0){
			$recommendresult = $lang['hr_no'];
		}else{
			$recommendresult = $lang['hr_yes'];
		}
		if(!empty($resume['id'])) {
			$resumelist .= showtablerow('', array('', 'class="td28"', '', '', ''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$resume[id]]\" value=\"$resume[id]\">",
				"$resume[co_username] ",
				"$resume[invite_username]",
				"$resume[post_time]",
				"$resume[cooperation_content]",
				"$readresult",
				"$agreeresult"
			), TRUE);
		}
	}

	echo <<<EOT
EOT;
	shownav('job', '合作邀请');
	showsubmenu('合作邀请', array(
		array('面试邀请', 'hr&operation=invite&atype=1&do=job', 1),
		array('职位申请', 'hr&operation=invite&atype=2&do=job',1),
	));
	showformheader('hr&operation=invite&type=does&do='.$do);
	showtableheader('合作邀请', 'fixpadding', 'id="resumelist"');
	showsubtitle(array('删除', '邀请者', '被邀请人', '发布时间', '合作内容','是否已读', '是否合作'));
	echo $resumelist;
	showsubmit('resumesubmit', 'submit', 'del', '', $multipage);
	showtablefooter();
	showformfooter();

} else {

	if($_GET['type'] == 'does') {

		if($ids = dimplode($_GET['delete'])) {
			DB::query("DELETE FROM ".DB::table('user_cooperation')." WHERE id IN ($ids)");
		}
	}

	cpmsg(cplang('update_success'), 'action=hr&operation=invite&do='.$do, 'succeed');

}

?>