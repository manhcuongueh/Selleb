<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo $tb['root']; ?>/js/shop.js"></script>

<form name="frmcartlist" method="post" action="<?php echo $cart_action_url; ?>">
<input type="hidden" name="act">

<div class="stit_txt">※ 총 <?php echo number_format($cart_count); ?>개의 상품이 장바구니에 있습니다.</div>
<div class="s_cont">
	<?php
	$tot_sell_price = 0;
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where gs_id = '$row[gs_id]'
					 and mb_no = '$mb_no'
					 and ct_select = '0'";
		$sum = sql_fetch($sql);

		if($i==0) { // 계속쇼핑
			$ca_id = $row['ca_id'];
		}

		unset($it_name);
		unset($mod_options);
		$it_options = print_item_options($row['gs_id'], $mb_no);
		if($it_options) {
			$mod_options = '<div class="sod_option_btn"><button type="button" class="mod_options">옵션변경/추가</button></div>';
			$it_name = $it_options;
		}

		$sell_price = $sum['price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'];

		// 배송비
		if($gs['use_aff'])
			$sr = get_partner($gs['mb_id']);
		else
			$sr = get_seller($gs['mb_id']);

		$info = get_item_sendcost($sell_price);
		$item_sendcost[] = $info['pattern'];

		$href = $tb['bbs_root'].'/view.php?gs_id='.$row['gs_id'];
	?>
	<div class="m_cart" id="sod_bsk_list">
		<table class="ca_box">
		<tbody>
		<tr>
			<th><input name="ct_chk[<?php echo $i; ?>]" type="checkbox" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked" class="css-checkbox"><label for="ct_chk_<?php echo $i; ?>" class="css-label"></label></th>
			<td class="mi_dt"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?></a></td>
			<td class="mi_bt" id='mi_bt'>
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<a href="<?php echo $href; ?>"><?php echo $gs['gname']; ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $mod_options; ?></td>
		</tr>
		</tbody>
		</table>

		<?php echo $it_name; ?>

		<table class="th_box">
		<tbody>
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
			<td class="tar mi_bt strong"><?php echo display_price2($sell_price); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php
		$tot_sell_price += $sell_price;
	}

	// 배송비 검사
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	if($i > 0) {
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

		$tune = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
		$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
		$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액
	}

	if($i == 0) {
		echo "<div class=\"sct_noitem\">장바구니에 담긴 상품이 없습니다.</div>";
		echo "<div>";
			echo "<button type=\"button\" onclick=\"location.href='$tb[root]';\" class=\"btn_medium bx-white wfull\">쇼핑계속하기</button>";
		echo "</div>";
	} else {
	?>

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
			<dd class="total"><?php echo display_price2($tot_price); ?></td>
		</dl>
	</div>
	<div class="tac mart10 m_cart_bt">
		<a href="./list.php?cate=<?php echo $ca_id; ?>" class="btn_medium bx-white">쇼핑계속하기</a>
		<button type="button" onclick="return form_check('buy');" class="btn_medium">주문하기</button>
		<button type="button" onclick="return form_check('seldelete');" class="btn_medium bx-white">선택삭제</button>
		<button type="button" onclick="return form_check('alldelete');" class="btn_medium bx-white">비우기</button>
		<?php if($naverpay_button_js) { ?>
		<div class="cart-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
</form>

<script>
$(function() {
	var close_btn_idx;

	// 선택사항수정
	$(".mod_options").click(function() {
		var gs_id = $(this).closest("tbody").find("input[name^=gs_id]").val();
		var $this = $(this);
		close_btn_idx = $(".mod_options").index($(this));

		$.post(
			"./cartoption.php",
			{ gs_id: gs_id },
			function(data) {
				$("#mod_option_frm").remove();
				$this.after("<div id=\"mod_option_frm\"></div>");
				$("#mod_option_frm").html(data);
				price_calculate();
			}
		);
	});

	// 모두선택
	$("input[name=ct_all]").click(function() {
		if($(this).is(":checked"))
			$("input[name^=ct_chk]").attr("checked", true);
		else
			$("input[name^=ct_chk]").attr("checked", false);
	});

	// 옵션수정 닫기
	$("#mod_option_close").live("click", function() {
		$("#mod_option_frm").remove();
		$(".mod_options").eq(close_btn_idx).focus();
	});
	$("#win_mask").click(function () {
		$("#mod_option_frm").remove();
		$(".mod_options").eq(close_btn_idx).focus();
	});
});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("구매하실 상품을 하나이상 선택해 주십시오.");
        return false;
    }

    return true;
}

function form_check(act) {
	var f = document.frmcartlist;

	if(act == "buy")
	{
		if($("input[name^=ct_chk]:checked").size() < 1) {
			alert("주문하실 상품을 하나이상 선택해 주십시오.");
			return false;
		}

		f.act.value = act;
		f.submit();
	}
	else if(act == "alldelete")
	{
		f.act.value = act;
		f.submit();
	}
	else if(act == "seldelete")
	{
		if($("input[name^=ct_chk]:checked").size() < 1) {
			alert("삭제하실 상품을 하나이상 선택해 주십시오.");
			return false;
		}

		f.act.value = act;
		f.submit();
	}

	return true;
}
</script>
