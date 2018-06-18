<?php
include_once("./_common.php");

$boardconfig = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
$boardinfo = sql_fetch("select * from shop_board_{$boardid} where index_no='$index_no'");

if($boardconfig['topfile']) {	
	@include_once($boardconfig['topfile']);
}

if($boardconfig['width'] <= 100) {	
	$boardconfig['width'] = $boardconfig['width'] ."%";	
}

$bo_img_url = TW_BBS_URL.'/skin/'.$boardconfig['skin'];

include "skin/{$boardconfig['skin']}/secret.php";

if($boardconfig['downfile']) {	
	@include_once($boardconfig['downfile']);
}
?>
