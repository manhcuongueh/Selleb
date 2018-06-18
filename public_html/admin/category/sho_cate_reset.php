<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($ca_no == 'reset') {

	// 가맹점 삭제
	$sql = "select id from shop_member where grade between 2 and 6 ";
	$result = sql_query($sql);
	while($row = sql_fetch_array($result)) {

		// 카테고리 테이블 DROP
		$target_table = 'shop_cate_'.$row['id'];
		sql_query(" drop table {$target_table} ", FALSE);

		// 카테고리 폴더 전체 삭제
		rm_rf(TW_DATA_PATH.'/category/'.$row['id']);

		// 카테고리 생성
		sql_member_category($row['id']);
	}
}

goto_url("../category.php?code=cate");
?> 