<?php
	header("Content-type:text/html;charset=utf-8");
	
	if($_GET['type'] == '2'){
		header("Content-Type: audio/mp3"); 
		header("Content-Disposition: attachment; filename=爱心天使中队之歌伴奏.mp3" );
		readfile("http://www.uestar.cn/static/aixintianshizhongduizhige.mp3");	
		//download($_SERVER['DOCUMENT_ROOT']."/static/","aixintianshizhongduizhige.mp3");
	}else if($_GET['type']=='1'){
		header("Content-Type: image/bmp"); 
		header("Content-Disposition: attachment; filename=爱心天使中队之歌.bmp" );
		readfile("http://www.uestar.cn/static/aixintianshizhongduizhige.bmp");	
	
	}else if($_GET['type']=='3'){
		header("Content-Type: winrar/rar"); 
		header("Content-Disposition: attachment; filename=爱心天使中队之歌报名表.rar" );
		readfile("http://www.uestar.cn/static/baomingbiao.rar");	
	}else if($_GET['type']=='4'){
		header("Content-Type: text/plain"); 
		header("Content-Disposition: attachment; filename=《唐洁姑娘》歌词（作词吕伟忠，作曲李昊霖，演唱杨小玲）.txt" );
		readfile("http://www.uestar.cn/static/tangjieguniang.txt");	
	}else if($_GET['type']=='5'){
		header("Content-Type: audio/mp3"); 
		header("Content-Disposition: attachment; filename=《唐洁姑娘》歌词（作词吕伟忠，作曲李昊霖，演唱杨小玲）.mp3" );
		readfile("http://www.uestar.cn/static/tangjieguniang.mp3");	
	}
    function download($file_dir,$file_name)
    //参数说明：
    //file_dir:文件所在目录
    //file_name:文件名
    {
        $file_dir = chop($file_dir);//去掉路径中多余的空格
        //得出要下载的文件的路径
        if($file_dir != '')
        {
            $file_path = $file_dir;
            if(substr($file_dir,strlen($file_dir)-1,strlen($file_dir)) != ''/'')
                $file_path .= ''/'';
            $file_path .= $file_name;
        }           
        else
            $file_path = $file_name;   
       
        //判断要下载的文件是否存在
        if(!file_exists($file_path))
        {
            echo '对不起,你要下载的文件不存在。';
            return false;
        }

        $file_size = filesize($file_path);
     
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $file_size");
        header("Content-Disposition: attachment; filename=".$file_name);
       
        $fp = fopen($file_path,"r");
        $buffer_size = 1024;
        $cur_pos = 0;
       
        while(!feof($fp)&&$file_size-$cur_pos>$buffer_size)
        {
            $buffer = fread($fp,$buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }
       
        $buffer = fread($fp,$file_size-$cur_pos);
        echo $buffer;
        fclose($fp);
        return true;

    }
	

?>