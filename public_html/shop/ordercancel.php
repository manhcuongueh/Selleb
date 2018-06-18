<?php
include_once("./_common.php");

$gw_head_title = '주문취소 요청';
include_once(TW_PATH."/head.sub.php");

$gs = get_order_goods($od_id);

$sql = " select *
		   from shop_order
		  where dan > 0
		    and orderno = '$od_id'
			and mb_no = '$mb_no'
		  group by orderno ";
$od = sql_fetch($sql);

if(!$od['orderno'])
    alert_close("주문내역이 없습니다.");

if(!in_array($od['dan'], array('1','2','3')))
    alert_close("배송대기 이전까지만 주문 취소 가능합니다.");

$sql = " select count(*) as cnt
		   from shop_order
		  where odrkey = '$od[odrkey]'
		    and mb_no = '$mb_no' ";
$row = sql_fetch($sql);
if($row['cnt'] > 1) {
	$ca_type = "부분취소"; // 부분취소
} else {
	$ca_type = "일반취소"; // 일반취소
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TW_SHOP_URL.'/ordercancel_update.php';

include_once($theme_path.'/ordercancel.skin.php');

include_once(TW_PATH."/tail.sub.php");
?>