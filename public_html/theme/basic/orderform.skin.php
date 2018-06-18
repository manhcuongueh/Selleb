<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 주문서작성 시작 { -->
<div><img src="<?php echo TW_IMG_URL; ?>/orderform.gif"></div>
<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
<div class="tbl_head02 mart20">
	<table class="wfull">
	<colgroup>
		<col>
		<col width="100">
		<col width="60">
		<col width="80">
		<col width="80">
		<col width="100">
	</colgroup>
	<thead>
	<tr>
		<th class="bl_nolne">상품/옵션정보</th>
		<th>상품금액</th>
		<th>수량</th>
		<th>적립금</th>
		<th>배송비</th>
		<th>주문금액</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$tot_point = 0;
	$tot_sell_price = 0;
	$tot_opt_price = 0;
	$tot_sell_qty = 0;
	$tot_sell_amt = 0;
	$gs_se_id = array();

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
				   from shop_cart
				  where gs_id = '$row[gs_id]'
				    and mb_no = '$mb_no'
				    and ct_select = '0'";
		$sum = sql_fetch($sql);

		unset($it_name);
		$it_options = print_item_options($row['gs_id'], $mb_no);
		if($it_options && $row['io_id']){
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		if($mb_yes) {
			$point = $sum['point'];
		}

		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 배송비
		if($gs['use_aff'])
			$sr = get_partner($gs['mb_id']);
		else
			$sr = get_seller($gs['mb_id']);

		$info = get_item_sendcost($sell_price);
		$item_sendcost[] = $info['pattern'];

		$gs_se_id[$i] = $gs['mb_id'];

		$href = TW_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="bl_nolne">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_se_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="gs_account[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['orderno']; ?>">

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
		<td><?php echo $info['content']; ?></td>
		<td class="bold"><?php echo display_price2($sell_price); ?></td>
	</tr>
	<?php
		$tot_point += (int)$point;
		$tot_sell_price += (int)$sell_price;
		$tot_opt_price += (int)$sell_opt_price;
		$tot_sell_qty += (int)$sell_qty;
		$tot_sell_amt += (int)$sell_amt;
	}

	// 배송비 검사
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	$k = 0;
	$condition = array();
	foreach($item_sendcost as $key) {
		list($userid, $bundle, $price) = explode('|', $key);
		$condition[$userid][$bundle][$k] = $price;
		$k++;
	}

	$com_array = array();
	$val_array = array();
	foreach($condition as $key=>$value) {
		if($condition[$key]['묶음']) {
			$com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
			$max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
			$com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
			$val_array[] = max(array_values($condition[$key]['묶음']));// max value
		}
		if($condition[$key]['개별']) {
			$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
			$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
			$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
		}
	}

	$del_total_amt = get_tune_sendcost($com_array, $val_array);

	$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
	$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
	$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
	$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액

	// 에스크로 사용중인가?
	$is_escrow_use = false;
	if($default['cf_escrow_yn']) {
		// 계좌이체 및 가상계좌를 사용해야만 에스크로 사용으로 인정됨.
		if($default['cf_iche_yn'] || $default['cf_vbank_yn']) {
			$is_escrow_use = true;
		}
	}

	// 카카오페이 사용중인가?
	$is_kakaopay_fld = 0;
	$is_kakaopay_use = false;
	if($default['de_kakaopay_mid'])
		$is_kakaopay_fld++;
	if($default['de_kakaopay_key'])
		$is_kakaopay_fld++;
	if($default['de_kakaopay_enckey'])
		$is_kakaopay_fld++;
	if($default['de_kakaopay_hashkey'])
		$is_kakaopay_fld++;
	if($default['de_kakaopay_cancelpwd'])
		$is_kakaopay_fld++;

	// 설정값이 모두 있어야만 사용으로 인정됨.
	if($is_kakaopay_fld == 5)
		$is_kakaopay_use = true;
	?>
	</tbody>
	</table>
</div>

<section class="mart30">
	<h3 class="s_stit marb5">결제금액 통계</h3>
	<div class="tbl_frm01">
		<table class="wfull tablef">
		<colgroup>
			<col class="w140">
			<col class="w140">
			<col>
			<col class="w140">
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>적립금</th>
			<td class="tar">적립 포인트</td>
			<td class="tar"><?php echo display_point($tot_point); ?></td>
			<th>주문</th>
			<td class="tar">(A) 주문금액 합계</td>
			<td class="tar"><?php echo display_price2($tot_sell_price); ?></td>
		</tr>
		<tr>
			<th rowspan='3'>상품</th>
			<td class="tar">상품금액 합계</td>
			<td class="tar"><?php echo display_price2($tot_sell_amt); ?></td>
			<th rowspan='3'>배송비</th>
			<td class="tar">상품별 배송비합계</td>
			<td class="tar"><?php echo display_price2($send_cost); ?></td>
		</tr>
		<tr>
			<td class="tar">옵션금액 합계</td>
			<td class="tar"><?php echo display_price2($tot_opt_price); ?></td>
			<td class="tar">배송비할인</td>
			<td class="tar">(-) <?php echo display_price2($tot_final_sum); ?></td>
		</tr>
		<tr>
			<td class="tar">주문수량 합계</td>
			<td class="tar"><?php echo display_qty($tot_sell_qty); ?></td>
			<td class="tar">(B) 최종배송비</td>
			<td class="tar"><?php echo display_price2($tot_send_cost); ?></td>
		</tr>
		<tr>
			<td class="tac list2 bold" colspan="2">현재 적립금 보유잔액</td>
			<td class="tar list2 bold fs14"><?php echo display_point($member['point']); ?></td>
			<td class="tac list2 bold" colspan="2">결제예정금액 (A+B)</td>
			<td class="tar list2 bold fs14 fc_red"><?php echo display_price2($tot_price); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
</section>

<input type="hidden" name='ss_cart_id' value="<?php echo $ss_cart_id; ?>">
<input type="hidden" name='settle_case' value="<?php echo $default['cf_card_pg']; ?>">
<input type="hidden" name='taxflag' value="<?php echo $default['cf_tax_flag_use']; ?>">
<input type="hidden" name='mb_point' value='<?php echo $member['point']; ?>'>
<input type="hidden" name='pt_id' value="<?php echo $mb_recommend; ?>">
<input type="hidden" name='shop_id' value="<?php echo $pt_id; ?>">
<input type="hidden" name='dc_sum_amt' value='0'>
<input type="hidden" name='dc_exp_amt' value=''>
<input type="hidden" name='dc_exp_lo_id' value=''>
<input type="hidden" name='dc_exp_cp_id' value=''>
<input type="hidden" name='del_amt' value='<?php echo $tot_send_cost; ?>'>
<input type="hidden" name='del_amt2' value='0'>
<input type="hidden" name='del_total_amt' value='<?php echo $del_total_amt; ?>'>
<input type="hidden" name='check_amt' value='<?php echo $tot_price; ?>'>
<?php if(!$mb_yes || !$config['usepoint_yes']) { ?>
<input type="hidden" name="use_point" value="0">
<?php } ?>

<section class="mart30">
	<h3 class="s_stit marb5">주문하시는 분</h3>
	<div class="tbl_frm02">
		<table class="wfull tablef">
		<colgroup>
			<col class="w140">
			<col>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$mb_yes) { ?>
		<tr>
			<th>비밀번호 <span class="fc_red">*</span></th>
			<td colspan="3"><input type="password" name="passwd" required itemname='비밀번호' class="ed" size="20"><span class="marl5 fc_red">영,숫자 3~20자 (주문서 조회시 필요)</span></td>
		</tr>
		<?php } ?>
		<tr>
			<th>이름 <span class="fc_red">*</span></th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname='이름' class="ed" size="20"></td>
			<th rowspan="4" class="bl">주소 <span class="fc_red">*</span></th>
			<td rowspan="4">
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname='우편번호' class="ed" maxLength=5 size=7>
					<a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">우편번호</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname='주소' class="ed wfull" readonly>
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" itemname='상세주소'class="ed w70p"> ※ 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" itemname='참고항목' class="ed w70p" readonly> ※ 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th>핸드폰 <span class="fc_red">*</span></th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname='핸드폰' class="ed" size="20"></td>
		</tr>
		<tr>
			<th>전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" itemname='전화번호' class="ed" size="20"></td>
		</tr>
		<tr>
			<th>E-mail <span class="fc_red">*</span></th>
			<td><input type="text" name="email" value="<?php echo $member['email']; ?>" class="ed" size="30" required email itemname='E-mail'></td>
		</tr>
		</table>
	</div>
</section>

<section class="mart30">
	<h3 class="s_stit marb5">받으시는 분</h3>
	<div class="tbl_frm02">
		<table class="wfull tablef">
		<colgroup>
			<col class="w140">
			<col>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th>배송지선택</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 주문자와 동일</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 새로운 주소</label>
				<?php if($mb_yes) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 주소록에서 선택</label>
				<?php } ?>
			</td>
			<th rowspan="4" class="bl">주소 <span class="fc_red">*</span></th>
			<td rowspan="4">
				<div>
					<input type="text" name="b_zip" required itemname='우편번호' class="ed" maxLength=5 size=7>
					<a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">우편번호</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname='주소' class="ed wfull" readonly>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" itemname='상세주소' class="ed w70p"> ※ 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" itemname='참고항목' class="ed w70p" readonly> ※ 참고항목
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th>이름 <span class="fc_red">*</span></th>
			<td><input type="text" name="b_name" required itemname='이름' class="ed" size="20"></td>
		</tr>
		<tr>
			<th>핸드폰 <span class="fc_red">*</span></th>
			<td><input type="text" name="b_cellphone" required itemname='핸드폰' class="ed" size="20"></td>
		</tr>
		<tr>
			<th>전화번호</th>
			<td><input type="text" name="b_telephone" itemname='전화번호' class="ed" size="20"></td>
		</tr>
		<tr>
			<th>전하실말씀</th>
			<td colspan="3">
				<textarea name="memo" class="frm_textbox h60" rows="3"></textarea>
				<p class="frm_info"><strong class="fc_red">'택배사원'</strong>에 전하실 말씀을 써주세요~!<br>C/S관련문의는 고객센터에 작성해주세요. 이곳에 남기시면 확인이 불가능합니다.</p>
			</td>
		</tr>
		</table>
	</div>
</section>

<section class="mart30">
	<h3 class="s_stit marb5">결제정보</h3>
	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th><?php echo ($is_escrow_use)?"일반결제":"결제방법"; ?></th>
			<td class="td_label">
				<?php
				if($is_kakaopay_use) {
					echo '<input type="radio" name="buymethod" id="od_settle_kakaopay" value="K" onclick="settle_method(this.value);"> <label for="od_settle_kakaopay" class="kakaopay_icon">카카오페이</label>'.PHP_EOL;
				}
				if($default['cf_bank_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_bank" value="B" onclick="settle_method(this.value);"> <label for="od_settle_bank">무통장입금</label>'.PHP_EOL;
				}
				if($default['cf_card_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_card" value="C" onclick="settle_method(this.value);"> <label for="od_settle_card">신용카드</label>'.PHP_EOL;
				}
				if($default['cf_iche_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_iche" value="R" onclick="settle_method(this.value);"> <label for="od_settle_iche">실시간 계좌이체</label>'.PHP_EOL;
				}
				if($default['cf_hp_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_hp" value="H" onclick="settle_method(this.value);"> <label for="od_settle_hp">휴대폰결제</label>'.PHP_EOL;
				}
				if($default['cf_vbank_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_vbank" value="S" onclick="settle_method(this.value);"> <label for="od_settle_vbank">가상계좌</label>'.PHP_EOL;
				}
				if($mb_yes && $config['usepoint_yes']) {
					echo '<input type="radio" name="buymethod" id="od_settle_point" value="P" onclick="settle_method(this.value);"> <label for="od_settle_point">포인트결제</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<?php if($is_escrow_use) { ?>
		<tr>
			<th>에스크로결제</th>
			<td class="td_label">
				<?php
				if($default['cf_iche_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_eiche" value="ER" onclick="settle_method(this.value);"> <label for="od_settle_eiche">실시간 계좌이체</label>'.PHP_EOL;
				}
				if($default['cf_vbank_yn']) {
					echo '<input type="radio" name="buymethod" id="od_settle_evbank" value="ES" onclick="settle_method(this.value);"> <label for="od_settle_evbank">가상계좌</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th>합계</th>
			<td class="bold"><?php echo display_price2($tot_price); ?></td>
		</tr>
		<tr>
			<th>추가배송비</th>
			<td>
				(+) <strong><span id="send_cost2">0</span>원</strong>
				<span class="fc_137">(지역에 따라 추가되는 도선료 등의 배송비입니다.)</span>
			</td>
		</tr>
		<?php 
		if($mb_yes && $config['sp_coupon']) { // 보유쿠폰
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th>할인쿠폰</th>
			<td>(-) <b><span id="dc_amt">0</span>원 <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></b>
			<span id="dc_coupon"><a href='./ordercoupon.php' onclick="openwindow(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>보유쿠폰 <?php echo $cp_count[3]; ?>장</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php 
		if($mb_yes && $config['usepoint_yes']) { ?>
		<tr>
			<th>적립금결제</th>
			<td>
				<input type="text" name="use_point" value="0" class="ed" size="12" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 원 보유적립금 : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
				(<strong><?php echo display_point($config['usepoint']); ?></strong> 부터 사용가능)
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th>총 결제금액</th>
			<td>
				<input type="text" name="total_amt" class="ed" size="12" readonly value="<?php echo number_format($tot_price); ?>" style="font-weight:bold;color:#ec0e03;"> 원
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="settle_bank" style="display:none;">
	<div class="tbl_frm01 mart10">
		<table class="wfull">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th>입금계좌선택</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th>입금예정일</th>
			<td>
				<select name="in_year">
				<?php
				for($i=$time_year; $i<($time_year+2); $i++)
					echo option_selected($i, $time_year, $i);
				?>
				</select>년
				<select name="in_month">
				<?php
				for($i=1; $i<13; $i++) {
					$j = sprintf('%02d',$i);
					echo option_selected($j, $time_month, $j);
				}
				?>
				</select>월
				<select name="in_day">
				<?php
				for($i=1; $i<32; $i++) {
					$j = sprintf('%02d',$i);
					echo option_selected($j, $time_day, $j);
				}
				?>
				</select>일
			</td>
		</tr>
		<tr>
			<th>입금자명</th>
			<td><input type="text" name="incomename" value="<?php echo $member['name']; ?>" class="ed" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<?php if(!$config['company_type']) { ?>
<section id="settle_taxsave" style="display:none;">
	<div class="tbl_frm01 mart10">
		<table class="wfull">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th>현금영수증발행</th>
			<td class="td_label">
				<input type='radio' id='taxsave_1' name='taxsave_yes' value='Y' onclick="tax_bill(1);">
				<label for='taxsave_1'>개인 소득공제용</label>
				<input type='radio' id='taxsave_2' name='taxsave_yes' value='S' onclick="tax_bill(2);">
				<label for='taxsave_2'>사업자 지출증빙용</label>
				<input type='radio' id='taxsave_3' name='taxsave_yes' value='N' onclick="tax_bill(3);" checked>
				<label for='taxsave_3'>미발행</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th>핸드폰번호</th>
			<td>
				<input type="text" name="tax_hp" size="20" class="ed">
				<div class="padt5 lh4">
					- 현금영수증은 1원이상 현금 구매시 발급이 가능합니다.<br>
					- 현금영수증은 구매대금 입금확인일 다음날 발급됩니다.<br>
					- 현금영수증 홈페이지 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</div>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th>사업자등록번호</th>
			<td><input type="text" name="tax_saupja_no" size="20" class="ed"></td>
		</tr>
		<tr>
			<th>세금계산서발행</th>
			<td class="td_label">
				<input type='radio' id='taxbill_1' name='taxbill_yes' value='Y' onclick="tax_bill(4);">
				<label for='taxbill_1'>발행요청</label>
				<input type='radio' id='taxbill_2' name='taxbill_yes' value='N' onclick="tax_bill(5);" checked>
				<label for='taxbill_2'>미발행</label>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="settle_taxbill" style="display:none;">
	<div class="tbl_frm02 mart10">
		<table class="wfull">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th>사업자등록번호</td>
			<td><input type="text" name="company_saupja_no" size="20" class="ed"></td>
		</tr>
		<tr>
			<th>상호(법인명)</th>
			<td><input type="text" name="company_name" class="ed" size="20"> 예 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr>
			<th>대표자</th>
			<td><input type="text" name="company_owner" class="ed" size="20"> 예 : 홍길동</td>
		</tr>
		<tr>
			<th>사업장주소</th>
			<td><input type="text" name="company_addr" class="ed" size="60"></td>
		</tr>
		<tr>
			<th>업태</th>
			<td><input type="text" name="company_item" class="ed" size="20"> 예 : 도소매</td>
		</tr>
		<tr>
			<th>종목</th>
			<td><input type="text" name="company_service" class="ed" size="20"> 예 : 전자부품</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$mb_yes) { ?>
<section id="guest_privacy">
	<h3 class="s_stit marb5">개인정보 수집 및 이용</h3>
	<p>비회원으로 주문 시 포인트적립 및 추가 혜택을 받을 수 없습니다.</p>
	<div class="tbl_head03">
		<table class="wfull">
		<thead>
		<tr>
			<th>목적</th>
			<th>항목</th>
			<th>보유기간</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>이용자 식별 및 본인 확인</td>
			<td>이름, 비밀번호</td>
			<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
		</tr>
		<tr>
			<td>배송 및 CS대응을 위한 이용자 식별</td>
			<td>주소, 연락처(이메일, 휴대전화번호)</td>
			<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
		</tr>
		</tbody>
		</table>
	</div>

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
	</fieldset>
</section>
<?php } ?>

<div class="padt20 tac">
	<input type="submit" value="다음단계" class="btn_medium">
	<a href="javascript:history.go(-1);" class="btn_medium bx-white">주문취소</a>
</div>
</form>

<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open('./orderaddress.php','win_address', 550, 250, 'yes');
		}
	});
});

// 도서/산간 배송비 검사
function calculate_sendcost(code)
{
    $.post(
        "./ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=del_amt2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function calculate_order_price()
{
    var sell_price = parseInt($("input[name=check_amt]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=del_amt2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=dc_sum_amt]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //적립금결제
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=total_amt]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var settle_check = false;
	var settle_point = parseInt('<?php echo $config[usepoint]; ?>');
	var temp_point   = parseInt(no_comma(f.use_point.value));
	var sell_price   = parseInt(f.check_amt.value);
	var send_cost2   = parseInt(f.del_amt2.value);
	var mb_coupon    = parseInt(f.dc_sum_amt.value);
	var mb_point     = parseInt(f.mb_point.value);
	var tot_price    = sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert('적립금사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert('적립금사용 금액은 현재 보유적립금 보다 클수 없습니다.');
		f.total_amt.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert('적립금사용 금액은 최종결제금액 보다 클수 없습니다.');
		f.total_amt.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < settle_point)) {
		alert('적립금사용 금액은 '+number_format(String(settle_point))+'원 부터 사용가능 합니다.');
		f.total_amt.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "buymethod" && f.elements[i].checked==true){
			settle_check = true;
		}
	}

    if(!settle_check)
    {
        alert("결제방법을 선택하세요.");
        return false;
    }

    if(typeof(f.passwd) != 'undefined') {
        clear_field(f.passwd);
        if( (f.passwd.value.length<3) || (f.passwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.passwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getRadioVal(f.buymethod) == 'B') {
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.incomename, "입금자명을 입력하세요");
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.buymethod) == 'B' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
	}

	if(getRadioVal(f.buymethod) == 'B' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getRadioVal(f.buymethod) == 'B' && getRadioVal(f.taxbill_yes) == 'Y') {
		check_field(f.company_saupja_no, "사업자번호를 입력하세요");
		check_field(f.company_name, "상호명을 입력하세요");
		check_field(f.company_owner, "대표자명을 입력하세요");
		check_field(f.company_addr, "사업장소재지를 입력하세요");
		check_field(f.company_item, "업태를 입력하세요");
		check_field(f.company_service, "종목을 입력하세요");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("개인정보 수집 및 이용 내용을 읽고 이에 동의하셔야 합니다.");
			return false;
		}
	}

	if(!confirm("주문내역이 정확하며, 주문 하시겠습니까?"))
		return false;

	f.use_point.value = no_comma(f.use_point.value);
	f.total_amt.value = no_comma(f.total_amt.value);

	return true;
}

function calculate_temp_point(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point.value));
	var sell_price = parseInt(f.check_amt.value);
	var send_cost2 = parseInt(f.del_amt2.value);
	var mb_coupon  = parseInt(f.dc_sum_amt.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('적립금 사용액은 숫자이어야 합니다.');
		f.total_amt.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.total_amt.value = number_format(String(tot_price - temp_point));
	}
}

function settle_method(type) {
    var sell_price = parseInt($("input[name=check_amt]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=del_amt2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=dc_sum_amt]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == 'P' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("#od_settle_bank").attr("checked", true);
		$("#settle_bank").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false); 
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#settle_taxsave").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case 'B': // 무통장
			$("#settle_bank").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#settle_taxsave").show();
			<?php } ?>
			break;
		case 'P': // 포인트결제
			$("#settle_bank").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#settle_taxsave").hide();
			$("#settle_taxbill").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#settle_bank").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#settle_taxsave").hide();
			$("#settle_taxbill").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#settle_taxbill").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#settle_taxbill").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("#settle_taxbill").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$("#settle_taxbill").hide();
			break;
	}
}

function coupon_cancel(){
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.total_amt.value)); // 최종 결제금액
	var mb_coupon  = parseInt(f.dc_sum_amt.value); // 쿠폰할인
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=total_amt]").val(number_format(String(tot_price)));
	$("input[name=dc_sum_amt]").val(0);
	$("input[name=dc_exp_amt]").val("");
	$("input[name=dc_exp_lo_id]").val("");
	$("input[name=dc_exp_cp_id]").val("");
}

// 구매자 정보와 동일합니다.
function gumae2baesong(checked) {
    var f = document.buyform;

    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } 주문서작성 끝 -->
