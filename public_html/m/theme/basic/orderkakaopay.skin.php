<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="layer_cont">
	<?php include_once("./_head.php"); ?>

	<form name="payForm" id="payForm" action="./kakaopay/kakaopayLiteResult.php"  method="post" accept-charset="">
	<div class="m_mypage_bg">
		<table class="mynavbar mart10">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<tr>
			<td class="selected"><span class="strong">주문정보</span></td>
			<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		</tbody>
		</table>

		<div class="my_vbox mart10">
			<table>
			<tbody>
			<?php
			$comm_tax_mny  = 0; // 과세금액
			$comm_vat_mny  = 0; // 부가세
			$comm_free_mny = 0; // 면세금액
			$tot_tax_mny   = 0;
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$gs = get_goods($row['gs_id']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),
								   ((io_price + ct_price) * ct_qty))) as price,
								SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty
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
				if($it_options) {
					if($row['io_id']){
						$it_name = '<div class="padt5">'.$it_options.'</div>';
					}
				}

				$sell_price = $sum['price'];
				$sell_qty = $sum['qty'];

				// 복합과세금액
				if($od['taxflag']) {
					if($gs['notax']) {
						$tot_tax_mny += (int)$sell_price;
					} else {
						$comm_free_mny += (int)$sell_price;
					}
				}

				if($row['mb_yes'])
					$point = $sum['point'];
				else
					$point = 0;

			?>
			<tr>
				<td class="mi_at" colspan=2>
					<span class="strong"><?php echo get_text($gs['gname']); ?></span>
					<?php echo $it_name; ?>
				</td>
			</tr>
			<tr>
				<td class="mi_dt">주문금액</td>
				<td class="mi_bt tar"><?php echo display_price2($sell_price); ?></td>
			</tr>
			<tr>
				<td class="mi_dt">주문수량</td>
				<td class="mi_bt tar"><?php echo display_qty($sell_qty); ?></td>
			</tr>
			<?php
				if($row['mb_yes']) {
					$tot_point += $point;
				}
			}

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
		</div>

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

		<table class="mynavbar mart20">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<tr>
			<td class="selected"><span class="strong">결제정보</span></td>
			<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		</tbody>
		</table>

		<div class="my_vbox mart10">
			<table>
			<tbody>
			<tr>
				<td class="tal mi_dt">주문금액</td>
				<td class="tal mi_bt">
					상품금액
					<?php echo display_price2($tot_sum['it_amt']); ?> + 배송비 <?php echo display_price2($tot_sum['de_amt']); ?>
					<div class="strong"><?php echo display_price2($tot_sum['it_amt']+$tot_sum['de_amt']); ?></div>
				</td>
			</tr>
			<tr>
				<td class="tal mi_dt">쿠폰할인</td>
				<td class="tal mi_bt">(-) <?php echo display_price2($tot_sum['dc_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">포인트결제</td>
				<td class="tal mi_bt">(-) <?php echo display_point($tot_sum['po_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">배송비결제</td>
				<td class="tal mi_bt">(+) <?php echo display_price2($tot_sum['de_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">적립혜택</td>
				<td class="tal mi_bt"><?php echo display_point($tot_point); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">총결제금액</td>
				<td class="tal mi_bt"><span class="strong fc_red"><?php echo display_price2($tot_sum['buy_amt']); ?></span> (<?php echo $arr_mhd[$od['buymethod']]; ?>)</td>
			</tr>
			</tbody>
			</table>
		</div>

		<table class="mynavbar mart20">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<tr>
			<td class="selected"><span class="strong">배송정보</span></td>
			<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		</tbody>
		</table>

		<div class="my_vbox mart10">
			<table>
			<tbody>
			<tr>
				<td class="tal mi_dt">주문자명</td>
				<td class="tal mi_bt"><?php echo $od['name']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">주문자 연락처</td>
				<td class="tal mi_bt"><?php echo $od['cellphone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">주문자 이메일</td>
				<td class="tal mi_bt"><?php echo $od['email']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">받으시는분</td>
				<td class="tal mi_bt"><?php echo $od['b_name']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">연락처 1</td>
				<td class="tal mi_bt"><?php echo $od['b_telephone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">연락처 2</td>
				<td class="tal mi_bt"><?php echo $od['b_cellphone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">배송지주소</td>
				<td class="tal mi_bt"><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">주문시메모</td>
				<td class="tal mi_bt"><?php echo $od['memo'] ? $od['memo'] : '없음'; ?></td>
			</tr>
			</tbody>
			</table>
		</div>
		<div class="tac mart10">
			<button type="button" onClick="getTxnId();" class="btn_medium wfull"><?php echo $arr_mhd[$ss_pay_method]; ?> 결제하기</button>
		</div>
	</div>
	</form>

	<?php include_once("./_tail.php"); ?>
</div>

<!-- TODO :  LayerPopup의 Target DIV 생성 -->
<div id="kakaopay_layer" style="display:none"></div>

<iframe name="txnIdGetterFrame" id="txnIdGetterFrame" src=""  width="0" height="0"></iframe>