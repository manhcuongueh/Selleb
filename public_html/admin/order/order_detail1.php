<?php
if(!defined('_TUBEWEB_')) exit;

$mode = $_POST['mode'];
$od	= get_order_no($index_no);
$mb	= get_member_no($od['mb_no']);

// 수령자 주소수정
if($mode=='w2') {
	check_demo();

	$sql = " update shop_order
			  set b_zip	= '{$_POST['b_zip']}',
				  b_addr1 = '{$_POST['b_addr1']}',
				  b_addr2 = '{$_POST['b_addr2']}',
				  b_addr3 = '{$_POST['b_addr3']}',
				  b_addr_jibeon = '{$_POST['b_addr_jibeon']}'
			where index_no = '$index_no'";
	sql_query($sql);

	goto_url("pop_order_detail.php?code=$code&index_no=$index_no");
}

// 송장번호 수정
if($mode=='w3') {
	check_demo();

	$sql = " update shop_order
				set delivery = '{$_POST['delivery']}',
					gonumber = '{$_POST['gonum']}'
			  where index_no = '$index_no' ";
	sql_query($sql);

	goto_url("pop_order_detail.php?code=$code&index_no=$index_no");
}

// 관리자메모
if($mode=='w10') {
	check_demo();

	$sql = "insert into shop_order_memo
				   ( order_no, amemo, wdate, writer, gs_se_id )
			VALUES ( '$index_no', '{$_POST['com_memo']}', '$server_time', '관리자', '$od[gs_se_id]')";
	sql_query($sql);

	goto_url("pop_order_detail.php?code=$code&index_no=$index_no");
}

// 관리자메모 삭제
if($_GET['mode']=='dell') {
	check_demo();

	sql_query("delete from shop_order_memo where index_no='$index_no'");

	goto_url("pop_order_detail.php?code=$code&index_no=$index_s");
}

// 주문접수 상태로 주문초기화
if($mode=='w4') {
	check_demo();

	$sql = " update shop_order set ";
	if($_POST['chdate'] == 'Y') {
		$sql .= " orderdate = '$server_time', orderdate_s = '$time_ymd',";
	}

	$sql .= " incomedate = '0',
			  incomedate_s = '',
			  shipdate = '0',
			  dan = '1',
			  gonumber = '',
			  delivery = '',
			  swapdate = '',
			  overdate_s = '',
			  returndate_s = '',
			  canceldate_s = '',
			  cancel_amt = '0',
			  cash_ca_log = '' ";
	$sql .= " where index_no = '$index_no'";
	sql_query($sql);

	// 주문취소요청 삭제
	sql_query("delete from shop_order_cancel where ca_od_uid = '$index_no'");

	goto_url("pop_order_detail.php?code=$code&index_no=$index_no");
}

// 단계변경
if($mode=='w1') {
	check_demo();

	$dan		= $_POST['dan'];
	$gonumber	= $_POST['gonumber'];
	$year1		= $_POST['year1'];
	$month1		= $_POST['month1'];
	$day1	    = $_POST['day1'];
	$set_date1	= $year1."-".$month1."-".$day1;

	$year2		= $_POST['year2'];
	$month2		= $_POST['month2'];
	$day2		= $_POST['day2'];
	$set_date2	= $year2."-".$month2."-".$day2;

	$year3		= $_POST['year3'];
	$month3		= $_POST['month3'];
	$day3		= $_POST['day3'];
	$set_date3	= $year3."-".$month3."-".$day3;
	$delivery	= $_POST['delivery'];

	$year4		= $_POST['year4'];
	$month4		= $_POST['month4'];
	$day4		= $_POST['day4'];
	$set_date4	= $year4."-".$month4."-".$day4;

	switch($dan) {
		case '2': // 입금확인
			$sql = " update shop_order
						set dan = '$dan',
							incomedate = '$server_time',
							incomedate_s = '$time_ymd'
					  where index_no = '$index_no' ";
			sql_query($sql);

			// sms
			if($od['buymethod'] == 'B')
				icode_order_sms_send($od['cellphone'], '3', $od['odrkey']);

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '3': // 배송준비중
			sql_query("update shop_order set dan = '3' where index_no = '$index_no'");

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '4': // 배송중
			$sql = "update shop_order
					   set shipdate = '$server_time',
						   gonumber = '$gonumber',
						   delivery = '$delivery',
						   dan = '4'
					 where index_no = '$index_no'";
			sql_query($sql);

			// sms
			icode_order_sms_send($od['cellphone'], '4', $od['odrkey']);

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '5': // 배송완료
			if($od['dan'] != '4')
				alert('배송중 단계에서만 배송완료로 가능합니다.');

			// sms
			icode_order_sms_send($od['cellphone'], '6', $od['odrkey']);

			// 배송완료 날짜 변경
			sql_query(" update shop_order set overdate_s = '$set_date1', dan = '5' where index_no = '$index_no'" );

			// 장바구니 검사
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$result = sql_query($sql);
			for($i=0; $ct=sql_fetch_array($result); $i++) {

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
					for($i=0; $i<count($wr_list_coupon); $i++) {
						if($wr_list_coupon[$i]) {
							$coupon = sql_fetch("select * from shop_coupon where cp_id='$wr_list_coupon[$i]'");
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

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '6': // 반품처리
			if($od['dan'] != '5')
				alert('배송이 완료된 주문만 반품이 가능합니다.');

			sql_query("update shop_order set returndate_s='$set_date2', dan='6' where index_no='$index_no'");

			// 판매수수료 환수처리
			$sql = "select * from shop_partner_paylog where etc1='$od[orderno]' and etc2='shop'";
			$result = sql_query($sql);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$sql_certify = " income=income-$row[in_money], total=total-$row[in_money], p_shop=p_shop-$row[in_money] ";

				if($config['p_type'] == 'month') { // 월
					$sql_search = " where month_date='$row[month_date]' ";
				} else { // 주,실시간
					$sql_search = " where ju_date='$row[ju_date]' ";
				}

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
			for($i=0; $ct=sql_fetch_array($result); $i++) {

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
			for($i=0; $ct=sql_fetch_array($result); $i++) {
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

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '7': // 취소처리
			if($od['dan']>='5')
			{  alert('배송이 완료된 상품은 취소할수 없습니다.');  }
			else if($od['dan']=='1')
			{  $updan = 8;  }
			else
			{  $updan = 7;  }

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

			// PG 결제 취소
			$ca_logs = '';
			if($od['buymethod'] != 'B') {

				// 결제 캐쉬정보
				$cash = "";
				$cash = unserialize($od['cash_info']);
				if($cash['tid']) {

					// 가맹점 상품일경우 설정값을 교체!
					if($cash['ss_pg_id'] && $cash['ss_pg_id'] != 'admin') {
						$info = sql_fetch("select * from shop_partner where mb_id = '$cash[ss_pg_id]'");
						if($info['mb_id']) {
							$default['cf_card_test_yn'] = $info['cf_card_test_yn'];
							$default['cf_tax_flag_use'] = $info['cf_tax_flag_use'];
							$default['cf_escrow_yn'] = $info['cf_escrow_yn'];
							$default['cf_kcp_id'] = $info['cf_kcp_id'];
							$default['cf_kcp_key'] = $info['cf_kcp_key'];
							$default['cf_nm_pg'] = $info['cf_nm_pg'];
							$default['cf_kcp_quota'] = $info['cf_kcp_quota'];
							$default['cf_kcp_noint_yn'] = $info['cf_kcp_noint_yn'];
							$default['cf_kcp_noint_mt'] = $info['cf_kcp_noint_mt'];
							$default['cf_kcp_tax_yn'] = $info['cf_kcp_tax_yn'];
							$default['cf_inicis_escrow_id'] = $info['cf_inicis_escrow_id'];
							$default['cf_inicis_id'] = $info['cf_inicis_id'];
							$default['de_kakaopay_mid'] = $info['de_kakaopay_mid'];
							$default['de_kakaopay_key'] = $info['de_kakaopay_key'];
							$default['de_kakaopay_enckey'] = $info['de_kakaopay_enckey'];
							$default['de_kakaopay_hashkey'] = $info['de_kakaopay_hashkey'];
							$default['de_kakaopay_cancelpwd'] = $info['de_kakaopay_cancelpwd'];
						}
					}

					// kcp
					if($cash['tpg'] == 'kcp') {

						// kcp에 경우 신용카드, 계좌이체만 부분취소 가능!
						if(in_array($od['buymethod'], array('C','R'))) {

							set_session('ss_pay_method', $od['buymethod']);
							require ROOT_KCP.'/cfg/site_conf_inc.php';

							$_POST['tno'] = $cash['tid'];
							$_POST['req_tx'] = 'mod';
							if($ca_type == '부분취소') {
								$_POST['mod_type'] = 'STPC'; // 부분취소
								$_POST['mod_mny'] = (int)$ca_mod_mny; // 취소금액
								$_POST['rem_mny'] = (int)$ca_rem_mny; // 결제금액
							} else {
								$_POST['mod_type'] = 'STSC'; // 일반취소
							}
							$_POST['mod_desc'] = $ca_cancel.'-'.$ca_memo;

							// 에스크로 사용유무 (계좌이체 에스크로일 경우만 처리)
							if($cash['escw_yn'] == 'Y') {
								if($ca_type == '일반취소' && $od['buymethod'] == 'R') {
									$_POST['req_tx'] = 'mod_escrow';
									$_POST['mod_type'] = 'STE2';
								}
							}

							include_once(ROOT_KCP.'/pp_ax_hub_lib.php');
							include_once(ROOT_KCP.'/pp_ax_hub_cancel.php');
						}
					}

					// KG 이니시스
					if($cash['tpg'] == 'inicis' && $ca_type == '일반취소') {
						include_once(ROOT_INICIS.'/INIcancel.php');
					}

					// KAKAOPAY
					if($cash['tpg'] == 'kakaopay') {
						include_once(ROOT_KAKAOPAY.'/orderpartcancel.inc.php');
					}
				}
			}

			unset($cash);
			$cash = array();
			$cash['ca_bankcd'] = $_POST['ca_bankcd']; // 환불:은행명
			$cash['ca_banknum'] = $_POST['ca_banknum']; // 환불:계좌번호
			$cash['ca_bankname'] = $_POST['ca_bankname']; // 환불:예금주
			$cash['ca_cancel'] = $_POST['ca_cancel']; // 사유
			$cash['ca_memo'] = $_POST['ca_memo']; // 상세사유
			$cash['ca_logs'] = $ca_logs; // PG LOG
			$cash_ca_log = serialize($cash);

			// 취소
			$sql = "update shop_order
					   set canceldate_s = '$set_date3',
						   cash_ca_log = '$cash_ca_log',
						   cancel_amt = '{$_POST['ca_mod_mny']}',
						   dan = '$updan'
					 where index_no = '$index_no'";
			sql_query($sql);

			// 재고수량 되돌리기
			$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
			$result = sql_query($sql);
			for($i=0; $ct=sql_fetch_array($result); $i++) {
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

			// sms
			icode_order_sms_send($od['cellphone'], '5', $od['odrkey']);

			$msg = "정상적으로 처리 되었습니다";
			break;
		case '10': //교환처리
			if($od['dan']!='5') {
				alert('배송이 완료된 주문만 교환 가능합니다.');
			}

			$sql = "update shop_order set dan = '$dan', swapdate	= '$set_date4' where index_no = '$index_no'";
			sql_query($sql);

			$msg = "정상적으로 처리 되었습니다";
			break;
	}

	goto_url("pop_order_detail.php?code=$code&index_no=$index_no");
}
?>

<script language="javascript">
function changeform_submit(f) {

	if(!f.dan.value) {
		alert('변경할 단계를 선택하세요.');
		f.dan.focus();
		return false;
	}

	if(f.dan.value=='4') {
		if(!f.delivery.value) {
			alert('배송사를 선택하세요.');
			f.delivery.focus();
			return false;
		}

		if(!f.gonumber.value) {
			alert('송장번호를 입력하세요.');
			f.gonumber.focus();
			return false;
		}
	}

	if(f.dan.value=='7') {
		if(!f.ca_cancel.value) {
			alert('사유를 선택해 주십시오.');
			f.ca_cancel.focus();
			return false;
		}

		if(!f.ca_memo.value) {
			alert('상세사유를 입력해 주십시오.');
			f.ca_memo.focus();
			return false;
		}
	}

	answer = confirm('단계를 변경합니다 맞습니까?');
	if(answer==true)
	{  return true;  }
	else
	{  return false;  }
}

function foch2( nn ) {
	var chk = nn.value;
	if( chk == "" ){
		alert('내용을 입력하세요.');
		nn.focus();
		return false;
	}
}

function goback(){
	document.changeform.mode.value = 'w4';
	if(confirm("주문접수 상태로 변경하시겠습니까?") == true){
	    document.changeform.submit();
	} else {   //취소
		return;
	}
}

function ADD_MOD(){
	document.addform.mode.value = 'w2';
	if(confirm("주소를 수정 하시겠습니까?") == true){
	    document.addform.submit();
	} else {   //취소
		return false;
	}
}
</script>

<h2>
	주문상세내역
	<span class="btn_wrap"><a href="javascript:win_open('./order/order_print.php?index_no=<?php echo $index_no; ?>','pop_print','670','600','yes');" class="btn_small blue">거래명세표 인쇄</a></span>
</h2>

<?php
// 결제 캐쉬정보
$cash = "";
$cash = unserialize($od['cash_info']);

switch($od['buymethod']) {
	case 'K' : // 카카오페이
	case 'C' : // 신용카드
	case 'ER' : // 에스크로 계좌이체
	case 'R' : // 실시간 계좌이체
	case 'H' : // 휴대폰결제
		$appname = "승인시간";
		$receipt = "{$cash[appldate]}";
		if($cash['applnum']) $receipt .= " (승인번호 : {$cash[applnum]})";
		break;
	case 'ES' : // 에스크로 가상계좌
	case 'S' : // 가상계좌
		$appname  = "계좌발급";
		$receipt  = "계좌번호 : {$cash[vact_num]}";
		if($cash['vact_name']) $receipt .= " / 예금주명 : {$cash[vact_name]} ";
		$receipt .= "<p class='padt7'>";
		if($cash['vact_bankcode']) $receipt .= "은행명(코드) : {$cash[vact_bankcode]}";
		if($cash['vact_date']) $receipt .= ", 입금마감시간 : {$cash[vact_date]}";
		if($cash['vact_inputname']) $receipt .= ", 입금자명 : {$cash[vact_inputname]}";
		$receipt .= "</p>";
		break;
	case 'B' : // 무통장결제
		$appname  = "계좌정보";
		$receipt  = "계좌번호 : {$od[bank]} / 입금예정일 : {$od[indate]}";
		break;
}

$gs = get_order_goods($od['orderno']);
$coupon	= sql_fetch("select * from shop_coupon_log where od_id='$od[orderno]' and lo_id='$od[dc_exp_lo_id]'");
$sr = sql_fetch("select * from shop_seller where sup_code='$od[gs_se_id]'");
$pt = get_member($od['pt_id']);

$log = sql_fetch("select * from shop_partner_paylog where etc1='$od[orderno]' and etc2='shop' ");

$is_supply = false;
if(substr($od['gs_se_id'],0,3) == 'AP-')
	$is_supply = true;

$is_aff = false;
if($gs['use_aff'])
	$is_aff = true;

$sql = " select * from shop_cart where orderno = '$od[orderno]' ";
$sql.= " group by gs_id order by io_type asc, index_no asc ";
$res = sql_query($sql);
for($k=0; $ct=sql_fetch_array($res); $k++) {
	$mny = (int)$gs['daccount'];

	// 합계금액 계산
	$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + {$mny}) * ct_qty))) as mny,
					SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
					SUM(IF(io_type = 1, (0),(ct_qty))) as qty
			   from shop_cart
			  where gs_id = '$ct[gs_id]'
				and odrkey = '$ct[odrkey]' ";
	$sum = sql_fetch($sql);

	unset($it_name);
	$it_options = print_complete_options($ct['gs_id'], $ct['odrkey']);
	if($it_options && $ct['io_id']){
		$it_name = '<div class="sod_opt">'.$it_options.'</div>';
	}
?>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="100px">
		<col width="60px">
		<col>
		<col width="100px">
		<col width="80px">
		<col width="60px">
		<col width="100px">
	</colgroup>
	<thead>
	<tr>
		<th>상품코드</th>
		<th>이미지</th>
		<th>주문 상품정보</th>
		<th>상품금액</th>
		<th>배송비</th>
		<th>수량</th>
		<th>소계</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo $gs['gcode']; ?></td>
		<td><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $ct['gs_id']; ?>" target="_blnak"><?php echo get_od_image($ct['odrkey'], $gs['simg1'], 40, 40); ?></a></td>
		<td class="tal">
			<?php echo $gs['gname']; ?>
			<?php echo $it_name;?>
		</td>
		<td><?php echo number_format($od['account']); ?></td>
		<td><?php echo number_format($od['del_account']); ?></td>
		<td><?php echo number_format($sum['qty']); ?></td>
		<td><?php echo number_format($od['account']+$od['del_account']); ?></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="tbl_frm02 mart10">
	<table>
	<colgroup>
		<col>
		<col width="100px">
		<col width="150px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td rowspan="<?php echo ($is_supply)?'7':'6'; ?>">
			<p>주문번호 : <b class="fc_red"><?php echo $od['odrkey']; ?></b></p>
			<p class="mart7">일련번호 : <b class="fc_197"><?php echo $od['orderno']; ?></b></p>
			<p class="mart7">주문날짜 : <?php echo date("Y.m.d H:i:s",$od['orderdate']); ?></p>
			<p class="mart7">결제방식 : <?php echo $ar_method[$od['buymethod']]; ?></p>
			<?php if($appname && $receipt) { ?>
			<p class="mart7"><?php echo $appname; ?> : <?php echo $receipt; ?></p>
			<?php } ?>
			<p class="mart7">주문자명 : <?php echo $od['name']; ?></p>
			<?php if($config['sp_coupon']) { ?>
			<p class="mart7">할인쿠폰 : <?php echo $coupon['cp_subject'] ? $coupon['cp_subject']:'미사용'; ?></p>
			<?php } ?>
		</td>
		<th>적립포인트</th>
		<td class="tar"><?php echo number_format($sum['point']); ?> P</td>
	</tr>
	<tr>
		<th>배송비</th>
		<td class="tar"><?php echo number_format($od['del_account']); ?> 원</td>
	</tr>
	<tr>
		<th>쿠폰할인</th>
		<td class="tar">(-) <?php echo number_format($od['dc_exp_amt']); ?> 원</td>
	</tr>
	<tr>
		<th>포인트결제</th>
		<td class="tar">(-) <?php echo number_format($od['use_point']); ?> 원</td>
	</tr>
	<tr>
		<th>실결제금액</th>
		<td class="tar fc_red bold"><?php echo number_format($od['use_account']); ?> 원</td>
	</tr>
	<tr>
		<th>총계</th>
		<td class="tar bold"><?php echo number_format($od['account']+$od['del_account']); ?> 원</td>
	</tr>
	<?php if($is_supply) { ?>
	<tr>
		<th>공급가격</th>
		<td class="tar bold"><font color='blue'><?php echo number_format($sum['mny']); ?> 원</font></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>

	<table class="mart10">
	<colgroup>
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>주문메모</th>
		<td colspan="5"><?php echo ($od['memo'])?nl2br($od['memo']):""; ?></td>
	</tr>
	<?php if($is_supply || $od['gs_se_id'] == 'admin') { ?>
	<tr>
		<th>판매처</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"본사":"가맹점"; ?></td>
		<th>가맹점ID</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"admin":$od['pt_id']; ?></td>
		<th>가맹점회원명</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"관리자":$pt['name']; ?></td>
	</tr>
	<?php if($log['index_no'] && $config['p_shop'] == 'y') { ?>
	<tr>
		<th>적립유형</th>
		<td colspan="5">
			<?php
			if($gs['money_type'])
				echo '개별설정으로 적립';
			else
				echo '공통설정으로 적립';
			?>
		</td>
	</tr>
	<tr>
		<th>적립로그</th>
		<td colspan="5"><?php echo $log['memo']; ?> <span class="fc_red">(적립일 : <?php echo date("Y-m-d H:i:s", $log['wdate']); ?>)</span></td>
	</tr>
	<tr>
		<th>상세로그</th>
		<td colspan="5"><?php echo $log['etc3']; ?></td>
	</tr>
	<?php }
	}
	?>
	</tbody>
	</table>

	<?php
	if($od['cash_ca_log'] && in_array($od['dan'], array('7','8'))) {
		$cash = "";
		$cash = unserialize($od['cash_ca_log']);
	?>
	<table class="mart10">
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>환불계좌</th>
		<td>
			<?php
			$ca_bank = "";
			if($cash['ca_bankcd'])
				$ca_bank .= "은행명 : ".$cash['ca_bankcd'].", ";
			if($cash['ca_banknum'])
				$ca_bank .= "계좌번호 : ".$cash['ca_banknum'].", ";
			if($cash['ca_bankname'])
				$ca_bank .= "예금주 : ".$cash['ca_bankname'];

			if($ca_bank)
				echo $ca_bank;
			else
				echo "계좌정보 미등록";
			?>
		</td>
	</tr>
	<?php if($od['cancel_amt'] > 0) { ?>
	<tr>
		<th>취소금액</th>
		<td><?php echo number_format($od['cancel_amt']); ?> 원</td>
	</tr>
	<?php } ?>
	<tr>
		<th>사유</th>
		<td><?php echo $cash['ca_cancel']; ?></td>
	</tr>
	<tr>
		<th>상세사유</th>
		<td><?php echo $cash['ca_memo']; ?></td>
	</tr>
	<?php if($cash['ca_logs']) { ?>
	<tr>
		<th>PG LOG</th>
		<td><?php echo $cash['ca_logs']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>

<?php
}

// 배송추적 값이 없을때
$baesong = "";
$delivery = explode('|', $od['delivery']);
if(!$delivery[1]) {
	$baesong .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\" class=\"btn_ssmall grey\">";
} else {
	$baesong .= "<a href='javascript://' onclick=\"openwindow('".$delivery[1].$od['gonumber']."','open','600','650','yes');return false\" class=\"btn_ssmall grey\">";
}
$baesong .= "배송추적</a>";

// 취소관련 정보
$row = sql_fetch(" select count(*) as cnt from shop_order where odrkey='$od[odrkey]' ");
if($row['cnt'] > 1) {
	$ca_type = "부분취소"; // 부분취소
} else {
	$ca_type = "일반취소"; // 일반취소
}

// 결제금액, 취소금액 합산
$sql = " select SUM(use_account) as us_amt,
				SUM(cancel_amt) as ca_amt
		   from shop_order
		   where odrkey = '$od[odrkey]' ";
$sum = sql_fetch($sql);
$ca_rem_mny = $sum['us_amt'] - $sum['ca_amt'];

// 주문취소 및 취소요청건중 배송비 합산
$sql = " select SUM(del_account) as de_amt
		   from shop_order
		   where odrkey = '$od[odrkey]'
			 and dan in('7','8','9') ";
$cum = sql_fetch($sql);

// 주문취소 및 취소요청건 카운팅
$sql = " select count(*) as cnt from shop_order where odrkey='$od[odrkey]' and dan in('7','8','9') ";
$can = sql_fetch($sql);

// 취소요청건 카운팅
$sql = " select SUM(del_account) as de_amt
		   from shop_order
		   where odrkey = '$od[odrkey]'
			 and dan in('9') ";
$cyn = sql_fetch($sql);

// 동일주문건 - 취소건 및 취소요청건
$tcnt = $row['cnt'] - $can['cnt'];

// 동일주문건이 1보다 크고 현재 주문건에 배송비가 있다면 배송비를 차감한다.
// 배송비를 먼저 차감해버리면 손실이기때문에...
$ca_mod_txt = "";
$ca_mod_mny = $od['use_account'];
if($cyn['de_amt'] == 0) {
	if($tcnt > 1 && $od['del_account'] > 0) {
		$ca_mod_mny = $od['use_account'] - $od['del_account'];
		$ca_mod_txt = "<span class='fc_red bold'>현재 주문건에 배송비가 존재하므로 배송비는 차감하지 않습니다.</span>";
	}
	// 마지막 주문건이고 주문취소건중 배송비가 있고 현주문건에 배송비가 없을때 취소주문건 배송비를 더한다.
	// 마지막 배송비이기때문에 배송비를 더해야 한다.
	else if($tcnt == 1 && $cum['de_amt'] > 0 && $od['del_account'] == 0) {
		$ca_mod_mny = $od['use_account'] + $cum['de_amt'];
		$ca_mod_txt = "<span class='fc_red bold'>마지막 취소건이며 동일 주문건에 배송비가 존재하므로 배송비를 합산하여 취소 됩니다.</span>";
	}
}
?>
<form name="changeform" action="pop_order_detail.php" method="post" onsubmit="return changeform_submit(this);">
<input type="hidden" name="code" value="A">
<input type="hidden" name="mode" value="w1">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="ca_mod_mny" value="<?php echo $ca_mod_mny; ?>">
<input type="hidden" name="ca_rem_mny" value="<?php echo $ca_rem_mny; ?>">
<input type="hidden" name="ca_type" value="<?php echo $ca_type; ?>">
<div class="tbl_frm02 mart10 od_chk od_dan_7_fld">
	<table>
	<colgroup>
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>환불계좌은행</th>
		<td><?php echo get_bank_select("ca_bankcd", "class='wfull'"); ?></td>
		<th>계좌번호</th>
		<td><input type="text" name="ca_banknum" class="frm_input"></td>
		<th>예금주명</th>
		<td><input type="text" name="ca_bankname" class="frm_input"></td>
	</tr>
	<tr>
		<th>사유</th>
		<td><?php echo get_cancel_select("ca_cancel","class='wfull'"); ?></td>
		<th>취소금액</th>
		<td><?php echo number_format($ca_mod_mny); ?>원</td>
		<th>취소일자</th>
		<td>
			<input type="text" name="year3" value="<?php echo $time_year; ?>" class="frm_input w50"> 년
			<input type="text" name="month3" value="<?php echo $time_month; ?>" class="frm_input w30 marl5"> 월
			<input type="text" name="day3" value="<?php echo $time_day; ?>" class="frm_input w30 marl5"> 일
		</td>
	</tr>
	<?php if($ca_mod_txt) { ?>
	<tr>
		<th>참고사항</th>
		<td colspan="5"><?php echo $ca_mod_txt; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th>상세사유</th>
		<td colspan="5"><textarea name="ca_memo" class="frm_textbox wfull"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="local_frm01 mart20">
	<?php if(in_array($od['dan'], array('7','8','9'))) { ?>
	<input type="checkbox" name="chdate" id="chdate" value="Y">
	<label for="chdate">오늘날짜를 기준으로</label>
	<a href="javascript:goback();" class="btn_small bx-white marl10">주문접수단계로 변경</a>
	<?php } ?>

	<div class="tar">
		<p class="od_chk od_dan_4_fld">
			<strong>송장번호 : </strong>
			<?php echo get_sorts_conf_select('delivery', $is_aff); ?>
			<input type="text" name="gonumber" class="frm_input">
		</p>
		<p class="od_chk od_dan_5_fld">
			<strong>배송완료일 : </strong>
			<input type="text" name="year1" value="<?php echo $time_year; ?>" class="frm_input w50"> 년
			<input type="text" name="month1" value="<?php echo $time_month; ?>" class="frm_input w30 marl5"> 월
			<input type="text" name="day1" value="<?php echo $time_day; ?>" class="frm_input w30 marl5"> 일
		</p>
		<p class="od_chk od_dan_6_fld">
			<strong>반품일 : </strong>
			<input type="text" name="year2" value="<?php echo $time_year; ?>" class="frm_input w50"> 년
			<input type="text" name="month2" value="<?php echo $time_month; ?>" class="frm_input w30 marl5"> 월
			<input type="text" name="day2" value="<?php echo $time_day; ?>" class="frm_input w30 marl5"> 일
		</p>
		<p class="od_chk od_dan_10_fld">
			<strong>교환일 : </strong>
			<input type="text" name="year4" value="<?php echo $time_year; ?>" class="frm_input w50"> 년
			<input type="text" name="month4" value="<?php echo $time_month; ?>" class="frm_input w30 marl5"> 월
			<input type="text" name="day4" value="<?php echo $time_day; ?>" class="frm_input w30 marl5"> 일
		</p>

		<b>※ 주문상태 : </b>
		<b class="fc_197 marr10"><?php echo $ar_dan[$od['dan']]; ?></b>
		<select name="dan">
			<option value=''>다음단계로 이동</option>
			<?php
			for($i=1; $i<=10; $i++) {
				if(!in_array($i, array('8','9'))) {
					if($od['dan'] < $i) {
						if(in_array($od['dan'], array('7','8','9')))
							break;

						$obj_dan = explode("(", $ar_dan[$i]);
						echo "<option value='{$i}'>{$obj_dan[0]}</option>\n";
					}
				}
			}
			?>
		</select>
		<input type="submit" value="단계변경" class="btn_small grey">
	</div>
</div>
</form>

<script>
$(function() {
	$(".od_chk").hide();
	$("select[name=dan]").on("change", function() {
		var val = $(this).val();
		$(".od_chk").hide();
		$(".od_dan_"+val+"_fld").show();				
    });
});
</script>

<?php if($is_supply) { ?>
<div class="tbl_frm02 marb10">
	<table>
	<colgroup>
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col width="190px">
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>업체구분</th>
		<td>공급업체</td>
		<th>업체</th>
		<td><?php echo $sr['in_compay']; ?></td>
		<th>업체ID</th>
		<td><?php echo $sr['mb_id']; ?><?php echo ($sr['sup_code'])?" (".$sr['sup_code'].")":""; ?></td>
	</tr>
	<tr>
		<th>담당자명</th>
		<td><?php echo $sr['in_dam']; ?></td>
		<th>담당자핸드폰</th>
		<td><?php echo replace_tel($sr['n_phone']); ?></td>
		<th>담당자이메일</th>
		<td><?php echo $sr['n_email']; ?></td>
	</tr>
	</tbody>
	</table>
</div>
<?php } ?>

<?php if($od['taxbill_yes']!='N' or $od['taxsave_yes']!='N') { ?>
<div class="tbl_head01 marb10">
	<table>
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<thead>
	<tr>
		<th>세금계산서 발행요청</th>
		<th>현금영수증 발행요청</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="tal">
			<?php if($od['taxbill_yes']=='Y') { ?>
			- 사업자등록번호 : <?php echo $od['company_saupja_no']; ?><br>
			- 상호(법인명) : <?php echo $od['company_name']; ?><br>
			- 대표자명 : <?php echo $od['company_owner']; ?><br>
			- 사업장소재지 : <?php echo $od['company_addr']; ?><br>
			- 업태/종목 : <?php echo $od['company_item']; ?> / <?php echo $od['company_service']; ?>
			<?php } ?>
		</td>
		<td class="tal">
			<?php if($od['taxsave_yes']=='S') { ?>
			- 사업자 지출증빙용<br>
			- 사업자등록번호 : <?php echo $od['tax_saupja_no']; ?>
			<?php } ?>
			<?php if($od['taxsave_yes']=='Y') { ?>
			- 개인 소득공제용<br>
			- 핸드폰 : <?php echo $od['tax_hp']; ?>
			<?php } ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<?php } ?>

<div class="tbl_head01 marb10">
	<form name="abc" action="pop_order_detail.php" method="post">
	<input type="hidden" name="code" value="A">
	<input type="hidden" name="mode" value="w3">
	<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
	<table>
	<colgroup>
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th>입금확인일</th>
		<th>배송시작일</th>
		<th>배송완료일</th>
		<th>취소날짜</th>
		<th>반품날짜</th>
		<th>교환날짜</th>
		<th>배송사 [송장번호] <?php echo $baesong; ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo $od['incomedate_s']; ?></td>
		<td><?php if($od['shipdate'] > 0){ echo date("Y-m-d",$od['shipdate']); } ?></td>
		<td><?php echo $od['overdate_s']; ?></td>
		<td><?php echo $od['canceldate_s']; ?></td>
		<td><?php echo $od['returndate_s']; ?></td>
		<td><?php echo $od['swapdate']; ?></td>
		<td>
			<?php
			if($od['delivery']) {
				$od_delivery = explode('|', $od['delivery']);
			?>
			<?php echo get_sorts_conf_select('delivery', $is_aff); ?>
			<input type="text" name="gonum" value="<?php echo $od['gonumber']; ?>" class="frm_input w110">
			<input type="submit" value="송장번호수정" class="btn_small bx-white">
			<script>document.abc.delivery.value = '<?php echo $od[delivery]; ?>';</script>
			<?php } ?>
		</td>
	</tr>
	</tbody>
	</table>
	</form>
</div>

<?php
// 총 구매건수
$sql_search = " where mb_no='$od[mb_no]' and dan in('1','2','3','4','5') ";
$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
$buy_cnt = $sql_market['cnt'];

//총 결제금액
$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
$buy_amt = $sum['amt']+$sum['del_amt'];

//총 반품건수
$sql_search = " where mb_no='$od[mb_no]' and dan in('6') ";
$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
$bak_cnt = $sql_market['cnt'];

//총 반품금액
$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
$bak_amt = $sum['amt']+$sum['del_amt'];

//총 주문취소 건수
$sql_search = " where mb_no='$od[mb_no]' and dan in('7','8') ";
$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
$can_cnt = $sql_market['cnt'];

//총 취소금액
$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
$can_amt = $sum['amt']+$sum['del_amt'];
?>
<div class="tbl_frm02 mart30">
	<div class="half_bx">
		<h4 class="fs14 marb7">주문자</h4>
		<table class="marb10">
		<colgroup>
			<col width="100px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>이름</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th>핸드폰</th>
			<td>
				<?php echo $od['cellphone']; ?>
				<a href="/admin/sms/sms_user.php?ph=<?php echo conv_number($od['cellphone']);?>" onclick="openwindow(this,'sms_user','245','360','no');return false" class="btn_ssmall blue marl10">문자전송</a>
			</td>
		</tr>
		<tr>
			<th>전화번호</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th>E-Mail</th>
			<td><?php echo $od['email']; ?></td>
		</tr>
		<tr>
			<th>전체 구매</th>
			<td><?php echo number_format($buy_amt); ?> 원 (<?php echo number_format($buy_cnt); ?>건) </td>
		</tr>
		<tr>
			<th>전체 반품</th>
			<td><?php echo number_format($bak_amt); ?> 원 (<?php echo number_format($bak_cnt); ?>건)</td>
		</tr>
		<tr>
			<th>전체 취소</th>
			<td><?php echo number_format($can_amt); ?> 원 (<?php echo number_format($can_cnt); ?>건)</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="half_bx">
		<h4 class="fs14 marb7">수령자</h4>
		<form name="addform" action="pop_order_detail.php" method="post" onsubmit="return ADD_MOD();">
		<input type="hidden" name="code" value="A">
		<input type="hidden" name="mode" value="w2">
		<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
		<table class="marb10">
		<colgroup>
			<col width="100px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>이름</td>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th>핸드폰</td>
			<td>
				<?php echo $od['b_cellphone']; ?>
				<a href="/admin/sms/sms_user.php?ph=<?php echo conv_number($od['b_cellphone']);?>" onclick="openwindow(this,'sms_user','245','360','no');return false" class="btn_ssmall blue marl10">문자전송</a>
			</td>
		</tr>
		<tr>
			<th>전화번호</td>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr height="156px">
			<th>주소</td>
			<td>
				<p><input type="text" name="b_zip" value="<?php echo $od['b_zip']; ?>" class="frm_input w60" maxLength="5"> <a href="javascript:win_zip('addform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소찾기</a> <input type="submit" value="주소수정" class="btn_small"></p>
				<p class="mart5"><input type="text" name='b_addr1' value="<?php echo $od['b_addr1']; ?>" class="frm_input wfull"></p>
				<p class="mart5"><input type="text" name='b_addr2' value="<?php echo $od['b_addr2']; ?>" class="frm_input w200"> ※ 상세주소</p>
				<p class="mart5"><input type="text" name='b_addr3' value="<?php echo $od['b_addr3']; ?>" class="frm_input w200"> ※ 참고항목
				<input type="hidden" name="b_addr_jibeon" value="<?php echo $od['b_addr_jibeon']; ?>"></p>
			</td>
		</tr>
		</tbody>
		</table>
		</form>
	</div>
</div>

<div class="tbl_frm02">
	<form name="memo" action="pop_order_detail.php" method="post" onsubmit="return foch2(com_memo);">
	<input type="hidden" name="code" value="A">
	<input type="hidden" name="mode" value="w10">
	<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
	<table>
	<colgroup>
		<col width="100px">
		<col>
		<col width="155px">
	</colgroup>
	<tbody>
	<tr>
		<th>내용입력</th>
		<td><textarea name="com_memo" class="frm_textbox wfull"></textarea></td>
		<td><input type="submit" value="메시지 등록하기" class="btn_medium"></td>
	</tr>
	</tbody>
	</table>
	</form>
</div>

<div class="tbl_head01 mart10">
	<table>
	<colgroup>
		<col width="50px">
		<col width="90px">
		<col>
		<col width="90px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th>번호</th>
		<th>작성일</th>
		<th>메모내용</th>
		<th>작성자</th>
		<th>삭제</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql = "select count(*) from shop_order_memo where order_no='$index_no'";
	$res = sql_query($sql);
	$total_record = mysql_result($res,0,0);
	mysql_free_result($res);

	$sql = "select * from shop_order_memo where order_no='$index_no' order by wdate desc";
	$res = sql_query($sql);
	$num = $total_record;
	while($row=sql_fetch_array($res)) {
	?>
	<tr>
		 <td><?php echo $num--; ?></td>
		 <td><?php echo date("Y-m-d",$row['wdate']); ?></td>
		 <td class="tal"><?php echo $row['amemo']; ?></td>
		 <td><?php echo $row['writer']; ?></td>
		 <td><input type="button" value="삭제" onclick="if(confirm('삭제 하시겠습니까?')){location.href='<?php echo $_SERVER['SCRIPT_NAME']; ?>?code=A&mode=dell&index_s=<?php echo $index_no; ?>&index_no=<?php echo $row['index_no']; ?>';}" class="btn_ssmall red"></td>
	</tr>
	<?php 
	}
	if($total_record==0) {
	?>
	<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>
	<?php } ?>
	</tbody>
	</table>
</div>