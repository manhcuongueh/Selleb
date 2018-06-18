<?php
include_once("./_common.php");

// 장바구니 상품 재고 검사
$error = "";
$sql = " select * from shop_cart where index_no IN ({$_POST['ss_cart_id']}) and ct_select = '0' ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {
    // 상품에 대한 현재고수량
    if($row['io_id']) {
        $it_stock_qty = (int)get_option_stock_qty($row['gs_id'], $row['io_id'], $row['io_type']);
    } else {
        $it_stock_qty = (int)get_it_stock_qty($row['gs_id']);
    }
    // 장바구니 수량이 재고수량보다 많다면 오류
    if($row['ct_qty'] > $it_stock_qty)
        $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
}

if($i == 0)
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', TW_SHOP_URL.'/cart.php');

if($error != "") {
    $error .= "다른 고객님께서 {$name}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";
    alert($error);
}

$dan = 0;
if($_POST['buymethod'] == 'B') // 무통장인가?
	$dan = 1; // 주문접수 단계로 적용

if((int)$_POST['total_amt'] == 0) { // 총 결제금액이 0 이면	
	$dan = 2; // 입금확인 단계로 적용

	// 포인트로 전액 결제시는 포인트결제로 값을 바꾼다.
	if($_POST['buymethod'] != 'P' && (int)$_POST['check_amt'] == (int)$_POST['use_point']) {
		$_POST['buymethod'] = 'P';
	}	
}

set_session('del_amt', (int)$_POST['del_amt']);
set_session('total_amt', (int)$_POST['total_amt']);
set_session('use_point', (int)$_POST['use_point']); 
set_session('ss_pay_method', $_POST['buymethod']); 

$del_total_amt	= explode("|",$_POST['del_total_amt']); // 상품별 배송비
$dc_exp_amt		= explode("|",$_POST['dc_exp_amt']); // 상품별 할인가
$dc_exp_lo_id	= explode("|",$_POST['dc_exp_lo_id']); // 상품별 쿠폰 shop_coupon_log (필드:lo_id)
$dc_exp_cp_id	= explode("|",$_POST['dc_exp_cp_id']); // 상품별 쿠폰 shop_coupon_log (필드:cp_id)
$ss_cart_id		= explode(",",$_POST['ss_cart_id']); // 장바구니 idx

$use_point = (int)$_POST['use_point']; // 포인트결제
$del_amt2  = (int)$_POST['del_amt2']; // 추가배송비

$indate = date_conv($_POST['in_year'].$_POST['in_month'].$_POST['in_day']);

if($mb_yes)
    $passwd = $member['passwd'];
else
    $passwd = get_encrypt_string($_POST['passwd']);


$odrkey = get_uniqid(); // 주문번호

for($i=0; $i<count($gs_id); $i++) {
	
	// 주문 일련번호
	$od_id = $cart_id[$i];

	if($i==0) {
		$u_point = $use_point;  // 포인트 결제금액				
		for($k=0; $k<count($gs_id); $k++) {	
			if($k == 0 && $del_amt2 > 0) {
				$del_total_amt[$k] = (int)$del_total_amt[$k] + $del_amt2; // 배송비 + 추가배송비
			}

			$de_amt = (int)$del_total_amt[$k]; // 배송비 결제금액			
			$gd_amt = (int)$gs_account[$k] - (int)$dc_exp_amt[$k]; // 상품 판매가 - 쿠폰 할인가
			if($u_point > 0) {
				if(($gd_amt+$de_amt) >= $u_point) {
					$arr_account[$k] = ($gd_amt+$de_amt)-$u_point;
					$arr_point[$k] = $u_point;
					$u_point = 0; 

				} else if(($gd_amt+$de_amt) < $u_point) {
					$arr_account[$k] = 0;
					$arr_point[$k] = $gd_amt+$de_amt;
					$u_point = $u_point-($gd_amt+$de_amt); 
				}

			} else {
				$u_point = 0;
				$arr_point[$k] = 0;
				$arr_account[$k] = $gd_amt+$de_amt;
			}
		}
	} else {
		$del_amt2 = 0;
	}

	$sql = "insert into shop_order 
			   set odrkey				= '{$odrkey}'
			     , orderno				= '{$od_id}'
				 , mb_yes				= '{$mb_yes}'
				 , mb_no				= '{$mb_no}'
				 , passwd				= '{$passwd}'
				 , name					= '{$_POST['name']}'
				 , cellphone			= '{$_POST['cellphone']}'
				 , telephone			= '{$_POST['telephone']}'
				 , email				= '{$_POST['email']}'
				 , zip					= '{$_POST['zip']}'
				 , addr1				= '{$_POST['addr1']}'
				 , addr2				= '{$_POST['addr2']}'
				 , addr3				= '{$_POST['addr3']}'
				 , addr_jibeon			= '{$_POST['addr_jibeon']}'
				 , b_name				= '{$_POST['b_name']}'
				 , b_cellphone			= '{$_POST['b_cellphone']}'
				 , b_telephone			= '{$_POST['b_telephone']}'
				 , b_zip				= '{$_POST['b_zip']}'
				 , b_addr1				= '{$_POST['b_addr1']}'
				 , b_addr2				= '{$_POST['b_addr2']}'
				 , b_addr3				= '{$_POST['b_addr3']}'
				 , b_addr_jibeon		= '{$_POST['b_addr_jibeon']}'
				 , taxflag				= '{$_POST['taxflag']}'
				 , gs_id				= '{$gs_id[$i]}'
				 , gs_se_id				= '{$gs_se_id[$i]}'
				 , gs_notax				= '{$gs_notax[$i]}'
				 , account				= '{$gs_account[$i]}'
				 , dc_exp_amt			= '{$dc_exp_amt[$i]}'
				 , dc_exp_lo_id			= '{$dc_exp_lo_id[$i]}'
				 , dc_exp_cp_id			= '{$dc_exp_cp_id[$i]}'
				 , use_account			= '{$arr_account[$i]}'
				 , use_point			= '{$arr_point[$i]}'
				 , del_account			= '{$del_total_amt[$i]}'
				 , del_account2			= '{$del_amt2}'
				 , buymethod			= '{$_POST['buymethod']}'
				 , bank					= '{$_POST['bank']}'
				 , incomename			= '{$_POST['incomename']}'
				 , indate				= '{$indate}'
				 , orderdate			= '{$server_time}'
				 , orderdate_s			= '{$time_ymd}'
				 , dan					= '{$dan}'
				 , memo					= '{$_POST['memo']}'
				 , taxsave_yes			= '{$_POST['taxsave_yes']}'
				 , taxbill_yes			= '{$_POST['taxbill_yes']}'
				 , company_saupja_no	= '{$_POST['company_saupja_no']}'
				 , company_name			= '{$_POST['company_name']}'
				 , company_owner		= '{$_POST['company_owner']}'
				 , company_addr			= '{$_POST['company_addr']}'
				 , company_item			= '{$_POST['company_item']}'
				 , company_service		= '{$_POST['company_service']}'
				 , tax_hp				= '{$_POST['tax_hp']}'
				 , tax_saupja_no		= '{$_POST['tax_saupja_no']}'
				 , ip					= '{$_SERVER['REMOTE_ADDR']}'
				 , pt_id				= '{$_POST['pt_id']}'
				 , shop_id				= '{$_POST['shop_id']}' ";
	sql_query($sql, FALSE);	

	// 주문 상품을 shop_goods => shop_order_goods에 복사. 
	// 고객이 주문/배송조회를 위해 보관해 둔다.
	if($gs_id[$i]) {
		get_goodsinfo_move($gs_id[$i], $od_id, $odrkey);
	}

	// 쿠폰 사용함으로 변경 (무통장, 포인트결제일 경우만)
	if($dc_exp_lo_id[$i] && $mb_yes && in_array($_POST['buymethod'],array('B','P'))) {
		sql_query("update shop_coupon_log set mb_use='1',od_id='$od_id',cp_udate='$time_ymdhis' where lo_id='$dc_exp_lo_id[$i]'");
	}

	// 쿠폰 주문건수 증가
	if($dc_exp_cp_id[$i] && $mb_yes) {
		sql_query("update shop_coupon set cp_odr_cnt=(cp_odr_cnt + 1) where cp_id='$dc_exp_cp_id[$i]'");
	}

	// 주문완료 후 쿠폰발행
	$gs = get_goods($gs_id[$i]);
	if(!$gs['use_aff'] && $config['sp_coupon'] && $mb_yes) {
		unset($tmp_coupon);
		$tmp_coupon = tbl_chk_coupon('1', $gs_id[$i]);

		if($tmp_coupon) {
			$wr_list_coupon = explode(",", $tmp_coupon);
			for($g=0; $g<count($wr_list_coupon); $g++) {
				if($wr_list_coupon[$g]) {
					$coupon = sql_fetch("select * from shop_coupon where cp_id='$wr_list_coupon[$g]'");
					tbl_publish_coupon($member['id'], $member['name']);
				}
			}
		}
	}
}

// 장바구니 주문완료 처리 (무통장, 포인트결제)
if(in_array($_POST['buymethod'],array('B','P')))
	$sql_isord = " , ct_select = '1' ";

$sql = "update shop_cart set odrkey = '$odrkey' $sql_isord where index_no IN ({$_POST['ss_cart_id']}) ";
sql_query($sql);

// 재고수량 감소
for($i=0; $i<count($ss_cart_id); $i++) {
	$ct = get_cart_id($ss_cart_id[$i]);
	$gs = get_goods($ct['gs_id']);

	if($ct['io_id']) {
		// 옵션 : 재고수량 감소	
		$sql_stock_qty = " io_id = '$ct[io_id]' and gs_id = '$ct[gs_id]' and io_type = '{$ct['io_type']}' ";
		$sql2 = " select io_id, gs_id, io_type, io_stock_qty from shop_goods_option where $sql_stock_qty ";
		$opt = sql_fetch($sql2);

		if($opt['io_stock_qty'] != '999999999') {
			$io_stock_qty = $opt['io_stock_qty'] - $ct['ct_qty'];
			sql_query("update shop_goods_option set io_stock_qty='$io_stock_qty' where $sql_stock_qty ");
		}	
	} else { 
		// 상품 : 재고수량 감소	 			
		if($gs['stock_mod']) {
			sql_query("update shop_goods set stock_qty = stock_qty - '{$ct['ct_qty']}' where index_no='$ct[gs_id]'");
		}
	}
}

// 포인트 결제시 차감
if($use_point > 0 && $mb_yes && in_array($_POST['buymethod'],array('B','P'))) {
	insert_point($mb_no, $use_point, "포인트결제-주문번호 : $odrkey", 1);
}

// 장바구니 session 삭제
set_session('ss_cart_id', '');

if(in_array($_POST['buymethod'],array('B','P'))) { // 무통장, 포인트전액결제
	goto_url(TW_SHOP_URL."/orderinquiryview.php?odrkey=$odrkey");
} else if($_POST['buymethod']=='K') { // 카카오페이
	goto_url(TW_SHOP_URL."/orderkakaopay.php?odrkey=$odrkey");
} else {
	if($_POST['settle_case'] == 'kcp') 		
		goto_url(TW_SHOP_URL."/orderkcp.php?odrkey=$odrkey");
	else if($_POST['settle_case'] == 'ini')
		goto_url(TW_SHOP_URL."/orderinicis.php?odrkey=$odrkey");
	else if($_POST['settle_case'] == 'all')
		goto_url(TW_SHOP_URL."/orderallthegate.php?odrkey=$odrkey");
}
?>