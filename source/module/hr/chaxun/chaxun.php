<?php
//李旭光
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 11711");
$info_LXG= DB::fetch($query);
$pic_l= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 11711");
while($num=DB::fetch($pic_l)){
	$picnum_l[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_l);$i++){
	$num_LXG+=0+$picnum_l[$i];
}
$tongji_LXG=intval($info_LXG['friends'])+intval($info_LXG['doings'])+intval($info_LXG['oltime'])+intval($info_LXG['follower'])+intval($info_LXG['following'])+$num_LXG;

//王宏泽
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 100");
$info_WHZ= DB::fetch($query);
$pic_w= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 100");
while($num=DB::fetch($pic_w)){
	$picnum_w[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_w);$i++){
	$num_WHZ+=0+$picnum_w[$i];
}
$tongji_WHZ=intval($info_WHZ['friends'])+intval($info_WHZ['doings'])+intval($info_WHZ['oltime'])+intval($info_WHZ['follower'])+intval($info_WHZ['following'])+$num_WHZ;
//文豪
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 77");
$info_WH= DB::fetch($query);
$pic_wh= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 77");
while($num=DB::fetch($pic_wh)){
	$picnum_wh[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_wh);$i++){
	$num_WH+=0+$picnum_wh[$i];
}
$tongji_WH=intval($info_WH['friends'])+intval($info_WH['doings'])+intval($info_WH['oltime'])+intval($info_WH['follower'])+intval($info_WH['following'])+$num_WH;
//陈亮
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 9183");
$info_CL= DB::fetch($query);
$pic_c= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 9183");
while($num=DB::fetch($pic_c)){
	$picnum_c[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_c);$i++){
	$num_CL+=0+$picnum_c[$i];
}
$tongji_CL=intval($info_CL['friends'])+intval($info_CL['doings'])+intval($info_CL['oltime'])+intval($info_CL['follower'])+intval($info_CL['following'])+$num_CL;
//张丹
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 146");
$info_ZD= DB::fetch($query);
$pic_zd= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 146");
while($num=DB::fetch($pic_zd)){
	$picnum_zd[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_zd);$i++){
	$num_ZD+=0+$picnum_zd[$i];
}
$tongji_ZD=intval($info_ZD['friends'])+intval($info_ZD['doings'])+intval($info_ZD['oltime'])+intval($info_ZD['follower'])+intval($info_ZD['following'])+$num_ZD;
//陈芳
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 14969");
$info_CF= DB::fetch($query);
$pic_cf= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 14969");
while($num=DB::fetch($pic_cf)){
	$picnum_cf[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_cf);$i++){
	$num_CF+=0+$picnum_cf[$i];
}
$tongji_CF=intval($info_CF['friends'])+intval($info_CF['doings'])+intval($info_CF['oltime'])+intval($info_CF['follower'])+intval($info_CF['following'])+$num_CF;
//张亚峰
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 12874");
$info_ZYF= DB::fetch($query);
$pic_zyf= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 12874");
while($num=DB::fetch($pic_zyf)){
	$picnum_zyf[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_zyf);$i++){
	$num_ZYF+=0+$picnum_zyf[$i];
}
$tongji_ZYF=intval($info_ZYF['friends'])+intval($info_ZYF['doings'])+intval($info_ZYF['oltime'])+intval($info_ZYF['follower'])+intval($info_ZYF['following'])+$num_ZYF;
//徐灿
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 11568");
$info_XC= DB::fetch($query);
$pic_xc= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 11568");
while($num=DB::fetch($pic_xc)){
	$picnum_xc[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_xc);$i++){
	$num_XC+=0+$picnum_xc[$i];
}
$tongji_XC=intval($info_XC['friends'])+intval($info_XC['doings'])+intval($info_XC['oltime'])+intval($info_XC['follower'])+intval($info_XC['following'])+$num_XC;
//张岩
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 79");
$info_ZY= DB::fetch($query);
$pic_zy= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 79");
while($num=DB::fetch($pic_zy)){
	$picnum_zy[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_zy);$i++){
	$num_ZY+=0+$picnum_zy[$i];
}
$tongji_ZY=intval($info_ZY['friends'])+intval($info_ZY['doings'])+intval($info_ZY['oltime'])+intval($info_ZY['follower'])+intval($info_ZY['following'])+$num_ZY;
//张兵
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 11670");
$info_ZB= DB::fetch($query);
$pic_zb= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 11670");
while($num=DB::fetch($pic_zb)){
	$picnum_zb[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_zb);$i++){
	$num_ZB+=0+$picnum_zb[$i];
}
$tongji_ZB=intval($info_ZB['friends'])+intval($info_ZB['doings'])+intval($info_ZB['oltime'])+intval($info_ZB['follower'])+intval($info_ZB['following'])+$num_ZB;
//崔宇畅
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 127");
$info_CYC= DB::fetch($query);
$pic_CYC= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 127");
while($num=DB::fetch($pic_CYC)){
	$picnum_CYC[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_CYC);$i++){
	$num_CYC+=0+$picnum_CYC[$i];
}
$tongji_CYC=intval($info_CYC['friends'])+intval($info_CYC['doings'])+intval($info_CYC['oltime'])+intval($info_CYC['follower'])+intval($info_CYC['following'])+$num_CYC;
//张钧雅
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 129");
$info_ZJY= DB::fetch($query);
$pic_ZJY= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 129");
while($num=DB::fetch($pic_ZJY)){
	$picnum_ZJY[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_ZJY);$i++){
	$num_ZJY+=0+$picnum_ZJY[$i];
}
$tongji_ZJY=intval($info_ZJY['friends'])+intval($info_ZJY['doings'])+intval($info_ZJY['oltime'])+intval($info_ZJY['follower'])+intval($info_ZJY['following'])+$num_ZJY;
//易恩宇
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 131");
$info_YNY= DB::fetch($query);
$pic_YNY= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 131");
while($num=DB::fetch($pic_YNY)){
	$picnum_YNY[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_YNY);$i++){
	$num_YNY+=0+$picnum_YNY[$i];
}
$tongji_YNY=intval($info_YNY['friends'])+intval($info_YNY['doings'])+intval($info_YNY['oltime'])+intval($info_YNY['follower'])+intval($info_YNY['following'])+$num_YNY;
//苏玮明
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 152");
$info_SWM= DB::fetch($query);
$pic_SWM= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 152");
while($num=DB::fetch($pic_SWM)){
	$picnum_SWM[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_SWM);$i++){
	$num_SWM+=0+$picnum_SWM[$i];
}
$tongji_SWM=intval($info_SWM['friends'])+intval($info_SWM['doings'])+intval($info_SWM['oltime'])+intval($info_SWM['follower'])+intval($info_SWM['following'])+$num_SWM;
//杨颖
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 130");
$info_YY= DB::fetch($query);
//var_dump($info_YY);
$pic_YY= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 130");
while($num=DB::fetch($pic_YY)){
	$picnum_YY[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_YY);$i++){
	$num_YY+=0+$picnum_YY[$i];
}
$tongji_YY=intval($info_YY['friends'])+intval($info_YY['doings'])+intval($info_YY['oltime'])+intval($info_YY['follower'])+intval($info_YY['following'])+$num_YY;
//荆巍
$query = DB::query("SELECT friends,doings,oltime,follower,following FROM  ".DB::table('common_member_count')." where uid = 9071");
$info_JY= DB::fetch($query);
$pic_JY= DB::query("SELECT picnum FROM  ".DB::table('home_album')." where uid = 9071");
while($num=DB::fetch($pic_JY)){
	$picnum_JY[]=intval($num['picnum']);
}
for($i=0;$i<count($picnum_JY);$i++){
	$num_JY+=0+$picnum_JY[$i];
}
$tongji_JY=intval($info_JY['friends'])+intval($info_JY['doings'])+intval($info_JY['oltime'])+intval($info_JY['follower'])+intval($info_JY['following'])+$num_JY;
include template('diy:chaxun/index');
?>