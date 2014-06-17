<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: class_bbcode.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class shortlink {

    public static $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

	/*
	 * 生成短链接
	 * $type 0 返回6位的短链接
	 * $type 1 返回12位的短链接
	 * $type 2 返回18位的短链接
	 * $type 3 返回24位的短链接
	 * $type 4 返回 6,12,18,24位短链接的数组
	 * 
	 */

	public function short($url,$type='0') {
		
		$key = "alexis";
        $urlhash = md5($key . $url);
        $len = strlen($urlhash);

        //域名
        $short_url = "http://".$_SERVER['HTTP_HOST']."/";
        
        
        #将加密后的串分成4段，每段4字节，对每段进行计算，一共可以生成四组短连接
        for ($i = 0; $i < 4; $i++) {
        	$urlhash_piece = substr($urlhash, $i * $len / 4, $len / 4);
            #将分段的位与0x3fffffff做位与，0x3fffffff表示二进制数的30个1，即30位以后的加密串都归零
            $hex = hexdec($urlhash_piece) & 0x3fffffff; #此处需要用到hexdec()将16进制字符串转为10进制数值型，否则运算会不正常
 
            
            #生成6位短连接
            for ($j = 0; $j < 6; $j++) {
            	#将得到的值与0x0000003d,3d为61，即charset的坐标最大值
                $short_url .= self::$charset[$hex & 0x0000003d];
                #循环完以后将hex右移5位
                $hex = $hex >> 5;
			}
 
            $short_url_list[] = $short_url;
		}
		
 		if($type > 3){
			return $short_url_list;
 		}else{
 			return $short_url_list[$type];
 		}
 		
	}
	
    
    
}

?>