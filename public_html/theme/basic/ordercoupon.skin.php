<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="flist" method="post" class="pop_wrap">
<input type="hidden" name="sum_dc_amt">
<input type="hidden" name="layer_cnt">

<h2 class="pop_tit"><i class="fa fa-check-square-o"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
<div class="pop_inner">
	<table class="wfull marb30">
	<tr>
		<td align="center">
		<table class='wfull marb10'>
		<tr height='17'>
			<td class='padl5'>* 쿠폰적용시 <span class='fc_blk'>개별 (주문/상품) 에 한해서만 적용 됩니다</span> (단! 중복할인 쿠폰은 제외)</td>
		</tr>
		<tr height='17'>
			<td class='padl5'>* 쿠폰적용시 <span class='fc_blk'>배송비는 할인되지 않습니다.</span> (할인예시 : {상품 판매가 x 수량}+{좌동}+{좌동}…만 적용)</td>
		</tr>
		<tr height='17'>
			<td class='padl5'>* 주문을 취소하시거나 <span class='fc_blk'>반품하실 경우</span>에는 쿠폰은 <span class='fc_blk'>자동소멸</span> 됩니다.</td>
		</tr>
		</table>

		<div class="tbl_head02">
			<table class="wfull">
			<colgroup>
				<col width="60">
				<col>
				<col width="80">
				<col width="104">
				<col width="80">
			</colgroup>
			<thead>
			<tr>
				<th class="bl_nolne">이미지</th>
				<th>상품명</th>
				<th>판매가</th>
				<th>쿠폰선택</th>
				<th>할인금액</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for($i=0; $row=sql_fetch_array($result2); $i++) {
				$list = $i%2;
				$gs = get_goods($row['gs_id']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty
							from shop_cart
						   where gs_id = '$row[gs_id]'
							 and mb_no = '$member[index_no]'
							 and ct_select = '0'";
				$sum = sql_fetch($sql);

				$price = $sum['price'];

				// 소속 카테고리를 콤마로 구분하여 추출
				$ca_list = get_extract($row['gs_id']);
				$cp_tmp[] = $price ."|". $row['gs_id'] ."|". $ca_list ."|". $gs['use_aff'];
			?>
			<input type="hidden" name="gd_dc_amt_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_info_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_no_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_idx_<?php echo $i; ?>">
			<tr class="list<?php echo $list; ?>" height="70" align="center">
				<td class="bl_nolne"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?></td>
				<td class="td_tal"><?php echo $gs['gname']; ?></td>
				<td class="td_tar">
					<div><?php echo display_price2($price); ?></div>
					<div class='padt5 fc_197'>(수량:<?php echo $sum['qty']; ?>)</div>
				</td>
				<td>
					<span id="cp_avail_button_<?php echo $i; ?>">
					<a href="#" onclick="show_coupon('<?php echo $i; ?>');return false;"><img src='<?php echo TW_IMG_URL; ?>/btn_odr_coupon.jpg'></a>
					</span>
				</td>
				<td class="td_tar">
					<span id="dc_amt_<?php echo $i; ?>">0</span>원
					<span id="dc_cancel_bt_<?php echo $i; ?>" style="display:none"><a href="javascript:coupon_cancel('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');">X</a></span>
				</td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>

		<table class="wfull">
		<tr height="30">
			<td class='tar bold padt3 padr3 fc_blk'>쿠폰 할인 금액 : <span id="to_dc_amt" class='fc_red'>0</span>원</td>
		</tr>
		</table>

		<table class="wfull mart15">
		<tr align="center">
			<td><a href="#" onclick="cp_submit();return false;" onfocus="this.blur();" class="btn_medium red"><i class="fa fa-check"></i> 쿠폰 적용하기</a></td>
		</tr>
		</table>

		<table class="wfull mart30 marb5">
		<tr>
			<td>
				전체 : <b><?php echo number_format($total_count); ?></b>건 조회
				(<b><?php echo $member['name']; ?></b>님께서 사용가능한 쿠폰상세내역입니다)
			</td>
		</tr>
		</table>

		<div class="tbl_head02">
			<table class="wfull">
			<colgroup>
				<col width="60">
				<col>
			</colgroup>
			<thead>
			<tr>
				<th class="bl_nolne">쿠폰번호</th>
				<th>할인쿠폰명</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$list  = ($i%2);
				$lo_id = $row['lo_id'];

				$str = get_cp_contents();

				for($j=0; $j<$cart_count; $j++) {

					$is_coupon = false;
					$is_gubun = explode("|", $cp_tmp[$j]);

					switch($row['cp_use_part']) {
						case '0': // 전체상품에 쿠폰사용 가능
							$is_coupon = true;
							break;
						case '1': // 일부 상품만 쿠폰사용 가능
							if($row['cp_use_goods']) {
								$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
								if($fields_cnt)
									$is_coupon = true;
							}
							break;
						case '2': // 일부 카테고리만 쿠폰사용 가능
							if($row['cp_use_category']) {
								$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
								if($fields_cnt)
									$is_coupon = true;
							}
							break;
						case '3': // 일부 상품은 쿠폰사용 불가
							if($row['cp_use_goods']) {
								$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
								if(!$fields_cnt)
									$is_coupon = true;
							}
							break;
						case '4': // 일부 카테고리는 쿠폰사용 불가
							if($row['cp_use_category']) {
								$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
								if(!$fields_cnt)
									$is_coupon = true;
							}
							break;
					}

					// 적용여부 && 가맹점상품제외 && 최대금액 <= 상품금액
					$seq = array();
					if($is_coupon && !$is_gubun[3] && ($row['cp_low_amt'] <= (int)$is_gubun[0])) {
						// 할인해택 검사
						$amt =  get_cp_sale_amt((int)$is_gubun[0]);
						$seq[] = $is_gubun[1];
						$seq[] = $lo_id;
						$seq[] = $row['cp_id'];
						$seq[] = $row['cp_dups'];
						$seq[] = $amt[1];
						$seq[] = $amt[0];
						$is_possible[] = implode("|", $seq);
					}
				}
			?>
			<tr class="list<?php echo $list; ?>" align="center">
				<td class="bl_nolne"><?php echo $row['cp_id']; ?></td>
				<td class="td_tal"><?php echo $str; ?></td>
			</tr>
			<?php
			}

			if($total_count==0)
				echo '<tr><td colspan="2" class="empty_list">자료가 없습니다.</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<?php if($total_count > 0) { ?>
		<table class="wfull mart10">
		<tr>
			<td align="center"><?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?></td>
		</tr>
		</table>
		<?php } ?>
		</td>
	</tr>
	</table>
</div>
<?php
$result2 = sql_query($sql2);
for($i=0; $row=sql_fetch_array($result2); $i++) {
?>
<div id="cp_list<?php echo $i; ?>" class="apply_cmd" style="display:none;">
	<table width="306">
	<tr>
		<td><img src="<?php echo TW_IMG_URL; ?>/coupon_apply_title.gif" usemap="#coupon_apply<?php echo $i; ?>">
		<map name="coupon_apply<?php echo $i; ?>">
		<area shape="rect" coords="286,0,304,14" href="#" onclick="hide_cp_list('<?php echo $i; ?>'); return false;">
		</map></td>
	</tr>
	</table>

	<div class="tbl_head02">
		<table width="306">
		<colgroup>
			<col width="106">
			<col width="100">
			<col width="100">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">쿠폰번호</th>
			<th>할인금액(율)</th>
			<th>할인가</th>
		</tr>
		</thead>
		<tbody>
		<?php
		//5|1|8|0|10%|37496
		// 상품주키|쿠폰주키|쿠폰번호|동시사용 여부|할인금액(율)|할인가
		$chk = 0;
		for($j=0; $j<count($is_possible); $j++) {
			$arr = explode("|", $is_possible[$j]);
			if($row['gs_id'] == $arr[0]) {
				$chk++;
		?>
		<tr class="list0" height="25" align="center">
			<td class="bl_nolne bold"><input type="radio" name="use_cp_<?php echo $row['gs_id']; ?>_<?php echo $row['index_no']; ?>" value="<?php echo $arr[2]; ?>|<?php echo $arr[5]; ?>|<?php echo $arr[1]; ?>|<?php echo $arr[3]; ?>"> <?php echo $arr[2]; ?></td>
			<td><?php echo $arr[4]; ?></td>
			<td><?php echo display_price2($arr[5]); ?></td>
		</tr>
		<?php
			}
		}

		if(!$chk) {
		?>
		<tr><td colspan='3' class='empty_table'>사용할 수 있는 쿠폰이 없습니다.</td></tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<table width='306' class='mart10'>
	<tr align='right'>
		<td><img src="<?php echo TW_IMG_URL; ?>/apply_btn.gif" style="cursor:pointer" onclick="return applycoupon('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');"></td>
	</tr>
	</table>
</div>
<?php } ?>
</form>

<script language="javascript">
var max_layer = '<?php echo $cart_count; ?>';
document.flist.layer_cnt.value = max_layer;

function applycoupon(gs_id, cart_id, layer_idx) {
	var f = document.flist;

	// 개별 상품에 적용할 쿠폰 미선택 시
	if(!getRadioValue(f["use_cp_"+gs_id+"_"+cart_id])){
		alert('상품에 적용하실 쿠폰을 선택해주세요.');
		return false;
	}

	// 쿠폰번호 얻기
	var info = getRadioValue(f["use_cp_"+gs_id+"_"+cart_id]).split("|");
	var cp_no = info[0]; // 사용된 쿠폰 번호
	var gd_dc_amt = info[1]; // 쿠폰 할인액
	var cp_idx = info[2]; // 쿠폰 IDX
	var cp_dups = info[3]; // 중복 적용 여부

	// 이미 적용된 쿠폰인지 검사
	for(i=0;i<max_layer;i++){
		tmp = f["gd_cp_no_"+i].value; // 사용된 쿠폰 번호
		if(tmp != ""){
			if(cp_no == tmp){
				// 중복 적용 불가
				if(cp_dups == "1"){
					alert('해당 쿠폰은 중복할인이 되지 않습니다.');
					f["use_cp_"+gs_id+"_"+cart_id].checked = false;
					hide_cp_list(layer_idx);
					return false;
				}
			}
		}
	}

	// 쿠폰 적용 할인가를 상품별로 기록
	f["gd_dc_amt_"+layer_idx].value = gd_dc_amt;

	// 적용된 쿠폰 정보를 상품별로 저장
	f["gd_cp_info_"+layer_idx].value = gs_id+"|"+cart_id+"|"+cp_no+"|"+cp_idx+"|"+gd_dc_amt;
	f["gd_cp_no_"+layer_idx].value = cp_no;
	f["gd_cp_idx_"+layer_idx].value = cp_idx;

	// 전체 할인가 얻기
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		} else {
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 총 할인액 기록
	f.sum_dc_amt.value = sum_dc_amt;

	// label 변경
	document.getElementById("dc_amt_"+layer_idx).innerText = formatComma(gd_dc_amt);
	document.getElementById("to_dc_amt").innerText = formatComma(sum_dc_amt);
	document.getElementById("cp_avail_button_"+layer_idx).style.display = "none"; // 적용한 것은 안보이게
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "";

	hide_cp_list(layer_idx);
}

function coupon_cancel(gs_id, cart_id, layer_idx){
	var f = document.flist;

	// 쿠폰 적용 할인가를 상품별로 기록
	f["gd_dc_amt_"+layer_idx].value = 0;

	// 적용된 쿠폰 정보를 상품별로 삭제
	f["gd_cp_info_"+layer_idx].value = "";
	f["gd_cp_no_"+layer_idx].value = "";
	f["gd_cp_idx_"+layer_idx].value = "";

	// 전체 할인가 얻기
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		}else{
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 총 할인액 기록
	f.sum_dc_amt.value = sum_dc_amt;

	// label 변경
	document.getElementById("dc_amt_"+layer_idx).innerText = formatComma(0);
	document.getElementById("to_dc_amt").innerText = formatComma(sum_dc_amt);
	document.getElementById("cp_avail_button_"+layer_idx).style.display = ""; // 다시 보이게
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "none";
}

function show_coupon(idx)
{
	var cp_list = $("#cp_list"+idx);
	var wt = Math.max(0, (($(window).height() - $(cp_list).outerHeight()) / 2) + $(window).scrollTop()) + "px";
	var wl = Math.max(0, (($(window).width() - $(cp_list).outerWidth()) / 2) + $(window).scrollLeft()) + "px";
	$(cp_list).css("top", wt);
	$(cp_list).css("left", wl);
	$(cp_list).show();
}

function hide_cp_list(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	coupon_layer.style.display = 'none';
}

function cp_submit() {
	var f = document.flist;
	var total_amt = opener.document.buyform.total_amt.value;

	if(f.sum_dc_amt.value == 0 || !f.sum_dc_amt.value) {
		alert("상품에 쿠폰을 선택해주세요.");
		return false;
	}

	if(parseInt(stripComma(total_amt)) < f.sum_dc_amt.value) {
		alert("쿠폰 할인 금액이 결제금액을 초과하였습니다.");
		return false;
	}

	if(!confirm("쿠폰적용을 하시겠습니까?"))
		return false;

	var tmp_dc_amt	= '';
	var tmp_lo_id	= '';
	var tmp_cp_id	= '';
	var chk_dc_amt	= '';
	var chk_lo_id	= '';
	var chk_cp_id	= '';
	var comma		= '';
	for(i = 0; i < max_layer; i++) {
		chk_dc_amt	= eval("f.gd_dc_amt_"+i).value ? eval("f.gd_dc_amt_"+i).value : 0;
		chk_lo_id   = eval("f.gd_cp_idx_"+i).value ? eval("f.gd_cp_idx_"+i).value : 0;
		chk_cp_id	= eval("f.gd_cp_no_"+i).value ? eval("f.gd_cp_no_"+i).value : 0;

		tmp_dc_amt += comma + chk_dc_amt;
		tmp_lo_id  += comma + chk_lo_id;
		tmp_cp_id  += comma + chk_cp_id;
		comma = '|';
	}

	// 로그
	opener.document.buyform.dc_exp_amt.value = tmp_dc_amt;
	opener.document.buyform.dc_exp_lo_id.value = tmp_lo_id;
	opener.document.buyform.dc_exp_cp_id.value = tmp_cp_id;

	// 총 할인액
	opener.document.buyform.dc_sum_amt.value = f.sum_dc_amt.value;
	opener.document.getElementById("dc_amt").innerText = formatComma(f.sum_dc_amt.value);
	opener.document.getElementById("dc_cancel").style.display = "";
	opener.document.getElementById("dc_coupon").style.display = "none";

	// 최종 결제금액
	opener.document.buyform.total_amt.value = formatComma(parseInt(stripComma(total_amt)) - f.sum_dc_amt.value);

	self.close();
}
</script>
