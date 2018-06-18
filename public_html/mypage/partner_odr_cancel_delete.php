<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$ca_uid = trim($_POST['ca_uid'][$k]);
	$ca_od_dan = trim($_POST['ca_od_dan'][$k]); 
	$ca_od_uid = trim($_POST['ca_od_uid'][$k]);

	sql_query("update shop_order set dan='$ca_od_dan' where index_no='$ca_od_uid'");

	// 삭제
	sql_query("delete from shop_order_cancel where ca_uid = '$ca_uid'");
}

goto_url("./page.php?$q1&page=$page");
?>