<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$re_table = trim($_POST['re_table'][$k]);

	// 삭제
	sql_query(" delete from shop_member_leave where index_no = '$re_table' ");
}

goto_url("../help.php?$q1&page=$page");
?>