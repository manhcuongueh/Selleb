<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="stit_txt tal">※ 주문번호 <?php echo $odrkey; ?>의 상세내역 입니다.</div>
<div class="m_mypage_bg">
	<table class="mynavbar mart10">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td class="selected"><span class="strong">주문정보</span></td>
		<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?> (<?php echo get_yoil($od['orderdate_s']); ?>)</td>
	</tr>
	</tbody>
	</table>

	<div class="my_vbox mart10">
		<table>
		<colgroup>
			<col width="35%">
			<col width="65%">
		</colgroup>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$sql = " select * from shop_cart where odrkey = '$row[odrkey]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);

			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$gs = get_order_goods($ct['orderno']);
				$od = get_order($ct['orderno']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
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

				unset($mb_point);
				if($sum['point'] > 0) {
					$tot_point += $sum['point'];
				}
		?>
		<tr>
			<td class="mi_at" colspan="2">
				<?php echo $gs['gname']; ?>
				<?php echo $it_name; ?>
			</td>
		</tr>
		<tr>
			<td class="mi_dt">&bull; 주문금액</td>
			<td class="mi_bt tar"><?php echo display_price2($sum['price']); ?></td>
		</tr>
		<tr>
			<td class="mi_dt">&bull; 주문수량</td>
			<td class="mi_bt tar"><?php echo display_qty($sum['qty']); ?></td>
		</tr>
		<tr>
			<td class="mi_dt">&bull; 주문상태</td>
			<td class="mi_bt tar"><?php echo $arr_dan[$od['dan']]; ?></td>
		</tr>
		<?php }
		}
		?>
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
		<td class="selected"><span class="strong">결제정보</span></td>
		<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
	</tr>
	</tbody>
	</table>

	<div class="my_vbox mart10">
		<table>
		<colgroup>
			<col width="35%">
			<col width="65%">
		</colgroup>
		<tbody>
		<tr>
			<td class="tal mi_dt">&bull; 주문금액</td>
			<td class="tal mi_bt">
				상품금액
				<?php echo display_price2($tot_sum['it_amt']); ?> + 배송비 <?php echo display_price2($tot_sum['de_amt']); ?>
				<div class="strong">
					<?php echo display_price2($tot_sum['it_amt']+$tot_sum['de_amt']); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 쿠폰할인</td>
			<td class="tal mi_bt">(-) <?php echo display_price2($tot_sum['dc_amt']); ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 포인트결제</td>
			<td class="tal mi_bt">(-) <?php echo display_point($tot_sum['po_amt']); ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 배송비결제</td>
			<td class="tal mi_bt">(+) <?php echo display_price2($tot_sum['de_amt']); ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 적립혜택</td>
			<td class="tal mi_bt"><?php echo display_point($tot_point); ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 총결제금액</td>
			<td class="tal mi_bt"><span class="strong"><?php echo display_price2($tot_sum['buy_amt']); ?></span> (<?php echo $arr_mhd[$od['buymethod']]; ?>)</td>
		</tr>
		<?php if($appname && $receipt) { ?>
		<tr>
			<td class="tal mi_dt">&bull; <?php echo $appname; ?></td>
			<td class="tal mi_bt"><?php echo $receipt; ?></td>
		</tr>
		<?php } ?>
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
		<colgroup>
			<col width="35%">
			<col width="65%">
		</colgroup>
		<tbody>
		<tr>
			<td class="tal mi_dt">&bull; 주문자명</td>
			<td class="tal mi_bt"><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 주문자 연락처</td>
			<td class="tal mi_bt"><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 주문자 이메일</td>
			<td class="tal mi_bt"><?php echo $od['email']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 받으시는분</td>
			<td class="tal mi_bt"><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 연락처 1</td>
			<td class="tal mi_bt"><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 연락처 2</td>
			<td class="tal mi_bt"><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<td class="tal mi_dt">&bull; 배송지주소</td>
			<td class="tal mi_bt"><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
		</tr>
		<?php if($od['memo']) { ?>
		<tr>
			<td class="tal mi_dt">&bull; 주문시메모</td>
			<td class="tal mi_bt"><?php echo $od['memo']; ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<div class="tac mart10">
		<a href="./orderlist.php" class="btn_medium bx-white wfull">주문배송 조회 목록보기</a>
	</div>
</div>
