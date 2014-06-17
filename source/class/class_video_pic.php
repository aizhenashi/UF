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

//��ƵͼƬץȡ��
class Videocatch{

	//ԴͼƬ·��
	private $resUrl;

	//��Ƶ��Ϣץȡ����
	private $function;
	
	
	//��Ƶ��Ϣ
	public $videoinfo = array();
	
	//��ʼ������
	public function __construct($resUrl){

		//Դ·����ֵ
		$this->resUrl = $resUrl;
		
		//ͨ��Դ·������ȡ ʹ�õĺ���
		$this->setFunction();
		
	}
	
	/**
	 * ͨ��Դ·�� �� ��ȡ����
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
	 *  �ſ���Ƶץȡ��Ϣ
	 */
	private function getYouku() {
	
		$pattern = "/\<a title=\"ת����QQ����\".*href=\".*\?title=(.*)&url=.*&pics=(.*)&site=.*\".*?<\/a>|<a title=\"ת����MOP\".*href=\".*&flashUrl=(.*)&pageUrl=.*>.*<\/a>/";
		
		//���ó�ʱʱ��
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'timeout'=>120,
			)
		);
		$context = stream_context_create($opts);
		$html = file_get_contents($this->resUrl, false, $context);
		
		//��html����ת����ΪGBK
		$html = iconv("UTF-8", "GBK//IGNORE", $html);
		preg_match_all($pattern, $html,$matches);
		
		//���� title pic flash_address
		$this->videoinfo['title'] = iconv('utf-8', 'gbk', urldecode($matches[1][0]));
		$this->videoinfo['pic'] = $matches[2][0];
		$this->videoinfo['flash_address'] = $matches[3][2];

	}

	/**
	 * ����
	 * http://www.tudou.com/listplay/YobYc9kS_T8/tvnbr43JhIg.html?FR=LIAN
	 * ������Ϣ
	 */
	private function getTudou_listplay(){

		$pattern = "/,lid =.*,lcode.*=.*\'(\w+)\'.*iid\:(\d+)\s+,kw:\"(.*)\".*pic:\"(.*)\".*for\(var n in itemData\)/sU";
		
		//���ó�ʱʱ��
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
	 * ����
	 * http://www.tudou.com/programs/view/Dv4_f3Y1Vjc/?fr=rec2
	 * ������Ϣ
	 */
	private function getTudou_programs(){

		$pattern = "/,icode:\s+\'([\w\-]+)\'.*,pic:\s+\'(.+)\'.*,kw:\s+\'(.*)\'.*,desc:/sU";
		
		//���ó�ʱʱ��
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
	 * ����
	 * http://www.tudou.com/albumplay/GPJO5cuV5Ms.html
	 * ������ַ����Ϣ
	 */
	function getTudou_albumplay(){
		
		$pattern = "/,acode='(.*)'.*iid:.*(\d+)\s.*kw:.*\"(.*)\".*pic.*:.*\"(.*)\".*,time:/sU";
		
		//���ó�ʱʱ��
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
	 * ���� sohu ����
	 * http://tv.sohu.com/20130720/n382159012.shtml
	 */
	function getSohu(){		

		$pattern = "/<meta property=\"og:video\" content=\"(.*)\".*<meta property=\"og:title\" content=\"(.*)\".*<meta property=\"og:image\" content=\"(.*)\"/sU";

		//���ó�ʱʱ��
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
	 * ͨ��������ץȡͼƬ
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