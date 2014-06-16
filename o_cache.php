<?php
function ob_cache($path){
	ob_start();

	include "$path.php";
	
	$content=ob_get_contents();
	ob_end_clean();
	
	
			file_put_contents("$path.html",$content);
	
	
			return header("Location:$path.html");
			

}
?>