<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class block_hragent {

	function name() {
		return '�˲���';
	}

	function blockclass() {
		return array('hragent', '��������Ϣ');
	}

	function fields() {
		return array();
	}

	var $setting = array();
	function getsetting() {

		$settings = array(
			'showtype' => array(
				'title' => '��ʾ����',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '�����н�'),
					array('1', '������'),
					array('2', '������ҵ')
				)
			),
			'agentids' => array(
				'title' => 'ָ��id',
				'type' => 'text'
			),
			'orderby' => array(
				'title' => '��ʾ˳��<br>(����������)',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '��ע��ʱ��'),
					array('1', '��ӵ����Ϣ��'),
					array('2', '����������')
				)
			),
			'ordersc' => array(
				'title'=> '����',
				'type' => 'mradio',
				'default' => 0,
				'value' => array(
					array('0', '����'),
					array('1', '����')
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
		
		if(empty($showtype)) { //�����н�
			$query = DB::query("SELECT gid, title, banner, membernum, threads FROM ".DB::table('hr_job_usergroup')." WHERE gid>'1' AND verify='1' AND type='intermediary' $wheresql $ordersql limit $items");
			while($agentdata = DB::fetch($query)) {
				$agentdata['banner'] = $agentdata['banner'] ? 'data/attachment/common/'.$agentdata['banner'] : 'static/image/job/noupload.gif';
				$agentlist[] = $agentdata;
			}
			$html = $this->createhtml($agentlist);
		} elseif($showtype =='1') { //������
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
		}elseif($showtype =='2') { //������ҵ
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
			if(isset($agent['membernum'])) { //�����н�
				$html .= '<dl class="xld">
							<dd class="m"><a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'"><img src="'.$agent['banner'].'"></a></dd>
							<dt><a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'" class="xi2">'.$agent['title'].'</a></dt>
							<dd>������: '.$agent['membernum'].' λ</dd>
							<dd>��Ϣ: '.$agent['threads'].' ��</dd>
						</dl>';

			} else { //������
				$html .= '<dl class="xld">
							<dd class="m"><a href="job.php?mod=broker&action=my&uid='.$agent['uid'].'">'.$agent['avatar'].'</a></dd>
							<dt><a href="job.php?mod=broker&action=my&uid='.$agent['uid'].'" class="xi2">'.$agent['realname'].'</a></dt>
							<dd>��˾:<a href="job.php?mod=agent&action=store&gid='.$agent['gid'].'">'.$agent['title'].'</a></dd>
							<dd>��Ϣ: '.$agent['threads'].' ��</dd>
						</dl>';
			}

		}

		$html .= '</div>';
		return $html;
	}

	function createcompanyhtml($companylist) {
		$html = '<div><ul class="ml mlmco cl">';

		foreach($companylist as $company) {
			if(isset($company['membernum'])) { //�����н�
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