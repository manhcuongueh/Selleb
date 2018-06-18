<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url("./login.php?url=".urlencode("$tb[bbs_root]/gift.php"));
}

if(!$config['sp_gift']) {
    alert("쿠폰사용이 중지 되었습니다.");
}

$tb['title'] = "쿠폰인증내역";
include_once("./_head.php");

$sql_common = " from shop_gift ";
$sql_search = " where mb_id = '$member[id]' ";

if($sfl && $stx) {
	$sql_search .= " and $sfl like '$stx%' ";
}

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search order by no desc limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path.'/gift_list.skin.php');
include_once("./_tail.php");
?>