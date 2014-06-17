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

$optionadd = $filterurladd = $searchsorton = '';

if(empty($sortid)) {
	showmessage(lang('hr/template', 'job_undefined_action'));
}

require_once libfile('function/hr');

$showpic = intval($_GET['showpic']);
$templatearray = $sortoptionarray = array();
loadcache(array('hr_option_'.$sortid, 'hr_template_'.$sortid));

$_GET['listtype'] = in_array($_GET['listtype'], array('text', 'pic')) ? $_GET['listtype'] : $channel['listmode'];
$templatearray = $_GET['listtype'] == 'text' ? $_G['cache']['hr_template_'.$sortid]['subjecttext'] : $_G['cache']['hr_template_'.$sortid]['subject'];
$vtemplatearray = $_G['cache']['hr_template_'.$sortid]['visit'];
$sortoptionarray = $_G['cache']['hr_option_'.$sortid];
$perpage = $_G['cache']['hr_template_'.$sortid]['perpage'] ? $_G['cache']['hr_template_'.$sortid]['perpage'] : $_G['tpp'];

if(empty($sortoptionarray)) {
	showmessage(lang('hr/template', 'job_class_nothing'));
}

$quicksearchlist = quicksearch($sortoptionarray);
$districtid = $_GET['district'] ? intval($_GET['district']) : '';
$streetid = $_GET['street'] ? intval($_GET['street']) : '';
$cityid = $_GET['city'] ? intval($_GET['city']) : '';

if(count($arealist['city']) == 1) {
	$citysearchlist = '';
	$cityid = array_keys($arealist['city']);
	$cityid = $cityid[0];
} else {
	$citysearchlist = $arealist ? $arealist['city'] : '';
}
$districtsearchlist = $arealist && $cityid ? $arealist['district'][$cityid] : '';
$streetsearchlist = $arealist && $districtid ? $arealist['street'][$districtid] : '';

$page = $_G['page'];
$start_limit = ($page - 1) * $perpage;

$filteradd = $sortoptionurl = $space = $searchkeyword = '';
$sorturladdarray = $selectadd = $conditionlist = $saveconditionlist = $savedistrictlist = $savestreetlist = $_G['hr_threadlist'] = array();
$catedisplayadd['order'] = '';
$filterfield = array('sortid', 'page', 'recommend', 'attachid', 'all');
$_GET['filter'] = isset($_GET['filter']) && in_array($_GET['filter'], $filterfield) ? $_GET['filter'] : 'all';

foreach ($filterfield as $v) {
	$catedisplayadd[$v] = '';
}

if($query_string = $_SERVER['QUERY_STRING']) {
	$query_string = substr($query_string, (strpos($query_string, "&") + 1));
	parse_str($query_string, $geturl);
	$geturl = daddslashes($geturl, 1);
	if($geturl && is_array($geturl)) {
		$selectadd = $geturl;
		foreach($filterfield as $option) {
			$sfilterfield = array_merge(array('filter', 'sortid', 'searchoption'), $filterfield);
			foreach($geturl as $soption => $value) {
				$catedisplayadd[$option] .= !in_array($soption, $sfilterfield) ? "&amp;$soption=$value" : '';
			}
		}

		foreach($quicksearchlist as $option) {
			$conditionlist[$option['identifier']]['choices'] = $option['choices'];
			$conditionlist[$option['identifier']]['type'] = $option['type'];
			if($option['unit']) {
				$conditionlist[$option['identifier']]['unit'] = $option['unit'];
			}
			$identifier = $option['identifier'];
			foreach($geturl as $option => $value) {
				$sorturladdarray[$identifier] .= !in_array($option, array('filter', 'sortid', 'searchoption', $identifier)) ? "&amp;$option=$value" : '';
			}
		}

		$conditionlist['city'] = $arealist['city'];
		$conditionlist['district'] = $arealist['district'][$cityid];
		$conditionlist['street'] = $arealist['street'][$districtid];

		foreach($geturl as $option => $value) {
			$sorturladdarray['city'] .= !in_array($option, array('filter', 'sortid', 'city', 'district', 'street')) ? "&amp;$option=$value" : '';
			$sorturladdarray['district'] .= !in_array($option, array('filter', 'sortid', 'district', 'street')) ? "&amp;$option=$value" : '';
			$sorturladdarray['street'] .= !in_array($option, array('filter', 'sortid', 'street')) ? "&amp;$option=$value" : '';
		}

		foreach($geturl as $soption => $value) {
			$catedisplayadd['order'] .= !in_array($soption, array('filter', 'sortid', 'orderby', 'ascdesc', 'searchoption')) ? "&amp;$soption=$value" : '';
		}

		foreach($geturl as $field => $value) {
			if($conditionlist[$field]) {
				$url = $modurl.'?mod=list&filter='.$_GET['filter'].'&sortid='.$sortid;
				if($field == 'city') {
					$savecitylist['title'] = $conditionlist[$field][$value];
					$savecitylist['url'] = $url.$sorturladdarray[$field];
				} elseif($field == 'district') {
					$savedistrictlist['title'] = $conditionlist[$field][$value];
					$savedistrictlist['url'] = $url.$sorturladdarray[$field];
				} elseif($field == 'street') {
					$savestreetlist['title'] = $conditionlist[$field][$value];
					$savestreetlist['url'] = $url.$sorturladdarray[$field];
				} else {
					$saveconditionlist[$field]['title'] = $conditionlist[$field]['choices'][$value].($conditionlist[$field]['type'] != 'range' ? $conditionlist[$field]['unit'] : '');
					$saveconditionlist[$field]['url'] = $url.$sorturladdarray[$field];
				}
			}
		}
	}
}

$navtitle = '';
$metakeywords = empty($metakeywords) ? $sortlist[$sortid]['keywords'] : $metakeywords;
$metadescription = empty($metadescription) ? $sortlist[$sortid]['description'] : $metadescription;
if($savedistrictlist || $savestreetlist || $savecitylist) {
	if($savecitylist) {
		$navtitle .= $savecitylist['title'];
		$metakeywords .= ','.$savecitylist['title'];
	}

	if($savedistrictlist) {
		$navtitle .= $savedistrictlist['title'];
		$metakeywords .= ','.$savedistrictlist['title'];
	}

	if($savestreetlist) {
		$navtitle .= $savestreetlist['title'];
		$metakeywords .= ','.$savestreetlist['title'];
	}
}


if($saveconditionlist) {
	foreach($saveconditionlist as $option) {
		$metakeywords .= ','.$option['title'];
	}
}

$page = $_G['page'];
$navtitle .= $sortlist[$sortid]['name'].' - ตฺ'.$page.'าณ - '.$channel['title'];

if($_GET['searchoption']){
	$catedisplayadd['page'] = '&sortid='.$sortid;
	foreach($_GET['searchoption'] as $optionid => $option) {
		if($option['type'] == 'text') {
			$searchkeyword = dhtmlspecialchars($option['value']);
			$option['value'] = rawurlencode(dhtmlspecialchars($option['value']));
		}
		$identifier = $sortoptionarray[$sortid][$optionid]['identifier'];
		$catedisplayadd['searchoption'] .= $option['value'] ? "&amp;searchoption[$optionid][value]=$option[value]&amp;searchoption[$optionid][type]=$option[type]" : '';
	}
}

$orderbyurl = array();
foreach($sortoptionarray as $sort) {
	if($sort['orderbyshow']) {
		$orderbyurl[$sort['identifier']]['title'] = $sort['title'];
		if(!empty($_GET['ascdesc']) && in_array($_GET['ascdesc'], array('asc', 'desc'))) {
			if($_GET['ascdesc'] == 'asc') {
				$orderbyurl[$sort['identifier']]['ascdesc'] =  'desc';
			} elseif($_GET['ascdesc'] == 'desc') {
				$orderbyurl[$sort['identifier']]['ascdesc'] =  'asc';
			}
		} else {
			$orderbyurl[$sort['identifier']]['ascdesc'] =  'desc';
		}
		$orderbyurl[$sort['identifier']]['classascdesc'] =  !empty($_GET['ascdesc']) && in_array($_GET['ascdesc'], array('asc', 'desc')) ? $_GET['ascdesc'] : 'desc';
	}
}

$sortcondition = array();
$sortcondition['orderby'] = !empty($_GET['orderby']) && $orderbyurl[$_GET['orderby']] ? $_GET['orderby'] : 'dateline';
$sortcondition['ascdesc'] = !empty($_GET['ascdesc']) && in_array(strtoupper($_GET['ascdesc']), array('ASC', 'DESC')) ? strtoupper($_GET['ascdesc']) : 'DESC';

$sortdata = sortsearch($_GET['sortid'], $sortoptionarray, $_GET['searchoption'], $selectadd, $sortcondition, $start_limit, $perpage);
$tidsadd = $sortdata['tids'] ? "tid IN (".dimplode($sortdata['tids']).")" : '';
$_G['hr_threadcount'] = $sortdata['count'];

$catedisplayadd['order'] = !empty($catedisplayadd['order']) ? $catedisplayadd['order'] : '';
$multipage = multi($_G['hr_threadcount'], $perpage, $page, "$modurl?mod=list&sortid=$sortid&filter=$_GET[filter]$catedisplayadd[order]$catedisplayadd[searchoption]", $_G['setting']['threadmaxpages']);
$_G['hr_threadlist'] = $sortdata['datalist'];

if($tidsadd) {
	$authorids = array(); 
	$query = DB::query("SELECT * FROM ".DB::table('hr_'.$modidentifier.'_thread')." WHERE $tidsadd");
	while($thread = DB::fetch($query)) {
		$_G['hr_threadlist'][$thread['tid']]['subject'] .= $thread['subject'];
		$_G['hr_threadlist'][$thread['tid']]['author'] .= $thread['author'];
		$_G['hr_threadlist'][$thread['tid']]['authorid'] .= $thread['authorid'];
		$authorids[] = $thread['authorid']; 
	}
	
	$query = DB::query("SELECT uid, realname FROM ".DB::table('hr_'.$modidentifier.'_member')." WHERE uid IN (".dimplode($authorids).")");
	$authors = array();
	while($author = DB::fetch($query)) {
		$authors[$author['uid']] = $author['realname'];
	}
	foreach($_G['hr_threadlist'] as $tid => $thread) {
		if(empty($thread['authorid'])) {
			$_G['hr_threadlist'][$tid]['author'] = lang('hr/template', 'job_visitor');
		} elseif($authors[$thread['authorid']]) {
			$_G['hr_threadlist'][$tid]['author'] = $authors[$thread['authorid']];
		}
	}
}

if($sortoptionarray && $templatearray && $sortdata['tids']) {
	$sortlistarray = showsorttemplate($sortid, $sortoptionarray, $templatearray, $_G['hr_threadlist'], $sortdata['tids'], $arealist, $modurl);
	$stemplate = $sortlistarray['template'];
}

if($threadvisited = getcookie('threadvisited')) {
	$threadvisited = explode(',', $threadvisited);
	$visitedlist = visitedshow($threadvisited, $sortoptionarray, $sortid, $vtemplatearray, $modurl);
}

include template('diy:hr/'.$modidentifier.'_list');

?>