<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$pp_id = trim($_POST['pp_id'][$k]);

	// 삭제
	$sql = "select memo from shop_popup where index_no='$pp_id' ";
	$po = sql_fetch($sql);

	delete_editor_image($po['memo']);

	sql_query("delete from shop_popup where index_no='$pp_id'");	
}

goto_url("./page.php?$q1&page=$page");
?>