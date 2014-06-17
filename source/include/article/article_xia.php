<?php
$articleId = $_GET["articleId"];
$key = $_GET["searchkey"];
$dramasql="update pre_common_article set xia=1 where articleId=".$articleId;
DB::query($dramasql);
echo "<script language='javascript'>";
echo "window.location='article.php?do=search&searchkey=".$key."';";
echo "</script>";