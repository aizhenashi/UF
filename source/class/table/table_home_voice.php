<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_voice extends discuz_table{
	
	public function __construct(){
		$this->_table = 'home_voice';
		//$this->_pk = 'id';
		parent::__construct();
	}

	public function fetch_one($where){
		$sql = "select `id`,`uid`,`bkid`,`title`,`path`,`time`,`privateUrl` from `".DB::table('home_voice')."` where ".$where;
		$data = DB::fetch_first($sql);
		return $data;
		
	}

	public function deleteforwhere($where){
		$sql = "delete from `".DB::table('home_voice')."` where ".$where;
		DB::query($sql);
		return true;
	}
}
?>