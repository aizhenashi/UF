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

//分页类
 
 class page
 {
  public $total;//需要计算的总数据
  public $perpage=10;//默认每页数据为10条
  protected $curr=1;//当前默认页码
  
  public function __construct($total,$perpage='')
  {
   $this->total=$total;
   if($perpage>0)
   {
    $this->perpage=$perpage;
   }
   
   //计算当前页码
   if(isset($_GET['page']) && ($_GET['page']+0)>0)//这里+0是为了格式化客户端传递过来的不法参数
   {
    $this->curr=$_GET['page']; 
   }
  }
  
  //显示分页内容
  public function show($howpage=8)
  {
   if($this->total<=0)
   {
    return '';
   }
   
   $cnt=ceil($this->total/$this->perpage);//算总页数
   if($cnt<=1)
   {
   return '';
   }
   //最终生成url里必然有page=N
   $url=$_SERVER['REQUEST_URI'];
   $parse=parse_url($url);
   //先判断$parse query不存在的情况
   if(!isset($parse['query']))
   {
    $parse['query']='page='.$this->curr;
   }
   //query存在的情况存不存在page
   //总是先判断不存在
   parse_str($parse['query'],$parms);
   if(!array_key_exists('page',$parms))
   {
    $parms['page']=$this->curr;
    
   }
   
   //print_r($parms);
   //判断除了page之外，还有没有其他参数
   if(count($parms)==1)
   {
    $url = $parse['path'].'?';
   }else
   {
    unset($parms['page']);
    $url = $parse['path'].'?'.http_build_query($parms).'&';
   }
   
   $pre=$this->curr-1;
   $next=$this->curr+1;
   if($pre<1)
   {
    $preLink=' ';
   }else
   {
   if($this->curr > 7 &&$this->curr !=8)
   {
    $preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; 上一页</a> <a href='.$url.'page=1 >1</a> ...  ';
	}
	elseif($this->curr ==8)
	{
	    $preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; 上一页</a> <a href='.$url.'page=1 >1</a>  ';
	}
	else
	{
	
	$preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; 上一页</a> ';
	
	}
   }
   if($next>$cnt)
   {
    $nextLink=' ';
   }else
   {
      if( $cnt -$this->curr  > 7)
   {
   $nextLink='... <a  href='.$url.'page='.$cnt.'>'.$cnt.'</a> <a class="next" href='.$url.'page='.$next.'>下一页 &gt;</a> ';
	}
	elseif($cnt -$this->curr  == 7)
	{
	$nextLink='<a  href='.$url.'page='.$cnt.'>'.$cnt.'</a> <a class="next" href='.$url.'page='.$next.'>下一页 &gt;</a>';
	}
	
		else
	{
	
	  $nextLink='<a class="next" href='.$url.'page='.$next.'>下一页 &gt;</a> ';
	
	}
   
  
   }
   //分页页码 首页 尾页 上一页 1 2 3 4 5 下一页
  //$current =$_GET['page']+0;
  //$num=$_GET['num']+0;
  //$star = 1;
  //$end = 10;
  //这里ceill和floor是为了出现浮点数
  $presome=$this->curr-ceil(($howpage-1)/2);
  $lastsome=$this->curr+floor(($howpage-1)/2);
  if($presome<1)
  {
   $lastsome+=(1-$presome);
   $presome=1;
   if($lastsome>$cnt)
   {
    $lastsome=$cnt;
   }
  }
  
  if($lastsome>$cnt)
  {
   $presome-=($lastsome-$cnt);
   $lastsome=$cnt;
   if($presome<1)
   {
    $presome=1;
   }
  }
  
  $pageLink='';
  
   for($i=$presome;$i<=$lastsome;$i++)
   {
    if($i==$this->curr)
    {
     $pageLink.='<a class="fyahover" href='.$url.'page='.$i.'>'.$i.'</a>&nbsp; ';
     continue;
    }
    $pageLink.='<a href='.$url.'page='.$i.'>'.$i.'</a>&nbsp; ';
   }
   
  return $preLink.'&nbsp;'.$pageLink.'&nbsp;'.$nextLink;   
  }
 }

?>