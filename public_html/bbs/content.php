<?php
include_once("./_common.php");

$co	= sql_fetch("select * from shop_content where co_id = '$co_id'");
if(!$co["co_id"]){
	alert('자료가 없습니다.', TW_URL);
}

include_once("./_head.php");
include_once($theme_path.'/content.skin.php');
include_once("./_tail.php");
?>