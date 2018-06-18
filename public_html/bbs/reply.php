<?php
include "./_common.php";

//게시판 정보추출
$boardconfig = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
$boardinfo = sql_fetch("select * from shop_board_{$boardid} where index_no='$index_no'");

//회원 권한추출
$grade = $member['grade'];

//비회원일경우 "99" 번호 임시부여
if(empty($grade)) { $grade = 99; }

//답글보기 권한이 비회원이 아닐경우 체킹
if($boardconfig['reply_priv'] < 99) {
	if($grade > $boardconfig['reply_priv'])	{
		alert('권한이 없습니다.');
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

include "skin/".$boardconfig['skin']."/reply.php";

if($boardconfig['content_tail']) {	
	echo $boardconfig['content_tail'];
}

if($boardconfig['downfile']) {	
	include $boardconfig['downfile'];	
}
?>
