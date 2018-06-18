<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$mb_id = trim($_POST['mb_id'][$k]);

	// 삭제
	$dr = sql_fetch("select sp_send_cost,mo_send_cost from shop_partner where mb_id='$mb_id'");
	delete_editor_image($dr['sp_send_cost']);
	delete_editor_image($dr['mo_send_cost']);

	sql_query(" delete from shop_partner where mb_id = '$mb_id' ");

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$mb_id;
	sql_query(" drop table {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	rm_rf(TW_DATA_PATH.'/category/'.$mb_id);

	$sql = " update shop_member 
			    set grade  = '9',
				    anew_date = '',
				    term_date = ''
			  where id = '$mb_id' ";
	sql_query($sql, FALSE);
}

goto_url("../partner.php?$q1&page=$page");
?>
