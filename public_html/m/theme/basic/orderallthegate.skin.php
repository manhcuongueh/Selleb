<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_mypage_bg">
	<table class="mynavbar mart10">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td class="selected"><strong>주문정보</strong></td>
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

			if(!$goodname) {
				$gname = ( strlen($gs['gname']) > 25 ) ? cut_str($gs['gname'], 20) : $gs['gname'];
				$goodname = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gname);
			}

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
				<strong><?php echo get_text($gs['gname']); ?></strong>
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
		}
		?>
		</tbody>
		</table>
	</div>

	<form method="post" action="<?php echo $strAegis; ?>/payment/mobilev2/intro.jsp" name="form" accept-charset="euc-kr">
	<input type="hidden" name="Job" value="<?php echo $paymethod; ?>"> <!-- 결제선택 -->
	<input type="hidden" name="StoreId" value="<?php echo $StoreId; ?>"> <!-- 상점아이디 -->
	<input type="hidden" name="OrdNo" value="<?php echo $odrkey; ?>"> <!-- 주문번호 -->
	<input type="hidden" name="Amt" value="<?php echo $good_mny; ?>"> <!-- 가격 -->
	<input type="hidden" name="DutyFree" value="<?php echo $comm_free_mny; ?>"> <!-- 면세금액 -->
	<input type="hidden" name="StoreNm" value="<?php echo $default['cf_nm_pg']; ?>"> <!-- 상점이름 -->
	<input type="hidden" name="ProdNm" value="<?php echo $goodname; ?>"> <!-- 상품명 -->
	<input type="hidden" name="MallUrl" value="<?php echo TW_URL; ?>"> <!-- 상점URL -->
	<input type="hidden" name="UserEmail" value="<?php echo $od['email']; ?>"> <!-- 이메일 -->
	<input type="hidden" name="UserId" value="<?php echo $member['id']; ?>"> <!-- 회원아이디 -->
	<input type="hidden" name="OrdNm" value="<?php echo $od['name']; ?>"> <!-- 구매자이름 -->
	<input type="hidden" name="OrdPhone" value="<?php echo $od['cellphone']; ?>"> <!-- 휴대폰번호 -->
	<input type="hidden" name="OrdAddr" value="<?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?>"> <!-- 주문자주소 -->
	<input type="hidden" name="RcpNm" value="<?php echo $od['b_name']; ?>"> <!-- 수신자명 -->
	<input type="hidden" name="RcpPhone" value="<?php echo $od['b_cellphone']; ?>"> <!-- 수신자연락처 -->
	<input type="hidden" name="DlvAddr" value="<?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?>"> <!-- 배송지주소 -->	
	<input type="hidden" name="Remark" value="<?php echo $od['memo']; ?>"> <!-- 기타요구사항 -->
	<input type="hidden" name="CardSelect" value=""> <!-- 카드사선택 -->
	<input type="hidden" name="RtnUrl" value="<?php echo $RtnUrl; ?>"> <!-- 성공 URL -->
	<input type="hidden" name="CancelUrl" value="<?php echo $CancelUrl; ?>"> <!-- 취소 URL -->
	<!-- 앱 URL Scheme (독자앱일 경우) -->
	<!-- 네이버 예시 :  naversearchapp://inappbrowser?url= -->
	<input type="hidden"  name="AppRtnScheme" value="">	
	<input type="hidden" name="Column1" value=""> <!-- 추가사용필드1 (200) -->
	<input type="hidden" name="Column2" value=""> <!-- 추가사용필드1 (200) -->
	<input type="hidden" name="Column3" value=""> <!-- 추가사용필드1 (200) -->

	<!-- 가상계좌 결제에서 입/출금 통보를 위한 필수 입력 사항 입니다. -->
	<!-- 페이지주소는 도메인주소를 제외한 '/'이후 주소를 적어주시면 됩니다. -->
	<input type="hidden" name="MallPage" value="/shop/allthegate/AGS_VirAcctResult.php">

	<input type="hidden" name="VIRTUAL_DEPODT" value=""> <!-- 입금예정일 -->
	<input type="hidden" name="HP_ID" value="<?php echo $default['cf_ags_hp_id']; ?>"> <!-- CP아이디 -->
	<input type="hidden" name="HP_PWD" value="<?php echo $default['cf_ags_hp_pwd']; ?>"> <!-- CP비밀번호 -->
	<input type="hidden" name="HP_SUBID" value="<?php echo $default['cf_ags_hp_subid']; ?>"> <!-- SUB-CP아이디 -->

	<!-- 상품코드를 핸드폰 결제 실거래 전환후에는 발급받으신 상품코드로 변경하여 주시기 바랍니다. -->
	<input type="hidden" name="ProdCode" value="<?php echo $default['cf_ags_hp_code']; ?>">
	<input type="hidden" name="HP_UNITType" value="<?php echo $default['cf_ags_hp_unit']; ?>"> <!-- 상품종류 -->

	<!-- 금액;품명;2014.09.21~28 -->
	<input type="hidden" name="SubjectData" value=""> <!-- 상품제공기간 -->

	<input type="hidden" name="DeviId" value=""> <!-- 단말기아이디 -->
	<input type="hidden" name="QuotaInf" value="0"> <!-- 할부개월설정변수 -->
	<input type="hidden" name="NointInf" value="NONE"> <!-- 무이자할부개월설정변수 -->

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
		<button type="button" onclick="doPay(document.form);" class="btn_medium wfull"><?php echo $arr_mhd[$ss_pay_method]; ?> 결제하기</button>
	</div>
	</form>
</div>
