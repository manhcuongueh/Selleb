<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU6;
$pg_num = 6;
$snb_icon = "<i class=\"ionicons ion-clipboard\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "today")	$pg_title2 = ADMIN_MENU6_1;
if($code == "1")		$pg_title2 = ADMIN_MENU6_2;
if($code == "2")		$pg_title2 = ADMIN_MENU6_3;
if($code == "3")		$pg_title2 = ADMIN_MENU6_4;
if($code == "4")		$pg_title2 = ADMIN_MENU6_5;
if($code == "5")		$pg_title2 = ADMIN_MENU6_6;
if(in_array($code, array('delivery_xls','delivery_xls_update'))) $pg_title2 = ADMIN_MENU6_7;
if($code == "cancel")	$pg_title2 = ADMIN_MENU6_8;
if($code == "7")		$pg_title2 = ADMIN_MENU6_9;
if($code == "8")		$pg_title2 = ADMIN_MENU6_10;
if($code == "6")		$pg_title2 = ADMIN_MENU6_11;
if($code == "10")		$pg_title2 = ADMIN_MENU6_12;
if($code == "whole")	$pg_title2 = ADMIN_MENU6_13;
if($code == "memo")		$pg_title2 = ADMIN_MENU6_14;
if($code == "aff")		$pg_title2 = ADMIN_MENU6_15;

$sql_where1 = " where (left(gs_se_id,3)='AP-' or gs_se_id = 'admin') ";

$row = admin_order_status_sum("$sql_where1 and orderdate_s='$time_ymd' and dan!='0'"); // 오늘접수 된 주문
$tdayCnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='1' "); // 총 주문접수
$dan1Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='2' "); // 총 입금확인
$dan2Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='3' "); // 총 배송대기
$dan3Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='4' "); // 총 배송중
$dan4Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='5' "); // 총 배송완료
$dan5Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='6' "); // 총 반품처리
$dan6Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='7' "); // 총 입금후 주문취소
$dan7Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='8' "); // 총 입금전 주문취소
$dan8Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan='10' "); // 총 교환처리
$dan10Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where1 and dan!='0' "); // 총 주문내역
$cumuCnt = (int)$row['cnt'];

// 총 관리자메모
$row = sql_fetch("select count(*) as cnt from shop_order_memo $sql_where1 ");
$memoCnt = (int)$row['cnt'];

// 총 주문취소 요청
$sql_where2 = " from shop_order_cancel where ca_yn='0' and ca_cancel_use ='주문취소' and ca_it_aff='0' ";
$row = sql_fetch("select count(*) as cnt $sql_where2 ");
$cancelCnt = (int)$row['cnt'];

//총 가맹점상품 주문현황
$sql_where3 = " where (left(gs_se_id,3)!='AP-' and gs_se_id != 'admin') ";
$row = admin_order_status_sum("$sql_where3 and dan!='0' "); // 총 주문내역
$userCnt = (int)$row['cnt'];

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./order/order_{$code}.php");
	?>
</div>

<?php 
include_once("admin_tail.php");
?>