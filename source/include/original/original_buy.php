<?php
if($_G['uid'] == false){
	header("Location:/login.html");				
}

include template('diy:original/buy');
?>