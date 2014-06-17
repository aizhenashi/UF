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
	 * ���ɶ�����
	 * $type 0 ����6λ�Ķ�����
	 * $type 1 ����12λ�Ķ�����
	 * $type 2 ����18λ�Ķ�����
	 * $type 3 ����24λ�Ķ�����
	 * $type 4 ���� 6,12,18,24λ�����ӵ�����
	 * 
	 */

	public function short($url,$type='0') {
		
		$key = "alexis";
        $urlhash = md5($key . $url);
        $len = strlen($urlhash);

        //����
        $short_url = "http://".$_SERVER['HTTP_HOST']."/";
        
        
        #�����ܺ�Ĵ��ֳ�4�Σ�ÿ��4�ֽڣ���ÿ�ν��м��㣬һ�������������������
        for ($i = 0; $i < 4; $i++) {
        	$urlhash_piece = substr($urlhash, $i * $len / 4, $len / 4);
            #���ֶε�λ��0x3fffffff��λ�룬0x3fffffff��ʾ����������30��1����30λ�Ժ�ļ��ܴ�������
            $hex = hexdec($urlhash_piece) & 0x3fffffff; #�˴���Ҫ�õ�hexdec()��16�����ַ���תΪ10������ֵ�ͣ���������᲻����
 
            
            #����6λ������
            for ($j = 0; $j < 6; $j++) {
            	#���õ���ֵ��0x0000003d,3dΪ61����charset���������ֵ
                $short_url .= self::$charset[$hex & 0x0000003d];
                #ѭ�����Ժ�hex����5λ
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