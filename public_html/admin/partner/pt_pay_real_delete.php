<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$p_no = trim($_POST['p_table'][$k]);

	// 삭제
	sql_query(" delete from shop_partner_payrun where index_no = '$p_no' ");
}

goto_url("../partner.php?$q1&page=$page");
?>
