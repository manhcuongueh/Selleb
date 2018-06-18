<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 주문/배송조회 <i class="ionicons ion-ios-arrow-right"></i>주문상세조회</p>
<h2 class="stit">주문상세조회</h2>
<p class="marb7 fs13">※ 주문일자 : <?php echo $od['orderdate_s']; ?> (<?php echo get_yoil($od['orderdate_s']); ?>), 주문번호 : <strong class="fc_255"><?php echo $odrkey; ?></strong></p>
<div class="tbl_head02">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
		<col width="100">
		<col width="60">
		<col width="80">
		<col width="110">
		<col width="100">
	</colgroup>
	<thead>
	<tr>
		<th class="bl_nolne">주문일자</th>
		<th>주문 상품정보</th>
		<th>상품금액</th>
		<th>수량</th>
		<th>배송비</th>
		<th>주문상태</th>
		<th>확인/후기</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {

		$sql = " select * from shop_cart where odrkey = '$row[odrkey]' ";
		$sql.= " group by gs_id order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		$rowspan = sql_num_rows($res) + 1;

		$cash = '';
		$od_card_bill = '';
		if($row['buymethod'] == 'C' || $row['buymethod'] == 'K') {
			$cash = unserialize($row['cash_info']);
		}

		if($row['buymethod'] == 'C') {
			$od_card_bill = "<div class='padt3' style='cursor:pointer' onclick=\"javascript:show_receipt('$cash[tid]','$cash[tpg]','$cash[appldate]','$cash[card_code]','$cash[acct_bankcode]')\"><u>카드전표</u></div>";
		}

		if($row['buymethod'] == 'K') {
			$od_card_bill = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$cash['tid'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';

			$od_card_bill = "<div class='padt3' style='cursor:pointer' onclick=\"javascript:".$od_card_bill."\"><u>카드전표</u></div>";
		}

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

			$od_btns = '';
			$od_trac = '';
			$delivery = explode('|', $od['delivery']);

			if(in_array($od['dan'], array('1','2','3'))) {
				$od_btns .= "<div><a href='".TW_SHOP_URL."/ordercancel.php?od_id={$ct[orderno]}' onclick=\"openwindow(this,'ordercancel','650','530','yes');return false\" class=\"btn_small bx-white\">주문취소</a></div>";
			}
			else if(in_array($od['dan'], array('4','5'))) {
				$od_btns = "";
				if(!$od['user_ok'])
					$od_btns = "<div><a href='".TW_SHOP_URL."/orderview.php?idx={$od[index_no]}&odrkey={$odrkey}&mode=decide' class=\"btn_small bx-white\">구매확정</a></div>";

				$od_trac  = "<div class='padt3'>{$delivery[0]}<br>({$od['gonumber']})</div>";
			}
			else if(in_array($od['dan'], array('9'))) {
				$od_btns .= "<div><span class='fs11 fc_red'>취소접수<span></div>";
			}

			// 구매후기 작성
			$od_btns .= "<div class='padt3'>";
			if(!$member[id]) {
				$od_btns .= "<a href=\"javascript:alert('회원만 작성 가능합니다.')\" class=\"btn_small bx-white\">";
			} else {
				$od_btns .= "<a href='".TW_SHOP_URL."/orderreview.php?gs_id={$ct[gs_id]}&od_id={$ct[odrkey]}' onclick=\"openwindow(this,'orderreview','650','530','yes');return false\" class=\"btn_small bx-white\">";
			}
			$od_btns .= "구매후기</a></div>";


			// 배송추적 값이 없을때
			$od_btns .= "<div class='padt3'>";
			if(!$delivery[1]) {
				$od_btns .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\" class=\"btn_small bx-white\">";
			} else {
				$od_btns .= "<a href='".$delivery[1].$od['gonumber']."' onclick=\"openwindow(this,'gonumber','600','650','yes');return false\" class=\"btn_small bx-white\">";
			}

			$od_btns .= "배송추적</a></div>";

			$href = TW_SHOP_URL.'/view.php?index_no='.$ct['gs_id'];

			if($k == 0) {
	?>
	<tr>
		<td rowspan="<?php echo $rowspan; ?>" class="bl_nolne tac br">
			<p><?php echo $row['orderdate_s']; ?> (<?php echo get_yoil($row['orderdate_s']); ?>)</p>
			<p class="fs11">(<?php echo $row['odrkey']; ?>)</p>
			<p class="padt3"><a href="<?php echo TW_SHOP_URL; ?>/orderprint.php?odrkey=<?php echo $od['odrkey']; ?>" onclick="openwindow(this,'order_print','670','600','yes');return false" class="btn_small bx-white"><i class="fa fa-print fs14 vam marb2 marr3"></i> 주문내역인쇄</a></p>
			<?php echo $od_card_bill; ?>
		</td>
	</tr>
	<?php } ?>
	<tr align="center">
		<td>
			<div class="tbl_wrap">
			<table class="wfull">
			<colgroup>
				<col width="80">
				<col>
			</colgroup>
			<tr>
				<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 70, 70); ?></a></td>
				<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a><?php echo $it_name; ?></td>
			</tr>
			</table>
			</div>
		</td>
		<td class="td_tar">
			<?php echo display_price2($sum['price']); ?>
			<?php echo $mb_point; ?>
		</td>
		<td><?php echo display_qty($sum['qty']); ?></td>
		<td class="td_tar"><?php echo display_price2($od['del_account']); ?></td>
		<td>
			<?php echo $ar_dan[$od['dan']]; ?>
			<?php echo $od_trac; ?>
		</td>
		<td><?php echo $od_btns; ?></td>
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
		<td class="tar"><?php echo display_price2($tot_sum['it_amt']+$tot_sum['de_amt']); ?></td>
		<td class="bl">상품금액 <?php echo display_price2($tot_sum['it_amt']); ?> + 배송비 <?php echo display_price2($tot_sum['de_amt']); ?></td>
	</tr>
	<tr>
		<th>할인금액</th>
		<td class="tar">-<?php echo display_price2($tot_sum['dc_amt']); ?></td>
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
<p class="tal mart7 fc_red">※ 현금영수증은 1원이상 현금 구매시, 구매대금 입금확인일 다음날 발급됩니다.</p>
<h3 class="s_stit mart30 marb5">주문고객정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>주문고객</th>
		<td><?php echo $od['name']; ?></td>
	</tr>
	<tr>
		<th>핸드폰번호</th>
		<td><?php echo $od['cellphone']; ?></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><?php echo $od['telephone']; ?></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><?php echo $od['email']; ?></td>
	</tr>
	<tr>
		<th>주소</th>
		<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
	</tr>
	</table>
</div>
<h3 class="s_stit mart30 marb5">배송지 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>수령인명</th>
		<td><?php echo $od['b_name']; ?></td>
	</tr>
	<tr>
		<th>핸드폰번호</th>
		<td><?php echo $od['b_cellphone']; ?></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><?php echo $od['b_telephone']; ?></td>
	</tr>
	<tr>
		<th>배송지주소</th>
		<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
	</tr>
	<?php if($od['memo']) { ?>
	<tr>
		<th>주문시메모</th>
		<td><?php echo $od['memo']; ?></td>
	</tr>
	<?php } ?>
	</table>
</div>

<div class="mart20 tac">
	<a href="<?php echo TW_SHOP_URL; ?>/orderlist.php" class="btn_medium">확인</a>
</div>