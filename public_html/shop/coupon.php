<?php
include_once("./_common.php");

if(!$member['id'])
	alert('회원 전용 서비스입니다.',TW_BBS_URL."/login.php?url=$nowurl");

if(!$config['sp_coupon'])
    alert("쿠폰사용이 중지 되었습니다.");

$gw_head_title = '쿠폰관리';
include_once("./_head.php");

$u_part = array();
$u_part[0] = "전체상품";
$u_part[1] = "일부 상품 사용가능";
$u_part[2] = "일부 카테고리 사용가능";
$u_part[3] = "일부 상품 사용불가";
$u_part[4] = "일부 카테고리 사용불가";

$sql_common = " from shop_coupon_log ";
$sql_search = " where mb_id = '$member[id]' ";
$sql_order  = " order by cp_wdate desc ";

if($sca) {
	// 사용완료 및 기한만료 쿠폰
	$sql_search .= " and mb_use='1' or ( ";
	$sql_search .= " (cp_inv_type='0' and cp_inv_edate != '9999999999' and cp_inv_edate < curdate()) ";
	$sql_search .= " or ";
	$sql_search .= " (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) < now()) ";
	$sql_search .= " ) ";
} else {
	// 사용가능한 쿠폰
	$sql_search .= " and mb_use='0' and ( ";
	$sql_search .= " (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) ";
	$sql_search .= " or ";
	$sql_search .= " (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ";
	$sql_search .= " ) ";
}

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once($theme_path.'/coupon.skin.php');

include_once("./_tail.php");
?>