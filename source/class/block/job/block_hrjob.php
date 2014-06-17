<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_job.php 55 2010-09-15 05:41:47Z sunxianwei $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class block_hrjob {
	
	function name() {
		return '人才类';
	}
	
	function blockclass() {
		return array('hrjob', '人才信息');
	}
	
	function fields() {
		return array();
	}
	
	var $setting = array();	
	function getsetting() {
		global $_G;

		$settings = array(
			'tids' => array(
				'title' => 'hrlist_infoid',
				'type' => 'text'
			),
			'authorids' => array(
				'title' => 'hrlist_uid',
				'type' => 'text'
			),
			'sortids' => array(
				'title' => 'hrlist_sortids',
				'type' => 'mradio',
				'value' => array()
			),
			'styleids' => array(
				'title' => 'hrlist_styleids',
				'type' => 'select',
				'value' => array(
					array('style1', 'hrlist_styleids_style1'),
					array('style2', 'hrlist_styleids_style2'),
					array('style3', 'hrlist_styleids_style3'),
					array('style4', 'hrlist_styleids_style4'),
					array('style5', 'hrlist_styleids_style5'),
				)
			),
			'district' => array(
				'title' => 'hrlist_district',
				'type' => 'select',
				'default' => 0,
				'value' => array(
					array(0, 'hrlist_all')
				)
			),
			'pic' => array(
				'title' => 'hrlist_haspic',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', 'hrlist_any'),
					array('1', 'hrlist_withpic_only'),
				)
			),
			'displayorder' => array(
				'title' => 'hrlist_top',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', 'hrlist_any'),
					array('1', 'hrlist_top_only'),
				)
			),
			'recommend' => array(
				'title' => 'hrlist_recommend_thread',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', 'hrlist_any'),
					array('1', 'hrlist_digest_only'),
				)
			),
			'startrow' => array(
				'title' => 'sortlist_startrow',
				'type' => 'text',
				'default' => 0
			)
		);

		// 分类信息
		if($settings['sortids']) {
			$defaultvalue = '';
			$query = DB::query("SELECT sortid, name FROM ".DB::table('hr_sort')." WHERE cid=1 ORDER BY displayorder DESC");
			while($threadsort = DB::fetch($query)) {
				if(empty($defaultvalue)) {
					$defaultvalue = $threadsort['sortid'];
				}
				$settings['sortids']['value'][] = array($threadsort['sortid'], $threadsort['name']);
			}
			$settings['sortids']['default'] = $defaultvalue;
		}

		if($settings['district']) {
			$query = DB::query("SELECT aid, title FROM ".DB::table('hr_area')." WHERE type='district' ORDER BY displayorder DESC");
			while($area = DB::fetch($query)) {
				$settings['district']['value'][] = array($area['aid'],$area['title']);
			}
		}
		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);

		//参数准备
		loadcache('forums');
		$tids		= !empty($parameter['tids']) ? explode(',', $parameter['tids']) : array();
		$authorids	= !empty($parameter['authorids']) ? explode(',', $parameter['authorids']) : array();
		$startrow	= !empty($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= !empty($parameter['items']) ? intval($parameter['items']) : 10;
		$district	= isset($parameter['district']) ? $parameter['district'] : '';
		$sortids	= isset($parameter['sortids']) ? $parameter['sortids'] : '';
		$style		= isset($parameter['styleids']) ? $parameter['styleids'] : '';
		$pic		= isset($parameter['pic']) ? $parameter['pic'] : '';
		$displayorder	= isset($parameter['displayorder']) ? $parameter['displayorder'] : '';
		$recommend	= isset($parameter['recommend']) ? $parameter['recommend'] : '';

		loadcache(array('hr_option_'.$sortids, 'hr_template_block_'.$sortids));
		$headerhtml = $_G['cache']['hr_template_block_'.$sortids][$style]['header'];
		$footerhtml = $_G['cache']['hr_template_block_'.$sortids][$style]['footer'];
		$loophtml = $_G['cache']['hr_template_block_'.$sortids][$style]['loop'];
		$sortoption = $_G['cache']['hr_option_'.$sortids];

		$areadatalist = $sortdata = $sortdatatids = array();

		$sql = 	($tids ? ' AND s.tid IN ('.dimplode($tids).')' : '')
			.($authorids ? ' AND t.authorid IN ('.dimplode($authorids).')' : '')
			.($district ? ' AND s.district=\''.$district.'\'' : '')
			.($pic ? ' AND s.attachid>0' : '')
			.($displayorder ? ' AND s.displayorder>0' : '')
			.($recommend ? ' AND s.recommend>0' : '');

		$query = DB::query("SELECT aid, title FROM ".DB::table('hr_area')." ORDER BY displayorder");
		while($areadata = DB::fetch($query)) {
			$areadatalist[$areadata['aid']] =  $areadata['title'];
		}

		$sortcondition['orderby'] = 's.dateline';
		$sortcondition['ascdesc'] = 'DESC';

		$query = DB::query("SELECT s.tid, s.attachid, s.dateline, s.expiration, s.displayorder, s.recommend, s.attachnum, s.highlight, s.groupid, s.city, s.district, s.street, t.authorid FROM ".DB::table('hr_sortvalue')."$sortids s LEFT JOIN ".DB::table('hr_job_thread')." t ON t.tid=s.tid WHERE 1 $sql ORDER BY s.displayorder DESC, $sortcondition[orderby] $sortcondition[ascdesc] LIMIT $startrow,$items");
		while($thread = DB::fetch($query)) {
			if($thread['highlight']) {
				$string = sprintf('%02d', $thread['highlight']);
				$stylestr = sprintf('%03b', $string[0]);

				$thread['highlight'] = ' style="';
				$thread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
				$thread['highlight'] .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
				$thread['highlight'] .= '"';
			} else {
				$thread['highlight'] = '';
			}
			$sortdatatids[]= $thread['tid'];
			$sortdata['datalist'][$thread['tid']]= $thread;
		}

		if($sortdatatids) {
			$authorids = array(); //*
			$query = DB::query("SELECT * FROM ".DB::table('hr_job_thread')." WHERE tid IN (".dimplode($sortdatatids).")");
			while($data = DB::fetch($query)) {
				$sortdata['datalist'][$data['tid']]['subject'] .= $data['subject'];
				$sortdata['datalist'][$data['tid']]['author'] .= $data['author'];
				$sortdata['datalist'][$data['tid']]['authorid'] .= $data['authorid'];
				$authorids[] = $data['authorid']; //*
			}
			
			//取用户真实姓名 //*
			$query = DB::query("SELECT uid, realname FROM ".DB::table('hr_job_member')." WHERE uid IN (".dimplode($authorids).")");
			while($member = DB::fetch($query)) {
				foreach($sortdata['datalist'] as $tid => $tinfo) {
					if(empty($tinfo['authorid'])) {
						$sortdata['datalist'][$tid]['author'] = '游客';
					} elseif($member['realname'] && $tinfo['authorid'] == $member['uid']) {
							$sortdata['datalist'][$tid]['author'] = $member['realname'];
						}
					
				}
			}
		}
		
		$html = $headerhtml ? $headerhtml : '';

		//数据获取
		foreach($sortdata as $datalist) {
			foreach($datalist as $data) {
				$htmldata = $this->showsort($data, $sortoption, $loophtml, $areadatalist);
				$html .= $htmldata ? $htmldata : '';
			}
		}

		$html .= $footerhtml ? $footerhtml : '';
		$html = $html ? $html : lang('block/hrlist', 'hrlist_template_empty');
		return array('html' => $html, 'data' => null);
	}

	function getcateimg($aid, $nocache = 0, $w = 140, $h = 140, $type = '') {
		global $_G;
		return 'hr.php?mod=misc&action=thumb&aid='.$aid.'&size='.$w.'x'.$h.'&key='.rawurlencode($key).($nocache ? '&nocache=yes' : '').($type ? '&type='.$type : '');
	}

	function showsort($threaddata, $sortoption, $template, $areadatalist) {
		global $_G;
		$sortid = intval($threaddata['sortid']);
		$tid = intval($threaddata['tid']);

		$optiondata = $optionvaluelist = $optiontitlelist = $optionunitlist = $searchtitle = $searchvalue = $searchunit = $typetemplate = array();
		$query = DB::query("SELECT optionid, value FROM ".DB::table('hr_sortoptionvar')." WHERE tid='$tid'");
		while($option = DB::fetch($query)) {
			$optiondata[$option['optionid']] = $option['value'];
		}

		$threaddata['image'] = '<img src="static/image/job/noupload.gif">';
		$threaddata['subject'] = '<a href="'.'job.php?mod=view&tid='.$threaddata['tid'].'" target="_blank">'.$threaddata['subject'].'</a>';
		$threaddata['author'] = !empty($threaddata['authorid']) ? '<a href="'.'job.php?mod=broker&action=my&uid='.$threaddata['authorid'].'" target="_blank">'.$threaddata['author'].'</a>' : $threaddata['author'];
		if($threaddata['attachid']) {
			$w = $h = 140;
			$aid = $threaddata['attachid'];
			$key = authcode("$aid\t$w\t$h", 'ENCODE', $_G['config']['security']['authkey']);
			$threaddata['image'] = '<img src="hr.php?mod=misc&action=thumb&aid='.$aid.'&size='.$w.'x'.$h.'&key='.rawurlencode($key).'">';
		}

		$threaddata['city'] = $threaddata['city'] ? $areadatalist[$threaddata['city']] : '';
		$threaddata['district'] = $threaddata['district'] ? $areadatalist[$threaddata['district']] : '';
		$threaddata['street'] = $threaddata['street'] ? $areadatalist[$threaddata['street']] : '';

		if($sortoption && $template && $optiondata && $threaddata) {
			foreach($sortoption as $optionid => $option) {
				$optiontitlelist[] = $sortoption[$optionid]['title'];
				$optionunitlist[] = $sortoption[$optionid]['unit'];
				if($sortoption[$optionid]['type'] == 'checkbox') {
					$choicedata = '';
					foreach(explode("\t", $optiondata[$optionid]) as $choiceid) {
						$choicedata .= '<span>'.$sortoption[$optionid]['choices'][$choiceid].'</span>';
					}
					$optionvaluelist[] = $choicedata;
				} elseif(in_array($sortoption[$optionid]['type'], array('radio', 'select'))) {
					$optionvaluelist[] = $sortoption[$optionid]['choices'][$optiondata[$optionid]];
				} elseif($sortoption[$optionid]['type'] == 'url') {
					$optiondata[$optionid] = preg_match('/^(ftp|http|)[s]?:\/\//', $optiondata[$optionid]) ? $optiondata[$optionid] : $optiondata[$optionid];
					$optionvaluelist[] = $optiondata[$optionid] ? "<a href=\"".$optiondata[$optionid]."\" target=\"_blank\">".$optiondata[$optionid]."</a>" : '';
				} elseif($sortoption[$optionid]['type'] == 'textarea') {
					$optionvaluelist[] = $optiondata[$optionid] ? nl2br($optiondata[$optionid]) : '';
				} else {
					$optionvaluelist[] = $optiondata[$optionid] ? $optiondata[$optionid] : $sortoption[$optionid]['defaultvalue'];
				}
			}

			foreach($sortoption as $option) {
				$searchtitle[] = '/{('.$option['identifier'].')}/i';
				$searchvalue[] = '/\[('.$option['identifier'].')value\]/i';
				$searchunit[] = '/\[('.$option['identifier'].')unit\]/i';
			}

			$typetemplate = preg_replace(array("/\{city\}/i", "/\{district\}/i", "/\{street\}/i", "/\{image\}/i", "/\{author\}/i", "/\{subject\}/i", "/\{dateline\}/i", "/\{url\}/i", "/\[url\](.+?)\[\/url\]/i"),
							array(
								$threaddata['city'],
								$threaddata['district'],
								$threaddata['street'],
								$threaddata['image'],
								$threaddata['author'],
								$threaddata['subject'],
								dgmdate($threaddata['dateline'], 'm-d'),
								"job.php?mod=view&tid=$tid",
								"<a href=\""."job.php?mod=view&tid=$tid\" target=\"_blank\">\\1</a>"
							), stripslashes($template));
			$typetemplate = preg_replace($searchtitle, $optiontitlelist, $typetemplate);
			$typetemplate = preg_replace($searchvalue, $optionvaluelist, $typetemplate);
			$typetemplate = preg_replace($searchunit, $optionunitlist, $typetemplate);
		}

		return $typetemplate;
	}

}


?>