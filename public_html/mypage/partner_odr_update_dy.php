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

	$sql = "update shop_order 
			   set delivery	= '{$_POST['od_delivery'][$k]}',
			       gonumber	= '{$_POST['od_gonumber'][$k]}'
			 where index_no = '{$_POST['od_table'][$k]}'";
	sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>