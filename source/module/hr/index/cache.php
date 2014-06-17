<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: job_index.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class fzz_cache{
	public $limit_time = 20000; //缓存过期时间
	public $cache_dir = "./data/cache/"; //缓存文件保存目录

	//写入缓存
	function __set($key , $val){
		$this->_set($key ,$val);
	}
	//第三个参数为过期时间
	function _set($key ,$val,$limit_time=null){		
		$limit_time = $limit_time ? $limit_time : $this->limit_time;
		$file = $this->cache_dir."/".$key.".cache";
		$val = serialize($val);
		@file_put_contents($file,$val) or $this->error(__line__,"fail to write in file");
		@chmod($file,0777);
		@touch($file,time()+$limit_time) or $this->error(__line__,"fail to change time");
	}


	//读取缓存
	function __get($key){
		return $this->_get($key);
	}
	function _get($key){
		$file = $this->cache_dir."/".$key.".cache";
		if (@filemtime($file)>=time()){
			return unserialize(file_get_contents($file));
		}else{
			@unlink($file) or $this->error(__line__,"fail to unlink");
			return false;
		}
	}


	//删除缓存文件
	function __unset($key){
		return $this->_unset($key);
	}
	function _unset($key){
		if (@unlink($this->cache_dir."/".$key.".cache")){
			return true;
		}else{
			return false;
		}
	}


	//检查缓存是否存在，过期则认为不存在
	function __isset($key){
		return $this->_isset($key);
	}
	function _isset($key){
		$file = $this->cache_dir."/".$key.".cache";
		if (@filemtime($file)>=time()){
			return true;
		}else{
			@unlink($file) ;
			return false;
		}
	}


	//清除过期缓存文件
	function clear(){
		$files = scandir($this->cache_dir);
		foreach ($files as $val){
			if (filemtime($this->cache_dir."/".$val)<time()){
				@unlink($this->cache_dir."/".$val);
			}
		}
	}


	//清除所有缓存文件
	function clear_all(){
		$files = scandir($this->cache_dir);
		foreach ($files as $val){
			@unlink($this->cache_dir."/".$val);
		}
	}
	
	function error($msg,$debug = false) {
		$err = new Exception($msg);
		$str = "<pre>\n<span style='color:red'>error:</span>\n".print_r($err->getTrace(),1)."\n</pre>";
		if($debug == true) {
			file_put_contents(date('Y-m-d H_i_s').".log",$str);
			return $str;
		}else{
			die($str);
		}
	}
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
    } 
