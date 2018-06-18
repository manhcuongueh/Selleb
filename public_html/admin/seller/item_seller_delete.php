<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$index_no = trim($_POST['index_no'][$k]);

	// 삭제
	$sr = sql_fetch("select sp_send_cost,mo_send_cost from shop_seller where index_no='$index_no'");
	
	delete_editor_image($sr['sp_send_cost']);
	delete_editor_image($sr['mo_send_cost']);

	sql_query(" delete from shop_seller where index_no='$index_no' ");
}

goto_url("../seller.php?$q1&page=$page");
?>