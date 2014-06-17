<?php
$ajaxcontent = $_POST["ajaxgetcontent"];
if($ajaxcontent=='false')
{
	$articleId = $_POST["articleId"];
	if($_G["uid"])
	{
		$arr = array('18009','17199','17694');
		if(in_array($_G["uid"],$arr))
		{
			$sql = "update pre_common_article set introduce=1 where articleId=".$articleId;
			DB::query($sql);
			$sql1="select * from pre_common_article where articleId=".$articleId;
			$rs = DB::query($sql1);
			$article = DB::fetch($rs);
			include template("article/introduce");
		}
	}else{
	showmessage('ÇëµÇÂ¼ºóÍÆ¼ö','login.html');
	}
}
?>