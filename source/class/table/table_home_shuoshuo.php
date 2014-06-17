<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_feed.php 28335 2012-02-28 04:37:47Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_shuoshuo extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_shuoshuo';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	/**
	 * 
	 * 添加说说
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function insert_shuoshuo($data) {

		global $_G;
		require_once libfile('function_shuoshuo','function');
		//将content 转码 utf-8 转 gb2312 并去空格
		$data['content'] = defineIconv($data['content']);
		$data['time'] = time();
		$data['uid'] = $_G['uid'];

		if(!empty($data) && is_array($data)) {
			$rs = DB::insert($this->_table, $data, false, true);
			if($rs == '1'){
				$id = DB::insert_id();				
			}
			return $id;
		}
		return 0;
	}	
	
	/**
	 * 还原content
	 * Enter description here ...
	 * @param unknown_type $content
	 */
	public function huanYuanContent($content){
		
		$temp = $content;
		//还原表情
		$content = $this->huanYuanBiaoQing($content);
		
		//还原视频
		$content = $this->huanYuanVideo($content);
		
		$content .= "
			<div class=\"videoPicList\">
				<ul class=\"WB_media_list clearfix\">";

		//追加 视频 html 获取视频播放图片的html
		$content .= $this->getVideoPlayHtml($temp);

		//追加图片 html 待做
		
		
        $content .= "</ul></div>";
        
		$content .= "<div style=\"display: none;border-radius: 3px 3px 3px 3px; border-style: solid;border-width: 1px;margin: 5px 0 15px;padding: 10px 20px;\" class=\"WB_media_expand SW_fun2 S_line1 S_bg1\"></div>";

		return $content;
	}
	
	
	/*
	 * 
	 * 获取视频播放图片 的html
	 */
	public function getVideoPlayHtml($content){
		
		preg_match("/http:\/\/".$_SERVER['HTTP_HOST']."\/[a-zA-Z0-9]{6}/", $content,$match);

		$content = '';		
		if($match){		
			$data = c::t('video_shortlink')->fetch_by_shortlink($match[0]);
			
			//获取actiondata
			$actiondata = $this->getActionData($data);
			$content ="
				<li action-data=\"".$actiondata."\" defineid=\"{$data['id']}\">
					<img src=\"{$data['pic']}\" class=\"videoplay\" alt=\"{$data['title']}\">
	                <span class=\"W_ico20 ico_playvideo\"></span>
				</li>";	
		}
		return $content;
		
	}

	/**
	 * $data 视频信息 id shortlink reslink pic title
	 * $content 说说内容
	 * 还原视频
	 */
	public function huanYuanVideo($content){

		$temp = explode(' ', $content);
		foreach ($temp as &$val){
			if(stripos($val, 'http://') !== false){

				if(!$datas[$val]){
					$datas[$val] = c::t('video_shortlink')->fetch_by_shortlink($val);
				}

				$data = $datas[$val];
				$val = 	'<a class="wbvideo" defineid="'.$data['id'].'" action-data="'.$this->getActionData($data).'" style="color: #0A8CD2;" href="javascript:void(0)" title="'.$data['shortlink'].'">'.$data['shortlink'].'<span title="视频" class="W_ico16 icon_sw_movie"></span></a>';
			}
		}

		$content = implode(' ', $temp);

		return $content;
		
	}
	
	/**
	 * 根据 视频信息 来拼装actiondata
	 * Enter description here ...
	 */
	public function getActionData($data){
//同一个短链接 会造成 replace 替换重复 因此 还可能造成死循环 解决方法 字符串中不带短链接
		$actiondata = "id=".$data['id']."&title=".urlencode($data['title'])."&shortlink=".$data['shortlink'].'&reslink='.$data['reslink'].'&flash_address='.rawurlencode($data['flash_address']);

		return $actiondata;	
	}
	
	/**
	 * 还原表情
	 */
	public function huanYuanBiaoQing($content){
		//1.取出所有 图片id 数组
		preg_match_all("/\[img:(\d+)\]/", $content, $matches);
		$picArr = $matches[1];
		
		//2.按照 图片id 数组 取出 所有图片地址
		$biaoqingObj = c::t('liaotian_biaoqing');

		foreach ($picArr as $picid){
			$temp = $biaoqingObj->getBiaoQingForId($picid,'`imgsrc`');
			$imgarr[] = "<img src=\"{$temp}\" />";
		}

		//3. 将[img:num] 替换成为 真正的图片
		$content = str_replace($matches[0],$imgarr,$content);

		return $content;
	}
	
	/**
	 * 通过where 条件 获取指定 条数的说说 按照什么排序 
	 * $ziduan 字段
	 * $where 条件
	 * $order 排序
	 * $limit 条数
	 * @see source/class/discuz/discuz_table::fetch()
	 */
	public function getShuoshuo($ziduan,$where = '1 = 1',$order = '`id` desc',$limit = 3){
		
		$datas = DB::fetch_all('SELECT '.$ziduan.' FROM %t WHERE '.$where.' ORDER BY '.$order.' LIMIT '.$limit, array($this->_table));
		
		foreach ($datas as &$data){

			$data['content'] = $this->huanYuanContent($data['content']);
			
			$data['username'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['uid']}'");
			
			//如果有被回复人的uid
			if($data['fuid']){
				$data['funame'] = c::t('common_member')->getOneInfo('username',"uid = '{$data['fuid']}'");
			}

			//评论数
			$temp = DB::fetch_first('SELECT count(`id`) as pnum FROM '.DB::table($this->_table)." where fid = '{$data['id']}'");
			$data['pinglunnum'] = $temp['pnum'];
			
						
			
			//赞数
			$data['zannum'] = c::t('home_zan')->get_zan_count("statu = 1 && shuoshuoid = '{$data['id']}'");
			
			
		}
		
		return $datas;
	}
	
	/**
	 * 通过where 获取说说
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function select_shuoshuo($where = '1'){

		$data = DB::fetch_all("select `id`,`content`,`uid`,`fid`,`time`,`fuid` from ".DB::table($this->table)." where {$where}");
		return $data;
	}


	public function fetch($id, $idtype = '', $uid = '', $feedid = '') {
		$wherearr = array();
		if($feedid) {
			$wherearr[] = DB::field('feedid', $feedid);
		}
		if($id) {
			$wherearr[] = DB::field('id', $id);
			$wherearr[] = DB::field('idtype', $idtype);
		}
		if($uid) {
			$wherearr[] = DB::field('uid', $uid);
		}
		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';

		if(empty($wheresql)) {
			return null;
		}

		return DB::fetch_first('SELECT * FROM '.DB::table($this->_table).' '.$wheresql);
	}

	public function fetch_all_by_uid_dateline($uids, $findex = true, $start = 0, $limit = 5) {
		if(!($uids = dintval($uids, true))) {
			return null;
		}
		return DB::fetch_all('SELECT * FROM %t '.(($findex) ? 'USE INDEX(dateline)' : '').' WHERE uid IN (%n) ORDER BY dateline desc %i', array($this->_table, $uids, DB::limit($start, $limit)));
	}

	public function fetch_all_by_hot($hotstarttime) {
		return DB::fetch_all('SELECT * FROM %t USE INDEX(hot) WHERE dateline>=%d ORDER BY hot DESC LIMIT 0,10', array($this->_table, $hotstarttime));
	}

	public function update($id, $data, $idtype = '', $uid = '', $feedid = '') {
		$condition = array();
		if($feedid) {
			$condition[] = DB::field('feedid', $feedid);
		}
		if($id) {
			$condition[] = DB::field('id', $id);
			$condition[] = DB::field('idtype', $idtype);
		}
		if($uid) {
			$condition[] = DB::field('uid', $uid);
		}

		if(empty($data) || !is_array($data) || !count($condition)) {
			return null;
		}
		DB::update($this->_table, $data, implode(' AND ', $condition));
	}



}

?>