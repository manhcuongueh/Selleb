<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = "update shop_faq_cate 
			   set catename = '{$_POST['catename'][$k]}'
			 where index_no = '{$_POST['index_no'][$k]}'";
	sql_query($sql);
}

goto_url("../help.php?$q1&page=$page");
?>