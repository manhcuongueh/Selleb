<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gs_id = trim($_POST['gs_id'][$k]);

	// 기존 카테고리삭제
	sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");

	$sql = " insert into shop_goods_cate
				set gcate = '{$_POST['t_gcate']}',
					gs_id = '$gs_id' ";
	sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>