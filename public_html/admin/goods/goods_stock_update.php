<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	sql_query("update shop_goods set stock_qty='{$_POST['stock_qty']}' where index_no='{$_POST['gs_id'][$k]}'");	
}

goto_url( "../goods.php?$q1&page=$page");
?>