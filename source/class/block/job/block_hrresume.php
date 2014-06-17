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

class block_hrresume {
	
	function name() {
		return '人才类';
	}
	
	function blockclass() {
		return array('hrresume', '简历信息');
	}
	
	function fields() {
		return array();
	}
	
	var $setting = array();	
	function getsetting() {

		$settings = array(
			'uids' => array(
				'title' => 'hrlist_infoid',
				'type' => 'text'
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
			'recommend' => array(
				'title' => 'hrlist_recommend_thread',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', 'hrlist_any'),
					array('1', 'hrlist_recommend_only'),
				)
			),
			'orderby' =>array(
				'title' => 'hrlist_orderby',
				'type' => 'select',
				'default' => 1,
				'value' => array(
					array(1, 'hrlist_orderby_dateline'),
					array(2, 'hrlist_orderby_views'),
				)
			),
			'startrow' => array(
				'title' => 'sortlist_startrow',
				'type' => 'text',
				'default' => 0
			),
			'rsavw' => array(
				'title' => 'hrlist_rsavw',
				'type' => 'text',
				'default' => 90
			),
			'rsavh' => array(
				'title' => 'hrlist_rsavh',
				'type' => 'text',
				'default' => 130
			),
			'showstyle' => array(
				'title' => 'hrlist_showstyle',
				'type' => 'select',
				'default' => 1,
				'value' => array(
					array(1, 'hrlist_style_pic'),
					array(2, 'hrlist_style_txt'),
				)
			)
		);
		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);

		//参数准备
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$startrow	= !empty($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= !empty($parameter['items']) ? intval($parameter['items']) : 10;
		$pic		= isset($parameter['pic']) ? $parameter['pic'] : '';
		$recommend	= isset($parameter['recommend']) ? $parameter['recommend'] : '';
		$orderby	= isset($parameter['orderby']) ? $parameter['orderby'] : 1;
		$width		= isset($parameter['rsavw']) ? $parameter['rsavw'] : 90;
		$height		= isset($parameter['rsavh']) ? $parameter['rsavh'] : 130;
		$showstyle	= isset($parameter['showstyle']) ? $parameter['showstyle'] : 1;

		$sortdata = array();

		$sql = 	($uids ? ' AND rs.uid IN ('.dimplode($uids).')' : '')
			.($pic ? ' AND rs.avater>0' : '')
			.($recommend ? ' AND rs.recommend>0' : '');

		if($orderby == 1){
			$sortcondition['orderby'] = 'rs.updatetime';
		}else{
			$sortcondition['orderby'] = 'rs.views';
		}
		$sortcondition['ascdesc'] = 'DESC';

		$query = DB::query("SELECT rs.* , cmp.*, cm.username FROM ".DB::table('hr_resume')." rs LEFT JOIN ".DB::table('common_member')." cm ON cm.uid=rs.uid LEFT JOIN ".DB::table('common_member_profile')." cmp ON cmp.uid=rs.uid WHERE rs.verify='1' AND rs.available='1' $sql ORDER BY $sortcondition[orderby] DESC LIMIT $startrow,$items");
		while($resumedata = DB::fetch($query)) {
			if($resumedata['avater']) {
				$valueparse = parse_url($resumedata['avater']);
				if(isset($valueparse['host'])) {
					$resumedata['avater'] = $resumedata['avater'];
				} else {
					$resumedata['avater'] = $_G['setting']['attachurl'].'hr/'.$resumedata[avater].'?'.random(6);
				}
			}else{
				$resumedata['avater'] = $_G['siturl'].'static/image/job/avater.gif';
			}
			$resumedata['rsheight'] = $height;
			$resumedata['rswidth'] = $width;
			if($resumedata['gender'] == 1){
				$resumedata['gender'] = '男';
			}elseif($resumedata['gender'] == 2){
				$resumedata['gender'] = '女';
			}elseif($resumedata['gender'] == 0){
				$resumedata['gender'] = '保密';
			}
			if(!empty($resumedata['birthyear'])){
				$resumedata['age'] = dgmdate(TIMESTAMP, 'Y')-$resumedata['birthyear'];
			}
			$resumedata['updatetime'] = dgmdate($resumedata['updatetime'], 'm-d');
			
			$sortdata[]= $resumedata;
		}
		if($showstyle==1){
			$html = $this->createpichtml($sortdata);
		}elseif($showstyle==2){
			$html = $this->createtxthtml($sortdata);
		}
		return array('html' => $html, 'data' => null);
	}

	function createpichtml($sortdata) {
		$html = '<div><ul style="overflow:hidden;">';

		foreach($sortdata as $resume) {
			$html .= '<li style="float:left;">
						<div style="margin-left:10px;margin-bottom:10px;margin-right: 10px;"><center><a href="job.php?mod=resume&action=view&uid='.$resume[uid].'" title="'.$resume[realname].'" target="_blank"><img src="'.$resume[avater].'" width="'.$resume[rswidth].'px" height="'.$resume[rsheight].'px"></a><br /><a href="job.php?mod=resume&action=view&uid='.$resume[uid].'" target="_blank">'.$resume[realname].'</a></center></div>
					</li>';
		}

		$html .= '</ul></div>';
		return $html;
	}
	function createtxthtml($sortdata) {
		$html = '<div>';

		foreach($sortdata as $resume) {
			$html .= '<ul style="overflow:hidden; border-bottom: 1px dashed #ccc; line-height:26px;height:26px;">
			<span><li style="float:right; line-height:26px;"><font color="#999999">'.$resume[updatetime].'</font></li></span>
			<li style="float:left; width:90px; line-height:26px;"><img src="/static/image/feed/wall.gif"><a href="job.php?mod=resume&action=view&uid='.$resume[uid].'" target="_blank">'.$resume[realname].'</a></li>
			<li style="float:left; width:80px; line-height:26px;">'.$resume[gender].'，'.$resume[age].'岁</li>
			</ul>';
		}

		$html .= '</div>';
		return $html;
	}

}


?>