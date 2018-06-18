<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

// 실행일 비교
if(isset($default['de_optimize_date']) && $default['de_optimize_date'] >= $time_ymd)
    return;

// 환경설정 장바구니 보관일수 체크
if($default['de_cart_day'] > 0) {
	$tmp_before_date = date("Y-m-d",strtotime("-{$default['de_cart_day']} days", time()));
	$sql = " delete from shop_cart where left(ct_time,10) < '$tmp_before_date' and ct_select='0' ";
	sql_query($sql, FALSE);
}

// 환경설정 찜한상품 보관일수 체크
if($default['de_wish_day'] > 0) {
	$tmp_before_date = date("Y-m-d",strtotime("-{$default['de_wish_day']} days", time()));
	$sql = " delete from shop_wish where left(wi_time,10) < '$tmp_before_date' ";
	sql_query($sql, FALSE);
}

// 구매결정 강제승인 체크
if($config['dan'] > 0) {
	$tmp_before_date = date("Y-m-d",strtotime("-{$config['dan']} days", time()));
	$sql_dans = " where overdate_s < '$tmp_before_date' and user_ok = '0' and dan = '5' ";
	$sql_rows = sql_fetch("select count(*) as cnt from shop_order $sql_dans ");
	if($sql_rows['cnt']) {
		sql_query("update shop_order set user_ok = '1', user_date = '$server_time' $sql_dans ");
	}
}

// 미입금된 주문내역 자동취소
if($default['de_order_day'] > 0) {
	$tmp_before_date = strtotime("-{$default[de_order_day]} days", time()); 
	$sql = " select * 
			   from shop_order
			  where orderdate < '$tmp_before_date' 
			    and dan = '1' ";
	$rst = sql_query($sql);
	for($i=0; $od=sql_fetch_array($rst); $i++)
	{
		// 입금전 주문취소로 변경
		$sql = "update shop_order 
				   set canceldate_s = '$time_ymd',
				       dan = '8' 
				 where index_no = '{$od[index_no]}' ";
		sql_query($sql, FALSE);

		// 메모남기기
		$sql = "update shop_order_memo 
				   set order_no = '{$od[index_no]}',
				       amemo = '{$default['de_order_day']}일경과 미입금 자동 주문취소',
					   wdate = '$server_time',
					   writer = '관리자',
					   gs_se_id = '{$od[gs_se_id]}'
				 where index_no = '{$od[index_no]}' ";
		sql_query($sql, FALSE);

		// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
		$cp = sql_fetch("select lo_id,cp_type from shop_coupon_log where od_id='$od[orderno]'");
		if($cp['cp_type'] == '5') {
			$sql = "update shop_coupon_log 
					   set mb_use = '0',
						   od_id = '',
						   cp_udate = '' 
					 where lo_id = '$cp[lo_id]' ";
			sql_query($sql, FALSE);
		}

		// 재고수량 되돌리기
		$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
		$ct_rst = sql_query($sql);
		for($i2=0; $ct=sql_fetch_array($ct_rst); $i2++) {
			$gs = get_goods($ct['gs_id']);

			// 옵션 : 재고수량 증가	
			if($ct['io_id']) {
				// 옵션 : 재고수량 증가	
				$sql_stock_where = " where io_id = '$ct[io_id]' 
				                       and gs_id = '$ct[gs_id]' 
									   and io_type = '{$ct['io_type']}' ";

				$sql2 = " select * from shop_goods_option {$sql_stock_where} ";
				$opt = sql_fetch($sql2);

				if($opt['io_stock_qty'] != '999999999') {
					$io_stock_qty = $opt['io_stock_qty'] + $ct['ct_qty'];
					sql_query("update shop_goods_option set io_stock_qty='$io_stock_qty' {$sql_stock_where} ");
				}				
			} else { // 상품 : 재고수량 증가				
				if($gs['stock_mod']) {
					sql_query("update shop_goods set stock_qty = stock_qty + {$ct['ct_qty']} where index_no='$ct[gs_id]'");
				}
			}
		}

		if($od['use_point'] > 0) {
			$content = "포인트반환-일련번호 : $od[orderno] [사유:{$default['de_order_day']}일경과 미입금 자동 주문취소]";
			insert_point($od['mb_no'], $od['use_point'], $content);
		}
	}
}

// 실행일 기록
if(isset($default['de_optimize_date'])) {
    sql_query(" update shop_default set de_optimize_date = '$time_ymd' ");
}

unset($tmp_before_date);
?>
