<?php
include_once("./_common.php");

if(!$is_member && !get_session("ck_guest_od")) {	
	goto_url("./login.php?sel_field=guest&url=".urlencode("$tb[bbs_root]/orderlist.php"));
}

$tb['title'] = "주문내역";
include_once("./_head.php");

$sql_dan = " and dan IN (1,2,3,4,5) ";
$selected1 = " class='selected'";
if($sca) {
	$selected1 = "";
	$selected2 = " class='selected'";
	$sql_dan = " and dan IN (6,7,8,9,10) ";
}

// 총금액 뽑기
$sql = " select SUM(account + del_account) as total
		   from shop_order
		  where mb_no='$mb_no' $sql_dan ";
$row = sql_fetch($sql);
$tot_sum = $row['total'];

$sql_common = " from shop_order ";
$sql_search = " where mb_no='$mb_no' $sql_dan ";
$sql_order  = " group by odrkey order by index_no desc ";

$sql = " select count(DISTINCT odrkey) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path."/orderlist.skin.php");
include_once("./_tail.php");
?>