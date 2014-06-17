<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class block_hragent {

	function name() {
		return '人才类';
	}

	function blockclass() {
		return array('hragent', '代理人信息');
	}

	function fields() {
		return array();
	}

	var $setting = array();
	function getsetting() {

		$settings = array(
			'showtype' => array(
				'title' => '显示类型',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '劳务中介'),
					array('1', '代理人'),
					array('2', '合作企业')
				)
			),
			'agentids' => array(
				'title' => '指定id',
				'type' => 'text'
			),
			'orderby' => array(
				'title' => '显示顺序<br>(按降序排列)',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '按注册时间'),
					array('1', '按拥有信息数'),
					array('2', '按代理人数')
				)
			),
			'ordersc' => array(
				'title'=> '排序',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '降序'),
					array('1', '升序')
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

		$showtype = isset($parameter['showtype']) ? $parameter['showtype'] : '';
		$agentids = !empty($parameter['agentids']) ? explode(',', $parameter['agentids']) : array();
		$items = !empty($parameter['items']) ? intval($parameter['items']) : 10;
		$orderby = isset($parameter['orderby']) ? $parameter['orderby'] : 0;
		$ordersc = isset($parameter['ordersc']) ? $parameter['ordersc'] : 0;
		
		$wheresql = '';
		if($agentids) {
			$wheresql = " AND ".(empty($showtype) ? 'gid' : 'uid')." IN (".dimplode($agentids).") ";
		}
		
		$ordersql = '';
		if($showtype == '1' && $orderby == 0) {
			$ordersql = ' ORDER BY uid '.(empty($ordersc) ? 'DESC' : 'ASC');
		} elseif($showtype !== '1' && $orderby == 0) {
			$ordersql = ' ORDER BY gid '.(empty($ordersc) ? 'DESC' : 'ASC');
		} elseif($orderby == 1) {
			$ordersql = ' ORDER BY threads '.(empty($ordersc) ? 'DESC' : 'ASC');
		} elseif($showtype !== '1' && $orderby == 2) {
			$ordersql = ' ORDER BY membernum '.(empty($ordersc) ? 'DESC' : 'ASC');
		}
		
		$agentlist = array();
		
		if(empty($showtype)) { //劳务中介
			$query = DB::query("SELECT gid, title, banner, membernum, threads FROM ".DB::table('hr_job_usergroup')." WHERE gid>'1' AND verify='1' AND type='intermediary' $wheresql $ordersql limit $items");
			while($agentdata = DB::fetch($query)) {
				$agentdata['banner'] = $agentdata['banner'] ? 'data/attachment/common/'.$agentdata['banner'] : 'static/image/job/noupload.gif';
				$agentlist[] = $agentdata;
			}
			$html = $this->createhtml($agentlist);
		} elseif($showtype =='1') { //经纪人
			$query = DB::query("SELECT uid, groupid, threads, realname FROM ".DB::table('hr_job_member')." WHERE groupid>'1' AND verify='1' $wheresql $ordersql limit $items");
			$agentids = array();
			while($agentdata = DB::fetch($query)) {
				$agentdata['avatar'] = avatar($agentdata['uid']);
				$agentlist[$agentdata['uid']] = $agentdata;
				if(empty($agentdata['realname'])) {
					$agentids[] = $agentdata['uid'];
				}
			}

			$query = DB::query("SELECT uid, username FROM ".DB::table('common_member')." WHERE uid IN (".dimplode($agentids).")");
			while($name = DB::fetch($query)) {
				$agentlist[$name['uid']]['realname'] = $name['username'];
			}
			
			loadcache('hr_usergrouplist_job');
			if(isset($_G['cache']['hr_usergrouplist_job'])) {
				foreach($agentlist as $k => $v) {
					if($_G['cache']['hr_usergrouplist_job'][$v['groupid']]) {
						$agentlist[$k]['title'] = $_G['cache']['hr_usergrouplist_job'][$v['groupid']]['title'];
					}
				}
			}
			$html = $this->createhtml($agentlist);
		}elseif($showtype =='2') { //合作企业
			$query = DB::query("SELECT gid, title, banner, membernum, threads FROM ".DB::table('hr_job_usergroup')." WHERE gid>'1' AND verify='1' AND type='company' $wheresql $ordersql limit $items");
			while($companydata = DB::fetch($query)) {
				$companydata['banner'] = $companydata['banner'] ? 'data/attachment/common/'.$companydata['banner'] : 'static/image/job/defaultcompanylogo.gif';
				$companylist[] = $companydata;
			}
			$html = $this->createcompanyhtml($companylist);
		}

		return array('html' => $html, 'data' => null);
	}

	function createhtml($agentlist) {
		$html = '<div class="agency">';

		foreach($agentlist as $agent) {
			if(isset($agent['membernum'])) { //劳务中介
				$html .= '<dl class="xld">
							<dd class="m"><a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'"><img src="'.$agent['banner'].'"></a></dd>
							<dt><a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'" class="xi2">'.$agent['title'].'</a></dt>
							<dd>代理人: '.$agent['membernum'].' 位</dd>
							<dd>信息: '.$agent['threads'].' 条</dd>
						</dl>';

			} else { //代理人
				$html .= '<dl class="xld">
							<dd class="m"><a href="job.php?mod=broker&action=my&uid='.$agent['uid'].'">'.$agent['avatar'].'</a></dd>
							<dt><a href="job.php?mod=broker&action=my&uid='.$agent['uid'].'" class="xi2">'.$agent['realname'].'</a></dt>
							<dd>公司:<a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'">'.$agent['title'].'</a></dd>
							<dd>信息: '.$agent['threads'].' 条</dd>
						</dl>';
			}

		}

		$html .= '</div>';
		return $html;
	}

	function createcompanyhtml($companylist) {
		$html = '<div><ul class="ml mlmco cl">';

		foreach($companylist as $company) {
			if(isset($company['membernum'])) { //劳务中介
				$html .= '
						<li>
							<a target="_blank" href="job.php?mod=company&action=store&gid='.$company['gid'].'" title="'.$company['title'].'"><img src="'.$company['banner'].'" alt="'.$company['title'].'" title="'.$company['title'].'"></a>
						</li>';

			}

		}

		$html .= '</ul></div>';
		return $html;
	}

}

?>