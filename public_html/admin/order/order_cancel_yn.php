<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$ca_uid = trim($_POST['ca_uid'][$k]);	

	$ca = sql_fetch(" select * from shop_order_cancel where ca_uid='$ca_uid' ");
	$od = sql_fetch(" select * from shop_order where index_no='$ca[ca_od_uid]' ");

	// PG 결제 취소
	$ca_logs = '';
	if($od['buymethod'] != 'B') {
		
		// 결제 캐쉬정보
		$cash = unserialize($od['cash_info']);
		if($cash['tid']) {
			// kcp
			if($cash['tpg'] == 'kcp') {
				
				// kcp에 경우 신용카드, 계좌이체, 계좌이체 에스크로만 부분취소 가능!
				if(in_array($od['buymethod'], array('C','R','ER'))) {

					set_session('ss_pay_method', $od['buymethod']);
					require ROOT_KCP.'/cfg/site_conf_inc.php';

					$_POST['tno'] = $cash['tid'];
					$_POST['req_tx'] = 'mod';
					if($_POST['ca_type'][$k] == '부분취소') {
						$_POST['mod_type'] = 'STPC'; // 부분취소
						$_POST['mod_mny'] = rpc($_POST['ca_mod_mny'][$k]); // 취소금액
						$_POST['rem_mny'] = rpc($_POST['ca_rem_mny'][$k]); // 결제금액
					} else {
						$_POST['mod_type'] = 'STSC'; // 일반취소
					}					
					$_POST['mod_desc'] = $ca['ca_cancel'].'-'.$ca['ca_memo'];

					// 에스크로 사용유무 (계좌이체 에스크로일 경우만 처리)
					if($cash['escw_yn'] == 'Y' && $_POST['ca_type'][$k] == '일반취소' && $od['buymethod'] == 'ER') {
						$_POST['req_tx'] = 'mod_escrow';
						$_POST['mod_type'] = 'STE2';
						
						// 가상계좌 에스크로
						/*
						if(in_array($od['buymethod'], array('S','ES'))) {
							$_POST['mod_type'] = 'STE5';
							$_POST['vcnt_yn'] = 'Y';
						}
						*/
					}	

					@include ROOT_KCP.'/pp_ax_hub_lib.php';
					@include ROOT_KCP.'/pp_ax_hub_cancel.php';
				}
			}

			// KG 이니시스
			if($cash['tpg'] == 'inicis' && $_POST['ca_type'][$k] == '일반취소') {	

				// 테스트 결제시
				if($default['cf_card_test_yn']) {
					if($cash['escw_yn'] == 'Y') {
						// 에스크로결제 테스트
						$_POST['mid'] = "iniescrow0";
					}
					else {
						// 일반결제 테스트
						$_POST['mid'] = "INIpayTest";
					}
				}
				// 실결제시
				else {
					if($cash['escw_yn'] == 'Y') {
						// 에스크로결제 테스트
						$_POST['mid'] = $default['cf_inicis_escrow_id'];
					}
					else {
						// 일반결제 테스트
						$_POST['mid'] = $default['cf_inicis_id'];
					}
				}

				$_POST['tid'] = $cash['tid'];
				$_POST['msg'] = $ca['ca_cancel'].'-'.$ca['ca_memo'];

				@include ROOT_INICIS . "/INIcancel.php";				
			}

			// KAKAOPAY
			if($cash['tpg'] == 'kakaopay') {

				$ca_type = $_POST['ca_type'][$k];
				$ca_mod_mny = rpc($_POST['ca_mod_mny'][$k]); // 취소금액
				$ca_rem_mny = rpc($_POST['ca_rem_mny'][$k]); // 결제금액
				$ca_cancel = $_POST['ca_cancel'][$k]; // 사유
				$ca_memo = $_POST['ca_memo'][$k]; // 상세사유
				$index_no = $ca['ca_od_uid'];

				include_once(ROOT_KAKAOPAY.'/orderpartcancel.inc.php');
			}
		}
	}

	// 주문 취소
	$updan = 7; 
	if($ca['ca_od_dan'] == '1') { 
		$updan = 8;  
	}

	$ca_uid = trim($_POST['ca_uid'][$k]);	

	$sql = "update shop_order_cancel 
	           set ca_yn = '1',
				   ca_yname = '$member[name]',
				   ca_ydate = '$time_ymdhis',
				   ca_logs = '$ca_logs'
			 where ca_uid = '$ca_uid'";
	sql_query($sql);

	unset($cash);
	$cash = array();
	$cash['ca_bankcd']	 = $_POST['ca_bankcd'][$k]; // 환불:은행명
	$cash['ca_banknum']	 = $_POST['ca_banknum'][$k]; // 환불:계좌번호
	$cash['ca_bankname'] = $_POST['ca_bankname'][$k]; // 환불:예금주
	$cash['ca_cancel']	 = $_POST['ca_cancel'][$k]; // 사유
	$cash['ca_memo']	 = $_POST['ca_memo'][$k]; // 상세사유
	$cash['ca_logs']	 = $ca_logs; // PG LOG
	$cash_ca_log		 = serialize($cash);

	// 취소
	$sql = "update shop_order 
			   set canceldate_s = '$time_ymd', 
				   cash_ca_log = '$cash_ca_log',
				   cancel_amt = '{$_POST['ca_mod_mny'][$k]}',
				   dan	= '$updan' 
			 where index_no = '$ca[ca_od_uid]'";
	sql_query($sql);

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
	$sql = " select * from shop_cart where orderno='$ca[ca_key]' ";
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
			$gs = sql_fetch(" select stock_mod from shop_goods where index_no='$ct[gs_id]' ");
			if($gs['stock_mod']) {
				sql_query("update shop_goods set stock_qty = stock_qty + '{$ct['ct_qty']}' where index_no='$ct[gs_id]'");
			}
		}
	}

	// 주문취소 회원의 포인트를 되돌려 줌
	if($od['use_point'] > 0 && $od['mb_yes']) {		
		$content = "포인트반환-일련번호 : $od[orderno] [사유:주문취소건]";
		insert_point($od['mb_no'], $od['use_point'], $content);
	}				

	// sms
	icode_order_sms_send($od['cellphone'], '5', $od['odrkey']);
}

goto_url("../order.php?$q1&page=$page");
?>
