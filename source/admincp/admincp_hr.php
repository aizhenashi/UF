<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_threadtypes.php 7241 2010-03-31 08:13:42Z tiger $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();

@include_once DISCUZ_ROOT.'./source/module/hr/job/job_version.php';

$doarray = array('job');
$do = in_array($_GET['do'], $doarray) ? $_GET['do'] : '';

if($do = 'job') {
	$cid = 1;
	$classid = 2;
	$modurl = 'job.php';
}

$classoptionmenu = array(
	array('broker_mod_company', ($_GET['classid'] != 1 ? "hr&operation=userverify&classid=1" : ''), $_GET['classid'] == 1),
	array('broker_mod_member', ($_GET['classid'] != 2 ? "hr&operation=userverify&classid=2" : ''), $_GET['classid'] == 2)
);
$curclassname = isset($_GET['classid']) ? $classoptionmenu[$_GET['classid'] - 1][0] : '';

require_once libfile('function/hr');

//由于其他管理模块也可能用到channel中的配置信息，所以这里移出到 channel管理功能外
$channel = DB::fetch_first("SELECT * FROM ".DB::table('hr_channel')."");

if($operation == 'index') {
		$threadcount = DB::result_first("SELECT count(*) FROM ".DB::table('hr_job_thread'));
		$resumecount = DB::result_first("SELECT count(*) FROM ".DB::table('hr_resume'));
		$resumevcount = DB::result_first("SELECT count(*) FROM ".DB::table('hr_resume')." WHERE verify ='1'");

		shownav( "job", "menu_hr_index" );
		showsubmenu( "menu_hr_index");
		showtableheader( "hr_index_basic", "fixpadding" );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\"",
				"class=\"lineheight smallfont\""
		), array(
				cplang( "hr_index_version" ),
				$lang['mod_name_job']." V".JOB_VERSION."(R".JOB_RELEASE.") - <a href=\"http://www.kuozhan.net/mod.php?mod=product&productid=2\" target=\"_blank\">检查更新</a> - <a href=\"http://www.kuozhan.net/plugin.php?id=kz_donate:donate\" target=\"_blank\">捐助开发</a>",
		) );
		showtablefooter( );
		showtableheader( "hr_index_site", "fixpadding" );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\"",
				"class=\"lineheight smallfont\""
		), array(
				cplang( "hr_index_site" ),
				"本站已有信息 <b>".$threadcount."</b> 条，简历 <b>".$resumecount."</b> 份（其中已审核 <b>".$resumevcount."</b> 份）"
		) );
		showtablefooter( );
		showtableheader( "hr_index_dev", "fixpadding" );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\""
		), array(
				cplang( "hr_index_copyright" ),
				"<span class=\"bold\"><a href=\"http://www.kuozhan.net\" class=\"lightlink2\" target=\"_blank\">Discuz&#x6269;&#x5C55;&#x4E2D;&#x5FC3;&#xFF08;&#x676D;&#x5DDE;&#x5BCC;&#x8FEA;&#x6587;&#x5316;&#x827A;&#x672F;&#x7B56;&#x5212;&#x6709;&#x9650;&#x516C;&#x53F8;&#xFF09;</a> | <a href=\"http://www.kuozhan.net/plugin.php?id=kz_donate:donate\" target=\"_blank\">捐助开发</a></span>"
		) );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\"",
				"class=\"lineheight smallfont team\""
		), array(
				cplang( "hr_index_design" ),
				"<a href=\"http://www.kuozhan.net/home.php?mod=space&uid=1\" class=\"lightlink2 smallfont\" target=\"_blank\">湖中沉</a>"
		) );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\"",
				"class=\"lineheight smallfont team\""
		), array(
				cplang( "hr_index_develop" ),
				"<a href=\"http://www.kuozhan.net/home.php?mod=space&uid=1\" class=\"lightlink2 smallfont\" target=\"_blank\">湖中沉</a> <a href=\"http://www.kuozhan.net/home.php?mod=space&uid=77\" class=\"lightlink2 smallfont\" target=\"_blank\">猫咪困了</a> <a href=\"http://www.kuozhan.net/home.php?mod=space&uid=1482\" class=\"lightlink2 smallfont\" target=\"_blank\">管理员</a>"
		) );
		showtablerow( "", array(
				"class=\"vtop td24 lineheight\"",
				"class=\"lineheight smallfont team\""
		), array(
				cplang( "hr_index_moreproduct" ),
				"<a href=\"http://www.kuozhan.net/mod.php?mod=product\" class=\"lightlink2 smallfont\" target=\"_blank\">点击查看更多产品</a>"
		) );
		showtablefooter( );
		echo '<div id="boardnews"></div>';
		echo '<style>.rssbook{margin:8px 0 0 5px;}</style>';
		echo '<script >var nId = "2dc91de30cc8e3d21a839f47e6763ddf978ea6d36a523459",nWidth="500px",sColor="light",sText="'.cplang('hr_index_email').'" ;</script><script src="http://list.qq.com/zh_CN/htmledition/js/qf/page/qfcode.js" charset="gb18030"></script>';

} elseif($operation == 'channel') {

	if(!submitcheck('channelsubmit')) {

		$channel['managegid'] = $channel['managegid'] ? unserialize($channel['managegid']) : array();
		$channel['mapinfo'] = $channel['mapinfo'] ? unserialize($channel['mapinfo']) : array();
		$channel['seoinfo'] = $channel['seoinfo'] ? unserialize($channel['seoinfo']) : array();

		shownav('job', 'hr_channel');
		showsubmenu('hr_channel');

		showformheader("hr&operation=channel&do=$do", 'enctype');
		showtableheader();
		showtitle($channel['title'].cplang('hr_channel'));
		showsetting('hr_channel_title', 'titlenew', $channel['title'], 'text');
		showsetting('hr_channel_open', 'statusnew', $channel['status'], 'radio');
		showsetting('hr_channel_indexsearchopen', 'indexsearchstatusnew', $channel['indexsearchstatus'], 'radio');
		showsetting('hr_channel_visitorpost', 'visitorpostnew', $channel['visitorpost'], 'radio');
		showsetting('hr_channel_pull', 'pullsetnew', $channel['pullset'], 'radio');
		showsetting('hr_channel_listmode', array('listmodenew', array(
			array('text', cplang('hr_channel_listmode_text')),
			array('pic', cplang('hr_channel_listmode_pic')))), $channel['listmode'], 'mradio');
		showtitle(cplang('hr_option_usergroup'));
		$varname = array('newmanagegid', array(), 'isfloat');
		$query = DB::query("SELECT groupid, grouptitle FROM ".DB::table('common_usergroup')." WHERE radminid IN('1','2') ORDER BY groupid");
		while($ugroup = DB::fetch($query)) {
			$varname[1][] = array($ugroup['groupid'], $ugroup['grouptitle'], '1');
		}
		showsetting('hr_option_usergroup', $varname, $channel['managegid'], 'omcheckbox');
		showtitle('hr_mapset');
		showsetting('hr_channel_mapkey', 'mapinfo[key]', $channel['mapinfo']['key'], 'text');

		showtableheader();
		showtitle('setting_seo');
		showsetting('setting_seo_seokeywords', 'seoinfo[seokeywords]', $channel['seoinfo']['seokeywords'], 'text');
		showsetting('setting_seo_seodescription', 'seoinfo[seodescription]', $channel['seoinfo']['seodescription'], 'text');

		showsubmit('channelsubmit');
		showtablefooter();
		showformfooter();
		updateinformation($cid, $do);

	} else {


		$_GET['mapinfo']['key'] = dhtmlspecialchars(trim($_GET['mapinfo']['key']));
		$mapinfo = serialize($_GET['mapinfo']);

		$_GET['seoinfo']['seokeywords'] = !empty($_GET['seoinfo']['seokeywords']) ? dhtmlspecialchars(trim($_GET['seoinfo']['seokeywords'])) : '';
		$_GET['seoinfo']['seodescription'] = !empty($_GET['seoinfo']['seodescription']) ? dhtmlspecialchars(trim($_GET['seoinfo']['seodescription'])) : '';
		$seoinfo = serialize($_GET['seoinfo']);

		DB::update('hr_channel', array(
			'title' => dhtmlspecialchars(trim($_GET['titlenew'])),
			'status' => intval($_GET['statusnew']),
			'indexsearchstatus' => intval($_GET['indexsearchstatusnew']),
			'pullset' => intval($_GET['pullsetnew']),
			'visitorpost' => intval($_GET['visitorpostnew']),
			'mapinfo' => $mapinfo,
			'listmode' => $_GET['listmodenew'],
			'managegid' => serialize($_GET['newmanagegid']),
			'seoinfo' => $seoinfo
		), "cid='$cid'");

		hrcache('channellist');
		cpmsg('threadtype_infotypes_option_succeed', 'action=hr&operation=channel&do='.$do, 'succeed');

	}

} elseif($operation == 'area') {

	if(!submitcheck('editsubmit')) {
?>
<script type="text/JavaScript">
var rowtypedata = [
	[[1,'',''], [1,'<input type="text" class="txt" name="newcityorder[]" value="0" />', 'td25'], [3, "<input name=newcity[] value='<?php echo cplang('city_name')?>' size='20' type='text' class='txt' />"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="newdistrictorder[{1}][]" value="0" />', 'td25'], [3, "<div class='board'><input name='newdistrict[{1}][]' value='<?php echo cplang('province_name')?>' size='20' type='text' class='txt' /></div>"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="newstreetorder[{1}][]" value="0" />', 'td25'], [3, "<div class='childboard'><input name='newstreet[{1}][]' value='<?php echo cplang('street_name')?>' size='20' type='text' class='txt' /></div>"]],
];
</script>
<?php
		shownav('job', 'hr_area');
		showsubmenu('hr_area');
		showformheader('hr&operation=area&do='.$do);
		showtableheader('');
		showsubtitle(array('del', 'display_order', cplang('class_name')));

		$citylist = $districtlist = $streetlist = array();
		$addcid = $_GET['cid'] ? "WHERE cid='$cid'" : '';
		$query = DB::query("SELECT aid, aup, cid, type, title, displayorder FROM ".DB::table('hr_area')." $addcid ORDER BY displayorder");
		while($area = DB::fetch($query)) {
			if($area['type'] == 'city') {
				$citylist[$area['aid']] = $area;
			} elseif($area['type'] == 'district') {
				$districtlist[$area['aup']][] = $area;
			} elseif($area['type'] == 'street') {
				$streetlist[$area['aup']][] = $area;
			}
		}

		foreach($citylist as $aid => $city) {
			showhr($city, 'city');
			if(!empty($districtlist[$aid])) {
				foreach ($districtlist[$aid] as $district) {
					showhr($district);
					$lastaid = 0;
					if(!empty($streetlist[$district['aid']])) {
						foreach ($streetlist[$district['aid']] as $street) {
							showhr($street, 'street');
							$lastaid = $street['aid'];
						}
					}
					showhr($district, $lastaid, 'lastchildboard');
				}
			}
			showhr($city, '', 'lastboard');
		}

		showhr($city, '', 'last');

		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {

		if($_GET['delete']) {
			foreach($_GET['delete'] as $aid) {
				$subaid = DB::result_first("SELECT aid FROM ".DB::table('hr_area')." WHERE aup='$aid'");
				if($subaid) {
					cpmsg(cplang('delete_tips'), '', 'error');
				} else {
					DB::query("DELETE FROM ".DB::table('hr_area')." WHERE aid='$aid'");
				}
			}
		}

		if($_GET['name']) {
			foreach($_GET['name'] as $aid => $name) {
				DB::update('hr_area', array(
					'title' => dhtmlspecialchars(trim($name)),
					'displayorder' => intval($_GET['order'][$aid])
				), "aid='$aid'");
			}
		}

		if($_GET['newcity']) {
			foreach($_GET['newcity'] as $aid => $city) {
				DB::insert('hr_area', array('type' => 'city', 'aup' => '0', 'title' => dhtmlspecialchars(trim($city)), 'cid' => $cid, 'displayorder' => intval($_GET['newcityorder'][$aid])));
			}
		}

		if($_GET['newdistrict']) {
			foreach($_GET['newdistrict'] as $aup => $districts) {
				foreach($districts as $aid => $district) {
					DB::insert('hr_area', array('type' => 'district', 'aup' => $aup, 'title' => dhtmlspecialchars(trim($district)), 'cid' => $cid, 'displayorder' => intval($_GET['newdistrictorder'][$aid])));
				}
			}
		}

		if($_GET['newstreet']) {
			foreach($_GET['newstreet'] as $aup => $streets) {
				foreach($streets as $aid => $street) {
					DB::insert('hr_area', array('type' => 'street', 'aup' => $aup, 'title' => dhtmlspecialchars(trim($street)), 'cid' => $cid, 'displayorder' => intval($_GET['newstreetorder'][$aid])));
				}
			}
		}

		hrcache('arealist');
		cpmsg(cplang('region_update_success'), 'action=hr&operation=area&do='.$do, 'succeed');
	}
}
elseif($operation == 'activitysort') {//活动分类

	if(!submitcheck('editsubmit')) {
?>
<script type="text/JavaScript">
var rowtypedata = [
	[[1,'',''], [1,'<input type="text" class="txt" name="firsttypeorder[]" value="0" />', 'td25'], [3, "<input name=firsttype[] value='一级分类' size='20' type='text' class='txt' />"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="secondtypeorder[{1}][]" value="0" />', 'td25'], [3, "<div class='board'><input name='secondtype[{1}][]' value='二级分类' size='20' type='text' class='txt' /></div>"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="thirdtpyeorder[{1}][]" value="0" />', 'td25'], [3, "<div class='childboard'><input name='thirdtpye[{1}][]' value='三级分类' size='20' type='text' class='txt' /></div>"]],
];
</script>
<?php
		shownav('job', '活动管理');
		showsubmenu('活动分类&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?action=hr&operation=activityverify&do=job">活动审核</a>');
		showformheader('hr&operation=activitysort&do='.$do);
		showtableheader('');
		showsubtitle(array('del', 'display_order', cplang('class_name')));

		$typelist = $districtlist = $streetlist = array();
		$addcid = $_GET['sid'] ? "WHERE sid='$sid'" : '';
		$query = DB::query("SELECT * FROM ".DB::table('activity_sort')." $addcid ORDER BY displayorder");
		while($area = DB::fetch($query)) {
			if($area['rank'] == '1') {
				$firsttype[$area['sid']] = $area;
			} elseif($area['rank'] == '2') {
				$secondtype[$area['fsid']][] = $area;
			} elseif($area['rank'] == '3') {
				$thirdtpye[$area['fsid']][] = $area;
			}
		}
//print_r($firsttype);
//print_r($secondtype);
//print_r($thirdtpye);
		foreach($firsttype as $sid => $firsttypes) {
			showsort($firsttypes, $firsttypes['rank']);
			if(!empty($secondtype[$sid])) {
				foreach ($secondtype[$sid] as $secondtypes) {
					showsort($secondtypes,$secondtypes['rank']);
					$lastaid = 0;
					if(!empty($thirdtpye[$secondtypes['sid']])) {
						foreach ($thirdtpye[$secondtypes['sid']] as $thirdtpyes) {
							showsort($thirdtpyes,$thirdtpyes['rank']);
							$lastaid = $thirdtpye['sid'];
						}
					}
					showsort($thirdtpye, $lastaid, 'lastchildboard');
				}
			}
			
			showsort($firsttypes, '', 'lastboard');
		}

		showsort($firsttypes, '', 'last');

		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {
		if($_GET['delete']) {
			foreach($_GET['delete'] as $sid) {
				$subaid = DB::result_first("SELECT sid FROM ".DB::table('activity_sort')." WHERE fsid='$sid'");
				if($subaid) {
					cpmsg(cplang('delete_tips'), '', 'error');
				} else {
					DB::query("DELETE FROM ".DB::table('activity_sort')." WHERE sid='$sid'");
				}
			}
		}

		if($_GET['name']) {
			foreach($_GET['name'] as $sid => $sname) {
				DB::update('activity_sort', array(
					'sname' => dhtmlspecialchars(trim($sname)),
					'displayorder' => intval($_GET['order'][$sid])
				), "sid='$sid'");
			}
		}

		if($_GET['firsttype']) {
		
			foreach($_GET['firsttype'] as $sid => $sname) {
				DB::insert('activity_sort', array('type' => $sid, 'fsid' => 0, 'sname' =>  dhtmlspecialchars(trim($sname)), 'rank'=>'1','displayorder' => intval($_GET['firsttypeorder'][$sid])));
			}
		}

		if($_GET['secondtype']) {
			foreach($_GET['secondtype'] as $fsid => $secondtypes) {
				foreach($secondtypes as $sid => $sname) {
					DB::insert('activity_sort', array('type' => $sid , 'fsid' => $fsid, 'sname' => dhtmlspecialchars(trim($sname)),'rank'=>'2', 'displayorder' => intval($_GET['secondtypeorder'][$sid])));
				}
			}
		}

		if($_GET['thirdtpye']) {
			foreach($_GET['thirdtpye'] as $fsid => $thirdtpyes) {
				foreach($thirdtpyes as $sid => $sname) {
					DB::insert('activity_sort', array('type' =>$sid, 'fsid' => $fsid, 'sname' => dhtmlspecialchars(trim($sname)), 'rank' => '3', 'displayorder' => intval($_GET['thirdtpyeorder'][$sid])));
				}
			}
		}

		hrcache('typelist');
		cpmsg("类型更新成功", 'action=hr&operation=activitysort&do='.$do, 'succeed');
	}
}elseif($operation == 'activityverify') {//审核

	$verify=isset($_GET['verify'])?$_GET['verify']:'';
	$id=isset($_GET['id'])?$_GET['id']:'';
	if($verify=="yes" and $id<>'') {
		DB::update('activity_content', array('verify' => 1), "id='$id'");
		cpmsg("审核完成", 'action=hr&operation=activityverify&do='.$do, 'succeed');
	}
	if($verify=="cancel" and $id<>'') {
		DB::update('activity_content', array('verify' => 0), "id='$id'");
		cpmsg("取消审核完成", 'action=hr&operation=activityverify&do='.$do, 'succeed');
	}
	
	if(!submitcheck('delsubmit')) {//显示

		$content = '';
		$tb_ac_content="`".DB::table('activity_content')."`";
		$tb_ac_sort="`".DB::table('activity_sort')."`";
		$tb_member="`".DB::table('common_member')."`";
		$sql="SELECT ".$tb_ac_content.".*,".$tb_ac_sort.".`sid`,".$tb_ac_sort.".`sname`,".$tb_member.".`uid`,".$tb_member.".`username` FROM ".$tb_ac_content.",".$tb_ac_sort.",".$tb_member." where ".$tb_ac_sort.".`sid`=".$tb_ac_content.".`sid` and ".$tb_member.".`uid`=".$tb_ac_content.".`uid` order by ".$tb_ac_content.".`verify` asc , ".$tb_ac_content.".`id` desc";
		$query = DB::query($sql);
		while($content = DB::fetch($query)) {
			$contents .= showtablerow('', array('class="td25"', 'class="td28"', '', 'class="td29"', 'class="td29"', 'class="td25"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$content[id]\">",
				$content['username'],
				$content['sname'],
				$content['subject'],
				"<a href=\"#\" class=\"act nowrap\">详情</a>",
				($content['verify']==1?"<a href=\"admin.php?action=hr&operation=activityverify&do=job&verify=cancel&id=$content[id]\" class=\"act nowrap\" title=\"点击取下审核\"><span style=\"color:red\">已审核</span></a>":"<a href=\"admin.php?action=hr&operation=activityverify&do=job&verify=yes&id=$content[id]\" class=\"act nowrap\">审核</a>"),
			), TRUE);
		}

		shownav('job', '活动管理');
		showsubmenu('活动审核&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?action=hr&operation=activitysort&do=job">活动分类</a>');

		showformheader("hr&operation=activityverify&do=$do");
		showtableheader('');
		showsubtitle(array('', '创建者', '分类', '活动主题', '详情','审核'));
		echo $contents;

		showsubmit('delsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		$updatefids = $modifiedtypes = array();

		if(is_array($_GET['delete'])) {

			if($deleteids = dimplode($_GET['delete'])) {
				DB::query("DELETE FROM ".DB::table('activity_content')." WHERE id IN ($deleteids)");
			}
		}
		cpmsg('删除活动记录完成', 'action=hr&operation=activityverify&do='.$do, 'succeed');
	}
}//活动管理End
 elseif($operation == 'type') {

	if(!submitcheck('editsubmit')) {
?>
<script type="text/JavaScript">
var rowtypedata = [
	[[1,'',''], [1,'<input type="text" class="txt" name="firsttypeorder[]" value="0" />', 'td25'], [3, "<input name=firsttype[] value='一级类型' size='20' type='text' class='txt' />"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="secondtypeorder[{1}][]" value="0" />', 'td25'], [3, "<div class='board'><input name='secondtype[{1}][]' value='二级分类' size='20' type='text' class='txt' /></div>"]],
	[[1,'',''], [1,'<input type="text" class="txt" name="thirdtpyeorder[{1}][]" value="0" />', 'td25'], [3, "<div class='childboard'><input name='thirdtpye[{1}][]' value='三级分类' size='20' type='text' class='txt' /></div>"]],
];
</script>
<?php
		shownav('job', 'hr_type');
		showsubmenu('hr_type');
		showformheader('hr&operation=type&do='.$do);
		showtableheader('');
		showsubtitle(array('del', 'display_order', cplang('class_name')));

		$typelist = $districtlist = $streetlist = array();
		$addcid = $_GET['id'] ? "WHERE id='$id'" : '';
		$query = DB::query("SELECT id, topid, rank, type, name, displayorder FROM ".DB::table('user_type')." $addcid ORDER BY displayorder");
		while($area = DB::fetch($query)) {
			if($area['rank'] == '1') {
				$firsttype[$area['id']] = $area;
			} elseif($area['rank'] == '2') {
				$secondtype[$area['topid']][] = $area;
			} elseif($area['rank'] == '3') {
				$thirdtpye[$area['topid']][] = $area;
			}
		}
//print_r($firsttype);
//print_r($secondtype);
//print_r($thirdtpye);
		foreach($firsttype as $id => $firsttypes) {
			showtype($firsttypes, $firsttypes['rank']);
			if(!empty($secondtype[$id])) {
				foreach ($secondtype[$id] as $secondtypes) {
					showtype($secondtypes,$secondtypes['rank']);
					$lastaid = 0;
					if(!empty($thirdtpye[$secondtypes['id']])) {
						foreach ($thirdtpye[$secondtypes['id']] as $thirdtpyes) {
							showtype($thirdtpyes,$thirdtpyes['rank']);
							$lastaid = $thirdtpye['id'];
						}
					}
					showtype($thirdtpye, $lastaid, 'lastchildboard');
				}
			}
			
			showtype($firsttypes, '', 'lastboard');
		}

		showtype($firsttypes, '', 'last');

		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {

		if($_GET['delete']) {
			foreach($_GET['delete'] as $id) {
				$subaid = DB::result_first("SELECT id FROM ".DB::table('user_type')." WHERE topid='$id'");
				if($subaid) {
					cpmsg(cplang('delete_tips'), '', 'error');
				} else {
					DB::query("DELETE FROM ".DB::table('user_type')." WHERE id='$id'");
				}
			}
		}

		if($_GET['name']) {
			foreach($_GET['name'] as $id => $name) {
				DB::update('user_type', array(
					'name' => dhtmlspecialchars(trim($name)),
					'displayorder' => intval($_GET['order'][$id])
				), "id='$id'");
			}
		}

		if($_GET['firsttype']) {
		
			foreach($_GET['firsttype'] as $id => $name) {
				DB::insert('user_type', array('type' => $id, 'topid' => 0, 'name' =>  dhtmlspecialchars(trim($name)), 'rank'=>'1','displayorder' => intval($_GET['firsttypeorder'][$id])));
			}
		}

		if($_GET['secondtype']) {
			foreach($_GET['secondtype'] as $topid => $secondtypes) {
				foreach($secondtypes as $id => $name) {
					DB::insert('user_type', array('type' => $id , 'topid' => $topid, 'name' => dhtmlspecialchars(trim($name)),'rank'=>'2', 'displayorder' => intval($_GET['secondtypeorder'][$id])));
				}
			}
		}

		if($_GET['thirdtpye']) {
			foreach($_GET['thirdtpye'] as $topid => $thirdtpyes) {
				foreach($thirdtpyes as $id => $name) {
					DB::insert('user_type', array('type' =>$id, 'topid' => $topid, 'name' => dhtmlspecialchars(trim($name)), 'rank' => '3', 'displayorder' => intval($_GET['thirdtpyeorder'][$id])));
				}
			}
		}

		hrcache('typelist');
		cpmsg("类型更新成功", 'action=hr&operation=type&do='.$do, 'succeed');
	}
}
elseif($operation == 'verify') {
//机构审核
	$uid = isset($_GET['uid'])?addslashes($_GET['uid']):'';
	$cancel = isset($_GET['cancel'])?$_GET['cancel']:'';
	$verify = isset($_GET['verify'])?$_GET['verify']:'0';
	$show = isset($_GET['show'])?$_GET['show']:'';
	if($show=='show'){
		$user_compay = DB::fetch_first("SELECT * FROM ".DB::table('user_company')." where uid=$uid");
			$company='';
			if($user_compay['image']=='') 
			{
				$image='未上传营业执照';
			}else {
				$image ="<img src=\"$user_compay[image]\" />";
			}

		shownav('job', '机构审核');
		showsubmenu('机构详情');
		showtableheader();
		
		echo <<<html
<table class="tb tb2">
<tr onmouseover="setfaq(this, 'faqb068')"><td colspan="2" class="td27" s="1">名称:{$user_compay['cpname']}</td></tr>
<tr onmouseover="setfaq(this, 'faq49eb')"><td colspan="2" class="td27" s="1">营业执照:<br />{$image}</td></tr>
<tr onmouseover="setfaq(this, 'faq49eb')"><td colspan="2" class="td27" s="1">联系人:<br />{$user_compay['cpuser']}</td></tr>
<tr onmouseover="setfaq(this, 'faq49eb')"><td colspan="2" class="td27" s="1">邮箱:<br />{$user_compay['email']}</td></tr>
<tr onmouseover="setfaq(this, 'faq49eb')"><td colspan="2" class="td27" s="1">公司地址:<br />{$user_compay['address']}</td></tr>
<tr onmouseover="setfaq(this, 'faq49eb')"><td colspan="2" class="td27" s="1">公司简介:<br />{$user_compay['introduce']}</td></tr>
</table>

html;
		showtablefooter();
		showformfooter();
		exit;
	}
	if($uid=='') {

			$company = '';
			if($verify=='1') {
				$query = DB::query("SELECT * FROM ".DB::table('user_company')." where verify=1");
			}else{
				$query = DB::query("SELECT * FROM ".DB::table('user_company')." where verify=0");
			}
			
			while($user_compay = DB::fetch($query)) {
			if($user_compay['image']=='') 
			{
				$image='未上传营业执照';
			}else {
				$image ="<a href=\"$user_compay[image]\" class=\"act\" target=\"_blank\">查看</a>";
			}
			$verify = $user_compay['verify']?'通过审核':'未审核';
				$company .= showtablerow('', array('class="td28"', 'class="td28"'), array(
					$user_compay['cpname'],
					$image,
					$verify,
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=verify&uid=$user_compay[uid]\" class=\"act\">审核</a>
					<a href=\"".ADMINSCRIPT."?action=hr&operation=verify&uid=$user_compay[uid]&cancel=yes\" class=\"act\">取消</a>",
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=verify&uid=$user_compay[uid]&show=show\" class=\"act\">详情</a>"
				), TRUE);
			}
			
		shownav('job', '机构审核');
		showsubmenu('<a href="'.ADMINSCRIPT.'?action=hr&operation=verify&verify=0">待审机构</a> <a href="'.ADMINSCRIPT.'?action=hr&operation=verify&verify=1">已审机构</a>');
		showtableheader();

		showsubtitle(array('机构名称', '营业执照', '审核状态','审核操作', '详情'));
		echo $company;
		showtablefooter();
		showformfooter();
		}else{
			if($cancel=='yes') {
			DB::update('user_company', array(
					'verify' => 0
				), "uid='$uid'");
				cpmsg('取消审核完成。', 'action=hr&operation=verify&do='.$do, 'succeed');
			
			}else{
				DB::update('user_company', array(
					'verify' => 1
				), "uid='$uid'");
				cpmsg('审核通过。', 'action=hr&operation=verify&do='.$do, 'succeed');
			}
		}
///机构审核完毕
}  
elseif($operation == 'sort') {

	if(!submitcheck('sortsubmit')) {

		$sorts = '';
		$query = DB::query("SELECT * FROM ".DB::table('hr_sort')." WHERE cid='$cid' ORDER BY displayorder");
		while($sort = DB::fetch($query)) {
			$sorts .= showtablerow('', array('class="td25"', 'class="td28"', '', 'class="td29"', 'class="td29"', 'class="td25"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$sort[sortid]\">",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$sort[sortid]]\" value=\"$sort[displayorder]\">",
				"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$sort[sortid]]\" value=\"".dhtmlspecialchars($sort['name'])."\">",
				"<input type=\"text\" class=\"txt\" size=\"20\" name=\"keywordsnew[$sort[sortid]]\" value=\"$sort[keywords]\">",
				"<input type=\"text\" class=\"txt\" size=\"30\" name=\"descriptionnew[$sort[sortid]]\" value=\"$sort[description]\">",
				"<a href=\"".ADMINSCRIPT."?action=hr&operation=sortdetail&do=$do&sortid=$sort[sortid]\" class=\"act nowrap\">$lang[detail]</a>"
			), TRUE);
		}

?>
<script type="text/JavaScript">
var rowtypedata = [
	[
		[1, '', 'td25'],
		[1, '<input type="text" class="txt" name="newdisplayorder[]" size="2" value="">', 'td28'],
		[1, '<input type="text" class="txt" name="newname[]" size="15">'],
		[1, '<input type="text" class="txt" name="newkeywords[]" size="20" value="">', 'td29'],
		[1, '<input type="text" class="txt" name="newdescription[]" size="30" value="">', 'td29'],
		[2, '']
	],
];
</script>
<?php
		shownav('job', 'hr_sort');
		showsubmenu('hr_sort');

		showformheader("hr&operation=sort&do=$do");
		showtableheader('');
		showsubtitle(array('', 'display_order', 'name', 'keywords', 'description', ''));
		echo $sorts;
		echo '<tr><td class="td25"></td><td colspan="5"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['threadtype_infotypes_add'].'</a></div></td>';

		showsubmit('sortsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		$updatefids = $modifiedtypes = array();

		if(is_array($_GET['delete'])) {

			if($deleteids = dimplode($_GET['delete'])) {
				DB::query("DELETE FROM ".DB::table('hr_sortoptionvar')." WHERE sortid IN ($deleteids)");
				DB::query("DELETE FROM ".DB::table('hr_sortvar')." WHERE sortid IN ($deleteids)");
				DB::query("DELETE FROM ".DB::table('hr_sort')." WHERE sortid IN ($deleteids)");
			}

			foreach($_GET['delete'] as $sortid) {
				DB::query("DROP TABLE IF EXISTS ".DB::table('hr_sortvalue')."{$sortid}");
			}

		}

		if(is_array($_GET['namenew']) && $_GET['namenew']) {
			foreach($_GET['namenew'] as $sortid => $val) {
				DB::update('hr_sort', array(
					'name' => trim($_GET['namenew'][$sortid]),
					'keywords' => dhtmlspecialchars(trim($_GET['keywordsnew'][$sortid])),
					'description' => dhtmlspecialchars(trim($_GET['descriptionnew'][$sortid])),
					'displayorder' => $_GET['displayordernew'][$sortid],
					'cid' => $cid,
				), "sortid='$sortid'");
			}
		}

		if(is_array($_GET['newname'])) {
			foreach($_GET['newname'] as $key => $value) {
				if($newname1 = trim($value)) {
					$query = DB::query("SELECT sortid FROM ".DB::table('hr_sort')." WHERE name='$newname1'");
					if(DB::num_rows($query)) {
						cpmsg('forums_threadtypes_duplicate', '', 'error');
					}
					$data = array(
						'name' => $newname1,
						'description' => dhtmlspecialchars(trim($_GET['newdescription'][$key])),
						'displayorder' => $_GET['newdisplayorder'][$key],
						'cid' => $cid,
					);
					DB::insert('hr_sort', $data);
				}
			}
		}

		hrcache('sortlist');
		cpmsg('forums_threadtypes_succeed', 'action=hr&operation=sort&do='.$do, 'succeed');

	}

} elseif($operation == 'option') {

	loadcache('hr_channellist');
	$cidentifier = $do;
	if(!submitcheck('optionsubmit')) {
		if($classid) {
			if(!$typetitle = DB::result_first("SELECT title FROM ".DB::table('hr_sortoption')." WHERE optionid IN ('$classid', 1, 3, 4, 5)")) {
				cpmsg('threadtype_infotypes_noexist', 'action=threadtypes', 'error');
			}

			$sortoptions = '';
			$query = DB::query("SELECT * FROM ".DB::table('hr_sortoption')." WHERE classid IN ('$classid', 1, 3, 4, 5) ORDER BY displayorder");
			while($option = DB::fetch($query)) {
				$option['type'] = $lang['threadtype_edit_vars_type_'. $option['type']];
				$sortoptions .= showtablerow('', array('class="td25"', 'class="td28"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$option[optionid]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayorder[$option[optionid]]\" value=\"$option[displayorder]\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"title[$option[optionid]]\" value=\"".dhtmlspecialchars($option['title'])."\">",
					"$option[identifier]<input type=\"hidden\" name=\"identifier[$option[optionid]]\" value=\"$option[identifier]\">",
					$option['type'],
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=optiondetail&optionid=$option[optionid]\" class=\"act\">$lang[detail]</a>"
				), TRUE);
			}
		}

		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[
			[1, '', 'td25'],
			[1, '<input type="text" class="txt" size="2" name="newdisplayorder[]" value="0">', 'td28'],
			[1, '<input type="text" class="txt" size="15" name="newtitle[]">'],
			[1, '{$cidentifier}_<input type="text" class="txt" size="15" name="newidentifier[]">'],
			[1, '<select name="newtype[]"><option value="number">$lang[threadtype_edit_vars_type_number]</option><option value="text" selected>$lang[threadtype_edit_vars_type_text]</option><option value="textarea">$lang[threadtype_edit_vars_type_textarea]</option><option value="radio">$lang[threadtype_edit_vars_type_radio]</option><option value="checkbox">$lang[threadtype_edit_vars_type_checkbox]</option><option value="select">$lang[threadtype_edit_vars_type_select]</option><option value="calendar">$lang[threadtype_edit_vars_type_calendar]</option><option value="email">$lang[threadtype_edit_vars_type_email]</option><option value="image">$lang[threadtype_edit_vars_type_image]</option><option value="url">$lang[threadtype_edit_vars_type_url]</option><option value="info">$lang[threadtype_edit_vars_type_info]</option></select>'],
			[1, '']
		],
	];
</script>
EOT;

		shownav('job', 'hr_option');
		showsubmenu('hr_option');
		showformheader("hr&operation=option&typeid={$_GET['typeid']}&do=$do");
		showhiddenfields(array('classid' => $_GET['classid']));
		showtableheader();

		showsubtitle(array('', 'display_order', 'name', 'threadtype_variable', 'threadtype_type', ''));
		echo $sortoptions;
		echo '<tr><td></td><td colspan="5"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['threadtype_infotypes_add_option'].'</a></div></td></tr>';
		showsubmit('optionsubmit', 'submit', 'del');

		showtablefooter();
		showformfooter();

	} else {

		if($ids = dimplode($_GET['delete'])) {
			DB::query("DELETE FROM ".DB::table('hr_sortoption')." WHERE optionid IN ($ids)");
			DB::query("DELETE FROM ".DB::table('hr_sortvar')." WHERE optionid IN ($ids)");
		}

		if(is_array($_GET['title'])) {
			foreach($_GET['title'] as $id => $val) {
				DB::update('hr_sortoption', array(
					'displayorder' => $_GET['displayorder'][$id],
					'title' => $_GET['title'][$id],
					'identifier' => $_GET['identifier'][$id],
				), "optionid='$id'");
			}
		}

		if(is_array($_GET['newtitle'])) {
			foreach($_GET['newtitle'] as $key => $value) {
				$newtitle1 = dhtmlspecialchars(trim($value));
				$newidentifier1 = trim($_GET['newidentifier'][$key]);
				if($newtitle1 && $newidentifier1) {
					$newidentifier1 = $cidentifier.'_'.$newidentifier1;
					$query = DB::query("SELECT optionid FROM ".DB::table('hr_sortoption')." WHERE identifier='$newidentifier1' LIMIT 1");
					if(DB::num_rows($query) || strlen($newidentifier1) > 40  || !ispluginkey($newidentifier1)) {
						cpmsg('threadtype_infotypes_optionvariable_invalid', '', 'error');
					}
					$data = array(
						'classid' => $classid,
						'displayorder' => $_GET['newdisplayorder'][$key],
						'title' => $newtitle1,
						'identifier' => $newidentifier1,
						'type' => $_GET['newtype'][$key],
					);
					DB::insert('hr_sortoption', $data);
				} elseif($newtitle1 && !$newidentifier1) {
					cpmsg('threadtype_infotypes_option_invalid', 'action=hr&operation=option&classid='.$_GET['classid'], 'error');
				}
			}
		}
		hrcache('hrsort');
		cpmsg('threadtype_infotypes_succeed', 'action=hr&operation=option&classid='.$_GET['classid'], 'succeed');

	}

} elseif($operation == 'optiondetail') {//note 分类信息选项详情

	$option = DB::fetch_first("SELECT * FROM ".DB::table('hr_sortoption')." WHERE optionid='{$_GET['optionid']}'");
	if(!$option) {
		cpmsg('undefined_action', '', 'error');
	}

	if(!submitcheck('editsubmit')) {

		shownav('job', 'hr_option');
		showsubmenu('hr_option');

		$typeselect = '<select name="typenew" onchange="var styles, key;styles=new Array(\'number\',\'text\',\'radio\', \'checkbox\', \'textarea\', \'select\', \'calendar\', \'range\', \'phone\', \'intermediary\'); for(key in styles) {var obj=$(\'style_\'+styles[key]); obj.style.display = styles[key] == this.options[this.selectedIndex].value ? \'\' : \'none\';}">';
		foreach(array('number', 'text', 'radio', 'checkbox', 'textarea', 'select', 'calendar', 'email', 'url', 'range', 'phone', 'intermediary') as $type) {
			$typeselect .= '<option value="'.$type.'" '.($option['type'] == $type ? 'selected' : '').'>'.$lang['threadtype_edit_vars_type_'.$type].'</option>';
		}
		$typeselect .= '</select>';

		$option['rules'] = unserialize($option['rules']);
		$option['protect'] = unserialize($option['protect']);

		$groups = array(array(0, cplang('no_limit')));
		$query = DB::query("SELECT groupid, grouptitle FROM ".DB::table('common_usergroup')."");
		while($group = DB::fetch($query)) {
			$groups[] = array($group['groupid'], $group['grouptitle']);
		}

		$extcreditarray = array(array(0, cplang('select')));
		foreach($_G['setting']['extcredits'] as $creditid => $extcredit) {
			$extcreditarray[] = array($creditid, $extcredit['title']);
		}

		showformheader("hr&operation=optiondetail&optionid=$_GET[optionid]&do=$do");
		showtableheader();
		showtitle('threadtype_infotypes_option_config');
		showsetting('name', 'titlenew', $option['title'], 'text');
		showsetting('threadtype_variable', 'identifiernew', $option['identifier'], 'text');
		showsetting('type', '', '', $typeselect);
		showsetting('threadtype_edit_desc', 'descriptionnew', $option['description'], 'textarea');
		showsetting('threadtype_unit', 'unitnew', $option['unit'], 'text');
		showsetting('threadtype_expiration', 'expirationnew', $option['expiration'], 'radio');
		if(in_array($option['type'], array('calendar', 'number', 'text', 'phone'))) {
			showsetting('threadtype_protect', 'protectnew[status]', $option['protect']['status'], 'radio', 0, 1);
			showsetting('threadtype_protect_mode', array('protectnew[mode]', array(
				array(1, $lang['threadtype_protect_mode_pic']),
				array(2, $lang['threadtype_protect_mode_html']),
				array(3, $lang['threadtype_protect_mode_usergroup']),
				array(4, $lang['threadtype_protect_mode_credits'])
			)), $option['protect']['mode'], 'mradio');
			showsetting('threadtype_add_extcredit', array('protectnew[credits][title]', $extcreditarray), $option['protect']['credits']['title'], 'select');
			showsetting('threadtype_price_extcredit', 'protectnew[credits][price]', $option['protect']['credits']['price'], 'text');
			showsetting('threadtype_protect_usergroup', array('protectnew[usergroup][]', $groups), explode("\t", $option['protect']['usergroup']), 'mselect');
		}

		showtagheader('tbody', "style_calendar", $option['type'] == 'calendar');
		showtitle('threadtype_edit_vars_type_calendar');
		showsetting('threadtype_edit_inputsize', 'rules[calendar][inputsize]', $option['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_number", $option['type'] == 'number');
		showtitle('threadtype_edit_vars_type_number');
		showsetting('threadtype_edit_maxnum', 'rules[number][maxnum]', $option['rules']['maxnum'], 'text');
		showsetting('threadtype_edit_minnum', 'rules[number][minnum]', $option['rules']['minnum'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[number][inputsize]', $option['rules']['inputsize'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[number][defaultvalue]', $option['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_text", $option['type'] == 'text');
		showtitle('threadtype_edit_vars_type_text');
		showsetting('threadtype_edit_textmax', 'rules[text][maxlength]', $option['rules']['maxlength'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[text][inputsize]', $option['rules']['inputsize'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[text][defaultvalue]', $option['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_textarea", $option['type'] == 'textarea');
		showtitle('threadtype_edit_vars_type_textarea');
		showsetting('threadtype_edit_textmax', 'rules[textarea][maxlength]', $option['rules']['maxlength'], 'text');
		showsetting('threadtype_edit_colsize', 'rules[textarea][colsize]', $option['rules']['colsize'], 'text');
		showsetting('threadtype_edit_rowsize', 'rules[textarea][rowsize]', $option['rules']['rowsize'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[textarea][defaultvalue]', $option['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_select", $option['type'] == 'select');
		showtitle('threadtype_edit_vars_type_select');
		showsetting('threadtype_edit_choices', 'rules[select][choices]', $option['rules']['choices'], 'textarea');
		showsetting('threadtype_edit_inputsize', 'rules[select][inputsize]', $option['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_radio", $option['type'] == 'radio');
		showtitle('threadtype_edit_vars_type_radio');
		showsetting('threadtype_edit_choices', 'rules[radio][choices]', $option['rules']['choices'], 'textarea');
		showtagfooter('tbody');

		showtagheader('tbody', "style_checkbox", $option['type'] == 'checkbox');
		showtitle('threadtype_edit_vars_type_checkbox');
		showsetting('threadtype_edit_choices', 'rules[checkbox][choices]', $option['rules']['choices'], 'textarea');
		showtagfooter('tbody');

		showtagheader('tbody', "style_range", $option['type'] == 'range');
		showtitle('threadtype_edit_vars_type_range');
		showsetting('threadtype_edit_maxnum', 'rules[range][maxnum]', $option['rules']['maxnum'], 'text');
		showsetting('threadtype_edit_minnum', 'rules[range][minnum]', $option['rules']['minnum'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[range][inputsize]', $option['rules']['inputsize'], 'text');
		showsetting('threadtype_edit_searchtxt', 'rules[range][searchtxt]', $option['rules']['searchtxt'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[range][defaultvalue]', $option['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_phone", $option['type'] == 'phone');
		showtitle('threadtype_edit_vars_type_phone');
		showsetting('threadtype_edit_numbercheck', 'rules[phone][numbercheck]', $option['rules']['numbercheck'], 'radio');
		showtagfooter('tbody');

		showtagheader('tbody', "style_intermediary", $option['type'] == 'intermediary');
		showtitle('threadtype_edit_vars_type_intermediary');
		showsetting('threadtype_edit_choices', 'rules[intermediary][choices]', $option['rules']['choices'], 'textarea');
		showsetting('threadtype_edit_inputsize', 'rules[intermediary][inputsize]', $option['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {

		$titlenew = trim($_GET['titlenew']);
		if(!$titlenew || !$_GET['identifiernew']) {
			cpmsg('threadtype_infotypes_option_invalid', '', 'error');
		}

		$query = DB::query("SELECT optionid FROM ".DB::table('hr_sortoption')." WHERE identifier='{$_GET['identifiernew']}' AND optionid!='{$_GET['optionid']}' LIMIT 1");
		if(DB::num_rows($query) || strlen($_GET['identifiernew']) > 40  || !ispluginkey($_GET['identifiernew'])) {
			cpmsg('threadtype_infotypes_optionvariable_invalid', '', 'error');
		}

		$_GET['protectnew']['usergroup'] = $_GET['protectnew']['usergroup'] ? implode("\t", $_GET['protectnew']['usergroup']) : '';

		DB::update('hr_sortoption', array(
			'title' => $titlenew,
			'description' => $_GET['descriptionnew'],
			'identifier' => $_GET['identifiernew'],
			'type' => $_GET['typenew'],
			'unit' => $_GET['unitnew'],
			'expiration' => $_GET['expirationnew'],
			'protect' => $_GET['protectnew']['status'] ? serialize($_GET['protectnew']) : 0,
			'rules' => serialize($_GET['rules'][$_GET['typenew']]),
		), "optionid='{$_GET['optionid']}'");

		hrcache('hrsort');
		cpmsg('threadtype_infotypes_option_succeed', 'action=hr&operation=option&classid='.$option['classid'], 'succeed');
	}

} elseif($operation == 'sortdetail') {//note 分类信息类别详情

	$_GET['template'] = $_GET['template'] ? $_GET['template'] : 'basic';
	$templateblock[$_GET['template']] = $_GET['template'] ? 1 : 0;

	if(!submitcheck('sortdetailsubmit') && !submitcheck('sortpreviewsubmit')) {
		$threadtype = DB::fetch_first("SELECT * FROM ".DB::table('hr_sort')." WHERE sortid='{$_GET['sortid']}'");
		$threadtype['btemplate'] = unserialize($threadtype['btemplate']);

		$sortoptions = $jsoptionids = '';
		$showoption = array();
		$query = DB::query("SELECT t.optionid, t.displayorder, t.available, t.required, t.unchangeable, t.search, t.subjectshow, t.visitedshow, t.orderbyshow, tt.title, tt.type, tt.identifier
			FROM ".DB::table('hr_sortvar')." t, ".DB::table('hr_sortoption')." tt
			WHERE t.sortid='{$_GET['sortid']}' AND t.optionid=tt.optionid ORDER BY t.displayorder");
		while($option = DB::fetch($query)) {
			$jsoptionids .= "optionids.push($option[optionid]);\r\n";
			$optiontitle[$option['identifier']] = $option['title'];
			$showoption[$option['optionid']]['optionid'] = $option['optionid'];
			$showoption[$option['optionid']]['title'] = $option['title'];
			$showoption[$option['optionid']]['type'] = $option['type'];
			$showoption[$option['optionid']]['identifier'] = $option['identifier'];
			$showoption[$option['optionid']]['displayorder'] = $option['displayorder'];
			$showoption[$option['optionid']]['available'] = $option['available'];
			$showoption[$option['optionid']]['required'] = $option['required'];
			$showoption[$option['optionid']]['unchangeable'] = $option['unchangeable'];
			$showoption[$option['optionid']]['search'] = $option['search'];
			$showoption[$option['optionid']]['subjectshow'] = $option['subjectshow'];
			$showoption[$option['optionid']]['visitedshow'] = $option['visitedshow'];
			$showoption[$option['optionid']]['orderbyshow'] = $option['orderbyshow'];
		}

		if($existoption && is_array($existoption)) {
			$optionids = $comma = '';
			foreach($existoption as $optionid => $val) {
				$optionids .= $comma.$optionid;
				$comma = '\',\'';
			}
			$query = DB::query("SELECT * FROM ".DB::table('hr_sortoption')." WHERE optionid IN ('$optionids')");
			while($option = DB::fetch($query)) {
				$showoption[$option['optionid']]['optionid'] = $option['optionid'];
				$showoption[$option['optionid']]['title'] = $option['title'];
				$showoption[$option['optionid']]['type'] = $option['type'];
				$showoption[$option['optionid']]['identifier'] = $option['identifier'];
				$showoption[$option['optionid']]['required'] = $existoption[$option['optionid']];
				$showoption[$option['optionid']]['available'] = 1;
				$showoption[$option['optionid']]['unchangeable'] = 0;
				$showoption[$option['optionid']]['model'] = 1;
			}
		}

		$searchtitle = $searchvalue = $searchunit = array();
		foreach($showoption as $optionid => $option) {
			$sortoptions .= showtablerow('id="optionid'.$optionid.'"', array('class="td25"', 'class="td28 td23"', '', 'title="'.$lang['threadtype_edit_vars_type_'. $option['type']].'"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$option[optionid]\">",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayorder[$option[optionid]]\" value=\"$option[displayorder]\">",
				"<input class=\"checkbox\" type=\"checkbox\" id=\"available_$option[identifier]\" name=\"available[$option[optionid]]\" value=\"1\" ".($option['available'] ? 'checked' : '')." ".($option['model'] ? 'disabled' : '').">",
				dhtmlspecialchars($option['title']),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"required[$option[optionid]]\" value=\"1\" ".($option['required'] ? 'checked' : '')." ".($option['model'] ? 'disabled' : '').">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"unchangeable[$option[optionid]]\" value=\"1\" ".($option['unchangeable'] ? 'checked' : '').">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"search[$option[optionid]]\" value=\"1\" ".($option['search'] ? 'checked' : '').">",
				"<input class=\"checkbox\" type=\"checkbox\" id=\"subject_$option[identifier]\" name=\"subjectshow[$option[optionid]]\" value=\"1\" ".($option['subjectshow'] ? 'checked' : '').">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"visitedshow[$option[optionid]]\" value=\"1\" ".($option['visitedshow'] ? 'checked' : '').">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"orderbyshow[$option[optionid]]\" value=\"1\" ".($option['orderbyshow'] ? 'checked' : '')." ".(!in_array($option['type'], array('number', 'range')) ? 'disabled' : '').">",
				($_GET['template'] == 'basic' ? "<a href=\"###\" onclick=\"insertvar('$option[identifier]', 'typetemplate', 'message');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_template']."</a><a href=\"###\" onclick=\"insertvar('$option[identifier]', 'stypetemplate', 'subject');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_stemplate']."</a><a href=\"###\" onclick=\"insertvar('$option[identifier]', 'ptypetemplate', 'post');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_ptemplate']."</a><input type=\"\" value=\"[$option[identifier]value][$option[identifier]unit]\" size=\"10\">" : "<a href=\"###\" onclick=\"insertvar('$option[identifier]', 'btypetemplate', 'subject');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_btemplate']."</a><input type=\"\" value=\"[$option[identifier]value][$option[identifier]unit]\" size=\"10\">"),
				"<a href=\"".ADMINSCRIPT."?action=hr&operation=optiondetail&optionid=$option[optionid]\" class=\"act\" target=\"_blank\">".$lang['edit']."</a>"
			), TRUE);
		}

		shownav('job', 'hr_sort');
		showsubmenu('hr_sort', array(
			array(cplang('base_tpl'), 'hr&operation=sortdetail&sortid='.$_GET['sortid'], $templateblock['basic']),
			array(cplang('call_tpl'), 'hr&operation=sortdetail&sortid='.$_GET['sortid'].'&template=block', $templateblock['block']),
            array('获取更多样式', 'hr&operation=jump&url=http://www.kuozhan.net/forum-37-1.html', 0),
			));
		showtips('forums_edit_threadsorts_tips');

		showformheader("hr&operation=sortdetail&sortid={$_GET['sortid']}&template={$_GET['template']}");
		showtableheader('threadtype_infotypes_validity', 'nobottom');
		showsetting('threadtype_infotypes_validity', 'expiration', $threadtype['expiration'], 'radio');
		showsetting('threadtype_infotypes_imgnum', 'imgnum', $threadtype['imgnum'], 'text');
		showsetting('hr_channel_perpage', 'perpage', $threadtype['perpage'], 'text');
		showsetting('hr_sort_pullfid', 'pullfid', $threadtype['pullfid'], 'text');
		showsetting('hr_sort_pulltypeid', 'pulltypeid', $threadtype['pulltypeid'], 'text');
		showsetting('hr_sort_pullsortid', 'pullsortid', $threadtype['pullsortid'], 'text');
		showtablefooter();

		showtableheader("$threadtype[name] - $lang[threadtype_infotypes_add_option]", 'noborder fixpadding');
		showtablerow('', 'id="classlist"', '');
		showtablerow('', 'id="optionlist"', '');
		showtablefooter();

		showtableheader("$threadtype[name] - $lang[threadtype_infotypes_exist_option]", 'noborder fixpadding', 'id="sortlist"');
		showsubtitle(array('<input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll(\'prefix\', this.form,\'delete\')" /><label for="chkall">'.cplang('del').'</label>', 'display_order', 'available', 'name', 'required', 'unchangeable', 'threadtype_infotypes_search', 'threadtype_infotypes_show', 'hr_infotypes_visitshow', 'hr_infotypes_orderbyshow',  'threadtype_infotypes_insert_template', '', ''));
		echo $sortoptions;
		showtablefooter();

?>

<a name="template"></a>
<div class="colorbox">
<?php
	if($_GET['template'] == 'basic') {
?>
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo $lang['threadtype_infotypes_template']?></h4>
<textarea cols="100" rows="15" id="typetemplate" name="typetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['template']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo $lang['threadtype_infotypes_ptemplate']?></h4>
<textarea cols="100" rows="15" id="ptypetemplate" name="ptypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['ptemplate']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo $lang['threadtype_infotypes_stemplate']?>(<?php echo cplang('img_version') ?>)</h4>
<textarea cols="100" rows="8" id="stypetemplate" name="stypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['stemplate']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo $lang['threadtype_infotypes_stemplate']?>(<?php echo cplang('char_version') ?>)</h4>
<textarea cols="100" rows="8" id="sttypetemplate" name="sttypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['sttemplate']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo  cplang('recent_view_tpl') ?></h4>
<textarea cols="100" rows="8" id="vtypetemplate" name="vtypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['vtemplate']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo  cplang('nearby_job_tpl') ?></h4>
<textarea cols="100" rows="8" id="ntypetemplate" name="ntypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['ntemplate']?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo  cplang('stick_tpl') ?></h4>
<textarea cols="100" rows="8" id="rtypetemplate" name="rtypetemplate" style="width: 95%;" onkeyup="textareasize(this)"><?php echo $threadtype['rtemplate']?></textarea>
<br /><br />
<?php
	} elseif($_GET['template'] == 'block') {

?>
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo cplang('tpl_style1') ?></h4>
<textarea cols="100" rows="8" id="btypetemplate" name="btypetemplate[style1]" style="width: 95%;" onkeyup="textareasize(this)"><?php echo stripslashes($threadtype['btemplate']['style1'])?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo cplang('tpl_style2') ?></h4>
<textarea cols="100" rows="8" id="btypetemplate" name="btypetemplate[style2]" style="width: 95%;" onkeyup="textareasize(this)"><?php echo stripslashes($threadtype['btemplate']['style2'])?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo cplang('tpl_style3') ?></h4>
<textarea cols="100" rows="8" id="btypetemplate" name="btypetemplate[style3]" style="width: 95%;" onkeyup="textareasize(this)"><?php echo stripslashes($threadtype['btemplate']['style3'])?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo cplang('tpl_style4') ?></h4>
<textarea cols="100" rows="8" id="btypetemplate" name="btypetemplate[style4]" style="width: 95%;" onkeyup="textareasize(this)"><?php echo stripslashes($threadtype['btemplate']['style4'])?></textarea>
<br /><br />
<h4 style="margin-bottom:15px;"><?php echo $threadtype['name']?> - <?php echo cplang('tpl_style5') ?></h4>
<textarea cols="100" rows="8" id="btypetemplate" name="btypetemplate[style5]" style="width: 95%;" onkeyup="textareasize(this)"><?php echo stripslashes($threadtype['btemplate']['style5'])?></textarea>
<br /><br />
<?php
	}
?>
<b><?php echo $lang['threadtype_infotypes_template']?>:</b>
<ul class="tpllist"><?php echo $lang['threadtype_infotypes_template_tips']?></ul>
<input type="submit" class="btn" name="sortdetailsubmit" value="<?php echo $lang['submit']?>">
</div>

</form>
<script type="text/JavaScript">
	//note 初始化已有的选项
	var optionids = new Array();
	<?php echo $jsoptionids?>
	function insertvar(text, focusarea, location) {
		$(focusarea).focus();
		selection = document.selection;
		var commonfield = '[' + text + 'value] [' + text + 'unit]';
		if(location == 'post' || location == 'message') {
			var checktext = 'available_' + text;
			$(checktext).checked = true;
		} else {
			var checktext = 'subject_' + text;
			$(checktext).checked = true;
		}
		if(selection && selection.createRange) {
			var sel = selection.createRange();
			if(location == 'post') {
				sel.text = '<dt><strong class="rq">[' + text + 'required]</strong>{' + text + '}</dt><dd>' + commonfield + '[' + text + 'tips] [' + text + 'description]</dd>\r\n';
			} else {
				sel.text = location == 'message' ? '<dt>{' + text + '}:</dt><dd>' + commonfield + ' </dd>\r\n' : '<p><em>{' + text + '}:</em>' + commonfield + '</p>';
			}
			sel.moveStart('character', -strlen(text));
		} else {
			if(location == 'post') {
				$(focusarea).value += '<dt><strong class="rq">[' + text + 'required]</strong>{' + text + '}</dt><dd>' + commonfield + ' [' + text + 'tips] [' + text + 'description]</dd>\r\n';
			} else {
				$(focusarea).value += location == 'message' ? '<dt>{' + text + '}:</dt><dd>' + commonfield + '</dd>\r\n' : '<p><em>{' + text + '}:</em>' + commonfield + '</p>';
			}
		}
	}

	function checkedbox() {
		var tags = $('optionlist').getElementsByTagName('input');
		for(var i=0; i<tags.length; i++) {
			if(in_array(tags[i].value, optionids)) {
				tags[i].checked = true;
			}
		}
	}
	function insertoption(optionid) {
		var x = new Ajax();
		x.optionid = optionid;
		x.get('<?php echo ADMINSCRIPT?>?action=hr&operation=sortlist&do=$do&inajax=1&optionid=' + optionid, function(s, x) {
			if(!in_array(x.optionid, optionids)) {
				var div = document.createElement('div');
				div.style.display = 'none';
				$('append_parent').appendChild(div);
				div.innerHTML = '<table>' + s + '</table>';
				var tr = div.getElementsByTagName('tr');
				var trs = $('sortlist').getElementsByTagName('tr');
				tr[0].id = 'optionid' + optionid;
				trs[trs.length - 1].parentNode.appendChild(tr[0]);
				$('append_parent').removeChild(div);
				optionids.push(x.optionid);
			} else {
				$('optionid' + x.optionid).parentNode.removeChild($('optionid' + x.optionid));
				for(var i=0; i<optionids.length; i++) {
					if(optionids[i] == x.optionid) {
						optionids[i] = 0;
					}
				}
			}
		});
	}

	function setCopy(text, msg){
		if(BROWSER.ie) {
			clipboardData.setData('Text', text);
			alert(msg);
		} else {
			var msg = '<div class="c"><div style="width: 200px; text-align: center; text-decoration:underline;">' + <?php echo cplang('hr_click_copy')?> + '</div>' +
			AC_FL_RunContent('id', 'clipboardswf', 'name', 'clipboardswf', 'devicefont', 'false', 'width', '200', 'height', '40', 'src', STATICURL + 'image/common/clipboard.swf', 'menu', 'false',  'allowScriptAccess', 'sameDomain', 'swLiveConnect', 'true', 'wmode', 'transparent', 'style' , 'margin-top:-20px') + '</div>';
			showDialog(msg, 'info');
			text = text.replace(/[\xA0]/g, ' ');
			clipboardswfdata = text;
		}
	}
</script>
<script type="text/JavaScript">ajaxget('<?php echo ADMINSCRIPT?>?action=hr&operation=classlist', 'classlist');</script>
<script type="text/JavaScript">ajaxget('<?php echo ADMINSCRIPT?>?action=hr&operation=optionlist&sortid=<?php echo $_GET['sortid']?>', 'optionlist', '', '', '', checkedbox);</script>
<?php

	} else {

		if($_GET['template'] == 'basic') {
			DB::update('hr_sort', array(
				'template' => $_GET['typetemplate'],
				'stemplate' => $_GET['stypetemplate'],
				'sttemplate' => $_GET['sttypetemplate'],
				'ptemplate' => $_GET['ptypetemplate'],
				'vtemplate' => $_GET['vtypetemplate'],
				'ntemplate' => $_GET['ntypetemplate'],
				'rtemplate' => $_GET['rtypetemplate'],
				'expiration' => $_GET['expiration'],
				'imgnum' => intval($_GET['imgnum']),
				'perpage' => intval($_GET['perpage']),
				'pullfid' => intval($_GET['pullfid']),
				'pulltypeid' => intval($_GET['pulltypeid']),
				'pullsortid' => intval($_GET['pullsortid']),
			), "sortid='{$_GET['sortid']}'");
		} elseif($_GET['template'] == 'block') {
			DB::update('hr_sort', array(
				'btemplate' => serialize($_GET['btypetemplate']),
				'expiration' => $_GET['expiration'],
				'imgnum' => intval($_GET['imgnum']),
				'perpage' => intval($_GET['perpage']),
				'pullfid' => intval($_GET['pullfid']),
				'pulltypeid' => intval($_GET['pulltypeid']),
				'pullsortid' => intval($_GET['pullsortid']),
			), "sortid='{$_GET['sortid']}'");
		}

		if(submitcheck('sortdetailsubmit')) {

			$orgoption = $orgoptions = $addoption = array();
			$query = DB::query("SELECT optionid FROM ".DB::table('hr_sortvar')." WHERE sortid='{$_GET['sortid']}'");
			while($orgoption = DB::fetch($query)) {
				$orgoptions[] = $orgoption['optionid'];
			}

			$addoption = $addoption ? (array)$addoption + (array)$_GET['displayorder'] : (array)$_GET['displayorder'];

			@$newoptions = array_keys($addoption);

			if(empty($addoption)) {
				cpmsg('threadtype_infotypes_invalid', '', 'error');
			}

			@$delete = array_merge((array)$_GET['delete'], array_diff($orgoptions, $newoptions));

			if($delete) {
				if($ids = dimplode($delete)) {
					$deletefield = array();
					$query = DB::query("SELECT optionid, identifier FROM ".DB::table('hr_sortoption')." WHERE optionid IN ($ids)");
					while($option = DB::fetch($query)) {
						$deletefield[$option['optionid']] = $option['identifier'];
					}

					foreach($deletefield as $identifier) {
						DB::query("ALTER TABLE ".DB::table('hr_sortvalue')."{$_GET['sortid']} DROP $identifier");
					}

					DB::query("DELETE FROM ".DB::table('hr_sortvar')." WHERE sortid='{$_GET['sortid']}' AND optionid IN ($ids)");
				}
				//note 删除
				foreach($delete as $id) {
					unset($addoption[$id]);
				}
			}

			$insertoptionid = $indexoption = array();
			$create_table_sql = $separator = $create_tableoption_sql = '';

			if(is_array($addoption) && !empty($addoption)) {
				$query = DB::query("SELECT optionid, type, identifier FROM ".DB::table('hr_sortoption')." WHERE optionid IN (".dimplode(array_keys($addoption)).")");
				while($option = DB::fetch($query)) {
					$insertoptionid[$option['optionid']]['type'] = $option['type'];
					$insertoptionid[$option['optionid']]['identifier'] = $option['identifier'];
				}

				$query = DB::query("SHOW TABLES LIKE '".DB::table('hr_sortvalue')."{$_GET['sortid']}'");
				if(DB::num_rows($query) != 1) {
					$create_table_sql = "CREATE TABLE ".DB::table('hr_sortvalue')."{$_GET['sortid']} (";
					foreach($addoption as $optionid => $option) {
						$identifier = $insertoptionid[$optionid]['identifier'];
						if(in_array($insertoptionid[$optionid]['type'], array('radio', 'select'))) {
							$create_tableoption_sql .= "$separator$identifier smallint(6) UNSIGNED NOT NULL DEFAULT '0'\r\n";
						} elseif(in_array($insertoptionid[$optionid]['type'], array('number', 'range'))) {
							$create_tableoption_sql .= "$separator$identifier int(10) UNSIGNED NOT NULL DEFAULT '0'\r\n";
						} else {
							$create_tableoption_sql .= "$separator$identifier mediumtext NOT NULL\r\n";
						}
						$separator = ' ,';
						if(in_array($insertoptionid[$optionid]['type'], array('radio', 'select', 'number'))) {
							$indexoption[] = $identifier;
						}
					}
					$create_table_sql .= ($create_tableoption_sql ? $create_tableoption_sql.',' : '')."tid mediumint(8) UNSIGNED NOT NULL DEFAULT '0',attachid int(10) UNSIGNED NOT NULL DEFAULT '0',dateline int(10) UNSIGNED NOT NULL DEFAULT '0',expiration int(10) UNSIGNED NOT NULL DEFAULT '0',displayorder tinyint(3) NOT NULL DEFAULT '0',recommend tinyint(3) NOT NULL DEFAULT '0',attachnum tinyint(3) NOT NULL DEFAULT '0',highlight tinyint(3) NOT NULL DEFAULT '0',groupid smallint(6) UNSIGNED NOT NULL DEFAULT '0',city smallint(6) UNSIGNED NOT NULL DEFAULT '0',district smallint(6) UNSIGNED NOT NULL DEFAULT '0',street smallint(6) UNSIGNED NOT NULL DEFAULT '0', mapposition VARCHAR(50) NOT NULL DEFAULT '',";
					$create_table_sql .= "KEY (tid), KEY(groupid), KEY(dateline), KEY(city), KEY(district), KEY(street)";
					if($indexoption) {
						foreach($indexoption as $index) {
							$create_table_sql .= "$separator KEY $index ($index)\r\n";
							$separator = ' ,';
						}
					}
					$create_table_sql .= ") TYPE=MyISAM;";
					$dbcharset = empty($dbcharset) ? str_replace('-','',CHARSET) : $dbcharset;
					$db = DB::object();
					$create_table_sql = syntablestruct($create_table_sql, $db->version() > '4.1', $dbcharset);
					DB::query($create_table_sql);
				} else {
					$tables = array();
					$db = DB::object();
					if($db->version() > '4.1') {
						$query = DB::query("SHOW FULL COLUMNS FROM ".DB::table('hr_sortvalue')."{$_GET['sortid']}", 'SILENT');
					} else {
						$query = DB::query("SHOW COLUMNS FROM ".DB::table('hr_sortvalue')."{$_GET['sortid']}", 'SILENT');
					}
					while($field = @DB::fetch($query)) {
						$tables[$field['Field']] = 1;
					}

					foreach($addoption as $optionid => $option) {
						$identifier = $insertoptionid[$optionid]['identifier'];
						if(!$tables[$identifier]) {
							$fieldname = $identifier;
							if(in_array($insertoptionid[$optionid]['type'], array('radio', 'select'))) {
								$fieldtype = 'smallint(6) UNSIGNED NOT NULL DEFAULT \'0\'';
							} elseif(in_array($insertoptionid[$optionid]['type'], array('number', 'range'))) {
								$fieldtype = 'int(10) UNSIGNED NOT NULL DEFAULT \'0\'';
							} else {
								$fieldtype = 'mediumtext NOT NULL';
							}
							DB::query("ALTER TABLE ".DB::table('hr_sortvalue')."{$_GET['sortid']} ADD $fieldname $fieldtype");

							if(in_array($insertoptionid[$optionid]['type'], array('radio', 'select', 'number'))) {
								DB::query("ALTER TABLE ".DB::table('hr_sortvalue')."{$_GET['sortid']} ADD INDEX ($fieldname)");
							}
						}
					}
				}
				foreach($addoption as $id => $val) {
					$optionid = DB::fetch_first("SELECT optionid FROM ".DB::table('hr_sortoption')." WHERE optionid='$id'");
					if($optionid) {
						$data = array(
							'sortid' => $_GET['sortid'],
							'optionid' => $id,
							'available' => 1,
							'required' => intval($val),
						);
						DB::insert('hr_sortvar', $data, 0, 0, 1);
						DB::update('hr_sortvar', array(
							'displayorder' => $_GET['displayorder'][$id],
							'available' => $_GET['available'][$id],
							'required' => $_GET['required'][$id],
							'unchangeable' => $_GET['unchangeable'][$id],
							'search' => $_GET['search'][$id],
							'subjectshow' => $_GET['subjectshow'][$id],
							'visitedshow' => $_GET['visitedshow'][$id],
							'orderbyshow' => $_GET['orderbyshow'][$id],
						), "sortid='{$_GET['sortid']}' AND optionid='$id'");
					} else {
						DB::query("DELETE FROM ".DB::table('hr_sortvar')." WHERE sortid='{$_GET['sortid']}' AND optionid IN ($id)");
					}
				}
			}
			
			hrcache('hrsort');
			hrcache('sortlist');
			cpmsg('threadtype_infotypes_succeed', 'action=hr&operation=sortdetail&sortid='.$_GET['sortid'].'&template='.$_GET['template'], 'succeed');

		}

	}

} elseif($operation == 'content') {

	loadcache(array('hr_option_'.$_GET['sortid'], 'hr_arealist_'.$do));
	$sortoptionarray = $_G['cache']['hr_option_'.$_GET['sortid']];
	$sortarealist = $_G['cache']['hr_arealist_'.$do];

	if(!submitcheck('searchsortsubmit', 1) && !submitcheck('delsortsubmit') && !submitcheck('sendpmsubmit')) {

		shownav('job', 'menu_hr_content');

		$_GET['sortid'] = intval($_GET['sortid']);
		$threadtypes = '<select name="sortid" onchange="window.location.href = \'?action=hr&operation=content&do='.$do.'&sortid=\'+ this.options[this.selectedIndex].value"><option value="0">'.cplang('none').'</option>';
		$query = DB::query("SELECT * FROM ".DB::table('hr_sort')." WHERE cid='1' ORDER BY displayorder");
		while($type = DB::fetch($query)) {
			$threadtypes .= '<option value="'.$type['sortid'].'" '.($_GET['sortid'] == $type['sortid'] ? 'selected="selected"' : '').'>'.dhtmlspecialchars($type['name']).'</option>';
		}
		$threadtypes .= '</select>';

		showformheader('hr&operation=content&sortid='.$_GET['sortid'].'&do='.$do);
		showtableheader(cplang('select_class'));
		showsetting(cplang('class_name'), '', '', $threadtypes);

		if($_GET['sortid']) {
			showtableheader(cplang('screening_conditions'));
			$arealist = '<select name="searchoption[0][value]"><option value="">'.cplang('all').'</option>';
			foreach($sortarealist['city'] as $cityid => $cityname) {
				$arealist .= "<option value=\"district|$cityid\">$cityname</option>";
				if(!empty($sortarealist['district'][$cityid])) {
					foreach($sortarealist['district'][$cityid] as $districtid => $districtname) {
						$arealist .= "<option value=\"district|$districtid\">&nbsp;&nbsp;$districtname</option>";
						if(!empty($sortarealist['street'][$districtid])) {
							foreach($sortarealist['street'][$districtid] as $streetid => $streetname) {
								$arealist .= "<option value=\"street|$streetid\">&nbsp;&nbsp;&nbsp;&nbsp;$streetname</option>";
							}
						}
					}
				}
				$arealist .= '</optgroup>';
			}
			$arealist .= '</select><input type="hidden" name="searchoption[0][type]" value="areaid">';

			showsetting(cplang('select_region'), '', '', $arealist);
			showsetting(cplang('post_user'), 'postusername', '', 'text');
			if(is_array($sortoptionarray)) foreach($sortoptionarray as $optionid => $option) {
				$optionshow = '';
				if($option['search']) {
					if(in_array($option['type'], array('radio', 'checkbox', 'select', 'intermediary'))){
						if($option['type'] == 'select' || $option['type'] == 'intermediary') {
							$optionshow .= '<select name="searchoption['.$optionid.'][value]"><option value="0">'.cplang('unlimited').'</option>';
							foreach($option['choices'] as $id => $value) {
								$optionshow .= '<option value="'.$id.'" '.($_GET['searchoption'][$optionid]['value'] == $id ? 'selected="selected"' : '').'>'.$value.'</option>';
							}
							$optionshow .= '</select><input type="hidden" name="searchoption['.$optionid.'][type]" value="select">';
						} elseif($option['type'] == 'radio') {
							$optionshow .= '<input type="radio" class="radio" name="searchoption['.$optionid.'][value]" value="0" checked="checked"]>'.cplang('unlimited').'&nbsp;';
							foreach($option['choices'] as $id => $value) {
								$optionshow .= '<input type="radio" class="radio" name="searchoption['.$optionid.'][value]" value="'.$id.'" '.($_GET['searchoption'][$optionid]['value'] == $id ? 'checked="checked"' : '').'> '.$value.' &nbsp;';
							}
							$optionshow .= '<input type="hidden" name="searchoption['.$optionid.'][type]" value="radio">';
						} elseif($option['type'] == 'checkbox') {
							foreach($option['choices'] as $id => $value) {
								$optionshow .= '<input type="checkbox" class="checkbox" name="searchoption['.$optionid.'][value]['.$id.']" value="'.$id.'" '.($_GET['searchoption'][$optionid]['value'] == $id ? 'checked="checked"' : '').'> '.$value.'';
							}
							$optionshow .= '<input type="hidden" name="searchoption['.$optionid.'][type]" value="checkbox">';
						}
					} elseif(in_array($option['type'], array('number', 'text', 'email', 'calendar', 'image', 'url', 'textarea', 'upload', 'range'))) {
						if ($option['type'] == 'calendar') {
							$optionshow .= '<script type="text/javascript" src="'.$_G['setting']['jspath'].'calendar.js"></script><input type="text" name="searchoption['.$optionid.'][value]" class="txt" value="'.$_GET['searchoption'][$optionid]['value'].'" onclick="showcalendar(event, this, false)" />';
						} elseif($option['type'] == 'number') {
							$optionshow .= '<select name="searchoption['.$optionid.'][condition]">
								<option value="0" '.($_GET['searchoption'][$optionid]['condition'] == 0 ? 'selected="selected"' : '').'>'.cplang('equal_to').'</option>
								<option value="1" '.($_GET['searchoption'][$optionid]['condition'] == 1 ? 'selected="selected"' : '').'>'.cplang('more_than').'</option>
								<option value="2" '.($_GET['searchoption'][$optionid]['condition'] == 2 ? 'selected="selected"' : '').'>'.cplang('lower_than').'</option>
							</select>&nbsp;&nbsp;
							<input type="text" class="txt" name="searchoption['.$optionid.'][value]" value="'.$_GET['searchoption'][$optionid]['value'].'" />
							<input type="hidden" name="searchoption['.$optionid.'][type]" value="number">';
						} elseif($option['type'] == 'range') {
							$optionshow .= '<input type="text" name="searchoption['.$optionid.'][value][min]" size="16" value="'.$_GET['searchoption'][$optionid]['value']['min'].'" /> -
							<input type="text" name="searchoption['.$optionid.'][value][max]" size="16" value="'.$_GET['searchoption'][$optionid]['value']['max'].'" />
							<input type="hidden" name="searchoption['.$optionid.'][type]" value="range">';
						} else {
							$optionshow .= '<input type="text" name="searchoption['.$optionid.'][value]" class="txt" value="'.$_GET['searchoption'][$optionid]['value'].'" />';
						}
					}
					$optionshow .=  '&nbsp;'.$option['unit'];
					showsetting($option['title'], '', '', $optionshow);
				}
			}
		}

		showsubmit('searchsortsubmit', 'submit');
		showtablefooter();
		showformfooter();

	} else {

		if(submitcheck('searchsortsubmit', 1)) {

			if(empty($_GET['searchoption']) && !$_GET['sortid']) {
				cpmsg(cplang('no_select_class'), 'action=hr&operation=content&do='.$do, 'error');
			}
			$mpurl = 'admin.php?action=hr&operation=content&do='.$do.'&sortid='.$_GET['sortid'].'&searchsortsubmit=true';

			if(!is_array($_GET['searchoption'])) {
				$mpurl .= '&searchoption='.$_GET['searchoption'];
				$_GET['searchoption'] = unserialize(base64_decode($_GET['searchoption']));
			} else {
				$mpurl .= '&searchoption='.base64_encode(serialize($_GET['searchoption']));
			}

			shownav('job', 'menu_hr_content');
			$selectsql = $and = $sql = $multipage = '';
			foreach($_GET['searchoption'] as $optionid => $option) {
				$fieldname = $sortoptionarray[$optionid]['identifier'] ? $sortoptionarray[$optionid]['identifier'] : 1;
				if(!empty($option['value'])) {
					if(in_array($option['type'], array('number', 'radio', 'select'))) {
						$option['value'] = intval($option['value']);
						$exp = '=';
						if($option['condition']) {
							$exp = $option['condition'] == 1 ? '>' : '<';
						}
						$sql = "$fieldname$exp'$option[value]'";
					} elseif($option['type'] == 'checkbox') {
						$sql = "$fieldname LIKE '%".(implode("%", $option['value']))."%'";
					} elseif($option['type'] == 'range') {
						$sql = !empty($option['value']['min']) || !empty($option['value']['max']) ? "$fieldname BETWEEN ".intval($option['value']['min'])." AND ".intval($option['value']['max'])."" : '';
					} elseif($option['type'] == 'areaid') {
						$valuearray = explode('|', $option['value']);
						if(in_array($valuearray[0], array('city', 'district', 'street'))) {
							$sql = "$valuearray[0]='$valuearray[1]'";
						}
					} else {
						$sql = "$fieldname LIKE '%$option[value]%'";
					}

					if(!empty($sql)) {
						$selectsql .=  $and."$sql ";
						$and = 'AND ';
					}
				}
			}

			$selectsql = trim($selectsql);
			$searchtids = $searchthread = $datelinetids = array();
			$query = DB::query("SELECT tid, dateline FROM ".DB::table('hr_sortvalue')."$_GET[sortid] ".($selectsql ? "WHERE $selectsql" : '')."");
			while($thread = DB::fetch($query)) {
				$searchtids[] = $thread['tid'];
				$datelinetids[$thread['tid']] = $thread['dateline'];
			}

			if($searchtids) {
				$authorsql = '';
				if($_GET['postusername']) {
					$manageuid = DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[postusername]'");
					$authorsql = "AND authorid='$manageuid'";
					$mpurl .= '&authorid='.$manageuid;
				} elseif($_GET['authorid']) {
					$authorsql = "AND authorid='".intval($_GET['authorid'])."'";
					$mpurl .= '&authorid='.intval($_GET['authorid']);
				}

				$lpp = max(5, empty($_GET['lpp']) ? 50 : intval($_GET['lpp']));
				$start_limit = ($page - 1) * $lpp;

				$threadcount = DB::result_first("SELECT count(*) FROM ".DB::table('hr_'.$do.'_thread')." WHERE tid IN (".dimplode($searchtids).") $authorsql");
				$query = DB::query("SELECT tid, sortid, subject, authorid, author FROM ".DB::table('hr_'.$do.'_thread')." WHERE tid IN (".dimplode($searchtids).") $authorsql LIMIT $start_limit, $lpp");
				while($thread = DB::fetch($query)) {
					$threads .= showtablerow('', array('class="td25"', '', '', 'class="td28"', 'class="td28"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"tidsarray[]\" value=\"$thread[tid]\"/>",
					"<a href=\"$modurl?mod=view&tid=$thread[tid]\" target=\"_blank\">$thread[subject]</a>",
					"<a href=\"$modurl?mod=broker&action=my&uid=$thread[authorid]\" target=\"_blank\">$thread[author]</a>",
					dgmdate($datelinetids[$thread['tid']], 'd'),
					), TRUE);
				}

				$multipage = multi($threadcount, $lpp, $page, $mpurl, 0, 3);
			}

			showformheader('hr&operation=content&sortid='.$_GET['sortid'].'&do='.$do);
			showtableheader('admin', 'fixpadding');
			showsubtitle(array('', 'subject', 'author', cplang('post_time')));
			echo $threads;
			echo $multipage;
			showsubmit('', '', '', '<input type="checkbox" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'tidsarray\')" name="chkall">'.$lang['select_all'].'&nbsp;&nbsp;&nbsp;<input type="submit" class="btn" name="delsortsubmit" value="'.cplang('delete_info').'"/>');
			showtablefooter();
			showformfooter();

		} elseif(submitcheck('delsortsubmit')) {
			$tidsadd = isset($_GET['tidsarray']) ? 'WHERE tid IN ('.dimplode($_GET['tidsarray']).')' : '';
			if($tidsadd) {
				$memberdel = array();
				$query = DB::query("SELECT authorid, tid FROM ".DB::table('hr_'.$do.'_thread')." $tidsadd");
				while($result = DB::fetch($query)) {
					if($result['authorid']) {
						$memberdel[$result['authorid']]['tid'][] = $result['tid'];
						$memberdel[$result['authorid']]['today'] = 0;
						if($memberdel[$result['authorid']]['delnum']) {
							$memberdel[$result['authorid']]['delnum']++;
						} else {
							$memberdel[$result['authorid']]['delnum'] = 1;
						}
					}
				}

				$sorttoday = 0;
				$groupdel = array();
				$query = DB::query("SELECT tid, groupid, dateline FROM ".DB::table('hr_sortvalue'.$_GET['sortid'])." $tidsadd");
				while($result = DB::fetch($query)) {
					if($result['groupid'] > 1) {
						if($groupdel[$result['groupid']]) {
							$groupdel[$result['groupid']]++;
						} else {
							$groupdel[$result['groupid']] = 1;
						}
					}
					if(istoday($result['dateline'])) { //当天帖子
						$sorttoday++;
						foreach($memberdel as $k => $v) {
							if(in_array($result['tid'], $v['tid'])) {
								$memberdel[$k]['today']++;
							}
						}
					}
				}

				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_thread')." $tidsadd");
				DB::query("DELETE FROM ".DB::table('hr_sortoptionvar')." $tidsadd");
				DB::query("DELETE FROM ".DB::table('hr_sortvalue'.$_GET['sortid'])." $tidsadd");
				$query = DB::query("SELECT * FROM ".DB::table('hr_'.$do.'_pic')." $tidsadd");
				while($row = DB::fetch($query)) {
					@unlink($_G['setting']['attachdir'].'/hr/'.$row['url']);
				}
				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_pic')." $tidsadd");

				//更新当前分类发帖数
				DB::query("UPDATE ".DB::table('hr_sort')." SET threads=threads-".count($_GET['tidsarray']).", todaythreads=todaythreads-$sorttoday WHERE sortid='".$_GET['sortid']."'");
		
				//更新用户发帖数
				foreach($memberdel as $k => $v) {
					$sql = "UPDATE ".DB::table('hr_'.$do.'_member')." SET threads=threads-".$v['delnum'].", todaythreads=todaythreads-".$v['today']." WHERE uid = ".$k;
					DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET threads=threads-".$v['delnum'].", todaythreads=todaythreads-".$v['today']." WHERE uid = ".$k);
				}

				//更新中介发帖数
				foreach($groupdel as $k => $v) {
					DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET threads=threads-".$v." WHERE gid=".$k);
				}
			}
			cpmsg(cplang('data_del_success'), 'action=hr&operation=content&sortid='.$_GET['sortid'].'&do='.$do, 'succeed');

		}
	}

} elseif($operation == 'usergroup') {
	if(!submitcheck('groupsubmit')) {
		$query = DB::query("SELECT displayorder, gid, title, type, icon FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE verify='1' AND type='intermediary' ORDER BY displayorder");

		while($group = DB::fetch($query)) {
			$iconhtml = '';
			if($group['type'] == 'intermediary') {
				if($group['icon']) {
					$valueparse = parse_url($usergroup['icon']);
					if(isset($valueparse['host'])) {
						$groupicon = $group['icon'];
					} else {
						$groupicon = $_G['setting']['attachurl'].'common/'.$group['icon'].'?'.random(6);
					}
					$iconhtml = '<img src="'.$groupicon.'" />';
				}
				$intermediarygroup .= showtablerow('', array('', 'class="td28"', '', ''), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$group[gid]]\" value=\"$group[gid]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"group_displayorder[$group[gid]]\" value=\"$group[displayorder]\">",
					"<input type=\"text\" class=\"txt\" size=\"12\" name=\"group_title[$group[gid]]\" value=\"$group[title]\">",
					$iconhtml,
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=groupedit&groupid=$group[gid]\" class=\"act\">$lang[detail]</a>"
				), TRUE);
			}
		}

		echo <<<EOT
<script type="text/JavaScript">
var rowtypedata = [
	[
		[1,'', 'td25'],
		[1, '<input type="text" class="txt" name="groupdisplayordernewadd[]" size="2" value="">', 'td28'],
		[1, '<input type="text" class="txt" size="12" name="grouptitlenewadd[]">'],
		[1,''],
		[2,'']
	]
];
</script>
EOT;
		shownav('job', 'menu_hr_usergroup');
		showsubmenu('menu_hr_usergroup', array(
			array('menu_hr_usergroup', 'hr&operation=usergroup&do=job', 1),
			array(array('menu' => ($curclassname ? $curclassname : 'menu_hr_brokermod'), 'submenu' => $classoptionmenu), '', 0),
		));

		showformheader('hr&operation=usergroup&type=intermediary&do='.$do);
		showtableheader('usergroups_intermediary', 'fixpadding', 'id="intermediarygroups"');
		showsubtitle(array('', 'display_order', 'broker_name', 'broker_icon', ''));
		echo $intermediarygroup;
		echo '<tr><td>&nbsp;</td><td colspan="8"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['broker_add'].'</a></div></td></tr>';
		showsubmit('groupsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		if($_GET['type'] == 'intermediary') {
			if(is_array($_GET['grouptitlenewadd'])) {
				foreach($_GET['grouptitlenewadd'] as $k => $v) {
					if($v) {
						$data = array(
							'type' => 'intermediary',
							'title' => $_GET['grouptitlenewadd'][$k],
							'displayorder' => $_GET['groupdisplayordernewadd'][$k],
							'cid' => $cid,
						);
						$newgroupid = DB::insert('hr_'.$do.'_usergroup', $data, true);
					}
				}
			}

			if(is_array($_GET['group_title'])) {
				foreach($_GET['group_title'] as $id => $title) {
					if(!$_GET['delete'][$id]) {
						DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET 
						displayorder='{$_GET['group_displayorder'][$id]}', title='{$_GET['group_title'][$id]}' WHERE gid='$id'");
					}
				}
			}

			if($ids = dimplode($_GET['delete'])) {
				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN ($ids) AND type='intermediary'");
				//$newgroupid = DB::result_first("SELECT gid FROM ".DB::table('hr_usergroup')." WHERE type='personal' AND creditlower>'0' ORDER BY creditlower LIMIT 1");
				//DB::query("UPDATE ".DB::table('common_member')." SET groupid='$newgroupid', adminid='0' WHERE groupid IN ($ids)", 'UNBUFFERED');
			}

		}

		hrcache('usergroup', $do);
		cpmsg(cplang('update_success'), 'action=hr&operation=usergroup&do='.$do.'&type='.$_GET['type'], 'succeed');

	}
	
/*企业管理*/
} elseif($operation == 'company') {
	if(!submitcheck('groupsubmit')) {
		$query = DB::query("SELECT displayorder, gid, title, type, icon FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE verify='1' AND type='company' ORDER BY displayorder");

		while($group = DB::fetch($query)) {
			$iconhtml = '';
			if($group['type'] == 'company') {
				if($group['icon']) {
					$valueparse = parse_url($usergroup['icon']);
					if(isset($valueparse['host'])) {
						$groupicon = $group['icon'];
					} else {
						$groupicon = $_G['setting']['attachurl'].'common/'.$group['icon'].'?'.random(6);
					}
					$iconhtml = '<img src="'.$groupicon.'" />';
				}
				$companygroup .= showtablerow('', array('', 'class="td28"', '', ''), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$group[gid]]\" value=\"$group[gid]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"group_displayorder[$group[gid]]\" value=\"$group[displayorder]\">",
					"<input type=\"text\" class=\"txt\" size=\"12\" name=\"group_title[$group[gid]]\" value=\"$group[title]\">",
					$iconhtml,
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=groupedit&groupid=$group[gid]\" class=\"act\">$lang[detail]</a>"
				), TRUE);

			}
		}

		echo <<<EOT
<script type="text/JavaScript">
var rowtypedata = [
	[
		[1,'', 'td25'],
		[1, '<input type="text" class="txt" name="groupdisplayordernewadd[]" size="2" value="">', 'td28'],
		[1, '<input type="text" class="txt" size="12" name="grouptitlenewadd[]">'],
		[1,''],
		[2,'']
	]
];
</script>
EOT;
		shownav('job', 'menu_hr_company');
		showsubmenu('menu_hr_company', array(
			array('menu_hr_company', 'hr&operation=company&do=job', 1),
			array(array('menu' => ($cpcurclassname ? $cpcurclassname : 'menu_hr_cpbrokermod'), 'submenu' => $cpclassoptionmenu), '', 0),
		));

		showformheader('hr&operation=company&type=company&do='.$do);
		showtableheader('usergroups_company', 'fixpadding', 'id="companygroups"');
		showsubtitle(array('', 'display_order', 'company_name', 'company_icon', ''));
		echo $companygroup;
		echo '<tr><td>&nbsp;</td><td colspan="8"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['company_add'].'</a></div></td></tr>';
		showsubmit('groupsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		if($_GET['type'] == 'company') {
			if(is_array($_GET['grouptitlenewadd'])) {
				foreach($_GET['grouptitlenewadd'] as $k => $v) {
					if($v) {
						$data = array(
							'type' => 'company',
							'title' => $_GET['grouptitlenewadd'][$k],
							'displayorder' => $_GET['groupdisplayordernewadd'][$k],
							'cid' => $cid,
						);
						$newgroupid = DB::insert('hr_'.$do.'_usergroup', $data, true);
					}
				}
			}

			if(is_array($_GET['group_title'])) {
				foreach($_GET['group_title'] as $id => $title) {
					if(!$_GET['delete'][$id]) {
						DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET 
						displayorder='{$_GET['group_displayorder'][$id]}', title='{$_GET['group_title'][$id]}' WHERE gid='$id'");
					}
				}
			}

			if($ids = dimplode($_GET['delete'])) {
				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN ($ids) AND type='company'");
				//$newgroupid = DB::result_first("SELECT gid FROM ".DB::table('hr_usergroup')." WHERE type='personal' AND creditlower>'0' ORDER BY creditlower LIMIT 1");
				//DB::query("UPDATE ".DB::table('common_member')." SET groupid='$newgroupid', adminid='0' WHERE groupid IN ($ids)", 'UNBUFFERED');
			}

		}

		hrcache('usergroup', $do);
		cpmsg(cplang('update_success'), 'action=hr&operation=company&do='.$do.'&type='.$_GET['type'], 'succeed');

	}


} elseif($operation == 'userverify') {

	if(!submitcheck('passsubmit') && !submitcheck('rejectsubmit')) {

		shownav('job', 'menu_hr_usergroup');
		showsubmenu('menu_hr_usergroup', array(
			array('menu_hr_addusers', 'hr&operation=addusers', 0),
			array(array('menu' => ($curclassname ? $curclassname : 'menu_hr_brokermod'), 'submenu' => $classoptionmenu), '', 1),
			array('menu_hr_usergroup', 'hr&operation=usergroup&do=job', 0)
		));
		showformheader('hr&operation=userverify');
		showtableheader($curclassname);

		if($_GET['classid'] == 1) { //中介公司审核

			$query = DB::query("SELECT gid, title FROM ".DB::table('hr_'.$do.'_usergroup')."  WHERE verify='0' AND type='intermediary' ");
			$unverifiedhtml = '';
			while($group = DB::fetch($query)) {
				$unverifiedhtml .= showtablerow('', array('', 'class="td28"', 'class="td28"', ''), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$group[gid]]\" value=\"$group[gid]\">",
						"$group[title]",
						""
					), TRUE);
			}
			if($unverifiedhtml) {
				$unverifiedhtml .= '<tr><td></td><td colspan="15"><textarea onfocus="this.value=\'\'; this.onfocus=\'\';" name="rejectreason" rows="6" cols="50">'.$lang['broker_mod_tips'].'</textarea></td></tr>';
			}
			if(empty($unverifiedhtml)) {
				$unverifiedhtml = '<tr><td colspan="5">'.$lang['agent_not_exists'].'</td></tr>';
			}
			showsubtitle(array('', 'broker_name', '', ''));

		} else { //中介人员审核

			$query = DB::query("SELECT m.uid, m.realname, m.groupid, g.title
								FROM ".DB::table('hr_'.$do.'_member')." m
								LEFT JOIN ".DB::table('hr_'.$do.'_usergroup')." g
								ON m.groupid = g.gid
								WHERE m.verify='0' AND type='intermediary'");
			$unverifiedhtml = '';
			while($user = DB::fetch($query)) {
				$unverifiedhtml .= showtablerow('', array('', 'class="td28"', 'class="td28"', ''), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$user[uid]]\" value=\"$user[uid]\">",
						"$user[realname]",
						"$user[title]"
					), TRUE);
			}
			if($unverifiedhtml) {
				$unverifiedhtml .= '<tr><td></td><td colspan="15"><textarea onfocus="this.value=\'\'; this.onfocus=\'\';" name="rejectreason" rows="6" cols="50">'.$lang['broker_mod_tips'].'</textarea></td></tr>';
			}
			if(empty($unverifiedhtml)) {
				$unverifiedhtml = '<tr><td colspan="5">'.$lang['broker_not_exists'].'</td></tr>';
			}
			showsubtitle(array('', 'brokername', 'broker_company', ''));

		}
		echo $unverifiedhtml.'<input type="hidden" name="classid" value="'.$_GET['classid'].'" />';
		showsubmit('passsubmit', 'broker_mod_pass', 'select_all', '<input name="rejectsubmit" type="submit" class="btn" value="'.$lang['broker_mod_reject'].'" />', '', false);
		showtablefooter();
		showformfooter();

	} else {

		loadcache('hr_usergrouplist_'.$do);
		$pminfo = array();

		if($_GET['passsubmit']) { //通过

			if($_GET['classid'] == 1) { //中介公司审核
				DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET verify='1' WHERE gid IN (".dimplode($_GET['delete']).")");
				hrcache('usergroup', $do);
				
				$query = DB::query("SELECT gid, title, manageuid FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");
				while($group = DB::fetch($query)) {
					$pminfo[$group['manageuid']]['msgtitle'] = '您申请的中介公司“'.$group['title'].'”已获批准。';
				}

			} else { //中介人员审核

				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET verify='1' WHERE uid IN (".dimplode($_GET['delete']).")");

				$query = DB::query("SELECT uid, groupid FROM ".DB::table('hr_'.$do.'_member')." WHERE uid IN (".dimplode($_GET['delete']).")");
				$counter = array();
				while($result = DB::fetch($query)) {
					$pminfo[$result['uid']]['msgtitle'] = '您已被批准加入“'.$_G['cache']['hr_usergrouplist_'.$do][$result['groupid']]['title'].'”。';

					if($counter[$result['groupid']]) {
						$counter[$result['groupid']]++;
					} else {
						$counter[$result['groupid']] = 1;
					}
				}
				foreach($counter as $gid => $membernum) {
					DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET membernum=membernum+$membernum WHERE gid='$gid'");
				}
			}

		} else { //拒绝

			$_GET['rejectreason'] = trim($_GET['rejectreason']) == '拒绝申请时，请输入拒绝的原因' ? '' : trim($_GET['rejectreason']);

			if($_GET['classid'] == 1) { //中介公司审核

				//得到被拒中介公司的申请者
				$manageuid = array();
				$query = DB::query("SELECT title, manageuid FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");
				while($group = DB::fetch($query)) {
					$pminfo[$group['manageuid']]['msgtitle'] = '抱歉，您申请的中介公司“'.$group['title'].'”未获批准。';
					$pminfo[$group['manageuid']]['msgtxt'] = $_GET['rejectreason'];
					$manageuid[] = $group['manageuid'];
				}

				//将申请该中介公司用户的groupid置为1 (个人用户)
				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET groupid='1', verify='1' WHERE uid IN (".dimplode($manageuid).")");

				//删除被拒的中介公司
				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");

			} else { //中介人员审核

				//得到被拒用户申请的中介公司
				$query = DB::query("SELECT m.uid, g.title FROM ".DB::table('hr_'.$do.'_member')." m
									LEFT JOIN ".DB::table('hr_'.$do.'_usergroup')." g
									ON m.groupid = g.gid
									WHERE m.uid IN (".dimplode($_GET['delete']).")");
				while($appinfo = DB::fetch($query)) {
					$pminfo[$appinfo['uid']]['msgtitle'] = '抱歉，您未被批准加入“'.$appinfo['title'].'”。';
					$pminfo[$appinfo['uid']]['msgtxt'] = $_GET['rejectreason'];
				}

				//将被拒用户的groupid置为1 (个人用户)
				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET groupid='1', verify='1' WHERE uid IN (".dimplode($_GET['delete']).")");
			}
		}

		foreach($pminfo as $touid => $msg) {
			notification_add($touid, 'system', $msg['msgtitle'].$msg['msgtxt']);
		}

		cpmsg('审核完成', 'action=hr&operation=userverify&classid='.$_GET['classid'], 'succeed');
	}
/*企业审核*/

} elseif($operation == 'cpuserverify') {

	if(!submitcheck('passsubmit') && !submitcheck('rejectsubmit')) {

		shownav('job', 'menu_hr_company');
		showsubmenu('menu_hr_company', array(
			array('menu_hr_cpaddusers', 'hr&operation=cpaddusers', 0),
			array(array('menu' => ($cpcurclassname ? $cpcurclassname : 'menu_hr_cpbrokermod'), 'submenu' => $cpclassoptionmenu), '', 1),
			array('menu_hr_company', 'hr&operation=company&do=job', 0)
		));
		showformheader('hr&operation=cpuserverify');
		showtableheader($cpcurclassname);

		if($_GET['classid'] == 1) { //合作企业审核

			$query = DB::query("SELECT gid, title FROM ".DB::table('hr_'.$do.'_usergroup')."  WHERE verify='0' AND type='company'");
			$unverifiedhtml = '';
			while($group = DB::fetch($query)) {
				$unverifiedhtml .= showtablerow('', array('', 'class="td28"', 'class="td28"', ''), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$group[gid]]\" value=\"$group[gid]\">",
						"$group[title]",
						""
					), TRUE);
			}
			if($unverifiedhtml) {
				$unverifiedhtml .= '<tr><td></td><td colspan="15"><textarea onfocus="this.value=\'\'; this.onfocus=\'\';" name="rejectreason" rows="6" cols="50">'.$lang['broker_mod_tips'].'</textarea></td></tr>';
			}
			if(empty($unverifiedhtml)) {
				$unverifiedhtml = '<tr><td colspan="5">'.$lang['company_not_exists'].'</td></tr>';
			}
			showsubtitle(array('', 'company_name', '', ''));

		} else { //企业人事审核

			$query = DB::query("SELECT m.uid, m.realname, m.groupid, g.title
								FROM ".DB::table('hr_'.$do.'_member')." m
								LEFT JOIN ".DB::table('hr_'.$do.'_usergroup')." g
								ON m.groupid = g.gid
								WHERE m.verify='0'");
			$unverifiedhtml = '';
			while($user = DB::fetch($query)) {
				$unverifiedhtml .= showtablerow('', array('', 'class="td28"', 'class="td28"', ''), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$user[uid]]\" value=\"$user[uid]\">",
						"$user[realname]",
						"$user[title]"
					), TRUE);
			}
			if($unverifiedhtml) {
				$unverifiedhtml .= '<tr><td></td><td colspan="15"><textarea onfocus="this.value=\'\'; this.onfocus=\'\';" name="rejectreason" rows="6" cols="50">'.$lang['broker_mod_tips'].'</textarea></td></tr>';
			}
			if(empty($unverifiedhtml)) {
				$unverifiedhtml = '<tr><td colspan="5">'.$lang['cpbroker_not_exists'].'</td></tr>';
			}
			showsubtitle(array('', 'cpbrokername', 'broker_cpcompany', ''));

		}
		echo $unverifiedhtml.'<input type="hidden" name="classid" value="'.$_GET['classid'].'" />';
		showsubmit('passsubmit', 'broker_mod_pass', 'select_all', '<input name="rejectsubmit" type="submit" class="btn" value="'.$lang['broker_mod_reject'].'" />', '', false);
		showtablefooter();
		showformfooter();

	} else {

		loadcache('hr_companylist_'.$do);
		$pminfo = array();

		if($_GET['passsubmit']) { //通过

			if($_GET['classid'] == 1) { //合作企业审核
				DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET verify='1' WHERE gid IN (".dimplode($_GET['delete']).")");
				hrcache('usergroup', $do);
				
				$query = DB::query("SELECT gid, title, manageuid FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");
				while($group = DB::fetch($query)) {
					$pminfo[$group['manageuid']]['msgtitle'] = '您申请的合作企业"'.$group['title'].'"已获批准。';
				}

			} else { //企业HR审核

				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET verify='1' WHERE uid IN (".dimplode($_GET['delete']).")");

				$query = DB::query("SELECT uid, groupid FROM ".DB::table('hr_'.$do.'_member')." WHERE uid IN (".dimplode($_GET['delete']).")");
				$counter = array();
				while($result = DB::fetch($query)) {
					$pminfo[$result['uid']]['msgtitle'] = '您已被批准加入"'.$_G['cache']['hr_usergrouplist_'.$do][$result['groupid']]['title'].'"。';

					if($counter[$result['groupid']]) {
						$counter[$result['groupid']]++;
					} else {
						$counter[$result['groupid']] = 1;
					}
				}
				foreach($counter as $gid => $membernum) {
					DB::query("UPDATE ".DB::table('hr_'.$do.'_usergroup')." SET membernum=membernum+$membernum WHERE gid='$gid'");
				}
			}

		} else { //拒绝

			$_GET['rejectreason'] = trim($_GET['rejectreason']) == '拒绝申请时，请输入拒绝的原因' ? '' : trim($_GET['rejectreason']);

			if($_GET['classid'] == 1) { //合作企业审核

				//得到被拒合作企业的申请者
				$manageuid = array();
				$query = DB::query("SELECT title, manageuid FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");
				while($group = DB::fetch($query)) {
					$pminfo[$group['manageuid']]['msgtitle'] = '抱歉，您申请的合作企业"'.$group['title'].'"未获批准。';
					$pminfo[$group['manageuid']]['msgtxt'] = $_GET['rejectreason'];
					$manageuid[] = $group['manageuid'];
				}

				//将申请该合作企业用户的groupid置为1 (个人用户)
				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET groupid='1', verify='1' WHERE uid IN (".dimplode($manageuid).")");

				//删除被拒的合作企业
				DB::query("DELETE FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid IN (".dimplode($_GET['delete']).")");

			} else { //企业HR审核

				//得到被拒用户申请的合作企业
				$query = DB::query("SELECT m.uid, g.title FROM ".DB::table('hr_'.$do.'_member')." m
									LEFT JOIN ".DB::table('hr_'.$do.'_usergroup')." g
									ON m.groupid = g.gid
									WHERE m.uid IN (".dimplode($_GET['delete']).")");
				while($appinfo = DB::fetch($query)) {
					$pminfo[$appinfo['uid']]['msgtitle'] = '抱歉，您未被批准加入"'.$appinfo['title'].'"。';
					$pminfo[$appinfo['uid']]['msgtxt'] = $_GET['rejectreason'];
				}

				//将被拒用户的groupid置为1 (个人用户)
				DB::query("UPDATE ".DB::table('hr_'.$do.'_member')." SET groupid='1', verify='1' WHERE uid IN (".dimplode($_GET['delete']).")");
			}
		}

		foreach($pminfo as $touid => $msg) {
			notification_add($touid, 'system', $msg['msgtitle'].$msg['msgtxt']);
		}

		cpmsg('审核完成', 'action=hr&operation=cpuserverify&classid='.$_GET['classid'], 'succeed');
	}



} elseif($operation == 'groupedit') {

	$_GET['type'] = empty($_GET['groupid']) ? 'personal' : '';
	if($_GET['type'] == 'personal') {
		$_GET['groupid'] = DB::result_first("SELECT gid FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE type='personal'");
	}

	$groupid = intval($_GET['groupid']);

	if(!empty($groupid)) {

		$usergroup = DB::fetch_first("SELECT * FROM ".DB::table('hr_'.$do.'_usergroup')." WHERE gid='$groupid'");

		if(!submitcheck('groupsubmit')) {

			if($_GET['type'] == 'personal') {
				$title = $usergroup['title'];
			} else {
				$title = $lang['menu_hr_usergroup'] .' - '. $usergroup['title'];
			}

			shownav('job', '个人管理');
			showsubmenu($title);

			showformheader("hr&operation=groupedit&do=$do&groupid=$groupid", 'enctype');
			showtableheader();
			showtitle('usergroups_basic');
			showsetting('name', 'titlenew', $usergroup['title'], 'text');
			if($usergroup['icon']) {
				$valueparse = parse_url($usergroup['icon']);
				if(isset($valueparse['host'])) {
					$groupicon = $usergroup['icon'];
				} else {
					$groupicon = $_G['setting']['attachurl'].'common/'.$usergroup['icon'].'?'.random(6);
				}
				$iconhtml = '<img src="'.$groupicon.'" />';
			}

			if($usergroup['banner']) {
				$valueparse = parse_url($usergroup['banner']);
				if(isset($valueparse['host'])) {
					$groupbanner = $usergroup['banner'];
				} else {
					$groupbanner = $_G['setting']['attachurl'].'common/'.$usergroup['banner'].'?'.random(6);
				}
				$bannerhtml = '<img src="'.$groupbanner.'" />';
			}

			$manager = array();
			if($usergroup['manageuid']) {
				$manager = DB::fetch_first("SELECT username FROM ".DB::table('common_member')." WHERE uid='$usergroup[manageuid]'");
			}

			showsetting('usergroups_icon', 'iconnew', $usergroup['icon'], 'filetext', '', 0, $iconhtml);
			showsetting('usergroups_banner', 'bannernew', $usergroup['banner'], 'filetext', '', 0, $bannerhtml);
			showsetting('hr_usergroup_description', 'descriptionnew', $usergroup['description'], 'textarea');
			showtitle('usergroups_permissible');
			showsetting('hr_usergroups_allowpost', 'allowpostnew', $usergroup['allowpost'], 'radio');
			showsetting('hr_usergroups_postdayper', 'postdaypernew', $usergroup['postdayper'], 'text');
			if($usergroup['type'] == 'intermediary') {
				showtitle('usergroups_manage');
				showsetting('hr_usergroups_manager', 'manageusernamenew', $manager['username'], 'text');
				showsetting('hr_usergroups_allowpush', 'allowpushnew', $usergroup['allowpush'], 'radio');
				showsetting('hr_usergroups_allowrecommend', 'allowrecommendnew', $usergroup['allowrecommend'], 'radio');
				showsetting('hr_usergroups_allowhighlight', 'allowhighlightnew', $usergroup['allowhighlight'], 'radio');
				showsetting('hr_usergroups_pushdayper', 'pushdaypernew', $usergroup['pushdayper'], 'text');
				showsetting('hr_usergroups_recommenddayper', 'recommenddaypernew', $usergroup['recommenddayper'], 'text');
				showsetting('hr_usergroups_highlightdayper', 'highlightdaypernew', $usergroup['highlightdayper'], 'text');
			}
			if($usergroup['type'] == 'company') {
				showtitle('usergroups_manage');
				showsetting('hr_usergroups_manager', 'manageusernamenew', $manager['username'], 'text');
				showsetting('hr_usergroups_allowpush', 'allowpushnew', $usergroup['allowpush'], 'radio');
				showsetting('hr_usergroups_allowrecommend', 'allowrecommendnew', $usergroup['allowrecommend'], 'radio');
				showsetting('hr_usergroups_allowhighlight', 'allowhighlightnew', $usergroup['allowhighlight'], 'radio');
				showsetting('hr_usergroups_pushdayper', 'pushdaypernew', $usergroup['pushdayper'], 'text');
				showsetting('hr_usergroups_recommenddayper', 'recommenddaypernew', $usergroup['recommenddayper'], 'text');
				showsetting('hr_usergroups_highlightdayper', 'highlightdaypernew', $usergroup['highlightdayper'], 'text');
			}
			showsubmit('groupsubmit', 'submit');
			showtablefooter();
			showformfooter();

		} else {

			if($_FILES['iconnew']) {
				$data = array('extid' => 'hr_'.$usergroup['gid']);
				$groupdata['icon'] = upload_icon_banner($data, $_FILES['iconnew'], '');
			} else {
				$groupdata['icon'] = $_GET['iconnew'];
			}

			if($_FILES['bannernew']) {
				$data = array('extid' => 'hr_'.$usergroup['gid']);
				$groupdata['banner'] = upload_icon_banner($data, $_FILES['bannernew'], '');
			} else {
				$groupdata['banner'] = $_GET['bannernew'];
			}

			if($_GET['manageusernamenew']) {
				$manageuid = DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[manageusernamenew]'");
				if(empty($manageuid)) {
					cpmsg(cplang('not_exists_manager'), '', 'error');
				}

				$usergroupid = DB::result_first("SELECT groupid FROM ".DB::table('hr_'.$do.'_member')." WHERE uid='$manageuid'");
				if(!empty($usergroupid) && $usergroupid != $groupid) {
					cpmsg(cplang('manager_not_group'), '', 'error');
				}
			}

			$groupdata = array(
				'title' => $_GET['titlenew'],
				'allowpost' => intval($_GET['allowpostnew']),
				'postdayper' => intval($_GET['postdaypernew']),
				'allowpush' => intval($_GET['allowpushnew']),
				'pushdayper' => intval($_GET['pushdaypernew']),
				'allowrecommend' => intval($_GET['allowrecommendnew']),
				'recommenddayper' => intval($_GET['recommenddaypernew']),
				'allowhighlight' => intval($_GET['allowhighlightnew']),
				'highlightdayper' => intval($_GET['highlightdaypernew']),
				'icon' => $groupdata['icon'],
				'banner' => $groupdata['banner'],
				'description' => trim($_GET['descriptionnew']),
				'manageuid' => $manageuid,
			);

			DB::update('hr_'.$do.'_usergroup', $groupdata, "gid='$groupid'");

			hrcache('usergroup', $do, '', $modidentifier);
			cpmsg(cplang('update_success'), "action=hr&operation=groupedit&do=$do&groupid=$groupid", 'succeed');

		}

	} else {
		cpmsg(cplang('please_select_usergroup'), "action=hr&operation=usergroup&do=$do", 'error');
	}


} elseif($operation == 'membergroup') {

	$_G['setting']['memberperpage'] = 20;
	$page = max(1, $_G['page']);
	$start_limit = ($page - 1) * $_G['setting']['memberperpage'];
	$urladd = !empty($_GET['username']) ? '&username='.$_GET['username'] : '';
	$where = '';

	shownav('job', 'menu_hr_member');
	showsubmenu('menu_hr_member', array(
		array('menu_hr_member', 'hr&operation=membergroup&do=job', 1),
		array('menu_hr_addusers', 'hr&operation=addusers', 0),
	));

	showformheader("hr&operation=membergroup&do=$do");
	showtableheader();
	showtitle('nav_hr_member_search');
	showsetting('username', 'username', $_GET['username'], 'text');
	showsubmit('memberseachsubmit', 'search');
	showtablefooter();
	showformfooter();


	if(!submitcheck('addgroupmember')) {

		if($_GET['username']) {
			$searchuid = DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[username]'");
			$where = "WHERE m.uid='$searchuid'";
		}
		$membernum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_member')." m $where");
		if($membernum > 0) {
			$multipage = multi($membernum, $_G['setting']['memberperpage'], $page, ADMINSCRIPT."?action=hr&operation=membergroup&do=$do&memberseachsubmit=yes".$urladd);
			$members = '';

			$query = DB::query("SELECT mc.uid, m.username, mc.groupid, mc.realname FROM ".DB::table('hr_'.$do.'_member')." mc
			LEFT JOIN ".DB::table('common_member')." m ON mc.uid=m.uid
			$where LIMIT $start_limit, ".$_G['setting']['memberperpage']);
			while($member = DB::fetch($query)) {
				$selectgroup = selectgroup($member['uid'], $member['groupid'], $cid, $do);
				$members .= showtablerow('', array('', ''), array(
					"<a href=\"home.php?mod=space&uid=$member[uid]\" target=\"_blank\">$member[username]</a>",
					"<input name=\"realname[$member[uid]]\" type=\"text\" size=\"10\" value=\"$member[realname]\">",
					$selectgroup,
					"<a href=\"".ADMINSCRIPT."?action=hr&operation=memberdetail&uid=$member[uid]&do=$do\">详情</a>"
				), TRUE);
			}
		}

		showformheader("hr&operation=membergroup&do=$do&memberseachsubmit=yes", 'enctype');
		showtableheader();
		showtitle('nav_hr_member_group');
		showsubtitle(array('username', '真实姓名', 'usergroup', '操作'));
		echo $members;
		showsubmit('addgroupmember', 'submit', '', '', $multipage);
		showtablefooter();
		showformfooter();

	} else {

		if($_GET['groupid']) {
			foreach($_GET['groupid'] as $uid => $groupid) {
				$uid = intval($uid);
				$data = array(
					'groupid' => intval($groupid),
					'realname' => trim($_GET['realname'][$uid])
				);
				DB::update('hr_'.$do.'_member', $data, "uid='$uid'");
			}

			//更新usergroup成员数
			updategroupmember($do);
		}

		cpmsg(cplang('update_success'), "action=hr&operation=membergroup&do=$do", 'succeed');

	}
} elseif($operation == 'memberdetail') {

	if(!submitcheck('memberdetailsubmit')) {
		
		shownav('job', '编辑用户');
		showsubmenu('编辑用户');

		showformheader("hr&operation=memberdetail&uid=".$_GET['uid']."&do=$do");
		showtableheader();

		$sql = "SELECT mc.uid, m.username, mc.groupid, mc.realname, mc.verify, mc.tel, mc.address, mc.city, mc.district, mc.street, mc.certification
		FROM ".DB::table('hr_'.$do.'_member')." mc
		LEFT JOIN ".DB::table('common_member')." m
		ON mc.uid=m.uid WHERE mc.uid=".$_GET['uid'];
		$member = DB::fetch_first($sql);
		
		global $_G;
		loadcache('hr_usergrouplist_'.$do);
		$group = array();
		foreach($_G['cache']['hr_usergrouplist_'.$do] as $gid => $ginfo) {
			$group[] = array($gid, $ginfo['title']);
		}

		showtitle('用户 -- '.$member['username']);
		showhiddenfields(array('uid' => $member['uid']));
		showsetting('真实姓名', 'realname', $member['realname'], 'text');
		showsetting('所属中介公司', array('groupid', $group), $member['groupid'], 'select');
		showsetting('电话', 'tel', $member['tel'], 'text');
		showsetting('工作地址', 'address', $member['address'], 'text');
		showsetting('服务区域', '', '', showservicearea($member['city'], $member['district'], $member['street'], $do));
		showsetting('认证', array('certification', array('实名认证', '身份证认证', '手机认证')), $member['certification'], 'binmcheckbox');

		showsubmit('memberdetailsubmit');
		showtablefooter();
		showformfooter();

	} else {

		DB::update('hr_'.$do.'_member', array(
			'realname' => dhtmlspecialchars(trim($_GET['realname'])),
			'groupid' => intval($_GET['groupid']),
			'tel' => dhtmlspecialchars(trim($_GET['tel'])),
			'address' => dhtmlspecialchars(trim($_GET['address'])),
			'city' => intval($_GET['city']),
			'district' => intval($_GET['district']),
			'street' => intval($_GET['street']),
			'certification' => bindec(intval($_GET['certification'][3]).intval($_GET['certification'][2]).intval($_GET['certification'][1]))
		), "uid='".intval($_GET['uid'])."'");

		//更新usergroup成员数
		updategroupmember($do);

		cpmsg('更新用户信息成功', 'action=hr&operation=membergroup&do='.$do, 'succeed');
	}
} elseif($operation == 'resume') {
	require_once libfile('hr/resume', 'admincp');
} 
elseif($operation == 'invite') {
	require_once libfile('hr/invite', 'admincp');
} 
elseif($operation == 'resumeverify') {
	require_once libfile('hr/resume_verify', 'admincp');
} elseif($operation == 'cache') {
		shownav('job', '更新缓存');
	$cachearray = array('hrsort', 'sortlist', 'channellist', 'arealist', 'usergroup');
	foreach($cachearray as $cachename) {
		hrcache($cachename, $do);
	}

	cpmsg(cplang('update_success'), '', 'succeed');

} elseif($operation == 'counter') {
	
	$pertask = isset($_GET['pertask']) ? intval($_GET['pertask']) : 100;
	$current = isset($_GET['current']) && $_GET['current'] > 0 ? intval($_GET['current']) : 0;
	$next = $current + $pertask;
	
	if(submitcheck('userthreadsubmit', 1)) {
		$nextlink = "action=hr&operation=counter&current=$next&pertask=$pertask&userthreadsubmit=yes&do=$do";
		$processed = 0;
		$queryc = DB::query("SELECT uid, threads FROM ".DB::table('hr_'.$do.'_member')." LIMIT $current, $pertask");
		while($member = DB::fetch($queryc)) {
			$processed = 1;
			$threadcount = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_thread')." WHERE authorid='$member[uid]'");
			if($member['threads'] != $threadcount) {
				DB::query("UPDATE LOW_PRIORITY ".DB::table('hr_'.$do.'_member')." SET threads='$threadcount' WHERE uid='$member[uid]'", 'UNBUFFERED');
			}
		}
		
		if($processed) {
			cpmsg("更新用户发帖数: ".cplang('counter_processing', array('current' => $current, 'next' => $next)), $nextlink, 'loading');
		} else {
			cpmsg('更新用户发帖数完成', 'action=hr&operation=counter&do='.$do, 'succeed');
		}
		
	} elseif(submitcheck('agentmembersubmit', 1)) {
		$nextlink = "action=hr&operation=counter&current=$next&pertask=$pertask&agentmembersubmit=yes&do=$do";
		$processed = 0;
		$queryc = DB::query("SELECT gid, membernum FROM ".DB::table('hr_'.$do.'_usergroup')." LIMIT $current, $pertask");
		while($group = DB::fetch($queryc)) {
			$processed = 1;
			$membercount = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_member')." WHERE groupid='$group[gid]'");
			if($group['membernum'] != $membercount) {
				DB::query("UPDATE LOW_PRIORITY ".DB::table('hr_'.$do.'_usergroup')." SET membernum='$membercount' WHERE gid='$group[gid]'", 'UNBUFFERED');
			}
		}
		hrcache('usergroup', $do);
		
		if($processed) {
			cpmsg("更新中介公司成员数: ".cplang('counter_processing', array('current' => $current, 'next' => $next)), $nextlink, 'loading');
		} else {
			cpmsg('更新中介公司成员数完成', 'action=hr&operation=counter&do='.$do, 'succeed');
		}
		
	} elseif(submitcheck('agentjobsubmit', 1)) { 
		loadcache(array('hr_sortlist_'.$do));
		$nextlink = "action=hr&operation=counter&current=$next&pertask=$pertask&agentjobsubmit=yes&do=$do";
		$processed = 0;
		$queryc = DB::query("SELECT gid, threads FROM ".DB::table('hr_'.$do.'_usergroup')." LIMIT $current, $pertask");
		while($group = DB::fetch($queryc)) {
			$processed = 1;
			$threadnum = 0;
			foreach($_G['cache']['hr_sortlist_'.$do] as $sortid => $sort) {
				$sortthreads = DB::result_first("SELECT COUNT(tid) FROM ".DB::table('hr_sortvalue')."$sortid where groupid=$group[gid]");
				$threadnum += $sortthreads;
			}
			if($group['threads'] != $threadnum) {
				DB::query("UPDATE LOW_PRIORITY ".DB::table('hr_'.$do.'_usergroup')." SET threads='$threadnum' WHERE gid='$group[gid]'", 'UNBUFFERED');
			}
		}
		hrcache('usergroup', $do);
		
		if($processed) {
			cpmsg("更新中介公司房源数: ".cplang('counter_processing', array('current' => $current, 'next' => $next)), $nextlink, 'loading');
		} else {
			cpmsg('更新中介公司房源数完成', 'action=hr&operation=counter&do='.$do, 'succeed');
		}
		
	} else {
		shownav('job', '数据统计');
		showsubmenu('数据统计');
		showformheader("hr&operation=counter&do=$do");
		showtableheader();
		showsubtitle(array('', 'counter_amount'));
		showhiddenfields(array('pertask' => ''));
		showtablerow('', array('class="td21"'), array(
			"用户发帖数:",
			'<input name="pertask1" type="text" class="txt" value="100" /><input type="submit" class="btn" name="userthreadsubmit" onclick="this.form.pertask.value=this.form.pertask1.value" value="'.$lang['submit'].'" />'
		));
		showtablerow('', array('class="td21"'), array(
			"中介公司成员数:",
			'<input name="pertask2" type="text" class="txt" value="10" /><input type="submit" class="btn" name="agentmembersubmit" onclick="this.form.pertask.value=this.form.pertask2.value" value="'.$lang['submit'].'" />'
		));
		showtablerow('', array('class="td21"'), array(
			"中介公司信息数:",
			'<input name="pertask3" type="text" class="txt" value="10" /><input type="submit" class="btn" name="agentjobsubmit" onclick="this.form.pertask.value=this.form.pertask3.value" value="'.$lang['submit'].'" />'
		));
		
		showtablefooter();
		showformfooter();
	}
	
} elseif($operation == 'classlist') {

	$classoptions = '';
	$classidarray = array();
	$classid = $_GET['classid'] ? $_GET['classid'] : 0;
	$query = DB::query("SELECT optionid, title FROM ".DB::table('hr_sortoption')." WHERE classid='$classid' ORDER BY displayorder");
	while($option = DB::fetch($query)) {
		$classidarray[] = $option['optionid'];
		$classoptions .= "<a href=\"#ol\" onclick=\"ajaxget('".ADMINSCRIPT."?action=hr&operation=optionlist&typeid={$_GET['typeid']}&classid=$option[optionid]', 'optionlist', 'optionlist', 'Loading...', '', checkedbox)\">$option[title]</a> &nbsp; ";
	}

	include template('common/header');
	echo $classoptions;
	include template('common/footer');
	exit;

} elseif($operation == 'optionlist') {
	$classid = $_GET['classid'];
	if(!$classid) {
		//note 取顶层分类
		$classid = DB::result_first("SELECT optionid FROM ".DB::table('hr_sortoption')." WHERE classid='0' ORDER BY displayorder LIMIT 1");//note 小分类
	}
	$query = DB::query("SELECT optionid FROM ".DB::table('hr_sortvar')." WHERE sortid='{$_GET['typeid']}'");
	$option = $options = array();
	while($option = DB::fetch($query)) {
		$options[] = $option['optionid'];
	}

	$optionlist = '';
	$query = DB::query("SELECT * FROM ".DB::table('hr_sortoption')." WHERE classid='$classid' ORDER BY displayorder");
	while($option = DB::fetch($query)) {
		$optionlist .= "<input ".(in_array($option['optionid'], $options) ? ' checked="checked" ' : '')."class=\"checkbox\" type=\"checkbox\" name=\"typeselect[]\" id=\"typeselect_$option[optionid]\" value=\"$option[optionid]\" onclick=\"insertoption(this.value);\" /><label for=\"typeselect_$option[optionid]\">".dhtmlspecialchars($option['title'])."</label>&nbsp;&nbsp;";
	}
	include template('common/header');
	echo $optionlist;
	include template('common/footer');
	exit;

} elseif($operation == 'sortlist') {
	$optionid = $_GET['optionid'];
	$option = DB::fetch_first("SELECT * FROM ".DB::table('hr_sortoption')." WHERE optionid='$optionid' LIMIT 1");
	include template('common/header');
	$option['type'] = $lang['threadtype_edit_vars_type_'. $option['type']];
	$option['available'] = 1;
	showtablerow('', array('class="td25"', 'class="td28 td23"', '', 'title="'.$lang['threadtype_edit_vars_type_'. $option['type']].'"'), array(
		"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$option[optionid]\" ".($option['model'] ? 'disabled' : '').">",
		"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayorder[$option[optionid]]\" value=\"$option[displayorder]\">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"available[$option[optionid]]\" value=\"1\" ".($option['available'] ? 'checked' : '')." ".($option['model'] ? 'disabled' : '').">",
		dhtmlspecialchars($option['title']),
		"<input class=\"checkbox\" type=\"checkbox\" name=\"required[$option[optionid]]\" value=\"1\" ".($option['required'] ? 'checked' : '')." ".($option['model'] ? 'disabled' : '').">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"unchangeable[$option[optionid]]\" value=\"1\" ".($option['unchangeable'] ? 'checked' : '').">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"search[$option[optionid]]\" value=\"1\" ".($option['search'] ? 'checked' : '').">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"subjectshow[$option[optionid]]\" value=\"1\" ".($option['subjectshow'] ? 'checked' : '').">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"visitedshow[$option[optionid]]\" value=\"1\" ".($option['visitedshow'] ? 'checked' : '').">",
		"<input class=\"checkbox\" type=\"checkbox\" name=\"orderbyshow[$option[optionid]]\" value=\"1\" ".($option['orderbyshow'] ? 'checked' : '')." ".(!in_array($option['type'], array('number', 'range')) ? 'disabled' : '').">",
		"<a href=\"###\" onclick=\"insertvar('$option[identifier]', 'typetemplate', 'message');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_template']."</a><a href=\"###\" onclick=\"insertvar('$option[identifier]', 'stypetemplate', 'subject');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_stemplate']."</a><a href=\"###\" onclick=\"insertvar('$option[identifier]', 'ptypetemplate', 'post');doane(event);return false;\" class=\"act\">".$lang['threadtype_infotypes_add_ptemplate']."</a>",
		""
	));
	include template('common/footer');
	exit;

} elseif($operation == 'attach') {
		shownav('job', '上传设置');
	if(!submitcheck('attachsubmit')) {
		showformheader("hr&operation=attach");
		showhiddenfields(array('operation' => $operation));
		$imageinfo = DB::result_first("SELECT imageinfo FROM ".DB::table('hr_channel'));
		$channel = $imageinfo ? unserialize($imageinfo) : array();
		$checkwm = array($channel['watermarkstatus'] => 'checked');
		$checkmkdirfunc = !function_exists('mkdir') ? 'disabled' : '';
		$channel['watermarktext']['fontpath'] = str_replace(array('ch/', 'en/'), '', $channel['watermarktext']['fontpath']);

		$fontlist = '<select name="channel[watermarktext][fontpath]">';
		$dir = opendir(DISCUZ_ROOT.'./static/image/seccode/font/en');
		while($entry = readdir($dir)) {
			if(in_array(strtolower(fileext($entry)), array('ttf', 'ttc'))) {
				$fontlist .= '<option value="'.$entry.'"'.($entry == $channel['watermarktext']['fontpath'] ? ' selected>' : '>').$entry.'</option>';
			}
		}
		$dir = opendir(DISCUZ_ROOT.'./static/image/seccode/font/ch');
		while($entry = readdir($dir)) {
			if(in_array(strtolower(fileext($entry)), array('ttf', 'ttc'))) {
				$fontlist .= '<option value="'.$entry.'"'.($entry == $channel['watermarktext']['fontpath'] ? ' selected>' : '>').$entry.'</option>';
			}
		}
		$fontlist .= '</select>';
		showsubmenu('上传设置');
		showtableheader('', '', 'id="basic"');
		showsetting('setting_attach_image_lib', array('channel[imagelib]', array(
		array(0, $lang['setting_attach_image_watermarktype_GD'], array('imagelibext' => 'none')),
		array(1, $lang['setting_attach_image_watermarktype_IM'], array('imagelibext' => ''))
		)), $channel['imagelib'], 'mradio');
		showtagheader('tbody', 'imagelibext', $channel['imagelib'], 'sub');
		showsetting('setting_attach_image_impath', 'channel[imageimpath]', $channel['imageimpath'], 'text');
		showtagfooter('tbody');
		showsetting('setting_attach_image_watermarkstatus', '', '', '<table style="margin-bottom: 3px; margin-top:3px;"><tr><td colspan="3"><input class="radio" type="radio" name="channel[watermarkstatus]" value="0" '.$checkwm[0].'>'.$lang['setting_attach_image_watermarkstatus_none'].'</td></tr><tr><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="1" '.$checkwm[1].'> #1</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="2" '.$checkwm[2].'> #2</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="3" '.$checkwm[3].'> #3</td></tr><tr><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="4" '.$checkwm[4].'> #4</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="5" '.$checkwm[5].'> #5</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="6" '.$checkwm[6].'> #6</td></tr><tr><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="7" '.$checkwm[7].'> #7</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="8" '.$checkwm[8].'> #8</td><td><input class="radio" type="radio" name="channel[watermarkstatus]" value="9" '.$checkwm[9].'> #9</td></tr></table>');
		showsetting('setting_attach_image_watermarkminwidthheight', array('channel[watermarkminwidth]', 'channel[watermarkminheight]'), array(intval($channel['watermarkminwidth']), intval($channel['watermarkminheight'])), 'multiply');
		showsetting('setting_job_attach_image_watermarktype', array('channel[watermarktype]', array(
		array('gif', $lang['setting_attach_image_watermarktype_gif'], array('watermarktypeext' => 'none')),
		array('png', $lang['setting_attach_image_watermarktype_png'], array('watermarktypeext' => 'none')),
		array('text', $lang['setting_attach_image_watermarktype_text'], array('watermarktypeext' => ''))
		)), $channel['watermarktype'], 'mradio');
		showsetting('setting_attach_image_watermarktrans', 'channel[watermarktrans]', $channel['watermarktrans'], 'text');
		showsetting('setting_attach_image_watermarkquality', 'channel[watermarkquality]', $channel['watermarkquality'], 'text');
		showtagheader('tbody', 'watermarktypeext', $channel['watermarktype'] == 'text', 'sub');
		showsetting('setting_attach_image_watermarktext_text', 'channel[watermarktext][text]', $channel['watermarktext']['text'], 'textarea');
		showsetting('setting_attach_image_watermarktext_fontpath', '', '', $fontlist);
		showsetting('setting_attach_image_watermarktext_size', 'channel[watermarktext][size]', $channel['watermarktext']['size'], 'text');
		showsetting('setting_attach_image_watermarktext_angle', 'channel[watermarktext][angle]', $channel['watermarktext']['angle'], 'text');
		showsetting('setting_attach_image_watermarktext_color', 'channel[watermarktext][color]', $channel['watermarktext']['color'], 'color');
		showsetting('setting_attach_image_watermarktext_shadowx', 'channel[watermarktext][shadowx]', $channel['watermarktext']['shadowx'], 'text');
		showsetting('setting_attach_image_watermarktext_shadowy', 'channel[watermarktext][shadowy]', $channel['watermarktext']['shadowy'], 'text');
		showsetting('setting_attach_image_watermarktext_shadowcolor', 'channel[watermarktext][shadowcolor]', $channel['watermarktext']['shadowcolor'], 'color');
		showsetting('setting_attach_image_watermarktext_imtranslatex', 'channel[watermarktext][translatex]', $channel['watermarktext']['translatex'], 'text');
		showsetting('setting_attach_image_watermarktext_imtranslatey', 'channel[watermarktext][translatey]', $channel['watermarktext']['translatey'], 'text');
		showsetting('setting_attach_image_watermarktext_imskewx', 'channel[watermarktext][skewx]', $channel['watermarktext']['skewx'], 'text');
		showsetting('setting_attach_image_watermarktext_imskewy', 'channel[watermarktext][skewy]', $channel['watermarktext']['skewy'], 'text');
		showtagfooter('tbody');
		showsubmit('attachsubmit');
		showtablefooter();
		showformfooter();
		exit;

	} else {

		$channelnew = $_GET['channel'];
		if(isset($channelnew['watermarktext'])) {
			$channelnew['watermarktext']['size'] = intval($channelnew['watermarktext']['size']);
			$channelnew['watermarktext']['angle'] = intval($channelnew['watermarktext']['angle']);
			$channelnew['watermarktext']['shadowx'] = intval($channelnew['watermarktext']['shadowx']);
			$channelnew['watermarktext']['shadowy'] = intval($channelnew['watermarktext']['shadowy']);
			$channelnew['watermarktext']['fontpath'] = str_replace(array('\\', '/'), '', $channelnew['watermarktext']['fontpath']);
			if($channelnew['watermarktype'] == 'text' && $channelnew['watermarktext']['fontpath']) {
				$fontpath = $channelnew['watermarktext']['fontpath'];
				$fontpathnew = 'ch/'.$fontpath;
				$channelnew['watermarktext']['fontpath'] = file_exists('static/image/seccode/font/'.$fontpathnew) ? $fontpathnew : '';
				if(!$channelnew['watermarktext']['fontpath']) {
					$fontpathnew = 'en/'.$fontpath;
					$channelnew['watermarktext']['fontpath'] = file_exists('static/image/seccode/font/'.$fontpathnew) ? $fontpathnew : '';
				}
				if(!$channelnew['watermarktext']['fontpath']) {
					cpmsg('watermarkpreview_fontpath_error', '', 'error');
				}
			}
		}

		DB::update('hr_channel', array(
		'imageinfo' => serialize($channelnew)
		), "cid='$cid'");

		hrcache('channellist');
		cpmsg('threadtype_infotypes_option_succeed', 'action=hr&operation=attach&do='.$do, 'succeed');
	}
/*正版验证*/
} elseif($operation == 'checkvip') {

		shownav('job', '正版验证');
		$jobwebsite = $_SERVER['HTTP_HOST'];
	 	$jobwebsite2=explode(".",$jobwebsite);
		$jobwebsite3=explode(":",$jobwebsite2[0]);
		if($jobwebsite2[0] == '127' || $jobwebsite2[0] == '192' || $jobwebsite2[0] == '10' || $jobwebsite3[0] == 'localhost'){
        	cpmsg('localhost', '', 'error');
		}else{
			$type = file_get_contents("htt"."p://ww"."w.ku"."oz"."han.n"."et/cus"."tom"."erchec"."k.p"."hp?s"."ite=$jobwebsite&type=$do");
			if (empty($type)){
				$type = checkvip_get_url_content("htt"."p://ww"."w.ku"."oz"."han.n"."et/cus"."tom"."erchec"."k.p"."hp?s"."ite=$jobwebsite&type=$do");
				if (empty($type)){
					cpmsg('暂时无法查询您的服务，可能您的服务器无法连接到我们的正版服务提供处', '', 'error');
				}elseif ($type == $do){
					cpmsg('您使用的是正版服务请放心使用', '', 'succeed');
				}elseif(!empty($type) && $type !== $do){
					cpmsg('您未购买正版服务，请尽快购买，我们保留法律追究盗版使用的权利，请到www.kuozhan.net购买正版服务', 'http://www.kuozhan.net/', 'error');
    			}
			}elseif ($type == 'job'){
				cpmsg('您使用的是正版服务请放心使用', '', 'succeed');
			}elseif(!empty($type) && $type !== 'job'){
				cpmsg('您未购买正版服务，请尽快购买，我们保留法律追究盗版使用的权利，请到www.kuozhan.net购买正版服务', 'http://www.kuozhan.net/', 'error');
        	}
        }

} elseif($operation == 'jump'){
	header('location:'.$_GET[url]);
} elseif($operation == 'addusers') {

	if(!submitcheck('adduserssubmit')) {

		shownav('job', 'menu_hr_usergroup');
		showsubmenu('menu_hr_member', array(
			array('menu_hr_member', 'hr&operation=membergroup&do=job', 0),
			array('menu_hr_addusers', 'hr&operation=addusers', 1),
		));

		$_GET['gid'] = intval($_GET['gid']);
			
		global $_G;
		loadcache('hr_usergrouplist_'.$do);
		$usergrouptitle = array();
		$threadtypes = '<select name="sortid" onchange="window.location.href = \'?action=hr&operation=addusers&do='.$do.'&gid=\'+ this.options[this.selectedIndex].value"><option value="0">'.cplang('all').'</option>';
		foreach($_G['cache']['hr_usergrouplist_'.$do] as $gid => $usergroup) {
			$usergrouptitle[$usergroup['gid']] = $usergroup['title'];
			$threadtypes .= '<option value="'.$usergroup['gid'].'" '.($_GET['gid'] == $usergroup['gid'] ? 'selected="selected"' : '').'>'.dhtmlspecialchars($usergroup['title']).'</option>';
		}
		$threadtypes .= '</select>';

		showformheader('hr&operation=addusers&gid='.$_GET['gid'].'&do='.$do);
		showtableheader('hr_select_usergroup');
		showsetting('hr_usergroup_name', '', '', $threadtypes);

		showtableheader('hr_usergroup_manger');
		$_G['setting']['memberperpage'] = 20;
		$page = max(1, $_G['page']);
		$start_limit = ($page - 1) * $_G['setting']['memberperpage'];
		$addgid = $_GET['gid'] ? "AND hm.groupid='".intval($_GET['gid'])."' AND hm.verify='1'" : "AND hm.groupid>'1' AND hm.verify='1'";

		$members = '';
		$membernum = DB::result_first("SELECT COUNT(*)
			FROM ".DB::table('common_member')." m, ".DB::table('hr_'.$do.'_member')." hm
			WHERE m.uid=hm.uid $addgid");
		if($membernum > 0) {
			$multipage = multi($membernum, $_G['setting']['memberperpage'], $page, ADMINSCRIPT."?action=hr&operation=addusers&do=$do&gid=$_GET[gid]");
			$query = DB::query("SELECT m.username, m.uid, m.email, hm.groupid
				FROM ".DB::table('common_member')." m, ".DB::table('hr_'.$do.'_member')." hm
				WHERE m.uid=hm.uid $addgid LIMIT $start_limit, ".$_G['setting']['memberperpage']);
			while($member = DB::fetch($query)) {
				$members .= showtablerow('', array('class="td25"', 'class="td28"', 'class="td28"', 'class="td28"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$member[uid]\">",
					"<a href=\"job.php?mod=broker&action=my&uid=$member[uid]\" class=\"act nowrap\" target=\"_blank\">".dhtmlspecialchars($member['username'])."</a>",
					"*******",
					"$member[email]",
					$usergrouptitle[$member['groupid']]
				), TRUE);
			}
		}
?>
<script type="text/JavaScript">
var rowtypedata = [
	[
		[1, '', 'td25'],
		[1, '<input type="text" class="txt" name="newusernames[]" size="15" value="">', 'td29'],
		[1, '<input type="text" class="txt" name="newpasswords[]" size="15">'],
		[1, '<input type="text" class="txt" name="newemails[]" size="30" value="">', 'td29'],
		[1, ''],
		[2, '']
	],
];
</script>
<?php
		shownav('job', 'menu_hr_usergroup');
		showformheader('hr&operation=addusers&gid='.$_GET['gid'].'&do=$do');
		showtableheader('');
		showsubtitle(array('', 'username', 'password', 'email', '所属组'));
		echo $members;
		echo '<tr><td></td><td colspan="6"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['cplog_members_add'].'</a></div></td>';
		showsubmit('adduserssubmit', 'submit', 'del', '', $multipage);
		showtablefooter();
		showformfooter();

	} else {

		$groupid = intval($_GET['gid']);
		$newusernames = $_GET['newusernames'];
		$newpasswords = $_GET['newpasswords'];
		$newemails = $_GET['newemails'];

		if($deletes = dimplode($_GET['delete'])) {
			DB::update('hr_'.$do.'_member', array('groupid' => '1'), "uid IN ($deletes)");
		}

		if($newusernames && $newpasswords && $newemails && $groupid) {
			foreach($newusernames as $key => $newusername) {

				if(!$newusername || !$newpasswords[$key] || !$newemails[$key]) {
					continue;
				} else {
					$useruid = DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$newusername'");
					if($useruid) {
						if($usergroupid = DB::result_first("SELECT groupid FROM ".DB::table('hr_'.$do.'_member')." WHERE uid='$useruid'")) {
							if($usergroupid && $usergroupid != $groupid) {
								DB::update('hr_'.$do.'_member', array('groupid' => $groupid), "uid='$useruid'");
							} else {
								continue;
							}
						} else {
      						DB::insert('hr_'.$do.'_member', array('uid' => $useruid, 'groupid' => $groupid, 'cid' => 0, 'threads' => 0, 'todaythreads' => 0, 'todaypush' => 0, 'todayrecommend' => 0, 'todayhighlight' => 0, 'lastpost' => 0));
						}
					} else {
						loaducenter();
						$uid = uc_user_register($newusername, $newpasswords[$key], $newemails[$key]);
						if($uid <= 0) {
							continue;
						}
						$data = array(
							'uid' => $uid,
							'username' => $newusername,
							'password' => md5(random(10)),
							'email' => $newemails[$key],
							'adminid' => 0,
							'groupid' => 10,
							'regdate' => $_G['timestamp'],
							'credits' => 0,
						);
						DB::insert('common_member', $data);
						DB::insert('common_member_profile', array('uid' => $uid));
						DB::insert('common_member_field_forum', array('uid' => $uid));
						DB::insert('common_member_field_home', array('uid' => $uid));
						DB::insert('common_member_status', array('uid' => $uid, 'regip' => 'Manual Acting', 'lastvisit' => $_G['timestamp'], 'lastactivity' => $_G['timestamp']));
						$init_arr = explode(',', $_G['setting']['initcredits']);
						$count_data = array(
							'uid' => $uid,
							'extcredits1' => $init_arr[0],
							'extcredits2' => $init_arr[1],
							'extcredits3' => $init_arr[2],
							'extcredits4' => $init_arr[3],
							'extcredits5' => $init_arr[4],
							'extcredits6' => $init_arr[5],
							'extcredits7' => $init_arr[6],
							'extcredits8' => $init_arr[7]
							);
						DB::insert('common_member_count', $count_data);
						manyoulog('user', $uid, 'add');

						DB::insert('hr_'.$do.'_member', array('uid' => $uid, 'groupid' => $groupid, 'cid' => 0, 'threads' => 0, 'todaythreads' => 0, 'todaypush' => 0, 'todayrecommend' => 0, 'todayhighlight' => 0, 'lastpost' => 0));
					}
				}
			}
		}
		updatecache('setting');
		updategroupmember($do);
		cpmsg('hr_user_add_success', 'action=hr&operation=addusers&gid='.$_GET['gid'].'&do='.$do, 'succeed');
	}
}

function showsort($cate, $rank = '', $last = '') {//显示活动分类
	if($last == '') {
		$return = '<tr class="hover"><td class="td25"><input type="checkbox" class="checkbox" name="delete[]" value="'.$cate['sid'].'" /></td><td class="td25"><input type="text" class="txt" name="order['.$cate['sid'].']" value="'.$cate['displayorder'].'" /></td><td>';
		if($rank == '1') {
			$return .= '<div class="parentboard">';
		} elseif($rank == '2') {
			$return .= '<div class="board">';
		} elseif($rank == '3') {
			$return .= '<div id="cb_'.$cate['sid'].'" class="childboard">';
		}

		$return .= '<input type="text" name="name['.$cate['sid'].']" value="'.htmlspecialchars($cate['sname']).'" class="txt" />';
		$return .= $rank == '2' ? '<a href="###" onclick="addrowdirect = 1;addrow(this, 2, '.$cate['sid'].')" class="addchildboard">添加三级分类</a>' : '';
		$return .= '</div></td></tr>';
	} else {
		if($last == 'lastboard') {
			$return = '<tr><td></td><td colspan="3"><div class="lastboard"><a href="###" onclick="addrow(this, 1, '.$cate['sid'].')" class="addtr">添加二级分类</a></div></td></tr>';
		} elseif($last == 'lastchildboard' && $rank) {
			$return = '<script type="text/JavaScript">$(\'cb_'.$rank.'\').className = \'lastchildboard\';</script>';
		} elseif($last == 'last') {
			$return = '<tr><td colspan="3"><div><a href="###" onclick="addrow(this, 0)" class="addtr">添加一级分类</a></div></td></tr>';
		}
	}
	echo $return;
}

function selectgroup($uid, $groupid, $cid, $identifier) {
	global $_G;
	loadcache('hr_usergrouplist_'.$identifier);
	$usergroups = '<select name="groupid['.$uid.']">';
	foreach($_G['cache']['hr_usergrouplist_'.$identifier] as $gid => $group) {
		$usergroups .= '<option value="'.$gid.'" '.($gid == $groupid ? 'selected=selected' : '').'>'.$group['title'].'</option>';
	}
	$usergroups .= '</select>';

	return $usergroups;
}

function showoption($var, $type) {
	global $optiontitle, $lang;
	if($optiontitle[$var]) {
		$optiontitle[$var] = $type == 'title' ? $optiontitle[$var] : $optiontitle[$var].($type == 'value' ? $lang['value'] : $lang['unit']);
		return $optiontitle[$var];
	} else {
		return "!$var!";
	}
}

function syntablestruct($sql, $version, $dbcharset) {

	if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
		return $sql;
	}

	$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;

	if($sqlversion === $version) {

		return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
	}

	if($version) {
		return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);

	} else {
		return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
	}
}

function showhr(&$cate, $type = '', $last = '') {
	if($last == '') {
		$return = '<tr class="hover"><td class="td25"><input type="checkbox" class="checkbox" name="delete[]" value="'.$cate['aid'].'" /></td><td class="td25"><input type="text" class="txt" name="order['.$cate['aid'].']" value="'.$cate['displayorder'].'" /></td><td>';
		if($type == 'city') {
			$return .= '<div class="parentboard">';
		} elseif($type == '') {
			$return .= '<div class="board">';
		} elseif($type == 'street') {
			$return .= '<div id="cb_'.$cate['aid'].'" class="childboard">';
		}

		$return .= '<input type="text" name="name['.$cate['aid'].']" value="'.htmlspecialchars($cate['title']).'" class="txt" />';
		$return .= $type == '' ? '<a href="###" onclick="addrowdirect = 1;addrow(this, 2, '.$cate['aid'].')" class="addchildboard">'.cplang('hr_add_street').'</a>' : '';
		$return .= '</div></td></tr>';
	} else {
		if($last == 'lastboard') {
			$return = '<tr><td></td><td colspan="3"><div class="lastboard"><a href="###" onclick="addrow(this, 1, '.$cate['aid'].')" class="addtr">'.cplang('hr_add_city').'</a></div></td></tr>';
		} elseif($last == 'lastchildboard' && $type) {
			$return = '<script type="text/JavaScript">$(\'cb_'.$type.'\').className = \'lastchildboard\';</script>';
		} elseif($last == 'last') {
			$return = '<tr><td colspan="3"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.cplang('hr_add_province').'</a></div></td></tr>';
		}
	}
	echo $return;
}

function showtype($cate, $rank = '', $last = '') {
	if($last == '') {
		$return = '<tr class="hover"><td class="td25"><input type="checkbox" class="checkbox" name="delete[]" value="'.$cate['id'].'" /></td><td class="td25"><input type="text" class="txt" name="order['.$cate['id'].']" value="'.$cate['displayorder'].'" /></td><td>';
		if($rank == '1') {
			$return .= '<div class="parentboard">';
		} elseif($rank == '2') {
			$return .= '<div class="board">';
		} elseif($rank == '3') {
			$return .= '<div id="cb_'.$cate['aid'].'" class="childboard">';
		}

		$return .= '<input type="text" name="name['.$cate['id'].']" value="'.htmlspecialchars($cate['name']).'" class="txt" />';
		$return .= $rank == '2' ? '<a href="###" onclick="addrowdirect = 1;addrow(this, 2, '.$cate['id'].')" class="addchildboard">添加三级分类</a>' : '';
		$return .= '</div></td></tr>';
	} else {
		if($last == 'lastboard') {
			$return = '<tr><td></td><td colspan="3"><div class="lastboard"><a href="###" onclick="addrow(this, 1, '.$cate['id'].')" class="addtr">添加二级分类</a></div></td></tr>';
		} elseif($last == 'lastchildboard' && $rank) {
			$return = '<script type="text/JavaScript">$(\'cb_'.$rank.'\').className = \'lastchildboard\';</script>';
		} elseif($last == 'last') {
			$return = '<tr><td colspan="3"><div><a href="###" onclick="addrow(this, 0)" class="addtr">添加一级类型</a></div></td></tr>';
		}
	}
	echo $return;
}



function updateinformation($cid, $do) {

	global $_G;
	$db = DB::object();
	$siteuniqueid = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='siteuniqueid'");
	$update = array('uniqueid' => $siteuniqueid, 'version' => JOB_VERSION, 'release' => JOB_RELEASE, 'bbname' => $_G['setting']['bbname']);

	$sortnum = array();
	$updatetime = @filemtime(DISCUZ_ROOT.'./data/hrupdatetime.lock');
	if(empty($updatetime) || (TIMESTAMP - $updatetime > 3600 * 4)) {
		@touch(DISCUZ_ROOT.'./data/hrupdatetime.lock');
		$update['members'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_member')."");
		$update['threads'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_thread')."");
		$update['usergroups'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$do.'_usergroup')."");
		$update['areas'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_area')." WHERE cid='$cid'");
		$update['sorts'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_sort')." WHERE cid='$cid'");
		$query = DB::query("SELECT sortid, name FROM ".DB::table('hr_sort')." WHERE cid='$cid'");
		while($sort = DB::fetch($query)) {
			$sortnum[$sort['name']] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_sortvalue')."$sort[sortid]");
			$update['sortnum'] .= $sort['name'].'|'.$sortnum[$sort['name']]."\t";
		}
	}

	$data = '';
	foreach($update as $key => $value) {
		$data .= $key.'='.rawurlencode($value).'&';
	}

	echo '<div style="display:none;"><img src="ht'.'tp:/'.'/cus'.'tome'.'r.disc'.'uz.n'.'et/n'.'ews'.'.p'.'hp?os='.$do.'&update='.rawurlencode(base64_encode($data)).'&md5hash='.substr(md5($_SERVER['HTTP_USER_AGENT'].implode('', $update).TIMESTAMP), 8, 8).'&timestamp='.TIMESTAMP.'"/></div>';

}

function countmembers($condition) {
	include_once libfile('class/membersearch');
	$ms = new membersearch();
	return $ms->getcount($condition);
}

function searchmembers($condition) {
	include_once libfile('class/membersearch');
	$ms = new membersearch();
	return $ms->search($condition, 1000);
}

function showservicearea($u_city, $u_district, $u_street, $mod) {
	global $_G;
	loadcache('hr_arealist_'.$mod);
	$arealist = $_G['cache']['hr_arealist_'.$mod];
	$citylist = $districtlist = $streetlist = '';

	foreach($arealist['city'] as $cityid => $city) {
		$citylist .= '<option value="'.$cityid.'" '.($u_city == $cityid ? 'selected="selected"' : '').'>'.$city.'</option>';
	}

	if($u_city) {
		foreach($arealist['district'][$u_city] as $districtid => $district) {
			$districtlist .= '<option value="'.$districtid.'" '.($u_district == $districtid ? 'selected="selected"' : '').'>'.$district.'</option>';
		}
	}

	if($u_district) {
		foreach($arealist['street'][$u_district] as $streetid => $street) {
			$streetlist .= '<option value="'.$streetid.'" '.($u_street == $streetid ? 'selected="selected"' : '').'>'.$street.'</option>';
		}
	}

	$areahtml = '<select name="city" style="width:auto" onchange="ajaxget(\'hr.php?mod=misc&action=area&do=job&cityid=\'+ this.value, \'districtlist\');" tabindex="1" class="ps">
					<option value="0">城市</option>'.
					$citylist.
				'</select>';

	$areahtml.= '<em id="districtlist">
					<select name="district" style="width:auto" onchange="ajaxget(\'hr.php?mod=misc&action=area&do=job&districtid=\'+ this.value, \'streetlist\');" tabindex="2" class="ps">
						<option value="0">地区</option>'.
						$districtlist.'
					</select>
				</em>';

	$areahtml.= '<em id="streetlist">
					<select name="street" style="width:auto" tabindex="3" class="ps">
						<option value="0">街道</option>'.
						$streetlist.'
					</select>
				</em>';

	return $areahtml;
}

?>