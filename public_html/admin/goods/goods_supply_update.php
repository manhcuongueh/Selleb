<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];
	
	switch($w) {
		case 'state':
			sql_query("update shop_goods set shop_state = '0' where index_no = '{$_POST['gs_id'][$k]}'");
			break;
		case 'defer':
			sql_query("update shop_goods set shop_state = '2' where index_no = '{$_POST['gs_id'][$k]}'");
			break;
	}
}

goto_url("../goods.php?$q1&page=$page");
?>