<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 주문/배송조회</p>
<h2 class="stit">주문/배송조회</h2>
<div class="order_vbx">
	<dl class="od_bx1">
		<dt>전체 구매현황 <span>(취소/반품제외)</span></dt>
		<dd>
			<p class="ddtit">총 주문건수</p>
			<p><?php echo number_format($tot_count); ?></p>
		</dd>
		<dd class="total">
			<p class="ddtit">총 결제금액</p>
			<?php echo display_price2($tot_price); ?>
		</dd>
	</dl>
	<dl class="od_bx2">
		<dt>주문상태 현황</dt>
		<dd>
			<p class="ddtit">주문접수</p>
			<p><?php echo number_format($status_count_1); ?></p>
		</dd>
		<dd>
			<p class="ddtit">입금확인</p>
			<p><?php echo number_format($status_count_2); ?></p>
		</dd>
		<dd>
			<p class="ddtit">배송대기</p>
			<p><?php echo number_format($status_count_3); ?></p>
		</dd>
		<dd>
			<p class="ddtit">배송중</p>
			<p><?php echo number_format($status_count_4); ?></p>
		</dd>
		<dd>
			<p class="ddtit">배송완료</p>
			<p><?php echo number_format($status_count_5); ?></p>
		</dd>
	</dl>
	<dl class="od_bx3">
		<dt>구매이후 현황</dt>
		<dd>
			<p class="ddtit">구매미확정</p>
			<p><?php echo number_format($status_count_6); ?></p>
		</dd>
		<dd>
			<p class="ddtit">구매확정</p>
			<p><?php echo number_format($status_count_7); ?></p>
		</dd>
		<dd>
			<p class="ddtit">취소</p>
			<p><?php echo number_format($status_count_8); ?></p>
		</dd>
		<dd>
			<p class="ddtit">반품</p>
			<p><?php echo number_format($status_count_9); ?></p>
		</dd>
	</dl>
</div>
<p class="fc_137 marb7">※ 상세보기 버튼을 클릭하시면 주문상세내역을 조회하실 수 있습니다.</p>
<div class="tbl_head02 marb20">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
		<col width="100">
		<col width="60">
		<col width="80">
		<col width="80">
		<col width="80">
	</colgroup>
	<thead>
	<tr>
		<th class="bl_nolne">주문일자</th>
		<th>주문 상품정보</th>
		<th>상품금액</th>
		<th>수량</th>
		<th>배송비</th>
		<th>주문상태</th>
		<th>확인/후기</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$sql = " select * from shop_cart where odrkey = '$row[odrkey]' ";
		$sql.= " group by gs_id order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		$rowspan = sql_num_rows($res) + 1;

		$cash = '';
		$od_card_bill = '';
		if($row['buymethod'] == 'C' || $row['buymethod'] == 'K') {
			$cash = unserialize($row['cash_info']);
		}

		if($row['buymethod'] == 'C') {
			$od_card_bill = "<div class='padt3' style='cursor:pointer' onclick=\"javascript:show_receipt('$cash[tid]','$cash[tpg]','$cash[appldate]','$cash[card_code]','$cash[acct_bankcode]')\"><u>카드전표</u></div>";
		}

		if($row['buymethod'] == 'K') {
			$od_card_bill = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$cash['tid'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';

			$od_card_bill = "<div class='padt3' style='cursor:pointer' onclick=\"javascript:".$od_card_bill."\"><u>카드전표</u></div>";
		}
		for($k=0; $ct=sql_fetch_array($res); $k++) {
			$gs = get_order_goods($ct['orderno']);
			$od = get_order($ct['orderno'] ? $ct['orderno'] : $ct['odrkey']);

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
				$it_name = '<div class="sod_opt">'.$it_options.'</div>';
			}

			unset($mb_point);
			if($sum['point'] > 0) {
				$mb_point = "<div class='padt3'>적립금:".display_point($sum['point'])."</div>";
			}

			$od_btns = '';
			$od_trac = '';
			$delivery = explode('|', $od['delivery']);

			if(in_array($od['dan'], array('1','2','3'))) {
				$od_btns .= "<div><a href='".TW_SHOP_URL."/ordercancel.php?od_id={$ct[orderno]}' onclick=\"openwindow(this,'ordercancel','650','530','yes');return false\" class=\"btn_small bx-white\">주문취소</a></div>";
			}
			else if(in_array($od['dan'], array('4','5'))) {
				$od_btns = "";
				if(!$od['user_ok'])
					$od_btns = "<div><a href='".TW_SHOP_URL."/orderlist.php?idx={$od[index_no]}&mode=decide' class=\"btn_small bx-white\">구매확정</a></div>";

				$od_trac  = "<div class='padt3'>{$delivery[0]}<br>({$od['gonumber']})</div>";
			}
			else if(in_array($od['dan'], array('9'))) {
				$od_btns .= "<div><span class='fs11 fc_red'>취소접수<span></div>";
			}

			// 구매후기 작성
			$od_btns .= "<div class='padt3'>";
			if(!$member['id']) {
				$od_btns .= "<a href=\"javascript:alert('회원만 작성 가능합니다.')\" class=\"btn_small bx-white\">";
			} else {
				$od_btns .= "<a href='".TW_SHOP_URL."/orderreview.php?gs_id={$ct[gs_id]}&od_id={$ct[odrkey]}' onclick=\"openwindow(this,'orderreview','650','530','yes');return false\" class=\"btn_small bx-white\">";
			}
			$od_btns .= "구매후기</a></div>";


			// 배송추적 값이 없을때
			$od_btns .= "<div class='padt3'>";
			if(!$delivery[1]) {
				$od_btns .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\" class=\"btn_small bx-white\">";
			} else {
				$od_btns .= "<a href='".$delivery[1].$od['gonumber']."' onclick=\"openwindow(this,'gonumber','600','650','yes');return false\" class=\"btn_small bx-white\">";
			}

			$od_btns .= "배송추적</a></div>";

			$href = TW_SHOP_URL.'/view.php?index_no='.$ct['gs_id'];

			if($k == 0) {
	?>
	<tr>
		<td rowspan="<?php echo $rowspan; ?>" class="bl_nolne tac br">
			<p><?php echo $row['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($row['orderdate_s']); ?>)</p>
			<p class="fs11">(<?php echo $row['odrkey']; ?>)</p>
			<p class="padt3"><a href="<?php echo TW_SHOP_URL; ?>/orderview.php?odrkey=<?php echo $row['odrkey']; ?>" class="btn_small grey">상세보기</a></p>
			<?php echo $od_card_bill; ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<div class="tbl_wrap">
				<table class="wfull">
				<colgroup>
					<col width="80">
					<col>
				</colgroup>
				<tr>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 70, 70); ?></a></td>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a><?php echo $it_name; ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="td_tar">
			<?php echo display_price2($sum['price']); ?>
			<?php echo $mb_point; ?>
		</td>
		<td><?php echo display_qty($sum['qty']); ?></td>
		<td class="td_tar"><?php echo display_price2($od['del_account']); ?></td>
		<td>
			<?php echo $ar_dan[$od['dan']]; ?>
			<?php echo $od_trac; ?>
		</td>
		<td><?php echo $od_btns; ?></td>
	</tr>
	<?php }
	}
	if($total_count==0) {
	?>
	<tr><td colspan="7" class="empty_list">자료가 없습니다.</td></tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<?php
echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page=");
?>

