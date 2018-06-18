<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

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

goto_url("../order.php?$q1&page=$page");
?>
