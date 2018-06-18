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

	$od_table = trim($_POST['od_table'][$k]);
	$od = get_order_no($od_table);	
	$gs = sql_fetch("select simg1 from shop_order_goods where gcate = '$od[orderno]' ");

	$dir_list = TW_DATA_PATH."/order/".substr($od['odrkey'],0,4)."/".$od['odrkey'];
	
	if($gs['simg1']) {
		@unlink($dir_list."/".$gs['simg1']);
		delete_item_thumbnail($dir_list, $gs['simg1']);
	}

	// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
	$cp = sql_fetch("select lo_id,cp_type from shop_coupon_log where od_id='$od[orderno]'");
	if($cp['cp_type'] == '5') {
		$sql = "update shop_coupon_log 
				   set mb_use	= '0',
					   od_id	= '',
					   cp_udate	= '' 
				 where lo_id = '$cp[lo_id]' ";
		sql_query($sql);
	}

	// 재고수량 되돌리기
	unset($gs);
	$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
	$result = sql_query($sql);
	for($n=0; $ct=sql_fetch_array($result); $n++) {
		// 옵션 : 재고수량 증가	
		if($ct['io_id']) {
			// 옵션 : 재고수량 증가	
			$sql_stock_qty = " io_id = '$ct[io_id]' and gs_id = '$ct[gs_id]' and io_type = '{$ct['io_type']}' ";
			$sql2 = " select io_id, gs_id, io_type, io_stock_qty 
						from shop_goods_option where $sql_stock_qty ";
			$opt = sql_fetch($sql2);

			if($opt['io_stock_qty'] != '999999999') {
				$io_stock_qty = $opt['io_stock_qty'] + $ct['ct_qty'];
				sql_query("update shop_goods_option set io_stock_qty='$io_stock_qty' where $sql_stock_qty ");
			}

		// 상품 : 재고수량 증가
		} else {	
			$gs = sql_fetch("select stock_mod from shop_goods where index_no='$ct[gs_id]'");
			if($gs['stock_mod']) {
				sql_query("update shop_goods set stock_qty = stock_qty + '{$ct['ct_qty']}' where index_no='$ct[gs_id]'");
			}
		}
	}

	// 포인트 결제가 있는가?
	if($od['use_point'] > 0) {
		$mb = sql_fetch("select point from shop_member where index_no='$od[mb_no]'");
		$mb_point = $mb['point'] + $od['use_point'];

		sql_query("update shop_member set point='$mb_point' where index_no='$od[mb_no]'");
		sql_query("insert into shop_point 
						 ( mb_no, income, total, memo, wdate )
				  VALUES ('$od[mb_no]', '$od[use_point]', '$mb_point', 
						  '포인트반환-일련번호 : $od[orderno] [사유:주문취소건]', '$server_time')");
	}	

	// 삭제
	sql_query(" delete from shop_cart where orderno = '$od[orderno]' ");
	sql_query(" delete from shop_order where index_no = '$od_table' ");
	sql_query(" delete from shop_order_goods where gcate = '$od[orderno]' ");	
	sql_query(" delete from shop_order_memo where order_no = '$od_table' ");
	sql_query(" delete from shop_order_cancel where ca_od_uid = '$od_table' ");
}

goto_url("./page.php?$q1&page=$page");
?>