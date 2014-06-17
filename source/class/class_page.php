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

//��ҳ��
 
 class page
 {
  public $total;//��Ҫ�����������
  public $perpage=10;//Ĭ��ÿҳ����Ϊ10��
  protected $curr=1;//��ǰĬ��ҳ��
  
  public function __construct($total,$perpage='')
  {
   $this->total=$total;
   if($perpage>0)
   {
    $this->perpage=$perpage;
   }
   
   //���㵱ǰҳ��
   if(isset($_GET['page']) && ($_GET['page']+0)>0)//����+0��Ϊ�˸�ʽ���ͻ��˴��ݹ����Ĳ�������
   {
    $this->curr=$_GET['page']; 
   }
  }
  
  //��ʾ��ҳ����
  public function show($howpage=8)
  {
   if($this->total<=0)
   {
    return '';
   }
   
   $cnt=ceil($this->total/$this->perpage);//����ҳ��
   if($cnt<=1)
   {
   return '';
   }
   //��������url���Ȼ��page=N
   $url=$_SERVER['REQUEST_URI'];
   $parse=parse_url($url);
   //���ж�$parse query�����ڵ����
   if(!isset($parse['query']))
   {
    $parse['query']='page='.$this->curr;
   }
   //query���ڵ�����治����page
   //�������жϲ�����
   parse_str($parse['query'],$parms);
   if(!array_key_exists('page',$parms))
   {
    $parms['page']=$this->curr;
    
   }
   
   //print_r($parms);
   //�жϳ���page֮�⣬����û����������
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
    $preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; ��һҳ</a> <a href='.$url.'page=1 >1</a> ...  ';
	}
	elseif($this->curr ==8)
	{
	    $preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; ��һҳ</a> <a href='.$url.'page=1 >1</a>  ';
	}
	else
	{
	
	$preLink='<a class="top" href='.$url.'page='.$pre.'>&lt; ��һҳ</a> ';
	
	}
   }
   if($next>$cnt)
   {
    $nextLink=' ';
   }else
   {
      if( $cnt -$this->curr  > 7)
   {
   $nextLink='... <a  href='.$url.'page='.$cnt.'>'.$cnt.'</a> <a class="next" href='.$url.'page='.$next.'>��һҳ &gt;</a> ';
	}
	elseif($cnt -$this->curr  == 7)
	{
	$nextLink='<a  href='.$url.'page='.$cnt.'>'.$cnt.'</a> <a class="next" href='.$url.'page='.$next.'>��һҳ &gt;</a>';
	}
	
		else
	{
	
	  $nextLink='<a class="next" href='.$url.'page='.$next.'>��һҳ &gt;</a> ';
	
	}
   
  
   }
   //��ҳҳ�� ��ҳ βҳ ��һҳ 1 2 3 4 5 ��һҳ
  //$current =$_GET['page']+0;
  //$num=$_GET['num']+0;
  //$star = 1;
  //$end = 10;
  //����ceill��floor��Ϊ�˳��ָ�����
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