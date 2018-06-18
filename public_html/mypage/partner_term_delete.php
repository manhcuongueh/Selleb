<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$index_no = trim($_POST['index_no'][$k]);

	sql_query("delete from shop_partner_term where index_no='$index_no' ");
}

goto_url("./page.php?$q1&page=$page");
?>