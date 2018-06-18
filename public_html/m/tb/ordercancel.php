<?php
include_once("./_common.php");

$tb['title'] = "주문취소 요청";
include_once("$tb[root]/head.sub.php");

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

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

include_once($theme_path.'/ordercancel.skin.php');
include_once("$tb[root]/tail.sub.php");
?>