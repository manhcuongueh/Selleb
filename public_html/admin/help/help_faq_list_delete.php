<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$faq_table = trim($_POST['faq_table'][$k]);

	// 삭제
	$sql = "select memo from shop_faq where index_no='$faq_table' ";
	$faq = sql_fetch($sql);
	delete_editor_image($faq['memo']);

	sql_query(" delete from shop_faq where index_no = '$faq_table' ");
}

goto_url("../help.php?$q1&page=$page");
?>