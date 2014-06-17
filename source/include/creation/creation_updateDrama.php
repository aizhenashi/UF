<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!$_G['uid']){
	header("location:login.html");
}
//得到所有剧本类型的信息
$pname=DB::fetch_all("select wid,wname FROM ".DB::table('creation_workstype')." where tid='1'");
//修改作品
if($_GET["mod"]==2)
{
	if($_GET["c"]==1)
	{
		$articleId = $_GET["a"];
		$sql = "select * from pre_common_article where articleId=".$articleId;
		$row = DB::query($sql);
		$row = DB::fetch($row);
		$str = $row["dramaClass"];
		$str = str_replace(",","-",$str);
		$scriptchecked= "<script language='javascript'>".
		"var str='".$str."';".
		"var sucai = '".$str."'.split('-');".
		"var i; 
			for(i=0;i<sucai.length;i++)
			{
				var j;
				for(j=1;j<=21;j++)
				{
					var c = document.getElementById('checkbox'+j).value;
					if(sucai[i] == c)
					{
						var agree = document.getElementById('checkbox'+j).checked = true;
					}
				}
			}".
		"</script>";
	}
}
include template('creation/updateDrama');
?>