<?php
include_once("./_common.php");

$boardconfig = sql_fetch("select * from shop_board_conf where index_no='$boardid'");

//회원 권한추출
$grade = $member['grade'];

//비회원일경우 "99" 번호 임시부여
if(empty($grade)) { $grade = 99; }

//목록보기 권한이 비회원이 아닐경우 체킹
if($boardconfig['list_priv'] < 99)
{
	if($grade > $boardconfig['list_priv']) {
		alert('권한이 없습니다.');
	}
}

if($boardconfig['topfile']) {	
	if($key=='writer') {	
		if(!$keyword){	
			$keyword = $memindex;	
		}	
	} else {
		include_once($boardconfig['topfile']);	
	}
}

if($boardconfig['content_head']) {	
	echo $boardconfig['content_head'];
}

if($boardconfig['width']<=100) {	
	$boardconfig['width']  = $boardconfig['width'] ."%";	
}

$bo_img_url = TW_BBS_URL.'/skin/'.$boardconfig['skin'];

include "skin/".$boardconfig['skin']."/list.php";

if($boardconfig['content_tail']) {	
	echo $boardconfig['content_tail'];
}

if($boardconfig['downfile']) {	
	include_once($boardconfig['downfile']);
}
?>