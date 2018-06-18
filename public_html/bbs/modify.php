<?php
include_once("./_common.php");

$boardconfig = sql_fetch(" select * from shop_board_conf where index_no='$boardid'" );
$boardinfo = sql_fetch(" select * from shop_board_{$boardid} where index_no='$index_no'" );

//회원 권한추출
$grade = $member['grade'];

//비회원일경우 "99" 번호 임시부여
if(empty($grade)) { $grade = 99; }

if($member['id']) {
	if(!is_admin()) {
		if($boardinfo['writer']!=$member['index_no']) {
			alert('권한이 없습니다.');
		}
	}
}

if($boardconfig['topfile']) {	
	include $boardconfig['topfile'];	
}

if($boardconfig['content_head']) {	
	echo $boardconfig['content_head'];
}

if($boardconfig['width']<=100) {	
	$boardconfig['width']  = $boardconfig['width'] ."%";	
}

$bo_img_url = TW_BBS_URL.'/skin/'.$boardconfig['skin'];

include "skin/".$boardconfig['skin']."/modify.php";

if($boardconfig['content_tail']) {	
	echo $boardconfig['content_tail'];
}

if($boardconfig['downfile']) {	
	include $boardconfig['downfile'];
}
?>
