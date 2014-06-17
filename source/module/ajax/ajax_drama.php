<?php

/**
 *   像册 ajax提交模块 页 做action 分发 
 *   
 *   1.容错
 *   未登录 直接 exit
 *   
 */

//这个是页面跳转
// dheader("Location:home.php?mod=space&uid=$uid&do=profile");


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$dos = 
array(
	'upload','update','lyric','updateLy'
);

$do = in_array(getgpc('do'), $dos) ? getgpc('do') : NULL ;

if($do === NULL){
	showmessage('action error');
}
function setCharset($str){
			$data=iconv('UTF-8','GB2312',$str);
			return $data;
}
class dramaMoudle{

	public function upload(){
		global $_G;
		$type=setCharset($_POST['type']);
		$content=setCharset($_POST['content']);
		$price=setCharset($_POST['price']);
		$title=setCharset($_POST['title']);
		$time=date("Y-m-d");
		$writer=$_G[member][username];
		$creationtime=time();
		DB::insert('common_article',array('writer'=>$writer,'title'=>$title,'content'=>$content,'price'=>$price,'uid'=>$_G['uid'],'time'=>$time,'dramaClass'=>$type,'creationTime'=>$creationtime));

	}
	//更新剧本的数据
	public function update(){
		global $_G;
		$articleId=$_POST["articleId"];
		$type=setCharset($_POST['type']);
		$content=setCharset($_POST['content']);
		$price=setCharset($_POST['price']);
		$title=setCharset($_POST['title']);
		$sql="update pre_common_article set dramaClass='".$type."',content='".$content."',price='".$price."',title='".$title."'where articleId=".$articleId;
		$row = DB::query($sql);
	}
	public function lyric(){
		global $_G;
		$title=setCharset($_POST['title']);
		$content=setCharset($_POST['content']);
		$price=setCharset($_POST['price']);
		$username=$_G['member']['username'];
		$uid=$_G['uid'];
		$time=date("Y-m-d");
		DB::insert('common_music_lyric',array('uid'=>$uid,'title'=>$title,'content'=>$content,'price'=>$price,'username'=>$username,'time'=>$time));
	} 
	public function updateLy(){
		global $_G;
		$id = $_POST["lyricId"];
		$title=setCharset($_POST['title']);
		$content =setCharset($_POST['content']);
		$price = setCharset($_POST["price"]);
		$sql = "update pre_common_music_lyric set title='".$title."',content='".$content."', price='".$price."' where id=".$id;		
		DB::query($sql);
	}
}
$ajaxDrama = new dramaMoudle();
$ajaxDrama->$do();
?>