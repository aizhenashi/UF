<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//Ӱ�ӵĲ�ѯ
if($_POST["ajaxgetcontent1"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
	if($act=="ȫ��")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='Ӱ��' order by times desc limit 0,10";	
 	}elseif($act == "��������")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='Ӱ��' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='Ӱ��' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "���չٷ�")
 	{
 		$sql = "select * from pre_common_action where fristClassName='Ӱ��' and secondClassName='���չٷ�' order by times desc limit 0,10";
 	}elseif($act == "���ʻ")
 	{
 		$sql = "select * from pre_common_action where fristClassName='Ӱ��' and secondClassName='���ʻ' order by times desc limit 0,10";
 	}elseif($act == "��Ժ����")
 	{
 		$sql = "select * from pre_common_action where fristClassName='Ӱ��' and secondClassName='��Ժ����' order by times desc limit 0,10";
 	}
	$filmArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent');
	exit;
}
$filmSql = "select * from pre_common_action where fristClassName='Ӱ��' order by times desc limit  1,10";
$filmArr = DB::fetch_all($filmSql);
//���ѯ
if($_POST["ajaxgetcontent2"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
 	if($act=="ȫ��")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='�' order by times desc limit 0,10";	
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$sql = "select * from pre_common_action where fristClassName='�' and startime<=$now and $now<=endtime order by times desc limit 0,10";
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='�' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "���չٷ�")
 	{
 		$sql = "select * from pre_common_action where fristClassName='�' and secondClassName='���չٷ�' order by times desc limit 0,10";
 	}elseif($act == "���ʻ")
 	{
 		$sql = "select * from pre_common_action where fristClassName='�' and secondClassName='���ʻ' order by times desc limit 0,10";
 	}elseif($act == "��Ժ����")
 	{
 		$sql = "select * from pre_common_action where fristClassName='�' and secondClassName='��Ժ����' order by times desc limit 0,10";
 	}
	$activeArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent1');
	exit;	
}
$activeSql = "select * from pre_common_action where fristClassName='�' order by times desc limit 1,10";
$activeArr = DB::fetch_all($activeSql);
//���ֲ�ѯ
if($_POST["ajaxgetcontent3"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
	if($act=="ȫ��")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='����' order by times desc limit 0,10";	
 	}elseif($act == "��������")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='����' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='����' and startime >= $now and endtime<= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "���չٷ�")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='���չٷ�' order by times desc limit 0,10";
 	}elseif($act == "���ʻ")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='���ʻ' order by times desc limit 0,10";
 	}elseif($act == "��Ժ����")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='��Ժ����' order by times desc limit 0,10";
 	}
	$musicArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent2');
	exit;	
}
$musicSql = "select * from pre_common_action where fristClassName='����' order by times desc limit 1,10";
$musicArr = DB::fetch_all($musicSql);
//չ���ѯ
if($_POST["ajaxgetcontent4"] == true)
{
 	$act = $_POST["act"];
 	$act = iconv("UTF-8", "gb2312", $act);
 	if($act=="ȫ��")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='չ��' order by times desc limit 0,10";	
	}elseif($act == "��������")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='չ��' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='չ��' and startime>= $now and endtime<=$afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "���չٷ�")
 	{
 		$sql = "select * from pre_common_action where fristClassName='չ��' and secondClassName='���չٷ�' order by times desc limit 0,10";
 	}elseif($act == "���ʻ")
 	{
 		$sql = "select * from pre_common_action where fristClassName='չ��' and secondClassName='���ʻ' order by times desc limit 0,10";
 	}elseif($act == "��Ժ����")
 	{
 		$sql = "select * from pre_common_action where fristClassName='չ��' and secondClassName='��Ժ����' order by times desc limit 0,10";
 	}
 	$zhanArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent3');
	exit;	
}
	$zhanSql = "select * from pre_common_action where fristClassName='չ��' order by times desc limit 1,10";
	$zhanArr = DB::fetch_all($zhanSql);
//�����ѯ
if($_POST["ajaxgetcontent5"] == true)
{
 	$act = $_POST["act"]; 
 	$act = iconv("UTF-8", "gb2312", $act); 
 	if($act=="ȫ��")
 	{
   		$sql = "select * from pre_common_action where fristClassName ='����' order by times desc limit 0,10";	
 	}elseif($act == "��������")
 	{
 		$now = time();
   		$sql = "select * from pre_common_action where fristClassName='����' and  startime<=$now  and endtime>=$now order by times desc limit 0,10";
 	}elseif($act == "��������")
 	{
 		$now = time();
    	$afterWeek = time()+7*24*60*60;
 		$sql = "select * from pre_common_action where fristClassName='����' and startime >= $now and endtime <= $afterWeek order by times desc limit 0,10";
 	}
 	elseif($act == "���չٷ�")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='���չٷ�' order by times desc limit 0,10";
 	}elseif($act == "���ʻ")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='���ʻ' order by times desc limit 1,10";
 	}elseif($act == "��Ժ����")
 	{
 		$sql = "select * from pre_common_action where fristClassName='����' and secondClassName='��Ժ����' order by times desc limit 0,10";
 	}
	$dianArr = DB::fetch_all($sql);
	include template('diy:ajax/active/topcontent4');
	exit;
}
$dianSql = "select * from pre_common_action where fristClassName='����' order by times desc  limit 1,10";
$dianArr = DB::fetch_all($dianSql);
//��ѯһ��Ӱ�����µļ�¼��ʾ����ҳ��ͼλ��
$sql = "select * from pre_common_action where fristClassName='Ӱ��' order by times desc limit 0,1";
$lastFilmArr = DB::query($sql);
$lastFilmArr = DB::fetch($lastFilmArr);
//��ѯһ������µļ�¼��ʾ����ҳ��ͼλ��
$sql = "select * from pre_common_action where fristClassName='�' order by times desc limit 0,1";
$lastActiveArr = DB::query($sql);
$lastActiveArr = DB::fetch($lastActiveArr);
//��ѯһ���������µļ�¼��ʾ����ҳ��ͼλ��
$sql = "select * from pre_common_action where fristClassName='����' order by times desc limit 0,1";
$lastMusicArr = DB::query($sql);
$lastMusicArr = DB::fetch($lastMusicArr);
//��ѯһ��չ�����µļ�¼��ʾ����ҳ��ͼλ��
$sql = "select * from pre_common_action where fristClassName='չ��' order by times desc limit 0,1";
$lastZhanArr = DB::query($sql);
$lastZhanArr = DB::fetch($lastZhanArr);
//��ѯһ���������µļ�¼��ʾ����ҳ��ͼλ��
$sql = "select * from pre_common_action where fristClassName='����' order by times desc limit 0,1";
$lastDianArr = DB::query($sql);
$lastDianArr = DB::fetch($lastDianArr);
include template('diy:active/search1');
?>