<?php
define('_NEWWIN_', true);
define('_PURENESS_', true);
include_once("./_common.php");
include_once(TW_ADMIN_PATH."/admin_head.php");

$od = get_order($odrkey);
$mb	= get_member_no($od['mb_no']);

// 결제 캐쉬정보
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
		$appname = "계좌발급";
		$receipt = "계좌번호 : {$cash[vact_num]}";
		if($cash['vact_name']) $receipt .= " / 예금주명 : {$cash[vact_name]} ";
		$receipt .= "<p>";
		if($cash['vact_bankcode']) $receipt .= "은행명(코드) : {$cash[vact_bankcode]}";
		if($cash['vact_date']) $receipt .= ", 입금마감시간 : {$cash[vact_date]}";
		if($cash['vact_inputname']) $receipt .= ", 입금자명 : {$cash[vact_inputname]}";
		$receipt .= "</p>";
		break;
	case 'B' : // 무통장결제
		$appname = "계좌정보";
		$receipt = "계좌번호 : {$od[bank]} / 입금예정일 : {$od[indate]}";
		break;
}
?>

<div class="new_win_body">
	<div class="tbl_head01 marb10">
		<table>
		<colgroup>
			<col width="60px">
			<col>
			<col width="80px">
			<col width="80px">
			<col width="50px">
			<col width="80px">
		</colgroup>
		<thead>
		<tr>
			<th>이미지</th>
			<th>주문 상품정보</th>
			<th>상품금액</th>
			<th>배송비</th>
			<th>수량</th>
			<th>소계</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$tot_point = 0;

		$sql = " select * from shop_order where odrkey='$odrkey' group by odrkey order by index_no desc ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {

			$sql = " select * from shop_cart where odrkey = '$row[odrkey]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			for($k=0; $ct=sql_fetch_array($res); $k++) {			
				$gs = get_order_goods($ct['orderno']);
				$mk = get_order($ct['orderno']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty
						   from shop_cart
						  where gs_id = '$ct[gs_id]'
							and odrkey = '$ct[odrkey]' ";
				$sum = sql_fetch($sql);

				$tot_point += $sum['point'];

				unset($it_name);
				$it_options = print_complete_options($ct['gs_id'], $ct['odrkey']);
				if($it_options && $ct['io_id']){
					$it_name = '<div class="sod_opt">'.$it_options.'</div>';
				}
		?>
		<tr>
			<td><?php echo get_od_image($ct['odrkey'], $gs['simg1'], 40, 40); ?></td>
			<td class="tal"><?php echo $gs['gname']; ?><?php echo $it_name; ?></td>
			<td class="tar"><?php echo number_format($mk['account']); ?></td>
			<td class="tar"><?php echo number_format($mk['del_account']); ?></td>
			<td><?php echo number_format($sum['qty']); ?></td>
			<td class="tar bold"><?php echo number_format($mk['account']+$mk['del_account']); ?></td>
		</tr>
		<?php } // for
		} // for

		// 총금액 뽑기
		$sql = " select SUM(account) as it_amt,
						SUM(del_account) as de_amt,
						SUM(dc_exp_amt) as dc_amt,
						SUM(use_point) as po_amt,
						SUM(use_account) as buy_amt
				   from shop_order
				  where odrkey = '$odrkey' ";
		$tot_sum = sql_fetch($sql);
		?>
		<tr>
			<td rowspan="6" colspan="2" class="tal">
				<p>주문번호 : <b class="fc_red"><?php echo $od['odrkey']; ?></b></p>
				<p>주문일시 : <?php echo date("Y.m.d H:i:s",$od['orderdate']); ?></p>
				<p>결제방식 : <?php echo $ar_method[$od['buymethod']]; ?></p>
				<?php if($appname && $receipt) { ?>
				<p><?php echo $appname;?> : <?php echo $receipt; ?></p>
				<?php } ?>
			</td>
			<td class="list2 tal" colspan="2">적립포인트</td>
			<td class="tar" colspan="2"><?php echo number_format($tot_point); ?> P</td>
		</tr>
		<tr>
			<td class="list2 tal" colspan="2">배송비</td>
			<td class="tar" colspan="2"><?php echo number_format($tot_sum['de_amt']); ?> 원</td>
		</tr>
		<tr>
			<td class="list2 tal" colspan="2">쿠폰할인</td>
			<td class="tar" colspan="2">(-) <?php echo number_format($tot_sum['dc_amt']); ?> 원</td>
		</tr>
		<tr>
			<td class="list2 tal" colspan="2">포인트결제</td>
			<td class="tar" colspan="2">(-) <?php echo number_format($tot_sum['po_amt']); ?> 원</td>
		</tr>
		<tr>
			<td class="list2 tal" colspan="2">실결제금액</td>
			<td class="tar bold fc_red" colspan="2"><?php echo number_format($tot_sum['buy_amt']); ?> 원</td>
		</tr>
		<tr>
			<td class="list2 tal" colspan="2">총계</td>
			<td class="tar bold" colspan="2"><?php echo number_format($tot_sum['it_amt']+$tot_sum['de_amt']); ?> 원</td>
		</tr>
		</tbody>
		</table>
	</div>

	<?php if($od['memo']) { ?>
	<div class="tbl_frm02 marb10">
		<table>
		<tbody>
		<tr>
			<th>주문메모</th>
			<td><?php echo nl2br($od['memo']);?></td>
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
				사업자등록번호 : <?php echo $od['company_saupja_no']; ?><br>
				상호(법인명) : <?php echo $od['company_name']; ?><br>
				대표자명 : <?php echo $od['company_owner']; ?><br>
				사업장소재지 : <?php echo $od['company_addr']; ?><br>
				업태/종목 : <?php echo $od['company_item']; ?> / <?php echo $od['company_service']; ?>
				<?php } ?>
			</td>
			<td class="tal">
				<?php if($od['taxsave_yes']=='S') { ?>
				사업자 지출증빙용<br>
				사업자등록번호 : <?php echo $od['tax_saupja_no']; ?>
				<?php } ?>
				<?php if($od['taxsave_yes']=='Y') { ?>
				개인 소득공제용<br>
				핸드폰 : <?php echo $od['tax_hp']; ?>
				<?php } ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="tbl_frm02 mart30">	
		<div class="half_bx">
			<h4 class="fs14 marb7 tal">주문자 정보</h4>
			<table class="marb10">
			<colgroup>
				<col width="80px">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th>이름</th>
				<td><?php echo $od['name']; ?></td>
			</tr>
			<tr>
				<th>핸드폰</th>
				<td><?php echo $od['cellphone']; ?></td>
			</tr>
			<tr>
				<th>전화번호</th>
				<td><?php echo $od['telephone']; ?></td>
			</tr>
			<tr>
				<th>E-Mail</th>
				<td><?php echo $od['email']; ?></td>
			</tr>
			</tbody>
			</table>
		</div>
		<div class="half_bx">
			<h4 class="fs14 marb7 tal">수령자 정보</h4>
			<table class="marb10">
			<colgroup>
				<col width="80px">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th>이름</th>
				<td><?php echo $od['b_name']; ?></td>
			</tr>
			<tr>
				<th>핸드폰</th>
				<td><?php echo $od['b_cellphone']; ?></td>
			</tr>
			<tr>
				<th>전화번호</th>
				<td><?php echo $od['b_telephone']; ?></td>
			</tr>
			<tr>
				<th>주소</th>
				<td class="vat">
					<p>(<?php echo $od['b_zip']; ?>)</p>
					<p class="mart3"><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></p>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</div>	
</div>

<script>print();</script>

</body>
</html>