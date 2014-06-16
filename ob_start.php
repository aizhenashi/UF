<?php
include './o_cache.php';

$body=ob_cache('index');

echo $body;

?>