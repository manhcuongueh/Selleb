<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 카카오페이 결제 시작 { -->
<div><img src="<?php echo TW_IMG_URL; ?>/orderform_pay.gif"></div>
<div class="tbl_head02 mart20">
	<table class="wfull">
	<colgroup>
		<col>
		<col width="100">
		<col width="60">
		<col width="80">
		<col width="100">
	</colgroup>
	<thead>
	<tr>
		<th class="bl_nolne">상품/옵션정보</th>
		<th>상품금액</th>
		<th>수량</th>
		<th>적립금</th>
		<th>주문금액</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$comm_tax_mny  = 0; // 과세금액
	$comm_vat_mny  = 0; // 부가세
	$comm_free_mny = 0; // 면세금액
	$tot_tax_mny   = 0;
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where odrkey = '$odrkey'
					 and gs_id = '$row[gs_id]'
					 order by io_type asc, index_no asc ";
		$sum = sql_fetch($sql);

		if(!$goodname)
			$goodname = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

		$goods_count++;

		unset($it_name);
		$it_options = print_complete_options($row['gs_id'], $odrkey);
		if($it_options && $row['io_id']){
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		$sell_price = $sum['price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 복합과세금액
		if($od['taxflag']) {
			if($gs['notax']) {
				$tot_tax_mny += $sell_price;
			} else {
				$comm_free_mny += $sell_price;
			}
		}

		if($row['mb_yes'])
			$point = $sum['point'];
		else
			$point = 0;

		$href = TW_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="bl_nolne">
			<div class="tbl_wrap">
				<table class="wfull">
				<colgroup>
					<col width="90">
					<col>
				</colgroup>
				<tr>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></a></td>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a><?php echo $it_name; ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td><?php echo display_price2($sell_amt); ?></td>
		<td><?php echo display_qty($sell_qty); ?></td>
		<td><?php echo display_point($point); ?></td>
		<td class="bold"><?php echo display_price2($sell_price); ?></td>
	</tr>
	<?php
		if($row['mb_yes'])
			$tot_point += $point;
	}

	// 총금액 뽑기
	$sql = " select SUM(account) as it_amt,
					SUM(del_account) as de_amt,
					SUM(dc_exp_amt) as dc_amt,
					SUM(use_point) as po_amt,
					SUM(use_account) as buy_amt
			   from shop_order
			  where odrkey='$odrkey' ";
	$tot_sum = sql_fetch($sql);

	if($goods_count) $goodname .= ' 외 '.$goods_count.'건';

	// 복합과세처리
	if($od['taxflag']) {
		$comm_tax_mny = round(($tot_tax_mny + $tot_sum['de_amt']) / 1.1);
		$comm_vat_mny = ($tot_tax_mny + $tot_sum['de_amt']) - $comm_tax_mny;
		$SupplyAmt = (int)$comm_tax_mny + (int)$comm_free_mny;
	}
	?>
	</tbody>
	</table>
	<div class="total_price">
		<span class="fl">적립포인트 합계 : <strong><?php echo display_point($tot_point); ?></strong></span>
		<span class="fr">
			(주문금액 : <strong><?php echo display_price2($tot_sum['it_amt']); ?></strong> +
			배송비결제 : <strong><?php echo display_price2($tot_sum['de_amt']); ?></strong>) -
			(쿠폰할인 : <strong><?php echo display_price2($tot_sum['dc_amt']); ?></strong> +
			포인트결제 : <strong><?php echo display_price2($tot_sum['po_amt']); ?></strong>) =
			총계 : <strong class="fc_red fs18"><?php echo display_price2($tot_sum['buy_amt']); ?></strong>
		</span>
	</div>
</div>
<form name="payForm" id="payForm" action="./kakaopay/kakaopayLiteResult.php"  method="post" accept-charset="">
<input type="hidden" name="PayMethod"		value="KAKAOPAY">
<input type="hidden" name="GoodsName"		value="<?php echo $goodname; ?>">
<input type="hidden" name="Amt"				value="<?php echo $Amt; ?>">
<input type="hidden" name="GoodsCnt"		value="<?php echo ($goods_count + 1); ?>">
<input type="hidden" name="MID"				value="<?php echo $MID; ?>">
<input type="hidden" name="AuthFlg"			value="10">
<input type="hidden" name="EdiDate"			value="<?php echo $ediDate; ?>">
<input type="hidden" name="EncryptData"		value="<?php echo $hash_String; ?>">
<input type="hidden" name="BuyerEmail"		value="<?php echo $od['email']; ?>">
<input type="hidden" name="BuyerName"		value="<?php echo $od['name']; ?>">

<input type="hidden" name="OFFER_PERIOD_FLAG" value="Y">
<input type="hidden" name="OFFER_PERIOD"	value="제품표시일까지">
<input type="hidden" name="CERTIFIED_FLAG"	value="CN">
<input type="hidden" name="currency"		value="KRW">
<input type="hidden" name="merchantEncKey"	value="<?php echo $merchantEncKey; ?>">
<input type="hidden" name="merchantHashKey"	value="<?php echo $merchantHashKey; ?>">
<input type="hidden" name="requestDealApproveUrl" value="<?php echo $CNSPAY_WEB_SERVER_URL.$msgName; ?>">

<input type="hidden" name="prType"			value="WPM">
<input type="hidden" name="channelType"		value="4">
<input type="hidden" name="merchantTxnNum"	value="<?php echo $odrkey; ?>">

<input type="hidden" name="possiCard"		value="">
<input type="hidden" name="fixedInt"		value="">
<input type="hidden" name="maxInt"			value="">
<input type="hidden" name="noIntYN"			value="N">
<input type="hidden" name="pointUseYn"		value="N">
<input type="hidden" name="blockCard"		value="">
<input type="hidden" name="blockBin"		value="">

<input type="hidden" name="resultCode"      value="" id="resultCode">
<input type="hidden" name="resultMsg"       value="" id="resultMsg">
<input type="hidden" name="txnId"           value="" id="txnId">
<input type="hidden" name="prDt"            value="" id="prDt">
<input type="hidden" name="SPU"				value="">
<input type="hidden" name="SPU_SIGN_TOKEN"	value="">
<input type="hidden" name="MPAY_PUB"		value="">
<input type="hidden" name="NON_REP_TOKEN"	value="">
<?php if($od['taxflag']) { ?>
<input type="hidden" name="SupplyAmt"		value="<?php echo $SupplyAmt; ?>">
<input type="hidden" name="GoodsVat"		value="<?php echo $comm_vat_mny; ?>">
<input type="hidden" name="ServiceAmt"		value="0">
<?php } ?>

<h3 class="s_stit mart30 marb5">주문고객 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>주문자</th>
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
		<th>수령인</th>
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
		<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
	</tr>
	<?php if($od['memo']) { ?>
	<tr>
		<th>배송시 요청사항</th>
		<td><?php echo $od['memo']; ?></td>
	</tr>
	<?php } ?>
	</table>
</div>

<h3 class="s_stit mart30 marb5">결제 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>결제방법</th>
		<td>카카오페이</td>
	</tr>
	<tr>
		<th>총 결제금액</th>
		<td class="fs14 bold"><?php echo display_price2($Amt); ?></td>
	</tr>
	</table>
</div>
</form>

<div class="mart20 tac">
	<a href="javascript:getTxnId();" class="btn_medium">결제하기</a>
</div>

<!-- TODO :  LayerPopup의 Target DIV 생성 -->
<div id="kakaopay_layer" style="display:none"></div>

<iframe name="txnIdGetterFrame" id="txnIdGetterFrame" src=""  width="0" height="0"></iframe>
<!-- } 카카오페이 결제 끝 -->