<?php
include_once("./_common.php");

$tb['title'] = get_text($board['boardname']);
include_once("./_head.php");

//비회원일경우 "99" 번호 임시부여
if($is_member)
	$grade = $member['grade'];
else
	$grade = 99;

//목록보기 권한이 비회원이 아닐경우 체킹
if($board['list_priv'] < 99) {
	if($grade > $board['list_priv'])
		alert('권한이 없습니다.');
}

$sql_search2 = "";
if($default['de_board_wr_use']) {
	$sql_search2 = " and pt_id = '$pt_id' ";
}

$sql_common = " from shop_board_{$boardid} ";
$sql_search = " where btype = '2' {$sql_search2} ";
$sql_order  = " order by fid desc, thread asc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 15;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$reply_limit = 6;

$bo_img_url = TW_BBS_URL.'/skin/'.$board['skin'];

if($board['skin'] == 'basic') {
	include_once($theme_path.'/board_list.skin.php');
} else if($board['skin'] == 'gallery') {
	include_once($theme_path.'/board_gallery.skin.php');
}

include_once("./_tail.php");
?>