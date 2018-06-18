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

	$sql = "update shop_order_cancel 
			   set ca_cancel = '{$_POST['ca_cancel'][$k]}',
			       ca_bankcd = '{$_POST['ca_bankcd'][$k]}',
				   ca_banknum = '{$_POST['ca_banknum'][$k]}',
				   ca_bankname = '{$_POST['ca_bankname'][$k]}',
				   ca_memo = '{$_POST['ca_memo'][$k]}'
			 where ca_uid = '{$_POST['ca_uid'][$k]}'";
	sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>