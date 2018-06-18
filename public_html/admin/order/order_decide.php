<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$od_table = trim($_POST['od_table'][$k]);
	$result = user_ok_admin($od_table);	
}

goto_url("../order.php?$q1&page=$page");
?>
