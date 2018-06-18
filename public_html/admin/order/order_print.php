<?php
define('_NEWWIN_', true);
define('_PURENESS_', true);
include_once("./_common.php");
include_once("../admin_head.php");

$od = get_order_no($index_no);
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
		$appname  = "계좌발급";
		$receipt  = "계좌번호 : {$cash[vact_num]}";
		if($cash['vact_name']) $receipt .= " / 예금주명 : {$cash[vact_name]} ";
		$receipt .= "<p>";
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
	<tr>
		<td><?php echo get_od_image($ct['odrkey'], $gs['simg1'], 40, 40); ?></td>
		<td class="tal"><?php echo $gs['gname']; ?><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($od['account']); ?></td>
		<td class="tac"><?php echo number_format($od['del_account']); ?></td>
		<td><?php echo number_format($sum['qty']); ?></td>
		<td class="tac bold"><?php echo number_format($od['account']+$od['del_account']); ?></td>
	</tr>
	<tr>
		<td rowspan="<?php echo ($is_supply)?'7':'6'; ?>" colspan="2" class="tal">
			<p>주문번호 : <b class="fc_red"><?php echo $od['odrkey']; ?></b></p>
			<p>일련번호 : <b class="fc_197"><?php echo $od['orderno']; ?></b> (주문일 : <?php echo date("Y.m.d H:i:s",$od['orderdate']); ?>)</p>
			<p>결제방식 : <?php echo $ar_method[$od['buymethod']]; ?></p>
			<?php if($appname && $receipt) { ?>
			<p><?php echo $appname;?> : <?php echo $receipt; ?></p>
			<?php } ?>
			<?php if($config['sp_coupon']) { ?>
			<p>할인쿠폰 : <?php echo $coupon['cp_subject'] ? $coupon['cp_subject']:'미사용'; ?></p>
			<?php } ?>
		</td>
		<td class="list2 tal" colspan="2">적립포인트</td>
		<td class="tar" colspan="2"><?php echo number_format($sum['point']); ?> P</td>
	</tr>
	<tr>
		<td class="list2 tal" colspan="2">배송비</td>
		<td class="tar" colspan="2"><?php echo number_format($od['del_account']); ?> 원</td>
	</tr>
	<tr>
		<td class="list2 tal" colspan="2">쿠폰할인</td>
		<td class="tar" colspan="2">(-) <?php echo number_format($od['dc_exp_amt']); ?> 원</td>
	</tr>
	<tr>
		<td class="list2 tal" colspan="2">포인트결제</td>
		<td class="tar" colspan="2">(-) <?php echo number_format($od['use_point']); ?> 원</td>
	</tr>
	<tr>
		<td class="list2 tal" colspan="2">실결제금액</td>
		<td class="tar fc_red bold" colspan="2"><?php echo number_format($od['use_account']); ?> 원</td>
	</tr>
	<tr>
		<td class="list2 tal" colspan="2">총계</td>
		<td class="tar bold" colspan="2"><?php echo number_format($od['account']+$od['del_account']); ?> 원</td>
	</tr>
	<?php if($is_supply) { ?>
	<tr>
		<td class="list2 tal" colspan="2">공급가격</td>
		<td class="tar bold" colspan="2"><font color='blue'><?php echo number_format($sum['mny']); ?> 원</font></td>
	</tr>
	<?php }
	}
	?>
	</tbody>
	</table>
	</div>

	<div class="tbl_frm02 marb10">
	<table>
	<colgroup>
		<col width="80px">
		<col>
		<col width="80px">
		<col>
		<col width="80px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>주문메모</th>
		<td colspan="5"><?php echo nl2br($od[memo]);?></td>
	</tr>
	<?php if($is_supply || $od['gs_se_id'] == 'admin') { ?>
	<tr>
		<th>판매처</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"본사":"가맹점"; ?></td>
		<th>가맹점ID</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"admin":$od['pt_id']; ?> </td>
		<th>가맹점명</th>
		<td><?php echo (empty($od['pt_id']) || $od['pt_id'] == 'admin')?"관리자":$pt['name']; ?> </td>
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
	</div>

	<?php
	if($od['cash_ca_log'] && in_array($od['dan'], array('7','8'))) {
		$cash = "";
		$cash = unserialize($od['cash_ca_log']);
	?>
	<div class="tbl_frm02 marb10">
	<table>
	<colgroup>
		<col width="80px">
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
		<td><?php echo number_format($od['cancel_amt']); ?>원</td>
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
	</div>
	<?php } ?>

	<?php if($is_supply) { ?>
	<div class="tbl_frm02 marb10">
	<table>
	<colgroup>
		<col width="80px">
		<col>
		<col width="80px">
		<col>
		<col width="80px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>업체구분</th>
		<td>공급업체</td>
		<th>업체</th>
		<td><?php echo $sr['in_compay']; ?> </td>
		<th>업체ID</th>
		<td><?php echo $sr['mb_id']; ?> <?php echo ($sr['sup_code'])?"(".$sr['sup_code'].")":""; ?></td>
	</tr>
	<tr>
		<th>담당자명</th>
		<td><?php echo $sr['in_dam']; ?> </td>
		<th>핸드폰</th>
		<td><?php echo replace_tel($sr['n_phone']); ?></td>
		<th>이메일</th>
		<td><?php echo $sr['n_email']; ?> </td>
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

	<div class="tbl_head01 marb10">
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
		<th>입금확인</th>
		<th>배송시작</th>
		<th>배송완료</th>
		<th>취소완료</th>
		<th>반품완료</th>
		<th>교환완료</th>
		<th>택배업체 [송장번호]</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php if($od['incomedate']==0){echo "";}else{ echo date("Y-m-d",$od['incomedate']); } ?></td>
		<td><?php if($od['shipdate']==0){echo "";}else{ echo date("Y-m-d",$od['shipdate']); } ?></td>
		<td><?php echo $od['overdate_s']; ?></td>
		<td><?php echo $od['canceldate_s']; ?></td>
		<td><?php echo $od['returndate_s']; ?></td>
		<td><?php echo $od['swapdate']; ?></td>
		<td>
			<?php
			if($od['delivery']) {
				$delivery = explode('|', $od['delivery']);
			?>
			<b><?php echo $delivery[0]; ?></b>
			<?php echo $od['gonumber']; ?>
			<?php } ?>
		</td>
	</tr>
	</tbody>
	</table>
	</div>

	<?php
	// 총 구매건수
	$sql_search = " where mb_no='$od[mb_no]' and dan in('1','2','3','4','5') ";
	$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
	$buy_cnt = $sql_market[cnt];

	//총 결제금액
	$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
	$buy_amt = $sum[amt]+$sum[del_amt];

	//총 반품건수
	$sql_search = " where mb_no='$od[mb_no]' and dan in('6') ";
	$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
	$bak_cnt = $sql_market[cnt];

	//총 반품금액
	$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
	$bak_amt = $sum[amt]+$sum[del_amt];

	//총 주문취소 건수
	$sql_search = " where mb_no='$od[mb_no]' and dan in('7','8') ";
	$sql_market = sql_fetch("select count(*) as cnt from shop_order $sql_search ");
	$can_cnt = $sql_market[cnt];

	//총 취소금액
	$sum = sql_fetch("select sum(account) as amt,sum(del_account) as del_amt from shop_order $sql_search ");
	$can_amt = $sum[amt]+$sum[del_amt];
	?>
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
			<tr>
				<th>전체 구매</th>
				<td><?php echo number_format($buy_amt);?>원 (<?php echo number_format($buy_cnt); ?>건)</td>
			</tr>
			<tr>
				<th>전체 반품</th>
				<td><?php echo number_format($bak_amt);?>원 (<?php echo number_format($bak_cnt); ?>건)</td>
			</tr>
			<tr>
				<th>전체 취소</th>
				<td><?php echo number_format($can_amt); ?>원 (<?php echo number_format($can_cnt); ?>건)</td>
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
			<tr height="156px">
				<th>주소</th>
				<td>
					<p>(<?php echo $od['b_zip']; ?>)</p>
					<p class="mart3"><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></p>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</div>

	<h4 class="fs14 marb7 tal">관리자메모</h4>
	<div class="tbl_head01">
	<table>
	<colgroup>
		<col>
		<col width="90px">
		<col width="90px">
	</colgroup>
	<thead>
	<tr>
		<th>메모내용</th>
		<th>작성자</th>
		<th>작성일</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql_common = " from shop_order_memo ";
	$sql_search = " where order_no='$index_no' ";
	$sql_order = " order by wdate desc ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tal"><?php echo $row['amemo']; ?></td>
		<td><?php echo $row['writer']; ?></td>
		<td><?php echo date("Y-m-d",$row['wdate']); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	</div>

	<script>print()</script>
</div>
