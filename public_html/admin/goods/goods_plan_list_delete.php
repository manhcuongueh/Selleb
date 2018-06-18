<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$plan_dir = TW_DATA_PATH."/plan";

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$pl_no = trim($_POST['pl_no'][$k]);

	$row = sql_fetch("select * from shop_plan where pl_no = '$pl_no'");
	@unlink($plan_dir."/".$row['pl_limg']);
	@unlink($plan_dir."/".$row['pl_bimg']);

	sql_query("delete from shop_plan where pl_no='$pl_no' ");
}

goto_url("../goods.php?$q1&page=$page");
?>
