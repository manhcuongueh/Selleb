<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gmid = trim($_POST['gmid'][$k]);

	// 삭제
	sql_query("delete from shop_goods_review where index_no='$gmid'");
}

goto_url("../goods.php?$q1&page=$page");
?>
