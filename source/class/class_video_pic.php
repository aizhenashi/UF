<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: class_seccode.php 27489 2012-02-02 07:41:46Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//视频图片抓取类
class Videocatch{

	//源图片路径
	private $resUrl;

	//视频信息抓取函数
	private $function;
	
	
	//视频信息
	public $videoinfo = array();
	
	//初始化操作
	public function __construct($resUrl){

		//源路径赋值
		$this->resUrl = $resUrl;
		
		//通过源路径来获取 使用的函数
		$this->setFunction();
		
	}
	
	/**
	 * 通过源路径 来 获取函数
	 */
	private function setFunction(){
		
		$urlinfo = parse_url($this->resUrl);
		
		if($urlinfo['host'] == 'www.tudou.com'){

			if(strpos($urlinfo['path'],'programs')){
				$this->function = 'getTudou_programs';
			}else if(strpos($urlinfo['path'],'albumplay')){
				$this->function = 'getTudou_albumplay';
			}else{
				$this->function = 'getTudou_listplay';
			}
		}else if($urlinfo['host'] == 'v.youku.com'){
			$this->function = 'getYouku';
		}else if($urlinfo['host'] == 'tv.sohu.com'){
			$this->function = 'getSohu';
		}
		
	}
	
	/**
	 *  优酷视频抓取信息
	 */
	private function getYouku() {
	
		$pattern = "/\<a title=\"转发给QQ好友\".*href=\".*\?title=(.*)&url=.*&pics=(.*)&site=.*\".*?<\/a>|<a title=\"转发到MOP\".*href=\".*&flashUrl=(.*)&pageUrl=.*>.*<\/a>/";
		
		//设置超时时间
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);
		
		//将html编码转化成为GBK
		$html = iconv("UTF-8", "GBK//IGNORE", $html);
		preg_match_all($pattern, $html,$matches);
		
		//设置 title pic flash_address
		$this->videoinfo['title'] = iconv('utf-8', 'gbk', urldecode($matches[1][0]));
		$this->videoinfo['pic'] = $matches[2][0];
		$this->videoinfo['flash_address'] = $matches[3][2];

	}

	/**
	 * 处理
	 * http://www.tudou.com/listplay/YobYc9kS_T8/tvnbr43JhIg.html?FR=LIAN
	 * 这类信息
	 */
	private function getTudou_listplay(){

		$pattern = "/,lid =.*,lcode.*=.*\'(\w+)\'.*iid\:(\d+)\s+,kw:\"(.*)\".*pic:\"(.*)\".*for\(var n in itemData\)/sU";
		
		//设置超时时间
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);
				
		preg_match_all($pattern, $html,$matches);
		
		if($matches[0]){
			$this->videoinfo['title'] = $matches[3][0];
			$this->videoinfo['pic'] = $matches[4][0];
			$this->videoinfo['flash_address'] = "http://www.tudou.com/l/".$matches[1][0]."/&iid=".$matches[2][0]."/v.swf";
		}
		
	}
	
	/**
	 * 处理
	 * http://www.tudou.com/programs/view/Dv4_f3Y1Vjc/?fr=rec2
	 * 这类信息
	 */
	private function getTudou_programs(){

		$pattern = "/,icode:\s+\'([\w\-]+)\'.*,pic:\s+\'(.+)\'.*,kw:\s+\'(.*)\'.*,desc:/sU";
		
		//设置超时时间
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);

		preg_match_all($pattern, $html,$matches);
		if($matches[0]){
			$this->videoinfo['title'] = $matches[3][0];
			$this->videoinfo['pic'] = $matches[2][0];
			$this->videoinfo['flash_address'] = "http://www.tudou.com/v/".$matches[1][0];
		}

	}
	
	/**
	 * 处理
	 * http://www.tudou.com/albumplay/GPJO5cuV5Ms.html
	 * 这种网址的信息
	 */
	function getTudou_albumplay(){
		
		$pattern = "/,acode='(.*)'.*iid:.*(\d+)\s.*kw:.*\"(.*)\".*pic.*:.*\"(.*)\".*,time:/sU";
		
		//设置超时时间
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);
		preg_match_all($pattern, $html,$matches);
		
		if($matches[0]){
			$this->videoinfo['title'] = $matches[3][0];
			$this->videoinfo['pic'] = $matches[4][0];
			$this->videoinfo['flash_address'] = "http://www.tudou.com/a/".$matches[1][0]."/&iid=".$matches[2][0].'/v.swf';
		}
		
		
	}
	
	/**
	 * 处理 sohu 链接
	 * http://tv.sohu.com/20130720/n382159012.shtml
	 */
	function getSohu(){		

		$pattern = "/<meta property=\"og:video\" content=\"(.*)\".*<meta property=\"og:title\" content=\"(.*)\".*<meta property=\"og:image\" content=\"(.*)\"/sU";

		//设置超时时间
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);
		preg_match_all($pattern, $html,$matches);
		
		
		if($matches[0]){
			$this->videoinfo['title'] = $matches[2][0];
			$this->videoinfo['pic'] = $matches[3][0];
			$this->videoinfo['flash_address'] = $matches[1][0];
		}		
		
		
	}
	
	/**
	 * 通过正则来抓取图片
	 * Enter description here ...
	 */
	public function setVideoinfo(){
		
		if($this->function === null ){
			return NULL;
		}

		$function = $this->function;
		$this->$function();

	}	

} 
 	
?>