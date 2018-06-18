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

	$index_no = trim($_POST['index_no'][$k]);

	// 삭제
	sql_query(" delete from shop_order_memo where index_no = '$index_no' ");
}


goto_url("./page.php?$q1&page=$page");
?>