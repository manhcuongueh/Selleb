<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TW_IMG_URL; ?>/orderinquiryview.gif"></div>
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

		unset($it_name);
		$it_options = print_complete_options($row['gs_id'], $odrkey);
		if($it_options && $row['io_id']){
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		$sell_price = $sum['price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

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
		<td><?php echo $ar_method[$od['buymethod']]; ?></td>
	</tr>
	<?php if($appname && $receipt) { ?>
	<tr>
		<th><?php echo $appname; ?></th>
		<td><?php echo $receipt; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th>결제금액</th>
		<td class="bold fs14"><?php echo display_price2(get_session('total_amt')); ?></td>
	</tr>
	<?php if($od['buymethod'] == 'B') { ?>
	<tr>
		<th>인터넷뱅킹 주소</th>
		<td>
			<form name="theForm">
			<select name="theMenu" onChange="goThere();">
			<option value="">인터넷뱅킹 바로가기</option>
			<?php
			$cf_banking = explode("\n", $default['cf_banking']);
			for($i=0;$i<count($cf_banking);$i++) {
				$banking = explode(" ",$cf_banking[$i]);
				echo "<option value='$banking[1]'>$banking[0]</option>\n";
			}
			?>
			</select>
			</form>
			<script>
			var theTarget = "_blank";
			function goThere(){
				if(!document.theForm.theMenu.selectedIndex==""){
				window.open(document.theForm.theMenu.options[document.theForm.theMenu.selectedIndex].value,theTarget,"");}
			}
			</script>
		</td>
	</tr>
	<?php } ?>
	</table>
</div>
<div class="mart20 tac">
	<a href="<?php echo TW_SHOP_URL; ?>/orderlist.php" class="btn_medium">주문완료</a>
</div>
