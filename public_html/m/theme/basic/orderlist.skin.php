<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="stit_txt tal">※ <?php echo $member['name']; ?>님의 주문내역</div>
<div class="m_mypage_bg">
	<table class="my_tbox">
	<colgroup>
		<col width="25%">
		<col width="25%">
		<col width="25%">
		<col width="25%">
	</colgroup>
	<tbody>
	<tr>
		<td class="tal">총주문건수</td>
		<td class="tar bold"><?php echo number_format($total_count); ?>건</td>
		<td class="tal mi_bt">총주문금액</td>
		<td class="tar bold"><?php echo display_price2($tot_sum); ?></td>
	</tr>
	</tbody>
	</table>

	<table class="navbar mart10">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td<?php echo $selected1; ?>><a href="./orderlist.php">주문/배송조회</a></td>
		<td<?php echo $selected2; ?>><a href="./orderlist.php?sca=1">취소/반품/교환</a></td>
	</tr>
	</tbody>
	</table>

	<?php
	if(!$total_count) {
		echo "<div class=\"sct_noitem\">자료가 없습니다.</div>";
	} else {
	?>
	<div class="my_list">
		<table class="my_box">
		<colgroup>
			<col width="75%">
			<col width="25%">
		</colgroup>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {

			// 총금액 뽑기
			$tot = get_order_sum($sql_search, $row['odrkey']);

			$sql = " select * from shop_cart where odrkey = '$row[odrkey]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
		?>
		<tr class="tit">
			<td class="mi_dt tal strong"><?php echo $row['orderdate_s']; ?>&nbsp;&nbsp;(<?php echo $row['odrkey']; ?>)</td>
			<td class="mi_bt tar"><a href="./orderview.php?odrkey=<?php echo $row['odrkey']; ?>" class="btn_small bx-white">상세보기</a></td>
		</tr>
		<?php
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
				$it_name = '<div class="padt5">'.$it_options.'</div>';
			}

			$od_btns = '';
			if(in_array($od['dan'], array('1','2','3'))) {
				$od_btns = "<a href=\"javascript:window.open('./ordercancel.php?od_id={$ct[orderno]}');\" class='btn_small bx-red'>주문취소</a>";
			}
		?>
		<tr>
			<td class="mi_dt">
				<strong><?php echo $gs['gname']; ?></strong>
				<?php echo $it_name; ?>
			</td>
			<td class="mi_bt tar"><?php echo $od_btns; ?></td>
		</tr>
		<tr>
			<td class="mi_dt">주문금액</td>
			<td class="mi_at tar"><?php echo display_price2($sum['price']); ?></td>
		</tr>
		<tr>
			<td class="mi_dt">주문수량</td>
			<td class="mi_at tar"><?php echo display_qty($sum['qty']); ?></td>
		</tr>
		<tr>
			<td class="mi_dt">주문상태</td>
			<td class="mi_at tar"><?php echo $arr_dan[$od['dan']]; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="mi_dt tar" colspan=2>
				쿠폰할인 : <?php echo display_price2($tot['dc_amt']); ?>,&nbsp;&nbsp;
				적립금결제 : <?php echo display_price2($tot['po_amt']); ?>,&nbsp;&nbsp;
				배송비 : <?php echo display_price2($tot['del_amt']); ?>
				<div class="padt5 strong">총계 : <?php echo display_price2($tot['amt']+$tot['del_amt']); ?></div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<div class="mart10">
		<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?>
	</div>
	<?php } ?>
</div>
