<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = "update shop_plan 
			   set pl_name = '{$_POST['pl_name'][$k]}',
				   pl_use = '{$_POST['pl_use'][$k]}'
			 where pl_no = '{$_POST['pl_no'][$k]}'";
	sql_query($sql);
}

goto_url("../goods.php?$q1&page=$page");
?>
