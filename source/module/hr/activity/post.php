<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_G['uid']==0)
{
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
//�����ύStart
if(submitcheck('formhash')) {
	$image='';
	if ((($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 200000))
	{
		if ($_FILES["file"]["error"] > 0)
		{

		}
		else
		{
			$fileName = md5(rand()*10000000).'.jpg';
			$month=date('Ym');
			$day=date('d');
			$folder='data/attachment/hr/'.$month;
			if (!file_exists($folder)) {
				@mkdir($folder, 0777);
			}
			$folder .='/'.$day;
			if (!file_exists($folder)) {
				@mkdir($folder, 0777);
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $folder."/" . $fileName);
			$image=$month."/" .$day."/". $fileName;
		}
	}

	$sid = isset($_POST['sid']) ? daddslashes(trim($_POST['sid'])):'';
	$subject = isset($_POST['subject']) ? daddslashes(trim($_POST['subject'])) : '';
	$begintime = isset($_POST['begintime']) ? strtotime($_POST['begintime']) : time();
	$endtime = isset($_POST['endtime']) ? strtotime($_POST['endtime']) : $time()+3600*24;
	$repeatt = isset($_POST['repeatt']) ? daddslashes(trim($_POST['repeatt'])) : '0';
	$content = isset($_POST['content']) ? daddslashes(trim($_POST['content'])) : '0';
	$spend = isset($_POST['spend']) ? daddslashes(trim($_POST['spend'])) : '0';

	if($subject=='') {
		showmessage('����ⲻ��Ϊ�ա�');
	}

	DB::insert('activity_content',array(
		'id'=>null,
		'uid'=>$_G['uid'],
		'sid'=>$sid,
		'subject'=>$subject,
		'begintime'=>$begintime,
		'endtime'=>$endtime,
		'repeat'=>$repeat,
		'content'=>$content,
		'spend'=>$spend,
		'image'=>$image,
		'verify'=>0,
		'participants'=>''
	));

	showmessage('���������ɹ���','activity.php?mod=post');

}
//�����ύEnd

//�༭������Start
$editorid = 'e';
$_G['setting']['editoroptions'] = str_pad(decbin($_G['setting']['editoroptions']), 2, 0, STR_PAD_LEFT);
$editormode = $_G['setting']['editoroptions']{0};
$allowswitcheditor = $_G['setting']['editoroptions']{1};
$editor = array(
	'editormode' => $editormode,
	'allowswitcheditor' => $allowswitcheditor,
	'allowhtml' => 1,
	'allowhtml' => 1,
	'allowsmilies' => 1,
	'allowbbcode' => 1,
	'allowimgcode' => 1,
	'allowcustombbcode' => 0,
	'allowresize' => 1,
	'textarea' => 'content',
	'simplemode' => !isset($_G['cookie']['editormode_'.$editorid]) ? 1 : $_G['cookie']['editormode_'.$editorid],
);
loadcache('bbcodes_display');
//�༭������End

//��ȡ����Start
$query = DB::query("SELECT * FROM  ".DB::table('activity_sort'));
	while($db= DB::fetch($query)) {
		$sort[]=$db;
	}
//��ȡ����End

include template('activity/post');