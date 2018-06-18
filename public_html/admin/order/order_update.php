<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$od_table = trim($_POST['od_table'][$k]);
	$od = sql_fetch("select * from shop_order where index_no='$od_table'");
	$mb = get_member_no($od['mb_no']);

	switch($dan)
	{
		case '2': // 입금확인
			$sql = "update shop_order 
					   set dan			= '$dan',
						   incomedate	= '$server_time',
						   incomedate_s	= '$time_ymd'
					 where index_no		= '$od_table'";	
			sql_query($sql);
			
			if($od['buymethod'] == 'B') 
				icode_order_sms_send($od['cellphone'], '3', $od['odrkey']);
			break;
		case '3': //배송대기
			$sql = "update shop_order 
					   set dan = '3'
					 where index_no = '$od_table'";
			sql_query($sql);			
			break;
		case '4': //배송중
			$sql = "update shop_order 
					   set dan			= '4',
						   shipdate		= '$server_time',
						   gonumber		= '{$_POST['od_gonumber'][$k]}',						   
						   delivery		= '{$_POST['od_delivery'][$k]}' 
					 where index_no		= '$od_table'";
			sql_query($sql);

			icode_order_sms_send($od['cellphone'], '4', $od['odrkey']);
			break;
		case '5': // 배송완료
			if($od['dan']!='4')
				alert('배송중 단계에서만 배송완료로 가능합니다.'); 
			
			icode_order_sms_send($od['cellphone'], '6', $od['odrkey']);
			
			// 배송완료 날짜 변경
			$sql = "update shop_order 
					   set dan			= '5',
						   overdate_s	= '$time_ymd',
						   gonumber		= '{$_POST['od_gonumber'][$k]}',						   
						   delivery		= '{$_POST['od_delivery'][$k]}' 
					 where index_no		= '$od_table'";
			sql_query($sql);		

			// 장바구니 검사
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$result = sql_query($sql);
			for($j=0; $ct=sql_fetch_array($result); $j++) {
				
				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty
						   from shop_cart
						  where gs_id = '$ct[gs_id]' 
							and odrkey = '$ct[odrkey]' ";
				$sum = sql_fetch($sql);	

				$gs_id = $ct['gs_id'];
			}

			$point = (int)$sum['point'];
			$qty = (int)$sum['qty'];	
			
			// 상품정보
			$gs = get_goods($gs_id);

			// 주문카운터 증가
			sql_query(" update shop_goods set sum_qty = sum_qty + $qty where index_no = '$gs_id' ");

			// 주문완료 후 배송완료시에 쿠폰발행
			if(!$gs['use_aff'] && $config['sp_coupon']) {
				unset($tmp_coupon);
				$tmp_coupon = tbl_chk_coupon('2', $gs_id);

				if($tmp_coupon && $od['mb_yes']) {
					$wr_list_coupon = explode(",", $tmp_coupon);
					for($u=0; $u<count($wr_list_coupon); $u++) {
						if($wr_list_coupon[$u]) {
							$coupon = sql_fetch("select * from shop_coupon where cp_id='$wr_list_coupon[$u]'");
							tbl_publish_coupon($mb['id'], $mb['name']);
						}
					}
				}
			}

			// 포인트 적립
			if($od['mb_yes'] && $point > 0) { 
				$content = "포인트적립-주문 일련번호 : $od[orderno]";
				insert_point($od['mb_no'], $point, $content);
			}

			// 판매수수료 적립
			get_pt_commission($od['pt_id'], $qty);	

			break;
		case '6': // 반품처리	
			if($od['dan'] != '5')
				alert('배송이 완료된 주문만 반품이 가능합니다.'); 

			sql_query("update shop_order set returndate_s='$time_ymd',dan='6' where index_no='$od_table'");

			// 판매수수료 환수처리
			$sql = "select * from shop_partner_paylog where etc1='$od[orderno]' and etc2='shop'";
			$result = sql_query($sql);
			for($j=0; $row=sql_fetch_array($result); $j++) {
				$sql_certify = " income=income-$row[in_money], total=total-$row[in_money], p_shop=p_shop-$row[in_money] ";				
				
				if($config['p_type'] == 'month') // 월
					$sql_search = " where month_date='$row[month_date]' ";
				else // 주,실시간
					$sql_search = " where ju_date='$row[ju_date]' ";

				sql_query("update shop_partner_pay set $sql_certify $sql_search and mb_id='$row[mb_id]' ");
				sql_query("update shop_member set pay=pay-$row[in_money] where id='$row[mb_id]'");
				sql_query("delete from shop_partner_paylog where index_no='$row[index_no]' ");	
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

			// 장바구니 검사
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$result = sql_query($sql);
			for($j=0; $ct=sql_fetch_array($result); $j++) {
				
				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty
						   from shop_cart
						  where gs_id = '$ct[gs_id]' 
							and odrkey = '$ct[odrkey]' ";
				$sum = sql_fetch($sql);	

				$gs_id = $ct['gs_id'];
			}

			$point = (int)$sum['point'];
			$qty = (int)$sum['qty'];

			// 주문건수 취소
			sql_query("update shop_goods set sum_qty = sum_qty - $qty where index_no = '$gs_id'");		

			// 재고수량 되돌리기
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$result = sql_query($sql);
			for($j=0; $ct=sql_fetch_array($result); $j++) {
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
				} 
				// 상품 : 재고수량 증가
				else {	
					$gs = get_goods($ct['gs_id'], 'stock_mod');
					if($gs['stock_mod']) {
						sql_query("update shop_goods set stock_qty = stock_qty + '{$ct['ct_qty']}' where index_no='$ct[gs_id]'");
					}
				}
			}

			// 포인트차감		  
			if($od['mb_yes'] && $point > 0) { 
				$content = "포인트차감-일련번호 : $od[orderno] [사유:반품처리]";
				insert_point($od['mb_no'], $point, $content, 1);
			}

			// 포인트반환	  
			if($od['use_point'] > 0) {
				$content = "포인트반환-일련번호 : $od[orderno] [사유:반품처리]";
				insert_point($od['mb_no'], $od['use_point'], $content);
			}			
			break;
		case '7': // 취소처리
			if($od['dan']>='5')
			{ alert('배송이 완료된 상품은 취소할수 없으며 반품 및 교환만 변경 가능합니다.'); }  
			else if($od['dan']=='1')
			{ $updan = 8; }
			else
			{ $updan = 7; }

			// 취소
			sql_query("update shop_order set canceldate_s = '$time_ymd',dan = '$updan' where index_no = '$od_table'");

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
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$result = sql_query($sql);
			for($j=0; $ct=sql_fetch_array($result); $j++) {
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
				} 
				// 상품 : 재고수량 증가
				else {	
					$gs = get_goods($ct['gs_id'], 'stock_mod');
					if($gs['stock_mod']) {
						sql_query("update shop_goods set stock_qty = stock_qty + '{$ct['ct_qty']}' where index_no='$ct[gs_id]'");
					}
				}
			}

			if($od['use_point'] > 0) {
				$content = "포인트반환-일련번호 : $od[orderno] [사유:주문취소건]";
				insert_point($od['mb_no'], $od['use_point'], $content);
			}

			icode_order_sms_send($od['cellphone'], '5', $od['odrkey']);
			break;
		case '10': // 교환처리
			if($od['dan']!='5')
				alert('배송이 완료된 주문만 교환이 가능합니다.');

			$sql = "update shop_order 
			           set dan		= '10', 
					       swapdate	= '$time_ymd' 
					 where index_no	= '$od_table'";	
			sql_query($sql);			
			break;
	}
}

goto_url("../order.php?$q1&page=$page");
?>
