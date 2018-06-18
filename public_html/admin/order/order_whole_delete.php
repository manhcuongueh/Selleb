<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$od_table = trim($_POST['od_table'][$k]);
	$od = get_order_no($od_table);
	$gs = sql_fetch("select simg1 from shop_order_goods where gcate = '$od[orderno]' ");

	$dir_list = TW_DATA_PATH."/order/".substr($od['odrkey'],0,4)."/".$od['odrkey'];
	
	if($gs['simg1']) {
		@unlink($dir_list."/".$gs['simg1']);
		delete_item_thumbnail($dir_list, $gs['simg1']);
	}

	// 삭제
	sql_query(" delete from shop_cart where orderno = '$od[orderno]' ");
	sql_query(" delete from shop_order where index_no = '$od_table' ");
	sql_query(" delete from shop_order_goods where gcate = '$od[orderno]' ");	
	sql_query(" delete from shop_order_memo where order_no = '$od_table' ");
	sql_query(" delete from shop_order_cancel where ca_od_uid = '$od_table' ");
}

goto_url("./order_whole.php?$q1&page=$page");
?>