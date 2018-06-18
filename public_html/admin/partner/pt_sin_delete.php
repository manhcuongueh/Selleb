<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$si_table = trim($_POST['si_table'][$k]);

	// 삭제
	sql_query(" delete from shop_partner_term where index_no = '$si_table' ");
}

goto_url("../partner.php?$q1&page=$page");
?>
