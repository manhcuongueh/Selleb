<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$brand_dir = TW_DATA_PATH."/brand";

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$br_id = trim($_POST['br_id'][$k]);

	$row = sql_fetch("select br_logo from shop_brand where br_id = '$br_id'");
	@unlink($brand_dir."/".$row['br_logo']);

	sql_query("delete from shop_brand where br_id='$br_id' ");
}

goto_url("../goods.php?$q1&page=$page");
?>
