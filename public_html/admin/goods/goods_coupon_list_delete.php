<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$cp_id = trim($_POST['cp_id'][$k]);

	// 삭제
	sql_query("delete from shop_coupon where cp_id='$cp_id'");
}

goto_url("../goods.php?$q1&page=$page");
?>
