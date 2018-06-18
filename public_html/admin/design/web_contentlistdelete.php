<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$co_id = trim($_POST['co_id'][$k]);

	// 삭제
	sql_query("delete from shop_content where co_id='$co_id'");
}

goto_url("../design.php?$q1&page=$page");
?>