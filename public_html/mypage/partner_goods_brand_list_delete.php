<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

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

goto_url("./page.php?$q1&page=$page");
?>