<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gr_id = trim($_POST['gr_id'][$k]);

	// 삭제
	sql_query("delete from shop_gift_group where gr_id='$gr_id'");
	sql_query("delete from shop_gift where gr_id='$gr_id'");
}

goto_url("../goods.php?$q1&page=$page");
?>