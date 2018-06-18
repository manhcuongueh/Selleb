<?php
include_once("./_common.php");

$boardconfig = sql_fetch("select * from shop_board_conf where index_no='$boardid'");

$grade = $member['grade'];

//비회원일경우 "99" 번호 임시부여
if(empty($grade)) { $grade = 99; }

//글쓰기 권한이 비회원이 아닐경우 체킹
if($boardconfig['write_priv'] < 99) {
	if($grade > $boardconfig['write_priv']) {
		alert("권한이 없습니다.","list.php?boardid=$boardid");
	}
}

if($boardconfig['topfile']) {	
	@include_once($boardconfig['topfile']);
}

if($boardconfig['content_head']) {	
	echo $boardconfig['content_head'];
}

if($boardconfig['width']<=100) {	
	$boardconfig['width']  = $boardconfig['width'] ."%";	
}

$bo_img_url = TW_BBS_URL.'/skin/'.$boardconfig['skin'];

include "skin/{$boardconfig['skin']}/write.php";

if($boardconfig['content_tail']) {	
	echo $boardconfig['content_tail'];
}

if($boardconfig['downfile']) {	
	@include_once($boardconfig['downfile']);
}
?>