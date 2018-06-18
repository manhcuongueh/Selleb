<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = "update shop_brand 
			   set br_name = '{$_POST['br_name'][$k]}',
				   br_name_eng = '{$_POST['br_name_eng'][$k]}'
			 where br_id = '{$_POST['br_id'][$k]}'";
	sql_query($sql);

	// 상품 정보도 동시에 수정
	$sql = "update shop_goods 
			   set brand_nm = '{$_POST['br_name'][$k]}' 
			 where brand_uid = '{$_POST['br_id'][$k]}'";
	sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>