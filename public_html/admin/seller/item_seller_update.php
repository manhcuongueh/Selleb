<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sr = sql_fetch("select mb_id from shop_seller where index_no='{$_POST['index_no'][$k]}'");
	
	sql_query("update shop_seller set state='1',shop_open='1' where index_no='{$_POST['index_no'][$k]}'");
	sql_query("update shop_member set supply='Y' where id='$sr[mb_id]'");	
	sql_query("update shop_goods set isopen='1' where mb_id='$sr[mb_id]'");	
}

goto_url("../seller.php?$q1&page=$page");
?>