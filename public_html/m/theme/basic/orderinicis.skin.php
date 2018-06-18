<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="ini" id="ini" method="post" accept-charset="euc-kr">
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

			if(!$goodname)
				$goodname = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

			$goods_count++;

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty
						from shop_cart
					   where odrkey = '$odrkey'
						 and gs_id = '$row[gs_id]'
						 order by io_type asc, index_no asc ";
			$sum = sql_fetch($sql);

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
		}
		?>
		</tbody>
		</table>
	</div>

	<div class="mart20">
		<input type="hidden" name="inipaymobile_type" value="web">
		<input type="hidden" name="P_MID" value="<?php echo $p_mid; ?>"> <!-- 상점아이디 -->
		<input type="hidden" name="P_OID" value="<?php echo $odrkey; ?>"> <!-- 주문번호 -->
		<input type="hidden" name="P_GOODS" value="<?php echo $goodname; ?>"> <!-- 상품명 -->
		<input type="hidden" name="P_AMT" value="<?php echo $good_mny; ?>"> <!-- 가격 -->
		<input type="hidden" name="P_UNAME" value="<?php echo $od['name']; ?>"> <!-- 구매자이름 -->
		<input type="hidden" name="P_MNAME" value="<?php echo $default['cf_nm_pg']; ?>"> <!-- 상점이름 -->
		<input type="hidden" name="P_MOBILE" value="<?php echo $od['cellphone']; ?>"> <!-- 휴대폰번호 -->
		<input type="hidden" name="P_EMAIL" value="<?php echo $od['email']; ?>"> <!-- 이메일 -->
		<input type="hidden" name="paymethod" value="<?php echo $paymethod; ?>"> <!-- 결제방법 -->
		<input type="hidden" name="P_APP_BASE" value="<?php echo $p_app_base; ?>"> <!-- 계좌이체 일경우만 “ON” (고정) -->
		<input type="hidden" name="P_VBANK_DT" value="<?php echo $p_vbank_dt; ?>"> <!-- 가상계좌 입금기한 -->

		<!-- 결과화면 url -->
		<input type="hidden" name="P_NEXT_URL" value="<?php echo $inipay_url; ?>/mx_rnext.php">

		<!-- 결과처리 url -->
		<input type="hidden" name="P_NOTI_URL" value="<?php echo $inipay_url; ?>/mx_rnoti.php">

		<!-- 결과화면 url -->
		<input type="hidden" name="P_RETURN_URL" value="<?php echo $inipay_url; ?>/mx_rreturn.php?P_OID=<?php echo $odrkey; ?>">

		<!-- 주문취소 url -->
		<input type="hidden" name="P_CANCEL_URL" value="<?php echo $inipay_url; ?>/mx_rreturn.php?P_OID=<?php echo $odrkey; ?>">

		<!-- 상품 컨텐츠 구분 (휴대폰 결제 시) -->
		<input type="hidden" name="P_HPP_METHOD" value="<?php echo $default['cf_inicis_hp_unit']; ?>">
		<?php if($od['taxflag']) { ?>
		<input type="hidden" name="P_TAX"  value="<?php echo $comm_vat_mny; ?>">  <!-- 부가세 -->
		<input type="hidden" name="P_TAXFREE" value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 -->
		<?php } ?>

		<table class="mynavbar">
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
	</div>

	<div class="mart20">
		<table class="mynavbar">
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
	</div>
	<div class="tac mart10">
		<button type="button" onClick="onSubmit();" class="btn_medium wfull"><?php echo $arr_mhd[$ss_pay_method]; ?> 결제하기</button>
	</div>
</div>
</form>

<script type="application/x-javascript">
addEventListener("load", function()
{
	setTimeout(updateLayout, 0);
}, false);

var currentWidth = 0;
function updateLayout()
{
	if(window.innerWidth != currentWidth)
	{
		currentWidth = window.innerWidth;

		var orient = currentWidth == 320 ? "profile" : "landscape";
		document.body.setAttribute("orient", orient);
		setTimeout(function()
		{
			window.scrollTo(0, 1);
		}, 100);
	}
}

setInterval(updateLayout, 400);
</script>

<script language=javascript>
window.name = "BTPG_CLIENT";

function on_web()
{
	var f = document.ini;
	var paymethod = f.paymethod.value;

	if( paymethod == "bank")
		f.P_APP_BASE.value = "ON";
	f.target = "BTPG_WALLET";
	f.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
	f.submit();
}

function onSubmit()
{
	var f = document.ini;
	return on_web();
}
</script>
