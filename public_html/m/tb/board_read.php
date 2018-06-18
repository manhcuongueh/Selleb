<?php
include_once("./_common.php");

$tb['title'] = get_text($board['boardname']);
include_once("./_head.php");

//비회원일경우 "99" 번호 임시부여
if($is_member)
	$mb_grade = $member['grade'];
else
	$mb_grade = 99;

//목록보기 권한이 비회원이 아닐경우 체킹
if($board['read_priv'] < 99) {
	if($mb_grade > $board['read_priv'])
		alert('권한이 없습니다.');
}

$sql = "select * from shop_board_{$boardid} where index_no='$index_no'";
$row = sql_fetch($sql);

$bo_subject = get_text($row['subject']);
$bo_wdate = date("Y-m-d",$row['wdate']);

if($row['issecret'] == 'Y') {
	if($row['writer'] != 0) {
		if(!$is_member) {
			goto_url("./login.php?url=$urlencode");
		}
	}

	if($is_member) {
		if(!is_admin()) {
			if($member['index_no'] != $row['writer']) {
				alert("비밀글은 열람하실 수 없습니다.");
			}
		}

	} else {
		$mb_no = 0;
		if($_GET['inpasswd'] != $row['passwd']) {
			goto_url("./board_secret.php?index_no=$index_no&boardid=$boardid&page=$page");
		}
	}
}

$sql = "update shop_board_{$boardid} set readcount=(readcount+1) where index_no='$index_no'";
sql_query($sql);

$bo_img_url = TW_BBS_URL.'/skin/'.$board['skin'];

$qstr1 = "boardid=$boardid&page=$page";
$qstr2 = "index_no=$index_no&boardid=$boardid&page=$page";

include_once($theme_path.'/board_read.skin.php');
include_once("./_tail.php");
?>