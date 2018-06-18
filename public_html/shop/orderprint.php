<?php
include_once("./_common.php");

$gw_head_title = '주문내역인쇄';
include_once(TW_PATH."/head.sub.php");

$od = get_order($odrkey);

// 결제 캐쉬정보
$cash = unserialize($od['cash_info']);

$appname = ""; 
$receipt = "";
switch($od['buymethod']) {
	case 'K' : // 카카오페이
	case 'C' : // 신용카드
	case 'ER' : // 에스크로 계좌이체
	case 'R' : // 실시간 계좌이체
	case 'H' : // 휴대폰결제							
		if($cash['appldate']) {
			$appname .= "승인시간"; 
			$receipt .= "{$cash[appldate]}";
			if($cash['applnum']) $receipt .= " (승인번호 : {$cash[applnum]})";
		}
		break;
	case 'ES' : // 에스크로가상계좌
	case 'S' : // 가상계좌
		$appname  = "가상계좌정보";
		$receipt  = "계좌번호 : {$cash[vact_num]}";
		if($cash['vact_name']) $receipt .= "<br>예금주명 : {$cash[vact_name]} ";
		if($cash['vact_bankcode']) $receipt .= "<br>은행명(코드) : {$cash[vact_bankcode]}";
		if($cash['vact_date']) $receipt .= "<br>입금마감시간 : {$cash[vact_date]}";
		if($cash['vact_inputname']) $receipt .= "<br>입금자명 : {$cash[vact_inputname]}";	
		break; 
	case 'B' : // 무통장결제
		$appname  = "입금계좌정보"; 
		$receipt  = "계좌번호 : {$od[bank]}<br>입금예정일 : {$od[indate]}";
		break;
}
?>

<div style="margin:10px">
	<div class="tbl_head02">
		<table class="wfull">
		<colgroup>
			<col>
			<col width="100">
			<col width="60">
			<col width="80">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">주문 상품정보</th>
			<th>상품금액</th>
			<th>수량</th>
			<th>배송비</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = " select * 
				   from shop_order
				  where mb_no='$mb_no' 
				    and odrkey='$odrkey'
				  group by odrkey order by index_no desc ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {

			$sql  = " select * from shop_cart where odrkey = '$row[odrkey]' ";
			$sql .= " group by gs_id ";
			$sql .= " order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res) + 1;

			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$gs = get_order_goods($ct['orderno']);
				$od = get_order($ct['orderno']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
								SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
								SUM(io_price * ct_qty) as opt_price
						   from shop_cart
						  where gs_id = '$ct[gs_id]'
							and odrkey = '$ct[odrkey]' ";
				$sum = sql_fetch($sql);

				unset($it_name);
				$it_options = print_complete_options($ct['gs_id'], $ct['odrkey']);
				if($it_options && $ct['io_id']){
					$it_name = '<div class="sod_opt">'.$it_options.'</div>';
				}

				unset($mb_point);
				if($sum['point'] > 0) {
					$tot_point += $sum['point'];
					$mb_point = "<div class='padt3'>적립금:".display_point($sum['point'])."</div>";
				}
		?>
		<tr align="center">
			<td class="bl_nolne td_tal" style="padding:10px 5px;">
				<?php echo get_text($gs['gname']); ?>
				<?php echo $it_name; ?>
			</td>
			<td class="td_tar">
				<?php echo display_price($sum['price']); ?>
				<?php echo $mb_point; ?>
			</td>
			<td><?php echo display_qty($sum['qty']); ?></td>
			<td class="td_tar"><?php echo display_price($od['del_account']); ?></td>
		</tr>
		<?php
			}
		}

		// 총금액 뽑기
		$sql = " select SUM(account) as it_amt,
						SUM(del_account) as de_amt,
						SUM(dc_exp_amt) as dc_amt,
						SUM(use_point) as po_amt,
						SUM(use_account) as buy_amt
				   from shop_order
				  where mb_no='$mb_no'
				    and odrkey='$odrkey' ";
		$tot_sum = sql_fetch($sql);
		?>
		</tbody>
		</table>
	</div>

	<h3 class="s_stit mart30 marb5">주문고객정보</h3>
	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col width="125">
			<col>
		</colgroup>
		<tr>
			<th>주문일</th>
			<td><?php echo $od['orderdate_s']; ?> (<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		<tr>
			<th>주문번호</th>
			<td><?php echo $odrkey; ?></td>
		</tr>
		<tr>
			<th>주문인</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		</table>
	</div>

	<h3 class="s_stit mart30 marb5">결제정보</h3>
	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col width="125">
			<col width="150">
			<col>
		</colgroup>
		<tr>
			<th>주문금액</th>
			<td class="tar"><?php echo display_price($tot_sum['it_amt']+$tot_sum['de_amt']); ?></td>
			<td class="bl">상품금액 <?php echo display_price($tot_sum['it_amt']); ?> + 배송비 <?php echo display_price($tot_sum['de_amt']); ?> </td>
		</tr>
		<tr>
			<th>할인금액</th>
			<td class="tar">-<?php echo display_price($tot_sum['dc_amt']); ?></td>
			<td class="bl">즉시 할인쿠폰</td>
		</tr>
		<tr>
			<th>포인트결제</th>
			<td class="tar">-<?php echo display_point($tot_sum['po_amt']); ?></td>
			<td class="bl">쇼핑포인트 결제</td>
		</tr>
		<tr>
			<th>적립혜택</th>
			<td class="tar"><?php echo display_point($tot_point); ?></td>
			<td class="bl">쇼핑포인트 적립</td>
		</tr>
		<tr>
			<th<?php echo ($receipt)?' rowspan="2"':''; ?>>총결제금액</th>
			<td<?php echo ($receipt)?' rowspan="2"':''; ?> class="tar bold"><?php echo display_price2($tot_sum['buy_amt']); ?></td>
			<td class="bl"><?php echo $ar_method[$od['buymethod']]; ?></td>
		</tr>
		<?php if($receipt) { ?>
		<tr>
			<td class="bl"><?php echo $receipt; ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>
	<div class="tac mart20 marb50">
		<a href='javascript:od_print();' class="btn_lsmall">인쇄</a>
		<a href='javascript:self.close();' class="btn_lsmall bx-white">닫기</a>
	</div>
</div>

<script>
function od_print(){
	print();
}
</script>

<?php
include_once(TW_PATH."/tail.sub.php");
?>