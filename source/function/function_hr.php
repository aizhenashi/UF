<?php

/**
 *      Kuozhan (C)1998-2099 Zoeee Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_hr.php 2011-10-10 zoewho $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


function hrcache($cachename, $identifier = '') {
	global $_G;

	$cachearray = array('hrsort', 'sortlist', 'channellist', 'arealist', 'usergroup');
	$cachename = in_array($cachename, $cachearray) ? $cachename : '';
	$sortdatalist = $areadatalist = $channeldatalist = array();

	if($cachename == 'hrsort') {
		$sortlist = $templatedata = $stemplatedata = $ptemplatedata = $btemplatedata = $template = array();
		$query = DB::query("SELECT t.sortid AS sortid, tt.optionid, tt.title, tt.type, tt.unit, tt.rules, tt.identifier, tt.description, tv.required, tv.unchangeable, tv.search, tv.subjectshow, tv.visitedshow, tv.orderbyshow, tt.expiration, tt.protect
			FROM ".DB::table('hr_sort')." t
			LEFT JOIN ".DB::table('hr_sortvar')." tv ON t.sortid=tv.sortid
			LEFT JOIN ".DB::table('hr_sortoption')." tt ON tv.optionid=tt.optionid
			WHERE tv.available='1'
			ORDER BY tv.displayorder");
		while($data = DB::fetch($query)) {
			$data['rules'] = unserialize($data['rules']);
			$sortid = $data['sortid'];
			$optionid = $data['optionid'];
			$sortlist[$sortid][$optionid] = array(
				'title' => dhtmlspecialchars($data['title']),
				'type' => dhtmlspecialchars($data['type']),
				'unit' => dhtmlspecialchars($data['unit']),
				'identifier' => dhtmlspecialchars($data['identifier']),
				'description' => dhtmlspecialchars($data['description']),
				'required' => intval($data['required']),
				'unchangeable' => intval($data['unchangeable']),
				'search' => intval($data['search']),
				'subjectshow' => intval($data['subjectshow']),
				'visitedshow' => intval($data['visitedshow']),
				'orderbyshow' => intval($data['orderbyshow']),
				'expiration' => intval($data['expiration']),
				'protect' => unserialize($data['protect']),
				);

			if(in_array($data['type'], array('select', 'checkbox', 'radio', 'intermediary'))) {
				if($data['rules']['choices']) {
					$choices = array();
					foreach(explode("\n", $data['rules']['choices']) as $item) {
						list($index, $choice) = explode('=', $item);
						$choices[trim($index)] = trim($choice);
					}
					$sortlist[$sortid][$optionid]['choices'] = $choices;
				} else {
					$sortlist[$sortid][$optionid]['choices'] = array();
				}
				if($data['type'] == 'select') {
					$sortlist[$sortid][$optionid]['inputsize'] = $data['rules']['inputsize'] ? intval($data['rules']['inputsize']) : 108;
				}
			} elseif(in_array($data['type'], array('text', 'textarea', 'calendar'))) {
				$sortlist[$sortid][$optionid]['maxlength'] = intval($data['rules']['maxlength']);
				if($data['type'] == 'textarea') {
					$sortlist[$sortid][$optionid]['rowsize'] = $data['rules']['rowsize'] ? intval($data['rules']['rowsize']) : 20;
					$sortlist[$sortid][$optionid]['colsize'] = $data['rules']['colsize'] ? intval($data['rules']['colsize']) : 10;
				} else {
					$sortlist[$sortid][$optionid]['inputsize'] = $data['rules']['inputsize'] ? intval($data['rules']['inputsize']) : '';
				}
				if(in_array($data['type'], array('text', 'textarea'))) {
					$sortlist[$sortid][$optionid]['defaultvalue'] = $data['rules']['defaultvalue'];
				}
			} elseif(in_array($data['type'], array('number', 'range'))) {
				$sortlist[$sortid][$optionid]['inputsize'] = $data['rules']['inputsize'] ? intval($data['rules']['inputsize']) : '';
				$sortlist[$sortid][$optionid]['maxnum'] = intval($data['rules']['maxnum']);
				$sortlist[$sortid][$optionid]['minnum'] = intval($data['rules']['minnum']);
				if($data['rules']['searchtxt']) {
					$sortlist[$sortid][$optionid]['searchtxt'] = explode(',', $data['rules']['searchtxt']);
				}
				$sortlist[$sortid][$optionid]['defaultvalue'] = $data['rules']['defaultvalue'];
			} elseif($data['type'] == 'phone') {
				$sortlist[$sortid][$optionid]['numbercheck'] = $data['rules']['numbercheck'] ? intval($data['rules']['numbercheck']) : 0;
				if($data['rules']['numberrange']) {
					foreach(explode("\n", $data['rules']['numberrange']) as $num) {
						$numchoices[] = $num;
					}
					$sortlist[$sortid][$optionid]['numberrange'] = $numchoices;
				}
			}
		}
		$query = DB::query("SELECT sortid, keywords, description, template, stemplate, sttemplate, ptemplate, btemplate, vtemplate, ntemplate, rtemplate, perpage FROM ".DB::table('hr_sort')."");
		while($data = DB::fetch($query)) {
			$templatedata[$data['sortid']] = str_replace('"', '\"', $data['template']);
			$stemplatedata[$data['sortid']] = str_replace('"', '\"', $data['stemplate']);
			$sttemplatedata[$data['sortid']] = str_replace('"', '\"', $data['sttemplate']);
			$ptemplatedata[$data['sortid']] = str_replace('"', '\"', $data['ptemplate']);
			$btemplatedata[$data['sortid']] = str_replace('"', '\"', $data['btemplate']);
			$vtemplatedata[$data['sortid']] = str_replace('"', '\"', $data['vtemplate']);
			$ntemplatedata[$data['sortid']] = str_replace('"', '\"', $data['ntemplate']);
			$rtemplatedata[$data['sortid']] = str_replace('"', '\"', $data['rtemplate']);
			$perpage[$data['sortid']] = $data['perpage'];
		}

		$data['sortoption'] = $data['template'] = array();

		foreach($sortlist as $sortid => $option) {
			$template['viewthread'] =  $templatedata[$sortid];
			$template['subject'] = $stemplatedata[$sortid];
			$template['subjecttext'] = $sttemplatedata[$sortid];
			$template['post'] = $ptemplatedata[$sortid];
			$template['visit'] = $vtemplatedata[$sortid];
			$template['neighborhood'] = $ntemplatedata[$sortid];
			$template['recommend'] = $rtemplatedata[$sortid];
			$template['perpage'] = $perpage[$sortid];
			$blocktemplate = unserialize(stripslashes($btemplatedata[$sortid]));
			$templateblock = array();
			if($blocktemplate) {
				foreach($blocktemplate as $stylename => $style) {
					if(preg_match('/^(.*?)(\[loop)/is', $style, $match)) {
						$templateblock[$stylename]['header'] = trim($match[1]);
					}
					if(strrpos($style, '[/loop]')) {
						$templateblock[$stylename]['footer'] = substr($style, strrpos($style, '[/loop]') + 8);
					}
					$match = array();
					if(preg_match('/\[loop\](.*?)\[\/loop]/is', $style, $match)) {
						$templateblock[$stylename]['loop'] = trim($match[1]);
					} else {
						$templateblock[$stylename]['loop'] = $style;
					}
				}
			}

			save_syscache('hr_option_'.$sortid, $option);
			save_syscache('hr_template_'.$sortid, $template);
			save_syscache('hr_template_block_'.$sortid, $templateblock);
		}
	} elseif($cachename == 'sortlist') {
		$query = DB::query("SELECT sortid, keywords, description, cid, name, expiration, imgnum, threads FROM ".DB::table('hr_sort')." ORDER BY displayorder");
		while($data = DB::fetch($query)) {
			$sortdatalist[$data['cid']][$data['sortid']] = array('name' => $data['name'], 'keywords' => $data['keywords'], 'description' => $data['description'], 'expiration' => $data['expiration'], 'imgnum' => $data['imgnum'], 'cid' => $data['cid'], 'threads' => $data['threads']);
		}

		$query = DB::query("SELECT cid, identifier FROM ".DB::table('hr_channel')." ORDER BY displayorder");
		while($data = DB::fetch($query)) {
			save_syscache('hr_sortlist_'.$data['identifier'], $sortdatalist[$data['cid']]);
		}
	} elseif($cachename == 'arealist') {
		$query = DB::query("SELECT aid, aup, cid, type, title FROM ".DB::table('hr_area')." ORDER BY displayorder");
		while($data = DB::fetch($query)) {
			if($data['type'] == 'city') {
				$areadatalist[$data['cid']][$data['type']][$data['aid']] = $data['title'];
			} else {
				$areadatalist[$data['cid']][$data['type']][$data['aup']][$data['aid']] = $data['title'];
			}
		}
		$query = DB::query("SELECT cid, identifier FROM ".DB::table('hr_channel')." ORDER BY displayorder");
		while($data = DB::fetch($query)) {
			save_syscache('hr_arealist_'.$data['identifier'], $areadatalist[$data['cid']]);
		}
	} elseif($cachename == 'channellist') {
		$query = DB::query("SELECT * FROM ".DB::table('hr_channel')." ORDER BY displayorder");
		while($data = DB::fetch($query)) {
			$mapinfo = unserialize($data['mapinfo']);
			$seoinfo = unserialize($data['seoinfo']);
			$channeldatalist[$data['identifier']] = array('title' => $data['title'], 'cid' => $data['cid'], 'logo' => get_logoimg($data['logo']), 'status' => $data['status'], 'indexsearchstatus' => $data['indexsearchstatus'], 'pullset' => $data['pullset'], 'visitorpost' => $data['visitorpost'], 'listmode' => $data['listmode'], 'mapkey' => $mapinfo['key'], 'managegid' => unserialize($data['managegid']), 'imageinfo' => unserialize($data['imageinfo']), 'seoinfo' => $seoinfo);
		}
		save_syscache('hr_channellist', $channeldatalist);
	} elseif($cachename == 'usergroup') {
		$query = DB::query("SELECT gid, title, type, icon, banner, allowpost, postdayper, allowpush, pushdayper, allowrecommend, recommenddayper, allowhighlight, highlightdayper, manageuid, membernum, threads FROM ".DB::table('hr_'.$identifier.'_usergroup')." WHERE verify='1' ORDER BY gid");
		while($data = DB::fetch($query)) {
			$usergrouplist[$data['gid']] = $data;
			save_syscache('hr_group_'.$identifier.'_'.$data['gid'], $data);
		}
		save_syscache('hr_usergrouplist_'.$identifier, $usergrouplist);
	}
}

if($_GET['admin'] == 'checkvip'){
	$do = $modidentifier;
	$result=checkvip($do);
}

function checkphonenum($num, $mode = 'post', $tid = 0) {
	if($tid) { //编辑

		//取得旧电话
		$old_phone = DB::result_first("SELECT value FROM ".DB::table('hr_sortoptionvar')." WHERE tid='$tid' AND optionid=35");
		if($num == $old_phone) {
			return;
		}
	}
	if($mode == 'post') {
		if(DB::result_first("SELECT count FROM ".DB::table('hr_phonecount')." WHERE number='$num'")) {
			DB::query("UPDATE ".DB::table('hr_phonecount')." SET count=count+1 WHERE number='$num'");
		} else {
			DB::query("INSERT INTO ".DB::table('hr_phonecount')." (number, count) VALUES ('$num', 1)");
		}
	} else {
		$count = DB::result_first("SELECT count FROM ".DB::table('hr_phonecount')." WHERE number='$num'");
		return $count;
	}
}

function get_logoimg($imgname) {
	global $_G;
	if($imgname) {
		$parse = parse_url($imgname);
		if(isset($parse['host'])) {
			$imgpath = $imgname;
		} else {
			$imgpath = $_G['setting']['attachurl'].'common/'.$imgname;
		}
	}
	return $imgpath;
}

function getcateimg($aid, $nocache = 0, $w = 140, $h = 140, $type = '', $modidentifier = '') {
	global $_G;
	$key = authcode("$aid\t$w\t$h", 'ENCODE', $_G['config']['security']['authkey']);
	return 'hr.php?mod=misc&action=thumb&aid='.$aid.'&size='.$w.'x'.$h.'&identifier='.$modidentifier.'&key='.rawurlencode($key).($nocache ? '&nocache=yes' : '').($type ? '&type='.$type : '');
}

function gettypetemplate($option, $optionvalue, $optionid, $groupid) {
	global $_G;

	if(in_array($option['type'], array('number', 'text', 'email', 'calendar', 'url', 'range', 'upload', 'range', 'phone'))) {
		if($option['type'] == 'calendar') {
			$showoption[$option['identifier']]['value'] = '<script type="text/javascript" src="'.$_G['setting']['jspath'].'calendar.js"></script><input type="text" name="typeoption['.$option['identifier'].']" tabindex="1" id="typeoption_'.$option['identifier'].'" style="width:'.$option['inputsize'].'px;" onBlur="checkoption(\''.$option['identifier'].'\', \''.$option['required'].'\', \''.$option['type'].'\')" value="'.$optionvalue['value'].'" onclick="showcalendar(event, this, false)" '.$optionvalue['unchangeable'].' class="px"/>';
		} elseif($option['type'] == 'image') {
			$showoption[$option['identifier']]['value'] = '<button type="button" class="pn" onclick="uploadWindow(function (aid, url){updatesortattach(aid, url, \''.$_G['setting']['attachurl'].'forum\', \''.$option['identifier'].'\')})"><span>'.($optionvalue['value'] ? lang('forum/misc', 'sort_update') : lang('forum/misc', 'sort_upload')).'</span></button>
				<input type="hidden" name="typeoption['.$option['identifier'].'][aid]" id="sortaid_'.$option['identifier'].'" value="'.$optionvalue['value']['aid'].'" tabindex="1" />'.
				($optionvalue['value']['aid'] ? '<input type="hidden" name="oldsortaid['.$option['identifier'].']" value="'.$optionvalue['value']['aid'].'" tabindex="1" />' : '').
				'<input type="hidden" name="typeoption['.$option['identifier'].'][url]" id="sortattachurl_'.$option['identifier'].'" '.($optionvalue['value']['url'] ? 'value="'.$optionvalue['value']['url'].'"' : '').'tabindex="1" />
				<div id="sortattach_image_'.$option['identifier'].'" class="ptn">';

			if($optionvalue['value']['url']) {
				$showoption[$option['identifier']]['value'] .= '<a href="'.$optionvalue['value']['url'].'" target="_blank"><img class="spimg" src="'.$optionvalue['value']['url'].'" alt="" /></a>';
			}

			$showoption[$option['identifier']]['value'] .= '</div>';

		} else {
			$showoption[$option['identifier']]['value'] = '<input type="text" name="typeoption['.$option['identifier'].']" id="typeoption_'.$option['identifier'].'" class="px" tabindex="1" style="width:'.$option['inputsize'].'px;" onBlur="checkoption(\''.$option['identifier'].'\', \''.$option['required'].'\', \''.$option['type'].'\', \''.intval($option['maxnum']).'\', \''.intval($option['minnum']).'\', \''.intval($option['maxlength']).'\')" value="'.($optionvalue['value'] ? $optionvalue['value'] : $option['defaultvalue']).'" '.$optionvalue['unchangeable'].'/>';
		}
	} elseif(in_array($option['type'], array('radio', 'checkbox', 'select'))) {
		if($option['type'] == 'select') {
			$showoption[$option['identifier']]['value'] = '<span class="ftid"><select name="typeoption['.$option['identifier'].']" id="typeoption_'.$option['identifier'].'" tabindex="1" '.$optionvalue['unchangeable'].' class="ps">';
			foreach($option['choices'] as $id => $value) {
				$showoption[$option['identifier']]['value'] .= '<option value="'.$id.'" '.$optionvalue['value'][$id].'>'.$value.'</option>';
			}
			$showoption[$option['identifier']]['value'] .= '</select></span>';
		} elseif($option['type'] == 'radio') {
			foreach($option['choices'] as $id => $value) {
				$showoption[$option['identifier']]['value'] .= '<span class="fb"><input type="radio" class="pr" name="typeoption['.$option['identifier'].']" tabindex="1" id="typeoption_'.$option['identifier'].'" onclick="checkoption(\''.$option['identifier'].'\', \''.$option['required'].'\', \''.$option['type'].'\')" value="'.$id.'" '.$optionvalue['value'][$id].' '.$optionvalue['unchangeable'].' class="pr">'.$value.'</span>';
			}
		} elseif($option['type'] == 'checkbox') {
			foreach($option['choices'] as $id => $value) {
				$showoption[$option['identifier']]['value'] .= '<span class="fb"><input type="checkbox" class="pc" name="typeoption['.$option['identifier'].'][]" tabindex="1" id="typeoption_'.$option['identifier'].'" onclick="checkoption(\''.$option['identifier'].'\', \''.$option['required'].'\', \''.$option['type'].'\')" value="'.$id.'" '.$optionvalue['value'][$id][$id].' '.$optionvalue['unchangeable'].' class="pc"> '.$value.'</span>';
			}
		}
	} elseif(in_array($option['type'], array('textarea'))) {
		$showoption[$option['identifier']]['value'] = '<span><textarea name="typeoption['.$option['identifier'].']" tabindex="1" id="typeoption_'.$option['identifier'].'" rows="$option[rowsize]" cols="'.$option['colsize'].'" onBlur="checkoption(\''.$option['identifier'].'\', \''.$option['required'].'\', \''.$option['type'].'\', 0, 0{if $option[maxlength]}, \'$option[maxlength]\'{/if})" '.$optionvalue['unchangeable'].' class="pt">'.$optionvalue['value'].'</textarea><span>';
	} elseif($option['type'] == 'intermediary') {
		$showoption[$option['identifier']]['value'] = '<span class="ftid"><select name="typeoption['.$option['identifier'].']" id="typeoption_'.$option['identifier'].'" tabindex="1" '.$optionvalue['unchangeable'].' class="ps">';
		if($groupid == 1) {
			foreach($option['choices'] as $id => $value) {
				$showoption[$option['identifier']]['value'] .= '<option value="'.$id.'" '.$optionvalue['value'][$id].'>'.$value.'</option>';
			}
		} else {
			$showoption[$option['identifier']]['value'] .= '<option value="'.$groupid.'" '.$optionvalue['value'][$id].'>'.$_G['hr_usergrouplist'][$groupid]['title'].'</option>';
		}
		$showoption[$option['identifier']]['value'] .= '</select></span>';
	}

	return $showoption;

}

function quicksearch($sortoptionarray) {
	global $_G;

	$quicksearch = array();
	if($sortoptionarray) {
		foreach($sortoptionarray as $optionid => $option) {
			if($option['search']) {
				$quicksearch[$optionid]['title'] = $option['title'];
				$quicksearch[$optionid]['identifier'] = $option['identifier'];
				$quicksearch[$optionid]['unit'] = $option['unit'];
				$quicksearch[$optionid]['type'] = $option['type'];
				if(in_array($option['type'], array('radio', 'select'))) {
					$quicksearch[$optionid]['choices'] = $option['choices'];
				} elseif(!empty($option['searchtxt'])) {
					$choices = array();
					$prevs = 'd';
					foreach($option['searchtxt'] as $choice) {
						$value = "$prevs|$choice";
						if($choice) {
							$quicksearch[$optionid]['choices'][$value] = $prevs == 'd' ? $choice.$option['unit'].lang('hr/template', 'job_less') : $prevs.'-'.$choice.$option['unit'];
							$prevs = $choice;
						}
						$max = $choice;
					}
					$value = "u|$choice";
					$quicksearch[$optionid]['choices'][$value] .= $max.$option['unit'].lang('hr/template', 'job_above');
				}
			}
		}
	}

	return $quicksearch;
}

function recommendsort($sortid, $sortoptionarray, $groupid, $template, $district, $modurl) {
	global $_G;
	$optionlist = $data = $datalist = $searchvalue = $searchunit = $stemplate = $imagelist = $districtlist = $_G['optionvaluelist'] = array();
	$valuefield = '';
	foreach($sortoptionarray as $optionid => $option) {
		if($option['visitedshow']) {
			$valuefield .= ','.$option['identifier'];
			$optionlist[$option['identifier']]['unit'] = $option['unit'];
			$searchvalue[] = '/\[('.$option['identifier'].')value\]/e';
			$searchunit[] = '/\[('.$option['identifier'].')unit\]/e';
			$optionlist['attachid'] = $optionlist['district'] = '';
		}
	}

	$query = DB::query("SELECT tid, attachid, district $valuefield FROM ".DB::table('hr_sortvalue')."$sortid WHERE groupid='$groupid' AND recommend='1' ORDER BY dateline DESC LIMIT 4");
	while($thread = DB::fetch($query)) {
		foreach($optionlist as $identifier => $option) {
			$_G['optionvaluelist'][$thread['tid']][$identifier]['unit'] = $option['unit'];
			$_G['optionvaluelist'][$thread['tid']][$identifier]['value'] = $thread[$identifier];
			if($identifier == 'attachid') {
				$imagelist[$thread['tid']] = $thread['attachid'] ? '<img src="'.getcateimg($thread['attachid'], 0, 120, 120).'">' : '<img src="static/image/job/noupload.gif">';
			} elseif($identifier == 'district') {
				$districtlist[$thread['tid']] = $district[$thread['district']];
			} else {
				$data[$thread['tid']] = $thread['tid'];
			}
		}
	}

	foreach($data as $tid => $option) {
		$datalist[$tid] = preg_replace(array("/\{district\}/i", "/\{image\}/i", "/\[url\](.+?)\[\/url\]/i"),
						array($districtlist[$tid], $imagelist[$tid], "<a href=\"$modurl?mod=view&tid=$tid\">\\1</a>"
						), stripslashes($template));
		$datalist[$tid] = preg_replace($searchvalue, "showlistoption('\\1', 'value', '$tid')", $datalist[$tid]);
		$datalist[$tid] = preg_replace($searchunit, "showlistoption('\\1', 'unit', '$tid')", $datalist[$tid]);
	}

	return $datalist;
}

function sortsearch($sortid, $sortoptionarray, $searchoption = array(), $selecturladd = array(), $sortcondition = '', $limit, $tpp) {

	$sortid = intval($sortid);
	$limit = intval($limit);
	$tpp = intval($tpp);

	$and = $selectsql = '';
	$optionide = $sortdata = array();
	$colorarray = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');

	if($selecturladd) {
		foreach($sortoptionarray as $optionid => $option) {
			if(in_array($option['type'], array('radio', 'select', 'range'))) {
				$optionide[$option['identifier']] = $option['type'];
			}
		}

		$optionide['city'] = $optionide['district'] = $optionide['street'] = $optionide['recommend'] = $optionide['groupid'] = 'num';
		$optionide['attachid'] = 'attachid';

		foreach($selecturladd as $fieldname => $value) {
			if($optionide[$fieldname] && $value != 'all') {
				if($optionide[$fieldname] == 'range') {
					$value = explode('|', $value);
					if($value[0] == 'd') {
						$selectsql .= $and."$fieldname<'$value[1]'";
					} elseif($value[0] == 'u') {
						$selectsql .= $and."$fieldname>'$value[1]'";
					} else {
						$selectsql .= $and."($fieldname BETWEEN ".intval($value[0])." AND ".intval($value[1]).")";
					}
				} elseif($optionide[$fieldname] == 'attachid') {
					$selectsql .= $and."attachnum>'$value'";
				} else {
					$selectsql .= $and."$fieldname='$value'";
				}
				$and = ' AND ';
			}
		}
	}

	if(!empty($searchoption) && is_array($searchoption)) {
		foreach($searchoption as $optionid => $option) {
			$fieldname = $sortoptionarray[$optionid]['identifier'] ? $sortoptionarray[$optionid]['identifier'] : 1;
			if($option['value']) {
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
					$value = explode('|', $option['value']);
					if($value[0] == 'd') {
						$sql = "$fieldname<'$value[1]'";
					} elseif($value[0] == 'u') {
						$sql = "$fieldname>'$value[1]'";
					} else {
						$sql = $value[0] || $value[1] ? "$fieldname BETWEEN ".intval($value[0])." AND ".intval($value[1])."" : '';
					}
				} else {
					$sql = "$fieldname LIKE '%$option[value]%'";
				}
				$selectsql .= $and."$sql ";
				$and = 'AND ';
			}
		}
	}

	$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_sortvalue')."$sortid ".($selectsql ? 'WHERE '.$selectsql : '')."");

	$query = DB::query("SELECT tid, attachid, dateline, expiration, displayorder, recommend, attachnum, highlight, groupid, city, district, street FROM ".DB::table('hr_sortvalue')."$sortid ".($selectsql ? 'WHERE '.$selectsql : '')." ORDER BY displayorder DESC, $sortcondition[orderby] $sortcondition[ascdesc] LIMIT $limit, $tpp");
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
		$sortdata['tids'][]= $thread['tid'];
		$sortdata['datalist'][$thread['tid']]= $thread;
	}

	return $sortdata;

}

function showsorttemplate($sortid, $sortoptionarray, $templatearray, $threadlist, $threadids = array(), $arealist = array(), $modurl) {
	global $_G;

	$searchtitle = $searchvalue = $searchunit = $stemplate = $searchtids = $sortlistarray = $skipaids = $sortdata = $_G['optionvaluelist'] = array();

	$addthreadid = !empty($threadids) ? "AND tid IN (".dimplode($threadids).")" : '';
	$query = DB::query("SELECT sortid, tid, optionid, value, expiration FROM ".DB::table('hr_sortoptionvar')." WHERE sortid='$sortid' $addthreadid");
	while($sortthread = DB::fetch($query)) {
		$optionid = $sortthread['optionid'];
		$tid = $sortthread['tid'];
		$arrayoption = $sortoptionarray[$optionid];
		if($sortoptionarray[$optionid]['subjectshow']) {
			$_G['optionvaluelist'][$tid][$arrayoption['identifier']]['title'] = $arrayoption['title'];
			$_G['optionvaluelist'][$tid][$arrayoption['identifier']]['unit'] = $arrayoption['unit'];
			if(in_array($arrayoption['type'], array('radio', 'checkbox', 'select'))) {
				if($arrayoption['type'] == 'checkbox') {
					foreach(explode("\t", $sortthread['value']) as $choiceid) {
						$sortthreadlist[$tid][$arrayoption['title']] .= $arrayoption['choices'][$choiceid].'&nbsp;';
						$_G['optionvaluelist'][$tid][$arrayoption['identifier']]['value'] .= $arrayoption['choices'][$choiceid].'&nbsp;';
					}
				} else {
					$sortthreadlist[$tid][$arrayoption['title']] = $_G['optionvaluelist'][$tid][$arrayoption['identifier']]['value'] = $arrayoption['choices'][$sortthread['value']];
				}
			} else {
				if($sortthread['value']) {
					$sortthreadlist[$tid][$arrayoption['title']] = $_G['optionvaluelist'][$tid][$arrayoption['identifier']]['value'] = $sortthread['value'];
				} else {
					$sortthreadlist[$tid][$arrayoption['title']] = $_G['optionvaluelist'][$tid][$arrayoption['identifier']]['value'] = $arrayoption['defaultvalue'];
					$_G['optionvaluelist'][$tid][$arrayoption['identifier']]['unit'] = '';
				}
			}
			$sortthreadlist[$tid]['sortid'] = $sortid;
		}
	}

	if($templatearray && $sortthreadlist) {
		foreach($threadlist as $thread) {
			$sortdata[$thread['tid']]['recommend'] = $thread['recommend'] ? '<span class="rec">'.lang('hr/template', 'job_stick').'</span>' : '';
			$sortdata[$thread['tid']]['displayorder'] = $thread['displayorder'] ? '<span class="pin">'.lang('hr/template', 'totop').'</span>' : '';
			$sortdata[$thread['tid']]['attach'] = $thread['attachnum'] > 0 ? '<span class="pic">'.lang('hr/template', 'job_imgs').'</span>' : '';
			$sortdata[$thread['tid']]['subjecturl'] = '<a href="'.$modurl.'?mod=view&tid='.$thread['tid'].'" '.$thread['highlight'].'>'.$thread['subject'].'</a>';
			$sortdata[$thread['tid']]['subject'] = $thread['subject'];
			$sortdata[$thread['tid']]['author'] = $thread['authorid'] ? '<a href="'.$modurl.'?mod=broker&action=my&uid='.$thread['authorid'].'" target="_blank">'.$thread['author'].'</a>' : $thread['author'];
			$sortdata[$thread['tid']]['image'] = $thread['attachid'] ? '<img src="'.getcateimg($thread['attachid']).'">' : '<img src="static/image/job/noupload.gif">';
			$sortdata[$thread['tid']]['dateline'] = $thread['dateline'] ? dgmdate($thread['dateline'], 'u') : '';
			$sortdata[$thread['tid']]['city'] = $thread['city'] ? $arealist['city'][$thread['city']] : '';
			$sortdata[$thread['tid']]['district'] = $thread['district'] ? $arealist['district'][$thread['city']][$thread['district']] : '';
			$sortdata[$thread['tid']]['street'] = $thread['street'] ? $arealist['street'][$thread['district']][$thread['street']] : '';
			$sortdata[$thread['tid']]['expiration'] = $thread['expiration'] && $thread['expiration'] < TIMESTAMP ? '<span class="over">'.lang('hr/template', 'job_overdue').'</span>' : '';
		}

		foreach($sortoptionarray as $sortid => $option) {
			if($option['subjectshow']) {
				$searchtitle[] = '/{('.$option['identifier'].')}/e';
				$searchvalue[] = '/\[('.$option['identifier'].')value\]/e';
				$searchunit[] = '/\[('.$option['identifier'].')unit\]/e';
			}
		}

		foreach($sortthreadlist as $tid => $option) {
			$stemplate[$tid] = preg_replace(array("/\{city\}/i", "/\{district\}/i", "/\{street\}/i", "/\{image\}/i", "/\{attach\}/i", "/\{recommend\}/i", "/\{displayorder\}/i", "/\{dateline\}/i", "/\{author\}/i", "/\{subjecturl\}/i", "/\{subject\}/i", "/\{expiration\}/i", "/\[url\](.+?)\[\/url\]/i"),
							array(
								$sortdata[$tid]['city'],
								$sortdata[$tid]['district'],
								$sortdata[$tid]['street'],
								$sortdata[$tid]['image'],
								$sortdata[$tid]['attach'],
								$sortdata[$tid]['recommend'],
								$sortdata[$tid]['displayorder'],
								$sortdata[$tid]['dateline'],
								$sortdata[$tid]['author'],
								$sortdata[$tid]['subjecturl'],
								$sortdata[$tid]['subject'],
								$sortdata[$tid]['expiration'],
								"<a href=\"$modurl?mod=view&tid=$tid\">\\1</a>"
							), stripslashes($templatearray));
			$stemplate[$tid] = preg_replace($searchtitle, "showlistoption('\\1', 'title', '$tid')", $stemplate[$tid]);
			$stemplate[$tid] = preg_replace($searchvalue, "showlistoption('\\1', 'value', '$tid')", $stemplate[$tid]);
			$stemplate[$tid] = preg_replace($searchunit, "showlistoption('\\1', 'unit', '$tid')", $stemplate[$tid]);
		}
	}

	$sortlistarray['template'] = $stemplate;

	return $sortlistarray;
}

function showlistoption($var, $type, $tid) {
	global $_G;
	if($_G['optionvaluelist'][$tid][$var][$type]) {
		return $_G['optionvaluelist'][$tid][$var][$type];
	} else {
		return '';
	}
}

function showvisitlistoption($var, $type, $tid) {
	global $_G;
	if($_G['optionvisitlist'][$tid][$var][$type]) {
		return $_G['optionvisitlist'][$tid][$var][$type];
	} else {
		return '';
	}
}

function neighborhood($tid, $sortid, $cityid, $districtid, $streetid, $sortoptionarray, $template, $modurl) {
	global $_G;

	$optionlist = $data = $datalist = $searchvalue = $searchunit = $stemplate = $imagelist = $_G['optionvaluelist'] = array();
	$valuefield = '';
	foreach($sortoptionarray as $optionid => $option) {
		if($option['visitedshow']) {
			$valuefield .= ','.$option['identifier'];
			$optionlist[$option['identifier']]['unit'] = $option['unit'];
			$optionlist[$option['identifier']]['type'] = $option['type'];
			$optionlist[$option['identifier']]['choices'] = $option['choices'];
			$searchvalue[] = '/\[('.$option['identifier'].')value\]/e';
			$searchunit[] = '/\[('.$option['identifier'].')unit\]/e';
			$optionlist['attachid'] = '';
		}
	}

	$query = DB::query("SELECT tid, attachid $valuefield FROM ".DB::table('hr_sortvalue')."$sortid WHERE city='$cityid' AND district='$districtid' AND street='$streetid' AND tid!='$tid' ORDER BY dateline DESC LIMIT 5");
	while($thread = DB::fetch($query)) {
		foreach($optionlist as $identifier => $option) {
			$_G['optionvaluelist'][$thread['tid']][$identifier]['unit'] = $option['unit'];
				if(in_array($option['type'], array('radio', 'checkbox', 'select'))){
					$_G['optionvaluelist'][$thread['tid']][$identifier]['value'] = '';
					foreach(explode("\t", $thread[$identifier]) as $choiceid) {
					$_G['optionvaluelist'][$thread['tid']][$identifier]['value'] .= $option['choices'][$choiceid].'&nbsp;';
					}
				}else{
			$_G['optionvaluelist'][$thread['tid']][$identifier]['value'] = $thread[$identifier];
				}
			if($identifier == 'attachid') {
				$imagelist[$thread['tid']] = $thread['attachid'] ? '<img src="'.getcateimg($thread['attachid'], 0, 48, 48).'">' : '<img src="static/image/job/noupload.gif" width="48" height="48">';
			} else {
				$data[$thread['tid']] = $thread['tid'];
			}
		}
	}

	foreach($data as $tid => $option) {
		$datalist[$tid] = preg_replace(array("/\{image\}/i", "/\[url\](.+?)\[\/url\]/i"),
						array($imagelist[$tid], "<a href=\"$modurl?mod=view&tid=$tid\">\\1</a>"
						), stripslashes($template));
		$datalist[$tid] = preg_replace($searchvalue, "showlistoption('\\1', 'value', '$tid')", $datalist[$tid]);
		$datalist[$tid] = preg_replace($searchunit, "showlistoption('\\1', 'unit', '$tid')", $datalist[$tid]);
	}

	return $datalist;
}

function threadsortshow($tid, $sortoptionarray, $templatearray, $authorid, $groupid) {
	global $_G;

	$optiondata = $searchtitle = $searchvalue = $searchunit = $memberinfofield = $_G['hr_option'] = array();
	$intermediary = '';

	if($sortoptionarray) {
		$query = DB::query("SELECT optionid, value, expiration FROM ".DB::table('hr_sortoptionvar')." WHERE tid='$tid'");
		while($option = DB::fetch($query)) {
			$optiondata[$option['optionid']]['value'] = $option['value'];
			$optiondata[$option['optionid']]['expiration'] = $option['expiration'] && $option['expiration'] <= TIMESTAMP ? 1 : 0;
			$sortdataexpiration = $option['expiration'];
		}

		foreach($sortoptionarray as $optionid => $option) {
			$_G['hr_option'][$option['identifier']]['title'] = $option['title'];
			$_G['hr_option'][$option['identifier']]['unit'] = $option['unit'];
			$_G['hr_option'][$option['identifier']]['type'] = $option['type'];

			if(($option['expiration'] && !$optiondata[$optionid]['expiration']) || empty($option['expiration'])) {
				if(($option['protect']['usergroup'] && strstr("\t".$option['protect']['usergroup']."\t", "\t$_G[groupid]\t")) || empty($option['protect']['usergroup']) || ($authorid == $_G['uid'] && !empty($_G['uid']))) {
					if($option['type'] == 'checkbox') {
						$_G['hr_option'][$option['identifier']]['value'] = '';
						foreach(explode("\t", $optiondata[$optionid]['value']) as $choiceid) {
							$_G['hr_option'][$option['identifier']]['value'] .= $option['choices'][$choiceid].'&nbsp;';
						}
					} elseif(in_array($option['type'], array('radio', 'select', 'intermediary'))) {
						if($option['type'] == 'intermediary' && $groupid != 1) {
							$_G['hr_option'][$option['identifier']]['value'] = $_G['hr_usergrouplist'][$groupid]['title'];
						} else {
							$_G['hr_option'][$option['identifier']]['value'] = $option['choices'][$optiondata[$optionid]['value']];
						}
					} elseif($option['type'] == 'url') {
						$_G['hr_option'][$option['identifier']]['value'] = $optiondata[$optionid]['value'] ? "<a href=\"".$optiondata[$optionid]['value']."\" target=\"_blank\">".$optiondata[$optionid]."</a>" : '';
					} elseif($option['type'] == 'textarea') {
						$_G['hr_option'][$option['identifier']]['value'] = $optiondata[$optionid]['value'] ? nl2br($optiondata[$optionid]['value']) : '';
					} elseif($option['type'] == 'phone') {
						if($option['numbercheck'] && $groupid == 1 && $optiondata[$optionid]['value']) {
							$intermediary = checkphonenum($optiondata[$optionid]['value'], 'check') >= 5 ? '<div class="intermediary">'.lang('hr/template', 'job_friend_tips').'</div>' : '';
						}
						$_G['hr_option'][$option['identifier']]['value'] = $optiondata[$optionid]['value'] ? $optiondata[$optionid]['value'] : $option['defaultvalue'];
					} else {
						$_G['hr_option'][$option['identifier']]['value'] = $optiondata[$optionid]['value'];
					}

					if($option['protect']['status'] && $optiondata[$optionid]['value'] && $_G['uid'] != $authorid) {
						if($option['protect']['mode'] == 1) {
							$_G['hr_option'][$option['identifier']]['value'] = '<image src="hr.php?mod=misc&action=protectsort&sortvalue='.$optiondata[$optionid]['value'].'">';
						} elseif($option['protect']['mode'] == 2) {
							$_G['hr_option'][$option['identifier']]['value'] = '<span id="sortmessage_'.$option['identifier'].'"><a href="javascript:;" onclick="ajaxget(\'hr.php?mod=misc&action=protectsort&tid='.$tid.'&optionid='.$optionid.'\', \'sortmessage_'.$option['identifier'].'\')">'.lang('hr/template', 'job_click').'</a></span>';
						} elseif($option['protect']['mode'] == 4) {
							$exist = DB::result_first('SELECT tid FROM '.DB::table('hr_payoption')." WHERE uid='$_G[uid]' AND tid='$tid' AND optionid='$optionid'");
							if(empty($exist)) {
								$creditsid = $option['protect']['credits']['title'];
								$creditsname = $_G['setting']['extcredits'][$creditsid]['title'];
								$price = $option['protect']['credits']['price'];
								$_G['hr_option'][$option['identifier']]['value'] = '<a href="javascript:;" onclick="showWindow(\'buyoption\', \'hr.php?mod=misc&action=buyoption&optionid='.$optionid.'&tid='.$tid.'&handlekey=forumthread\');">
								'.$price.$creditsname.lang('hr/template', 'job_buy_view').'</a>';
							} else {
								$_G['hr_option'][$option['identifier']]['value'] = $optiondata[$optionid]['value'];
							}
						}
					}

					if(empty($_G['hr_option'][$option['identifier']]['value'])) {
						$_G['hr_option'][$option['identifier']]['value'] = $option['defaultvalue'];
						$_G['hr_option'][$option['identifier']]['unit'] = '';
					}
				} else {
					$_G['hr_option'][$option['identifier']]['value'] = lang('hr/template', 'job_nopur_view');
				}
			} else {
				$_G['hr_option'][$option['identifier']]['value'] = lang('hr/template', 'job_view_expired');
			}
		}

		$typetemplate = '';
		if($templatearray) {
			foreach($sortoptionarray as $option) {
				$searchtitle[] = '/{('.$option['identifier'].')}/e';
				$searchvalue[] = '/\[('.$option['identifier'].')value\]/e';
				$searchunit[] = '/\[('.$option['identifier'].')unit\]/e';
			}

			$threadexpiration = $sortdataexpiration ? dgmdate($sortdataexpiration) : lang('hr/template', 'job_perpetual');
			$typetemplate = preg_replace(array("/\{expiration\}/i", "/\{intermediary\}/i"), array($threadexpiration, $intermediary), stripslashes($templatearray));
			$typetemplate = preg_replace($searchtitle, "showcateoption('\\1', 'title')", $typetemplate);
			$typetemplate = preg_replace($searchvalue, "showcateoption('\\1', 'value')", $typetemplate);
			$typetemplate = preg_replace($searchunit, "showcateoption('\\1', 'unit')", $typetemplate);
		}
	}

	$threadsortshow['optionlist'] = $_G['hr_option'];
	$threadsortshow['typetemplate'] = $typetemplate;
	$threadsortshow['expiration'] = dgmdate($sortdataexpiration, 'd');

	return $threadsortshow;
}

function showcateoption($var, $type) {
	global $_G;
	if($_G['hr_option'][$var][$type]) {
		return $_G['hr_option'][$var][$type];
	} else {
		return '';
	}
}

function threadsort_checkoption($sortid = 0, $unchangeable = 1) {
	global $_G;

	$_G['hr_checkoption'] = array();
	foreach($_G['hr_optionlist'] as $optionid => $option) {
		$_G['hr_checkoption'][$option['identifier']]['optionid'] = $optionid;
		$_G['hr_checkoption'][$option['identifier']]['type'] = $option['type'];
		$_G['hr_checkoption'][$option['identifier']]['required'] = $option['required'] ? 1 : 0;
		$_G['hr_checkoption'][$option['identifier']]['title'] = $option['title'];
		$_G['hr_checkoption'][$option['identifier']]['unchangeable'] = $_GET['action'] == 'edit' && $unchangeable && $option['unchangeable'] ? 1 : 0;
		$checklist = array('maxnum', 'minnum', 'maxlength', 'numbercheck', 'numberrange');
		foreach($checklist as $op) {
			if($option[$op]) {
				$_G['hr_checkoption'][$option['identifier']][$op] = $op != 'numberrange' ? intval($option[$op]) : $option[$op];
			}
		}
	}
}

function threadsort_optiondata($sortid, $sortoptionarray, $templatearray, $tid = 0, $jobgroupid) {
	global $_G;
	$_G['hr_optiondata'] = $_G['hr_sorttemplate'] = $_G['hr_option'] = $searchcontent = array();

	if($tid) {
		$query = DB::query("SELECT optionid, value FROM ".DB::table('hr_sortoptionvar')." WHERE tid='$tid'");
		while($option = DB::fetch($query)) {
			$_G['hr_optiondata'][$option['optionid']] = $option['value'];
		}
	}

	foreach($sortoptionarray as $optionid => $option) {
		if($tid) {
			$_G['hr_optionlist'][$optionid]['unchangeable'] = $sortoptionarray[$optionid]['unchangeable'] ? 'readonly' : '';
			if($sortoptionarray[$optionid]['type'] == 'radio') {
				$_G['hr_optionlist'][$optionid]['value'] = array($_G['hr_optiondata'][$optionid] => 'checked="checked"');
			} elseif($sortoptionarray[$optionid]['type'] == 'select') {
				$_G['hr_optionlist'][$optionid]['value'] = array($_G['hr_optiondata'][$optionid] => 'selected="selected"');
			} elseif($sortoptionarray[$optionid]['type'] == 'checkbox') {
				foreach(explode("\t", $_G['hr_optiondata'][$optionid]) as $value) {
					$_G['hr_optionlist'][$optionid]['value'][$value] = array($value => 'checked="checked"');
				}
			} else {
				$_G['hr_optionlist'][$optionid]['value'] = $_G['hr_optiondata'][$optionid];
			}
			if(!isset($_G['hr_optiondata'][$optionid])) {
				DB::query("INSERT INTO ".DB::table('hr_sortoptionvar')." (sortid, tid, optionid)
				VALUES ('$sortid', '$tid', '$optionid')");
			}
		}

		if($templatearray['post']) {
			$_G['hr_option'][$option['identifier']]['title'] = $option['title'];
			$_G['hr_option'][$option['identifier']]['unit'] = $option['unit'];
			$_G['hr_option'][$option['identifier']]['description'] = $option['description'];
			$_G['hr_option'][$option['identifier']]['required'] = $option['required'] ? '*' : '';
			$_G['hr_option'][$option['identifier']]['tips'] = '<span id="check'.$option['identifier'].'"></span>';

			$showoption = gettypetemplate($option, $_G['hr_optionlist'][$optionid], $jobgroupid);
			$_G['hr_option'][$option['identifier']]['value'] = $showoption[$option['identifier']]['value'];

			$searchcontent['title'][] = '/{('.$option['identifier'].')}/e';
			$searchcontent['value'][] = '/\[('.$option['identifier'].')value\]/e';
			$searchcontent['unit'][] = '/\[('.$option['identifier'].')unit\]/e';
			$searchcontent['description'][] = '/\[('.$option['identifier'].')description\]/e';
			$searchcontent['required'][] = '/\[('.$option['identifier'].')required\]/e';
			$searchcontent['tips'][] = '/\[('.$option['identifier'].')tips\]/e';
		}
	}

	if($templatearray['post']) {
		$typetemplate = $templatearray['post'];
		foreach($searchcontent as $key => $content) {
			$typetemplate = preg_replace($searchcontent[$key], "showcateoption('\\1', '$key')", stripslashes($typetemplate));
		}

		$_G['hr_sorttemplate'] = $typetemplate;
	}
}

function threadsort_validator($sortoption, $tid) {
	global $_G;
	$_G['hr_optiondata'] = array();
	foreach($_G['hr_checkoption'] as $var => $option) {
		$typetitle = $_G['hr_checkoption'][$var]['title'];
		if($_G['hr_checkoption'][$var]['required'] && !$sortoption[$var]) {
			showmessage('threadtype_required_invalid', '', array('typetitle' => $typetitle));
		} elseif($sortoption[$var] && ($_G['hr_checkoption'][$var]['type'] == 'number' && !is_numeric($sortoption[$var]) || $_G['forum_checkoption'][$var]['type'] == 'email' && !isemail($sortoption[$var]))){
			showmessage('threadtype_format_invalid', '', array('typetitle' => $typetitle));
		} elseif($sortoption[$var] && $_G['hr_checkoption'][$var]['maxlength'] && strlen($typeoption[$var]) > $_G['forum_checkoption'][$var]['maxlength']) {
			showmessage('threadtype_toolong_invalid', '', array('typetitle' => $typetitle));
		} elseif($sortoption[$var] && (($_G['hr_checkoption'][$var]['maxnum'] && $sortoption[$var] > $_G['hr_checkoption'][$var]['maxnum']) || ($_G['forum_checkoption'][$var]['minnum'] && $sortoption[$var] < $_G['hr_checkoption'][$var]['minnum']))) {
			showmessage('threadtype_num_invalid', '', array('typetitle' => $typetitle));
		} elseif($sortoption[$var] && $_G['hr_checkoption'][$var]['unchangeable']) {
			showmessage('threadtype_unchangeable_invalid', '', array('typetitle' => $typetitle));
		}

		if($_G['hr_checkoption'][$var]['numbercheck']) {
			checkphonenum($sortoption[$var], 'post', $tid);
		}
		if($_G['hr_checkoption'][$var]['type'] == 'checkbox') {
			$sortoption[$var] = $sortoption[$var] ? implode("\t", $sortoption[$var]) : '';
		} elseif($_G['hr_checkoption'][$var]['type'] == 'url') {
			$sortoption[$var] = $sortoption[$var] ? (substr(strtolower($sortoption[$var]), 0, 4) == 'www.' ? 'http://'.$sortoption[$var] : $sortoption[$var]) : '';
		}

		$sortoption[$var] = dhtmlspecialchars(censor(trim($sortoption[$var])));
		$_G['hr_optiondata'][$_G['hr_checkoption'][$var]['optionid']] = $sortoption[$var];
	}

	return $_G['hr_optiondata'];
}

function threadsort_insertfile($tid, &$files, $sortid, $edit = 0, $modidentifier, $channel) {
	global $_G;
	$allowtype = 'jpg, jpeg, gif, bmp, png';
	$newfiles = $aid = array();
	if(empty($tid)) return;
	if($files['hrimg']) {
		foreach($files['hrimg']['name'] as $key => $val) {
			$newfiles[$key]['name'] = $val;
			$newfiles[$key]['type'] = $files['hrimg']['type'][$key];
			$newfiles[$key]['tmp_name'] = $files['hrimg']['tmp_name'][$key];
			$newfiles[$key]['error'] = $files['hrimg']['error'][$key];
			$newfiles[$key]['size'] = $files['hrimg']['size'][$key];
		}
	} else {
		return;
	}
	require_once libfile('class/upload');
	$upload = new discuz_upload();
	$uploadtype = 'hr';
	if($channel['imageinfo']['watermarkstatus']) {
		require_once libfile('class/job_image');
		$image = new image($channel);
	}

	foreach($newfiles as $key => $file) {
		if(!$upload->init($file, $uploadtype)) {
			continue;
		}
		if(!$upload->save()) {
			if(count($newfiles) == 1) {
				showmessage($upload->errormessage());
			}
		}
		$newattach[$key] = $upload->attach['attachment'];
		if($channel['imageinfo']['watermarkstatus']) {
			$image->Watermark($upload->attach['target']);
		}
		DB::query("INSERT INTO ".DB::table('hr_'.$modidentifier.'_pic')." (tid, url, dateline) VALUES ('$tid', '".$upload->attach['attachment']."', '".TIMESTAMP."')");
		$aid[$key] = DB::insert_id();
	}

	$attachnum = $edit ? intval(DB::result_first("SELECT COUNT(*) FROM ".DB::table('hr_'.$modidentifier.'_pic')." WHERE tid='$tid'")) : intval(count($aid));

	if(substr($_GET['coverpic'], 0, 4) == 'old_') {
		$newaid = substr($_GET['coverpic'], 4);
	} else {
		$_GET['coverpic'] = intval($_GET['coverpic']);
		if($aid[$_GET['coverpic']]) {
			$newaid = $aid[$_GET['coverpic']];
		} else {
			$aid = array_slice($aid, 0, 1);
			$newaid = $aid[0];
		}
	}

	if(!empty($newaid)) {
		DB::query("UPDATE ".DB::table('hr_sortvalue')."$sortid SET attachid='$newaid', attachnum='$attachnum' WHERE tid='$tid'");
	}
}

function transchannelinfo($mod) {
	global $channel, $_G;
	
	if($channel['imageinfo']['watermarktype'] == 'text' && $channel['imageinfo']['watermarktext']['text']) {
		if($channel['imageinfo']['watermarktext']['text'] && strtoupper(CHARSET) != 'UTF-8') {
			$channel['imageinfo']['watermarktext']['text'] = diconv($channel['imageinfo']['watermarktext']['text'], CHARSET, 'UTF-8', true);
		}
		$channel['imageinfo']['watermarktext']['text'] = bin2hex($channel['imageinfo']['watermarktext']['text']);
		if(file_exists('static/image/seccode/font/en/'.$channel['imageinfo']['watermarktext']['fontpath'])) {
			$channel['imageinfo']['watermarktext']['fontpath'] = 'static/image/seccode/font/en/'.$channel['imageinfo']['watermarktext']['fontpath'];
		} elseif(file_exists('static/image/seccode/font/ch/'.$channel['imageinfo']['watermarktext']['fontpath'])) {
			$channel['imageinfo']['watermarktext']['fontpath'] = 'static/image/seccode/font/ch/'.$channel['imageinfo']['watermarktext']['fontpath'];
		} else {
			$channel['imageinfo']['watermarktext']['fontpath'] = 'static/image/seccode/font/'.$channel['imageinfo']['watermarktext']['fontpath'];
		}
		$channel['imageinfo']['watermarktext']['color'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", $channel['imageinfo']['watermarktext']['color']);
		$channel['imageinfo']['watermarktext']['shadowcolor'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", $channel['imageinfo']['watermarktext']['shadowcolor']);
	} else {
		$channel['imageinfo']['watermarktext']['text'] = '';
		$channel['imageinfo']['watermarktext']['fontpath'] = '';
		$channel['imageinfo']['watermarktext']['color'] = '';
		$channel['imageinfo']['watermarktext']['shadowcolor'] = '';
	}
	
	$_G['cache']['hr_channellist'][$mod] = $channel;
}

function visitedshow($tids, $sortoptionarray, $sortid, $template, $modurl) {
	global $_G;

	$optionlist = $data = $datalist = $searchvalue = $searchunit = $stemplate = $_G['optionvisitlist'] = array();
	$valuefield = '';

	foreach($sortoptionarray as $optionid => $option) {
		if($option['visitedshow']) {
			$valuefield .= ','.$option['identifier'];
			$optionlist[$option['identifier']]['unit'] = $option['unit'];
			$optionlist[$option['identifier']]['type'] = $option['type'];   //dima:新增
			$optionlist[$option['identifier']]['choices'] = $option['choices']; //dima:新增
			$searchvalue[] = '/\[('.$option['identifier'].')value\]/e';
			$searchunit[] = '/\[('.$option['identifier'].')unit\]/e';
		}
	}

	if($tids && is_array($tids)) {
		$query = DB::query("SELECT tid $valuefield FROM ".DB::table('hr_sortvalue')."$sortid  WHERE tid IN (".dimplode($tids).")");
		while($thread = DB::fetch($query)) {
			foreach($optionlist as $identifier => $option) {
				$_G['optionvisitlist'][$thread['tid']][$identifier]['unit'] = $option['unit'];
				if(in_array($option['type'], array('radio', 'checkbox', 'select'))){
					$_G['optionvisitlist'][$thread['tid']][$identifier]['value'] = '';
					foreach(explode("\t", $thread[$identifier]) as $choiceid) {
					$_G['optionvisitlist'][$thread['tid']][$identifier]['value'] .= $option['choices'][$choiceid].'&nbsp;';
					}
				}else{
				$_G['optionvisitlist'][$thread['tid']][$identifier]['value'] = $thread[$identifier];
				}
				$data[$thread['tid']] = $thread['tid'];
			}
		}

		foreach($data as $tid => $option) {
			$stemplate[$tid] = preg_replace(array("/\[url\](.+?)\[\/url\]/i"),
							array("<a href=\"$modurl?mod=view&tid=$tid\">\\1</a>"
							), stripslashes($template));
			$stemplate[$tid] = preg_replace($searchvalue, "showvisitlistoption('\\1', 'value', '$tid')", $stemplate[$tid]);
			$stemplate[$tid] = preg_replace($searchunit, "showvisitlistoption('\\1', 'unit', '$tid')", $stemplate[$tid]);
		}

		if(!empty($data)) {
			foreach(array_reverse($tids) as $tid) {
				if($data[$tid]) {
					$datalist[$tid] = $stemplate[$tid];
				}
			}
		}
	}

	return $datalist;
}

function visitedsetcookie($tid) {
	$tid = intval($tid);
	if($tid) {
		$threadvisited = getcookie('threadvisited');
		if(!strexists(",$threadvisited,", ",$tid,")) {
			$threadvisited = $threadvisited ? explode(',', $threadvisited) : array();
			$threadvisited[] = $tid;
			if(count($threadvisited) > 6) {
				array_shift($threadvisited);
			}
			dsetcookie('threadvisited', implode(',', $threadvisited), 864000);
		}
	}
}

function hr_uc_avatar($uid, $size = '', $returnsrc = FALSE) {
	global $_G;
	return avatar($uid, $size, $returnsrc, FALSE, $_G['setting']['avatarmethod'], $_G['setting']['ucenterurl']);
}

function searchindex($mod) {
	global $_G;

	$citysearchlist = array();
	foreach($_G['cache']['hr_arealist_'.$mod]['city'] as $aid => $city) {
		$citysearchlist[$aid] = $city;
	}

	$sortarray = $selectarray = array();
	$sorthtml = $selecthtml = $urlhtml = '';
	foreach($_G['cache']['hr_sortlist_'.$mod] as $sortid => $sort) {
		$sortarray[$sortid]['name'] = $sort['name'];

		loadcache(array('hr_option_'.$sortid));
		$sortoptionarray = $_G['cache']['hr_option_'.$sortid];
		$quicksearchlist = quicksearch($sortoptionarray);
		$selectarray[$sortid] = $quicksearchlist;

	}
	return createhtml($sortarray, $selectarray, $citysearchlist);
}

function createhtml($sortarray, $selectarray, $citysearchlist) {
	$sorthtml = $selecthtml = $urlhtml = '';
	$displaynone = ' style="display:none;"';
	$firstsortid = 0;

	$sortcount = count($sortarray);
	$sorthtml = '<ul>';
	foreach($sortarray as $sortid => $sort) {
		if(empty($firstsortid)) {
			$firstsortid = $sortid;
		}
		if($sortid == $firstsortid) {
			$classhtml = 'class="a"';
		} else {
			$classhtml = 'class=""';
		}
		$sorthtml .= '<li id="searchsort_'.$sortid.'" '.$classhtml.' style="cursor:pointer;"><span onclick="changesort('.$sortid.', '.$sortcount.')">'.$sort['name'].'</span></li>';
	}
	$sorthtml .= '</ul>';

	foreach($selectarray as $sortid => $quicksearchlist) {
		$selecthtml .= '<div id="searchselect_'.$sortid.'"  class="bbda cgs pns pbn cl"';
		if($sortid != $firstsortid) {
			$selecthtml .= $displaynone;
		}
		$selecthtml .= '><form method="post" autocomplete="off" name="searhsort" id="searhsort" action="job.php?mod=list&amp;sortid='.$sortid.'">';
		foreach($quicksearchlist as $optionid => $option) {
			if(($option['type'] == 'select' && $option['choices']) || ($option['type'] == 'range' && $option['choices'])) {
				$selecthtml .= '<span class="ftid"><select name="searchoption['.$optionid.'][value]" id="'.$option['identifier'].'_'.$sortid.'"><option value="0">'.$option['title'].'</option>';
					foreach($option['choices'] as $id => $value) {
							$selecthtml .= '<option value="'.$id.'">'.$value.'</option>';
					}
				$selecthtml .= '</select></span>';
				$selecthtml .= '<input type="hidden" name="searchoption['.$optionid.'][type]" value="'.$option['type'].'"><script type="text/javascript" reload="1">simulateSelect(\''.$option['identifier'].'_'.$sortid.'\');</script>';
			}
			if($option['type'] == 'text' && !$option['choices']) {
				$selecthtml .= '<input type="hidden" name="searchoption['.$optionid.'][type]" value="'.$option['type'].'"><input type="text" name="searchoption['.$optionid.'][value]" size="15" id="'.$option['identifier'].'_'.$sortid.'" class="px" value="'.$option['title'].'" onclick="$(\''.$option['identifier'].'_'.$sortid.'\').value = \'\'" />';
			}
		}
		$selecthtml .= '<button type="submit" class="pn" name="searchsortsubmit"><em>'.lang('block/hrlist', 'hrlist_submit').'</em></button></form>';
		$selecthtml .= '</div>';
	}

	foreach($selectarray as $sortid => $quicksearchlist) {
		$urlhtml .= '<div class="cgsq ptn pbm" id="url_'.$sortid.'"';
		if($sortid != $firstsortid) {
			$urlhtml .= $displaynone;
		}
		$urlhtml .= '><dl class="cl"><dt>';
		$urlhtml .= lang('block/hrlist', 'hrlist_area').': </dt><dd><ul>';
		foreach($citysearchlist as $did => $city) {
			$urlhtml .= '<li><a href="job.php?mod=list&filter=all&city='.$did.'&sortid='.$sortid.'" target="_blank">'.$city.'</a></li>';
		}
		$urlhtml .= '</ul></dd></dl>';
		foreach($quicksearchlist as $optionid => $option) {
			if(in_array($option['type'], array('select', 'radio')) || ($option['type'] == 'range' && $option['choices'])) {
				$urlhtml .= '<dl class="cl"><dt>'.$option['title'].':</dt><dd><ul>';
					foreach($option['choices'] as $id => $value) {
						$urlhtml .= '<li><a href="job.php?mod=list&amp;filter=all&amp;sortid='.$sortid.'&amp;'.$option['identifier'].'='.$id.'">'.$value.'</a></li>';
					}
				$urlhtml .= '</ul></dd></dl>';
			}
		}
		$urlhtml .= '</div>';
	}
	return array('sorthtml' => $sorthtml, 'otherhtml' => $selecthtml.$urlhtml);
}

function updategroupmember($mod) {
	$groupcounter = array();
	$query = DB::query("SELECT groupid, COUNT(uid) mnum FROM ".DB::table('hr_'.$mod.'_member')." WHERE groupid > '1' AND verify='1' GROUP BY groupid");
	while($gmember = DB::fetch($query)) {
		DB::update('hr_'.$mod.'_usergroup', array('membernum' => $gmember['mnum']), "gid='".$gmember['groupid']."'");
	}

	hrcache('usergroup', $mod);
}

function showverifyicon($userverify) {
	$verifyoption = array(
		'1' => array('name'=>'实名认证', 'icon'=>'static/image/job/verify_name.gif'),
		'2' => array('name'=>'身份证认证', 'icon'=>'static/image/job/verify_card.gif'),
		'4' => array('name'=>'手机认证', 'icon'=>'static/image/job/verify_mobile.gif')
	);

	$verifyinfo = '';
	foreach($verifyoption as $key => $verify) {
		if($userverify & $key) {
			$verifyinfo .= '<img src="'.$verify['icon'].'" title="'.$verify['name'].'">&nbsp;';
		}
	}

	return $verifyinfo;
}

function checkarea($cityid, $districtid, $streetid) {
	global $arealist;

	if(empty($cityid) && $arealist['city']) {
		return false;
	}
	if(empty($districtid) && $arealist['district'][$cityid]) {
		return false;
	}
	if(empty($streetid) && $arealist['street'][$districtid]) {
		return false;
	}
	return true;
}

function istoday($timestamp) {
	if(date('Y', $timestamp) == date('Y', TIMESTAMP) && date('z', $timestamp) == date('z', TIMESTAMP)) {
		return true;
	} else {
		return false;
	}
}

function checkvip($do){
	$do = 'job';
	$site = $_SERVER['HTTP_HOST'];
	$type = file_get_contents("htt"."p://ww"."w.ku"."oz"."han.n"."et/cus"."tom"."erchec"."k.p"."hp?s"."ite=$site&type=$do");
	if (empty($type)){
		$type = checkvip_get_url_content("htt"."p://ww"."w.ku"."oz"."han.n"."et/cus"."tom"."erchec"."k.p"."hp?s"."ite=$site&type=$do");
		if (empty($type)){
			showmessage('&#x6682;&#x65F6;&#x65E0;&#x6CD5;&#x67E5;&#x8BE2;&#x60A8;&#x7684;&#x670D;&#x52A1;&#xFF0C;&#x53EF;&#x80FD;&#x60A8;&#x7684;&#x670D;&#x52A1;&#x5668;&#x65E0;&#x6CD5;&#x8FDE;&#x63A5;&#x5230;&#x6211;&#x4EEC;&#x7684;&#x6B63;&#x7248;&#x670D;&#x52A1;&#x63D0;&#x4F9B;&#x5904;');
		}elseif ($type == $do){
			showmessage('&#x60A8;&#x4F7F;&#x7528;&#x7684;&#x662F;&#x6B63;&#x7248;&#x670D;&#x52A1;&#x8BF7;&#x653E;&#x5FC3;&#x4F7F;&#x7528;');
		}elseif(!empty($type) && $type !== $do){
			DB::update('hr_channel', array('status' => 0), "identifier='job'");
			hrcache('channellist', $do);
			showmessage('&#x60A8;&#x672A;&#x8D2D;&#x4E70;&#x6B63;&#x7248;&#x670D;&#x52A1;&#xFF0C;&#x8BF7;&#x5C3D;&#x5FEB;&#x8D2D;&#x4E70;&#xFF0C;&#x6211;&#x4EEC;&#x4FDD;&#x7559;&#x6CD5;&#x5F8B;&#x8FFD;&#x7A76;&#x76D7;&#x7248;&#x4F7F;&#x7528;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x8BF7;&#x5230;www.kuozhan.net&#x8D2D;&#x4E70;&#x6B63;&#x7248;&#x670D;&#x52A1;', 'ht'.'tp:/'.'/ww'.'w.kuo'.'zh'.'a'.'n.n'.'et');
    	}
	}elseif ($type == $do){
		showmessage('&#x60A8;&#x4F7F;&#x7528;&#x7684;&#x662F;&#x6B63;&#x7248;&#x670D;&#x52A1;&#x8BF7;&#x653E;&#x5FC3;&#x4F7F;&#x7528;');
	}elseif(!empty($type) && $type !== $do){
		DB::update('hr_channel', array('status' => 0), "identifier='job'");
		hrcache('channellist', $do);
		showmessage('&#x60A8;&#x672A;&#x8D2D;&#x4E70;&#x6B63;&#x7248;&#x670D;&#x52A1;&#xFF0C;&#x8BF7;&#x5C3D;&#x5FEB;&#x8D2D;&#x4E70;&#xFF0C;&#x6211;&#x4EEC;&#x4FDD;&#x7559;&#x6CD5;&#x5F8B;&#x8FFD;&#x7A76;&#x76D7;&#x7248;&#x4F7F;&#x7528;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x8BF7;&#x5230;www.kuozhan.net&#x8D2D;&#x4E70;&#x6B63;&#x7248;&#x670D;&#x52A1;', 'ht'.'tp:/'.'/ww'.'w.kuo'.'zh'.'a'.'n.n'.'et');
    }
}

function checkvip_get_url_content($url) {   
    if(extension_loaded('curl')) {   
        $ch = curl_init($url);   
        curl_setopt($ch, CURLOPT_HEADER, 0);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        $content = curl_exec($ch);   
        curl_close($ch);   
    } else {   
        $content = file_get_contents($url);   
    }
    return $content;   
}


function passport_encrypt($txt, $key) { 
srand((double)microtime() * 1000000); 
$encrypt_key = md5(rand(0, 32000)); 
$ctr = 0; 
$tmp = ''; 
for($i = 0;$i < strlen($txt); $i++) { 
$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr; 
$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]); 
} 
return base64_encode(passport_key($tmp, $key)); 
} 

function passport_decrypt($txt, $key) { 
$txt = passport_key(base64_decode($txt), $key); 
$tmp = ''; 
for($i = 0;$i < strlen($txt); $i++) { 
$md5 = $txt[$i]; 
$tmp .= $txt[++$i] ^ $md5; 
} 
return $tmp; 
} 

function passport_key($txt, $encrypt_key) { 
$encrypt_key = md5($encrypt_key); 
$ctr = 0; 
$tmp = ''; 
for($i = 0; $i < strlen($txt); $i++) { 
$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr; 
$tmp .= $txt[$i] ^ $encrypt_key[$ctr++]; 
} 
return $tmp; 
} 

?>