<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($ca_no == 'reset') {

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$member['id'];
	sql_query(" drop table {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	rm_rf(TW_DATA_PATH.'/category/'.$member['id']);

	// 카테고리 생성
	sql_member_category($member['id']);
}

goto_url('./page.php?code=partner_category');
?>