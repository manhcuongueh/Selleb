<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($w == '') {
	alert('잘못된 접근입니다.');
}

if($mb_id == '') {
	alert('적용할 아이디가 넘어오지 않았습니다.');
}

if($w == 'reset') {

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$mb_id;
	sql_query(" drop table {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	rm_rf(TW_DATA_PATH.'/category/'.$mb_id);

	// 카테고리 생성
	sql_member_category($mb_id);
}

goto_url('./pt_category.php?mb_id='.$mb_id);
?>