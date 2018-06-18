<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$mb_no = trim($_POST['mb_table'][$k]);
	$mb = get_member_no($mb_no, 'id');

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$mb['id'];
	sql_query(" drop table {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	rm_rf(TW_DATA_PATH.'/category/'.$mb['id']);

	// 카테고리 생성
	sql_member_category($mb['id']);
}

goto_url("../partner.php?$q1&page=$page");
?>