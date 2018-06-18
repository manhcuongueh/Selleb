<?php
include_once("./_common.php");

$tb['title'] = get_text($board['boardname']);
include_once("./_head.php");

//비회원일경우 "99" 번호 임시부여
if($is_member)
	$mb_grade = $member['grade'];
else
	$mb_grade = 99;

if($w == "u" || $w == "r") {
	$sql = "select * from shop_board_{$boardid} where index_no='$index_no'";
	$row = sql_fetch($sql);
    if(!$row['index_no'])
        alert("자료가 없습니다.");

	if($w == "u") {
		if($is_member) {
			if(!is_admin()) {
				if($row['writer'] != $member['index_no']) {
					alert('글수정 권한이 없습니다.');
				}
			}
		}
	}

	if($w == "r") {
		if($board['reply_priv'] < 99) {
			if($mb_grade > $board['reply_priv'])	{
				alert("댓글작성 권한이 없습니다.");
			}
		}

		$row['writer_s'] = $member['name'];
	}
} else {
	if($board['write_priv'] < 99) {
		if($mb_grade > $board['write_priv'])
			alert("글쓰기 권한이 없습니다.");
	}

	$row['writer_s'] = $member['name'];
}

$qstr1 = "boardid=$boardid&page=$page";

include_once($theme_path.'/board_write.skin.php');
include_once("./_tail.php");
?>