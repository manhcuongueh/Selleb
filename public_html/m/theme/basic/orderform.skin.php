<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
<div class="m_od_bg">
	<p class="relay bold">주문하시는 분</p>
	<div class="m_od">
		<table class="horiz">
		<colgroup>
			<col style="width:85px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<?php if(!$mb_yes) { ?>
		<tr>
			<td class="mi_dt vam">비밀번호</td>
			<td class="mi_bt"><input type="password" name="passwd" required itemname='비밀번호' placeholder='영,숫자 3~20자 (주문서 조회시 필요)'></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="mi_dt vam">이름</td>
			<td class="mi_bt"><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname='이름'></td>
		</tr>
		<tr>
			<td class="mi_dt vam">핸드폰</td>
			<td class="mi_bt"><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname='핸드폰'></td>
		</tr>
		<tr>
			<td class="mi_dt vam">전화번호</td>
			<td class="mi_bt"><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" itemname='전화번호'></td>
		</tr>
		<tr>
			<td class="mi_dt vat">주소</td>
			<td class="mi_bt">
				<table class="horiz" style='margin:0'>
				<colgroup>
					<col width="18%">
					<col width="18%">
					<col width="30%">
					<col width="34%">
				</colgroup>
				<tr>
					<td class="tal"><input type="number" name="zip" value="<?php echo $member['zip']; ?>" required itemname='우편번호' maxLength="5" style='width:98%;'></td>
					<td class="tal"><a href="javascript:void(0);" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_lsmall grey">주소검색</a></td>
					<td></td>
				</tr>
				</table>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" placeholder='주소' required itemname="주소" readonly>
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" placeholder='상세주소' itemname="상세주소">
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" placeholder='참고항목' itemname="참고항목" readonly>
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam">E-mail</td>
			<td class="mi_bt"><input type="text" name="email" value="<?php echo $member['email']; ?>" required email itemname='E-mail'></td>
		</tr>
		</tbody>
		</table>
	</div>

	<p class="relay bold">받으시는 분</p>
	<div class="m_od">
		<table class="horiz">
		<colgroup>
			<col style="width:85px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam">배송지선택</td>
			<td class="mi_bt">
				<input type="radio" name="ad_sel_addr" id="sel_addr1" value="1" class="css-checkbox lrg">
				<label for="sel_addr1" class="css-label padr5">주문자와 동일</label>
				<input type="radio" name="ad_sel_addr" id="sel_addr2" value="2" class="css-checkbox lrg">
				<label for="sel_addr2" class="css-label">새로운 주소</label>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam">이름</td>
			<td class="mi_bt"><input type="text" name="b_name" required itemname='이름'></td>
		</tr>
		<tr>
			<td class="mi_dt vam">핸드폰</td>
			<td class="mi_bt"><input type="text" name="b_cellphone" required itemname='핸드폰'></td>
		</tr>
		<tr>
			<td class="mi_dt vam">전화번호</td>
			<td class="mi_bt"><input type="text" name="b_telephone" itemname='전화번호'></td>
		</tr>
		<tr>
			<td class="mi_dt vat">주소</td>
			<td class="mi_bt">
				<table class="horiz" style='margin:0'>
				<colgroup>
					<col width="18%">
					<col width="18%">
					<col width="30%">
					<col width="34%">
				</colgroup>
				<tr>
					<td class="tal"><input type="text" name="b_zip" required itemname='우편번호' maxLength="5" style='width:98%;'></td>
					<td class="tal"><a href="javascript:void(0);" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_lsmall grey">주소검색</a></td>
					<td></td>
				</tr>
				</table>
				<div class="padt5">
					<input type="text" name="b_addr1" placeholder='주소' required itemname='주소' readonly>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" placeholder='상세주소' itemname="상세주소">
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" placeholder='참고항목' itemname="참고항목" readonly>
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vat">전하실말씀</td>
			<td class="mi_bt">
				<select name="sel_memo" style="width:100%">
					<option value="">요청사항 선택하기</option>
					<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
					<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
					<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
					<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
				</select>
				<div class="padt5"><input type="text" name="memo" value=""></div>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<p class="relay bold">주문 정보 확인</p>
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
		if($it_options) {
			$it_name = $it_options;
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

		$href = $tb['bbs_root'].'/view.php?gs_id='.$row['gs_id'];
	?>
	<div class="m_cart">
		<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
		<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
		<input type="hidden" name="gs_se_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
		<input type="hidden" name="gs_account[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
		<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['orderno']; ?>">

		<table class="ca_box">
		<tbody>
		<tr>
			<td class="mi_od"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?></a></td>
			<td class="mi_bt"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a></td>
		</tr>
		</tbody>
		</table>

		<?php echo $it_name; ?>

		<table class="th_box">
		<tbody>
		<?php if($mb_yes) { ?>
		<tr>
			<td class="tal">적립혜택</td>
			<td class="tar"><?php echo display_point($point); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="tal">상품금액</td>
			<td class="tar"><?php echo display_price2($sell_amt); ?></td>
		</tr>
		<tr>
			<td class="tal">수량</td>
			<td class="tar"><?php echo display_qty($sell_qty); ?></td>
		</tr>
		<tr>
			<td class="tal">배송비</td>
			<td class="tar"><?php echo $info['content']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_bt">소계</td>
			<td class="tar mi_bt bold"><?php echo display_price2($sell_price); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
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

	$multi_str = '';
	if($is_kakaopay_use)
		$multi_str .= "<option value='K'>카카오페이</option>\n";
	if($default['cf_bank_yn'])
		$multi_str .= "<option value='B'>무통장입금</option>\n";
	if($default['cf_card_yn'])
		$multi_str .= "<option value='C'>신용카드</option>\n";
	if($default['cf_iche_yn'] && $default['cf_card_pg'] != 'all')
		$multi_str .= "<option value='R'>실시간계좌이체</option>\n";
	if($default['cf_vbank_yn'])
		$multi_str .= "<option value='S'>가상계좌</option>\n";
	if($default['cf_hp_yn'])
		$multi_str .= "<option value='H'>휴대폰결제</option>\n";
	if($default['cf_escrow_yn']) {
		if($default['cf_iche_yn'] && $default['cf_card_pg'] != 'all')
			$multi_str .= "<option value='ER'>(에스크로) 계좌이체</option>\n";
		if($default['cf_vbank_yn'])
			$multi_str .= "<option value='ES'>(에스크로) 가상계좌</option>\n";
	}
	if($mb_yes && $config['usepoint_yes']) {
		$multi_str .= "<option value='P'>포인트결제</option>\n";
	}
	?>

	<input type="hidden" name='ss_cart_id' value="<?php echo $ss_cart_id; ?>">
	<input type="hidden" name='settle_case' value="<?php echo $default['cf_card_pg']; ?>">
	<input type="hidden" name='taxflag' value="<?php echo $default['cf_tax_flag_use']; ?>">
	<input type="hidden" name='mb_point' value="<?php echo $member['point']; ?>">
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
	<input type="hidden" name='use_point' value='0'>
	<?php } ?>

	<div class="marb10">
		<div class="to_box">
			<dl>
				<dt class="point_bg">(A) 주문금액 합계</dt>
				<dd class="point_bg"><?php echo display_price2($tot_sell_price); ?></dd>
				<dt>상품별 배송비합계</dt>
				<dd><?php echo display_price2($send_cost); ?></dd>
				<dt class="point_bg">배송비할인</dt>
				<dd class="point_bg">(-) <?php echo display_price2($tot_final_sum); ?></dd>
				<dt>(B) 최종배송비</dt>
				<dd><?php echo display_price2($tot_send_cost); ?></dd>
				<dt class="total">결제예정금액 (A+B)</dt>
				<dd class="total"><?php echo display_price2($tot_price); ?></dd>
			</dl>
		</div>
	</div>

	<p class="relay bold">결제정보 입력</p>
	<div class="m_od">
		<table class="horiz">
		<colgroup>
			<col style="width:85px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam">결제방법</td>
			<td class="mi_bt">
				<select name="buymethod" onchange="settle_method(this.value);" style="width:100%">
				<option value="">결제방법 선택하기</option>
				<?php echo $multi_str; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam">합계</td>
			<td class="mi_bt"><b><?php echo display_price2($tot_price); ?></b></td>
		</tr>
		<tr>
			<td class="mi_dt vam">추가배송비</td>
			<td class="mi_bt">
				(+) <b><span id="send_cost2">0</span>원</b>
				<span class="fc_999">(도서/산간 지역)</span>
			</td>
		</tr>
		<?php
		if($mb_yes && $config['sp_coupon']) { // 보유쿠폰
			$sp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<td class="mi_dt vam">할인쿠폰</td>
			<td class="mi_ot vam"><span id="dc_coupon"><a href="javascript:void(0);" onclick="window.open('<?php echo $tb["bbs_root"]; ?>/ordercoupon.php');" class="ui-list-a">사용 가능 쿠폰 <?php echo $sp_count[3]; ?>장</a>&nbsp;</span>(-)&nbsp;&nbsp;<span class="bold"><span id="dc_amt">0</span>원&nbsp;<span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();" class="btn_small grey">삭제</a></span></span></td>
		</tr>
		<?php } ?>
		<?php 
		if($mb_yes && $config['usepoint_yes']) { ?>
		<tr>
			<td class="mi_dt vat">적립금결제</td>
			<td class="mi_ot">
				<input type="tel" name="use_point" value="0" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="width:100px;">&nbsp;원
				<div class="padt5">
					잔액 : <b><?php echo display_point($member['point']); ?></b>&nbsp;(<?php echo display_point($config['usepoint']); ?> 부터 사용가능)
				</div>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td class="mi_dt vam">총 결제금액</td>
			<td class="mi_ot">
				<input type="text" name="total_amt" value="<?php echo number_format($tot_price); ?>" <?php echo $prc_attr; ?>>&nbsp;원
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="m_od" id="settle_bank" style='display:none;margin-top:-12px;border-top:0;'>
		<table class="horiz">
		<colgroup>
			<col style="width:85px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam">무통장계좌</td>
			<td class="mi_bt">
				<?php echo get_bank_account("bank"); ?>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam">입금예정일</td>
			<td class="mi_bt">
				<table class="horiz" style='margin:0'>
				<colgroup>
					<col style="width:80px">
					<col style="width:67px">
					<col style="width:67px">
					<col style="width:auto">
				</colgroup>
				<tr>
					<td class="tal padr3">
						<select name="in_year" style='width:100%'>
						<?php
						for($i=$time_year; $i<($time_year+2); $i++)
							echo option_selected($i, $time_year, $i);
						?>
						</select>
					</td>
					<td class="tal padr3">
						<select name="in_month" style='width:100%'>
						<?php
						for($i=1; $i<13; $i++) {
							$j = sprintf('%02d',$i);
							echo option_selected($j, $time_month, $j);
						}
						?>
						</select>
					</td>
					<td class="tal">
						<select name="in_day" style='width:100%'>
						<?php
						for($i=1; $i<32; $i++) {
							$j = sprintf('%02d',$i);
							echo option_selected($j, $time_day, $j);
						}
						?>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam">입금자명</td>
			<td class="mi_ot">
				<input type="text" name="incomename" value="<?php echo $member['name']; ?>" placeholder='입금자명' style="width:100px;">
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<div id="settle_tax_fld" style="display:none">
		<p class="relay bold">증빙서류 발급</p>
		<div class="m_od">
			<table class="horiz">
			<colgroup>
				<col style="width:85px">
				<col style="width:auto">
			</colgroup>
			<tbody>
			<tr>
				<td class="mi_dt vat">현금영수증</td>
				<td class="mi_bt">
					<select name="taxsave_yes" onchange="tax_save(this.value);" style="width:100%">
						<option value="N">발행안함</option>
						<option value="Y">개인 소득공제용</option>
						<option value="S">사업자 지출증빙용</option>
					</select>
					<div id="taxsave_fld_1" style="display:none">
						<div class="padt5"><input type="number" name="tax_hp" placeholder='핸드폰번호'></div>
						<div class="padt5">
							1, 1원이상 현금 구매시 발급이 가능합니다.<br>
							2, 구매대금 입금확인일 다음날 발급됩니다.
						</div>
					</div>
					<div id="taxsave_fld_2" style="display:none">
						<div class="padt5"><input type="text" name="tax_saupja_no" placeholder='사업자등록번호'></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="mi_dt vat">세금계산서</td>
				<td class="mi_bt">
					<select name="taxbill_yes" onchange="tax_bill(this.value);" style="width:100%">
						<option value="N">발행안함</option>
						<option value="Y">발행요청</option>
					</select>
					<div id="taxbill_fld" style="display:none;">
						<div class="padt5"><input type="text" name="company_saupja_no" placeholder='사업자등록번호'></div>
						<div class="padt5"><input type="text" name="company_name" placeholder='상호(법인명)'></div>
						<div class="padt5"><input type="text" name="company_owner" placeholder='대표자명'></div>
						<div class="padt5"><input type="text" name="company_addr" placeholder='사업장주소'></div>
						<div class="padt5"><input type="text" name="company_item" placeholder='업태'></div>
						<div class="padt5"><input type="text" name="company_service" placeholder='종목'></div>
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</div>

	<?php if(!$mb_yes) { ?>
    <section id="guest_privacy">
		<h2>비회원 구매</h2>
		<p id="guest_helper">
			비회원으로 주문하시는 경우 포인트는 지급하지 않습니다.
		</p>

		<div class="tbl_head01 tbl_wrap">
			<table>
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

		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1">
			<label for="agree">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
		</div>
	</section>
	<?php } ?>

	<div class="tac mart10">
		<a href='<?php echo $tb['bbs_root']; ?>/cart.php' class='btn_medium bx-white'>장바구니 돌아가기</a>
		<input type='submit' value='결제하기' class='btn_medium'>
	</div>
</div>
</form>

<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("input[name=memo]").val($(this).val());
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

	if(!getSelectVal(f["buymethod"])){
		alert('결제방법을 선택해주세요!');
		f.buymethod.focus();
		return false;
	}

    if(typeof(f.passwd) != 'undefined') {
        clear_field(f.passwd);
        if( (f.passwd.value.length<3) || (f.passwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.passwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getSelectVal(f["buymethod"]) == 'B'){
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.incomename, "입금자명을 입력하세요");
	}

	<?php if(!$config['company_type']) { ?>
	if(getSelectVal(f["taxsave_yes"]) == 'Y') {
		check_field(f.tax_hp, "휴대폰번호를 입력하세요");
	}

	if(getSelectVal(f["taxsave_yes"]) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getSelectVal(f["taxbill_yes"]) == 'Y') {
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

// 결제방법
function settle_method(type) {
    var sell_price = parseInt($("input[name=check_amt]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=del_amt2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=dc_sum_amt]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == 'P' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("select[name=buymethod]").val('B');
		$("#settle_bank").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false); 
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#settle_tax_fld").show();
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
			$("#settle_tax_fld").show();
			<?php } ?>
			break;
		case 'P':
			$("#settle_bank").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#settle_tax_fld").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("#taxbill_fld").hide();
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#settle_bank").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#settle_tax_fld").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("#taxbill_fld").hide();
			<?php } ?>
			break;
	}
}

// 현금영수증
function tax_save(val) {
	switch(val) {
		case 'Y': // 개인 소득공제용
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_fld").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // 지출증빙용
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_fld").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: // 발행안함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}

// 세금계산서
function tax_bill(val) {
	switch(val) {
		case 'Y':  // 발행함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_fld").show();
			break;
		case 'N': //미발행
			$("#taxbill_fld").hide();
			break;
	}
}

// 할인쿠폰 삭제
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
