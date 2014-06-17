<?php
	require libfile('class/page');
	$perPage = 15;
	$page=$_GET["page"];
	$orderby = " order by id desc ";
	$where = " WHERE 1. and `xia` = '0' ";
	$getorderby = $_GET["orderby"];
	if($_GET["orderby"] == "new"){
		$orderby = " order by id desc ";
	}
	if($_GET["orderby"] == "hot"){
		$orderby = " order by countnum desc ";
	}
	if($_GET["charge"]){
		$charge = $_GET["charge"];
		$where = $where." and charge = $charge ";
	}
	
	$start_limit = ($page - 1) * $perPage;
	$start_limit=$start_limit>=0?$start_limit:"0";
	$limit=" limit ".$start_limit.",".$perPage ;
	$sql = "select * from pre_common_music ".$where.$orderby." {$limit}";
	$sql1 = "select * from pre_common_music".$where;
	$sortdata['count'] = count((DB::fetch_all($sql1)));
	$allpage=ceil($sortdata['count']/$perPage);
	if(!empty($_GET['page'])){
		$prepage=$_GET['page'];
	}else{
		$prepage=1;
	}
	$p=new page($sortdata['count'] ,$perPage);
	$multipage=$p->show(8);

	$datalist = DB::fetch_all($sql);

	$rs = DB::query($sql);
	$i = 0;
	while($row = DB::fetch($rs))
	{
		$get_name="select `username` from `pre_common_member` where `uid` = '".$row['uid']."';";
		$get_name = DB::query($get_name);
		$member_name = DB::fetch($get_name);
		$datalist[$i]['username'] = $member_name['username'];
		$i++;
	}
	include template("creation/musicindex");
?>