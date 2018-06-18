<?php
include_once("./_common.php");

if(!$member["id"] && !get_session("ck_guest_od")) {
	goto_url(TW_BBS_URL."/login.php?url=$nowurl&sel_field=guest");
}

if($_REQUEST['mode'] == 'decide') { // 구매결정
	user_ok($idx, $mb_no);
	alert("정상적으로 처리 되었습니다.", TW_SHOP_URL."/orderlist.php");
}

$gw_head_title = '주문/배송조회';
include_once("./_head.php");

// 총 주문접수 건수조회
$info = get_order_status_sum($mb_no, " and dan='1' ");
$status_count_1 = $info['count'];

// 총 입금확인 건수조회
$info = get_order_status_sum($mb_no, " and dan='2' ");
$status_count_2 = $info['count'];

// 총 배송대기 건수조회
$info = get_order_status_sum($mb_no, " and dan='3' ");
$status_count_3 = $info['count'];

// 총 배송중 건수조회
$info = get_order_status_sum($mb_no, " and dan='4' ");
$status_count_4 = $info['count'];

// 총 배송완료 건수조회
$info = get_order_status_sum($mb_no, " and dan='5' ");
$status_count_5 = $info['count'];

// 총 구매미확정 건수조회
$info = get_order_status_sum($mb_no, " and user_ok='0' and (dan between '4' and '5') ");
$status_count_6 = $info['count'];

// 총 구매확정 건수조회
$info = get_order_status_sum($mb_no, " and user_ok='1' and (dan between '4' and '5') ");
$status_count_7 = $info['count'];

// 총 주문취소 건수조회
$info = get_order_status_sum($mb_no, " and (dan between '7' and '8') ");
$status_count_8 = $info['count'];

// 총 반품처리 건수조회
$info = get_order_status_sum($mb_no, " and dan='6' ");
$status_count_9 = $info['count'];

// 총 구매건수, 금액 뽑기
$info = get_order_status_sum($mb_no, " and (dan between '1' and '5') ");
$tot_count = $info['count'];
$tot_price = $info['price'];

$sql_common = " from shop_order ";
$sql_search = " where mb_no='$mb_no' and dan!='0' ";
$sql_order  = " group by odrkey order by index_no desc ";

$sql = " select count(DISTINCT odrkey) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path.'/orderlist.skin.php');

include_once("./_tail.php");
?>