<?php
	/*
		ucenter
	*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class ucenter{
	
		public function get_user_info($group,$centeruid){


				if($group == 22){//机构会员信息
						$per_info = DB::fetch_first("select telephone,field3,field5,resideprovince,residecity,url,praise from ".DB::table("common_member_profile")." where uid = $centeruid");
						$user_info['telephone'] = $per_info['telephone'];//公司联系电话 
						$user_info['type'] = $per_info['field3'];//公司类型
						if(mb_strlen($per_info['field5'],'GB2312')>16){
							$user_info['jieshao']=mb_substr($per_info['field5'],0,16,'GB2312')."..";//个人介绍，截取16个字
						}else{
							$user_info['jieshao']=$per_info['field5'];
						}
						if($per_info['url'] != null){
								$user_info['url'] = 'http://www.uestar.cn/'.$per_info['url'];//个性url
						}else{
								$user_info['url'] = "http://www.uestar.cn/u_".$centeruid; 
						}
						if($per_info['resideprovince'] != null || $per_info['residecity'] != null){
							$user_info['diqu'] = $per_info['resideprovince'].'&nbsp;'.$per_info['residecity'];//地址
						}else{
							$user_info['diqu'] = null;
						}
						$user_info['praise'] = $per_info['praise'];//赞
						//关注数和粉丝数
						$guanzhu = DB::fetch_first("select count(uid) as num_guanzhu from ".DB::table("home_follow")." where uid = $centeruid");
						$fensi = DB::fetch_first("select count(followuid) as num_fensi from ".DB::table("home_follow")." where followuid = $centeruid");
						//说说数目
						$count = DB::fetch_first("select count(`id`) as tot from `".DB::table('home_shuoshuo')."` where uid = '{$centeruid}' && fid = 0");
						$user_info['shuoshuo'] = $count['tot'];						
						
						$user_info['num_guanzhu'] = $guanzhu['num_guanzhu'];//关注数
						$user_info['num_fensi'] = $fensi['num_fensi'];//粉丝数
						$user_info['num_shuoshuo'] = $shuoshuo['num_shuoshuo'];//说说数
						$renzheng = DB::fetch_first("select verify1 from ".DB::table("common_member_verify")." where uid = $centeruid");
						$user_info['renzheng'] = $renzheng['verify1'];//认证状态  1通过认证 0待认证 -1认证未通过 null也显示未通过认证

						
						$pid = substr($centeruid,-2);
						$bannerid = ceil(($pid+1)/4);
						$user_info['banner'] = "banner/$bannerid.jpg";
				}else{//个人会员信息
						$per_info = DB::fetch_first("select gender,constellation,resideprovince,residecity,bio,url,praise,spaceinfo from ".DB::table("common_member_profile")." where uid = $centeruid");
								//common_member表的基础信息
						$user_info['xingbie'] = intval($per_info['gender']);//性别 1男2女
						$user_info['xingzuo'] = $per_info['constellation'];//星座
						if($per_info['resideprovince'] != null || $per_info['residecity'] != null){
							$user_info['diqu'] = $per_info['resideprovince'].$per_info['residecity'];//地址
						}else{
							$user_info['diqu'] = null;
						}
						$user_info['spaceinfo'] = $per_info['spaceinfo'];//介绍

						$per_info['bio'] =  strip_tags($per_info['bio']);
						if(mb_strlen($per_info['bio'],'GB2312')>16){
							$user_info['jieshao']=mb_substr($per_info['bio'],0,16,'GB2312')."..";//个人介绍，截取32个字
						}else{
							$user_info['jieshao']=$per_info['bio'];
						}
						if($per_info['url'] != null){
								$user_info['url'] = 'http://www.uestar.cn/'.$per_info['url'];//个性url
						}else{
								$user_info['url'] = "http://www.uestar.cn/u_".$centeruid; 
						}
						$user_info['praise'] = $per_info['praise'];//赞
								//关注数和粉丝数
						$guanzhu = DB::fetch_first("select count(uid) as num_guanzhu from ".DB::table("home_follow")." where uid = $centeruid");
						$fensi = DB::fetch_first("select count(followuid) as num_fensi from ".DB::table("home_follow")." where followuid = $centeruid");
						//说说数目
						$count = DB::fetch_first("select count(`id`) as tot from `".DB::table('home_shuoshuo')."` where uid = '{$centeruid}' && fid = 0");
						$user_info['shuoshuo'] = $count['tot'];						
						
						$user_info['num_guanzhu'] = $guanzhu['num_guanzhu'];//关注数
						$user_info['num_fensi'] = $fensi['num_fensi'];//粉丝数
						$user_info['num_shuoshuo'] = $shuoshuo['num_shuoshuo'];//说说数

								//取出职业
						 $typenums=DB::fetch_first("SELECT count(*) as num FROM ".DB::table('user_actor_type')." WHERE uid=$centeruid");
						 if($typenums['num'] > 0){
								$query=DB::query("select name FROM ".DB::table('user_actor_type')." as a left join ".DB::table('user_type')." as b on a.typeid=b.id where a.uid=$centeruid");
								while($typenames= DB::fetch($query)) {
											$user_info['typename'][] = $typenames['name'];//职位数组
								}
						 }
								//认证状态
						$renzheng = DB::fetch_first("select verify6 from ".DB::table("common_member_verify")." where uid = $centeruid");
						$user_info['renzheng'] = $renzheng['verify6'];//认证状态  1通过认证 0待认证 -1认证未通过 null也显示未通过认证

						$pid = substr($centeruid,-2);
						$bannerid = ceil(($pid+1)/4);
						$user_info['banner'] = "banner/$bannerid.jpg";
				}
				return $user_info;

		}
		//取粉丝数据
		public function get_user_funs($centeruid,$limit = null,$Guid){
					$fansuid = DB::query("SELECT uid FROM ".DB::table('home_follow')." where followuid = $centeruid  order by dateline desc $limit");
					while($faid = DB::fetch($fansuid)){
						foreach($faid as $fanid){
						$ismyt=DB::fetch_first("SELECT fusername  FROM ".DB::table(home_follow)." where uid=$Guid and followuid=$fanid");
						$fansinfo1 = DB::query("SELECT m.username,m.groupid,p.birthprovince,p.field5,p.bio,p.field5,p.birthcity,p.url,p.uid,p.gender FROM ".DB::table('common_member'). " as m left join ".DB::table('common_member_profile')." as p on m.uid=p.uid where m.uid=$fanid");
							while($fansinfo=DB::fetch($fansinfo1)){
								$fansinfo['url']=!empty($fansinfo['url'])?$fansinfo['url']:"u_".$fansinfo['uid'];
								$attnum['numf'] = DB::fetch_first("SELECT count(uid) as numt FROM ".DB::table('home_follow')." where followuid={$fansinfo['uid']}");
								foreach($attnum['numf'] as $numf){
								
								}
								$attnum['numt'] = DB::fetch_first("SELECT count(followuid) as numf FROM ".DB::table('home_follow')." where uid={$fansinfo['uid']}");
								foreach($attnum['numt'] as $numt){
								
								}
								$fansinfo['ismyt']=$ismyt;
								$fansinfo['numt']=$numt;
								$fansinfo['numf']=$numf;
								if(strlen($attinfo['field5'])>120){
									$attinfo['field5']=cutstr($attinfo['field5'],120)."...";
								}else{
									$attinfo['field5']=$attinfo['field5'];
								}
								if(strlen($attinfo['bio'])>120){
									$attinfo['bio']=cutstr($attinfo['bio'],120)."...";
								}else{
									$attinfo['bio']=$attinfo['bio'];
								}
								$fans[]=$fansinfo;
							}
						}
					}
					return $fans;
		}
		//取关注数据
		public function get_user_attention($centeruid,$limit = null,$Guid){
					$followuid = DB::query("SELECT followuid FROM ".DB::table('home_follow')." where uid = $centeruid order by dateline desc $limit");
					while($fid = DB::fetch($followuid)){
						foreach($fid as $fluid){
						$ismyt=DB::fetch_first("SELECT fusername  FROM ".DB::table("home_follow")." where uid = $Guid and followuid = $fluid");
						$finfo = DB::query("SELECT m.username,m.groupid,p.birthprovince,p.field5,p.bio,p.field5,p.birthcity,p.url,p.uid,p.gender FROM ".DB::table('common_member'). " as m left join ".DB::table('common_member_profile')." as p on m.uid=p.uid where m.uid=$fluid");
							while($attinfo=DB::fetch($finfo)){
								$attinfo['url']=!empty($attinfo['url'])?$attinfo['url']:"u_".$attinfo['uid'];
								$attnum['numt'] = DB::fetch_first("SELECT count(followuid) as numt FROM ".DB::table('home_follow')." where uid = {$attinfo['uid']}");
									foreach($attnum['numt'] as $numt){
									}
								$attinfo['numt']=$numt;
								$attnum['numf'] = DB::fetch_first("SELECT count(uid) as numf FROM ".DB::table('home_follow')." where followuid={$attinfo['uid']}");
									foreach($attnum['numf'] as $numf){	
									}
								$attinfo['ismyt']=$ismyt;
								$attinfo['numf']=$numf;
								if(strlen($attinfo['field5'])>120){
									$attinfo['field5']=cutstr($attinfo['field5'],120)."...";
								}else{
									$attinfo['field5']=$attinfo['field5'];
								}
								if(strlen($attinfo['bio'])>120){
									$attinfo['bio']=cutstr($attinfo['bio'],120)."...";
								}else{
									$attinfo['bio']=$attinfo['bio'];
								}
								$arr[]=$attinfo;
							}
						}
					}
					return $arr;
		}
		//取访客记录
		public function get_user_visitors($centeruid,$limit = null){
				$query = DB::query("select vuid from ".DB::table("home_visitor")." where uid = $centeruid group by vuid order by dateline desc $limit");
				while($vuid = DB::fetch($query)){
						foreach($vuid as $duid){
							$uname = DB::fetch_first("select username from ".DB::table("common_member")." where uid = $duid");
							$time = DB::fetch_first("select dateline from ".DB::table("home_visitor")." where uid = $duid");
							$uname['uid'] = $duid;
							$uname['dateline'] = date('m-d',$time['dateline']);
							$username[] = $uname;
						}
				}
				return $username;
		}
		//自己看时关注我的机构和关注我的个人
		public function get_my_funs($centeruid,$limit =null,$flag = null){
				if($flag == 1){
					//SELECT g.`uid` FROM `pre_home_follow` g,`pre_common_member` u WHERE g.`followuid` = '5113' && g.`uid` = u.`uid` && u.`groupid` = '22'
						$fansuid = DB::query("SELECT g.uid FROM ".DB::table('home_follow')." g,".DB::table('common_member')." u where g.followuid = $centeruid and g.uid = u.uid and u.groupid = 22 order by g.dateline desc $limit");//机构粉丝
						while($faid = DB::fetch($fansuid)){
								foreach($faid as $fanid){
										$info = DB::fetch_first("select username from ".DB::table("common_member")." where uid = $fanid");
										$info['uid'] = $fanid;
										$user_fans[] = $info;
								}
							}
				}else{
						$fansuid = DB::query("SELECT g.uid FROM ".DB::table('home_follow')." g,".DB::table("common_member")." u where g.followuid = $centeruid and g.uid = u.uid and u.groupid = 21 order by g.dateline desc $limit");//个人粉丝
						while($faid = DB::fetch($fansuid)){
								foreach($faid as $fanid){								
										$info = DB::fetch_first("select username from ".DB::table("common_member")." where uid = $fanid");
										$info['uid'] = $fanid;
										$user_fans[] = $info;
								}
							}
				
				}
				return $user_fans;
		}
		//获取最新图片
		public function get_user_pics($centeruid,$limit =null){
				$query = DB::query("select albumname,albumid,pic94 from ".DB::table("home_album")." where uid = $centeruid order by dateline desc $limit");
				while($picinfo = DB::fetch($query)){
							$pic['albumname'] = $picinfo['albumname'];
							$pic['albumid'] = $picinfo['albumid'];
							$pic['pic94'] = $picinfo["pic94"];
							$pic_info[] = $pic;
				}
				return $pic_info;
		}
		//获取商业邀请信息，传入被查看人的uid 和limit
		public function get_user_invite($centeruid,$limit = null){
				$query = DB::query("select id,invite_uid,cooperation_uid,post_time,cooperation_content,read_flag,agree_flag from ".DB::table("user_cooperation")." where invite_uid = $centeruid or cooperation_uid = $centeruid order by post_time desc $limit");
				while($inviteinfo = DB::fetch($query)){
						$invite_info['invite_uid'] = $inviteinfo['invite_uid'];
						$invite_info['cooperation_uid'] = $inviteinfo['cooperation_uid'];
						

						$invit = DB::fetch_first("select username from ".DB::table("common_member")." where uid = {$invite_info['invite_uid']}");
						$cooperation = DB::fetch_first("select username from ".DB::table("common_member")." where uid = {$invite_info['cooperation_uid']}");
						if(strlen($invit['username']) < 16){
								$invite_info['invite_uname'] = $invit['username'];
						}else{
								$invite_info['invite_uname'] = cutstr($invit['username'],16)."...";
						}
						if(strlen($cooperation['username']) < 16){
								$invite_info['coo_uname'] = $cooperation['username'];
						}else{
								$invite_info['coo_uname'] = cutstr($cooperation['username'],16).'...';
						}
						$invite_info['time'] = date("Y-m-d",$inviteinfo['post_time']);
						//$invite_info['ltime'] = date("Y-m-d H:i:s",$inviteinfo['post_time']);
						$invite_time = getdate($inviteinfo['post_time']);
						$now_time = getdate(time());
						if($invite_time['yday'] == $now_time['yday']){
								$invite_info['day'] == 1;
								$invite_info['ltime'] = date("H:i:s",$inviteinfo['post_time']);
						}else{
								$invite_info['day'] == 0;
								$invite_info['ltime'] = date("Y-m-d H:i:s",$inviteinfo['post_time']);
						}
						$invite_info['content'] = $inviteinfo['cooperation_content'];
						$invite_info['read_flag'] = $inviteinfo['read_flag'];
						$invite_info['agree_flag'] = $inviteinfo['agree_flag'];
						$invite_info['id'] = $inviteinfo['id'];
						$invite[] = $invite_info;
						
				}
				return $invite;
		}
		
		public function getRightInfo($centeruid,$me){
			global $_G;
			
			$array['visitor'] = $this->get_user_visitors($centeruid,"limit 0,5");//访客5条			
			
			if($me == '1'){
				$array['my_fans_org'] = $this->get_my_funs($centeruid,"limit 0,5",1);//自己看关注我的机构
				
				$array['my_fans_pre'] = $this->get_my_funs($centeruid,"limit 0,5");//自己看关注我的个人
			}else{
			
				$array['funs'] = $this->get_user_funs($centeruid,"limit 0,5",$_G['uid']);//别人看粉丝5条
				
				$array['attention'] = $this->get_user_attention($centeruid,"limit 0,5",$_G['uid']);//别人看关注5条
			}
			
			$array['pic_info'] = $this->get_user_pics($centeruid,"limit 0,6");//6张最新照片
			
			$array['invite_info'] = $this->get_user_invite($centeruid,"limit 0,3");//3条最新商业邀请记录

			$array['myvideo'] = $this->getThevideo($centeruid,"limit 0,2");
			
			return $array;
		}
/*
		public function getTopbj(){
			$query = DB::query("select typeid,url from ".DB::table('bj_photo'));
			while($date = DB::fetch($query)){
					
			}
		}
	*/	


		public function  getThevideo($centeruid,$limit){
				$query=DB::query("select id,flash_address,title,sharepic,time from ".DB::table("user_addvideo")." where uid = $centeruid order by id desc $limit");
				$j = 0;
				while($data = DB::fetch($query)){
					$video['flash_address'] = rawurlencode($data['flash_address']);
					if(strlen($data['title'] < 36)){
							$video['title'] = $data['title'];
					}else{
							$video['title'] = cutstr($data['title'],36).'...';
					}
					$video['count'] = $j++;
					$video['sharepic'] = $data['sharepic'];
					$video['time'] = date('Y-m-d',$data['time']);
					$video['id'] = $data['id'];
					$myvideo[] = $video;
				}
				return $myvideo;
		}
}  

$center = new ucenter;
?>