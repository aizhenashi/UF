<?php 
	class MemData extends core{
		//��ѯ�������˲�����(ͷ���ļ���������)
		public static function user_type(){
		$data = core::memory()->get('data');
		if(!$data){
		$user_a = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
		while($user_type= DB::fetch($user_a)) 
		{
		if($user_type['rank']==3)
		{
			if($user_type['topid']==5)
			{
				$user_per[$user_type['id']]=$user_type['name'];
			}
			if($user_type['topid']==4){
				$user_mus[$user_type['id']]=$user_type['name'];
			}
			if($user_type['topid']==3){
				$user_mov[$user_type['id']]=$user_type['name'];
			}
		}	
		}
		$arr['user_per'] = $user_per;
		$arr['user_mus'] = $user_mus;
		$arr['user_mov'] = $user_mov;
		$data = $arr;
		core::memory()->set('data',$arr);
	}
		return $data;
		}
		//��ѯ�������˲����ͣ��������˲�����ҳ�ã�
		public static function job_tal(){
		$res = core::memory()->get('row');
		if(!$res){
		$user_a = DB::query("SELECT * FROM ".DB::table('user_type ')."order by displayorder asc");
		while($user_type= DB::fetch($user_a)) 
		{
		if($user_type['rank']==3)
		{
		if($user_type['topid']==5||$user_type['topid']==4||$user_type['topid']==3)  //�ж��Ƿ��ݳ�����
		{
		$user_types['choices'][$user_type['id']]=$user_type['name'];
		$user_types['rank'][$user_type['id']]=$user_type['topid'];
		}
		}
		}
		$res = $user_types;
		core::memory()->set('row',$user_types);
		}
		return $res;
		}
		//���ֹ��������ǻ��Ƭ����
		public static function huanlegu(){
			$photo=core::memory()->get('photo');
			if(!$photo){
			$page=$page = empty($_GET['page'])?1:intval($_GET['page']);
			$perpage=15;//ÿҳ��ʾ������
			$start_limit = ($page - 1) * $perpage;
			$start_limit=$start_limit>=0?$start_limit:"0";
			$limit=" limit ".$start_limit.",".$perpage ;
			$sortdata['count'] = DB::result_first("SELECT COUNT(*) FROM ".DB::table('home_pic')." where uid=11568 and albumid=280");
			$p=new page($sortdata['count'] ,$perpage);
			$multipage=$p->show(8);
			$allpage=ceil($sortdata['count']/$perpage);
							if(!empty($_GET['page'])){
								$prepage=$_GET['page'];
							}else{
								$prepage=1;
							}
			$photo=DB::fetch_all("select * from ".DB::table('home_pic')." where albumid=280 and uid=11568 $limit");
			core::memory()->set('photo',$photo);
			}
			return $photo;
		}
		
}



