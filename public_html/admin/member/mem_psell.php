<?php
if(!defined('_TUBEWEB_')) exit;

$mb = get_member_no($index_no);
$sr = sql_fetch("select * from shop_seller where mb_id = TRIM('$mb[id]')");

$query_string = "code=$code&index_no=$index_no$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_search = " where dan != '0' and gs_se_id = '$sr[sup_code]' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}
if($j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_ddate')";
}
if($j_sdate && !$j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_sdate')";
}
if(!$j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_ddate' and orderdate_s <= '$j_ddate')";
}

if(!$orderby) {
    $filed = "orderdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod, index_no asc";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

// 총금액 뽑기
$sql = " select SUM(use_account) as use_a,
		        SUM(use_point) as use_p,
		        SUM(del_account) as del_a ,
		        SUM(dc_exp_amt) as dc_amt
			$sql_common
			$sql_search ";
$total = sql_fetch($sql);

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<h2>상품판매내역</h2>
<h3>기본검색</h3>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col width="220px">
		<col width="100px">
		<col>
	</colgroup>
	<tbody>	
	<tr>
		<th>검색키워드</td>
		<td colspan="3">
			<select name="sfl">
				<?php echo option_selected('name', $sfl, '주문자명'); ?>
				<?php echo option_selected('odrkey', $sfl, '주문번호'); ?>
				<?php echo option_selected('orderno', $sfl, '일련번호'); ?>
				<?php echo option_selected('incomename', $sfl, '입금자명'); ?>
				<?php echo option_selected('b_name', $sfl, '수령자명'); ?>
				<?php echo option_selected('b_telephone', $sfl, '수령자집전화'); ?>
				<?php echo option_selected('b_cellphone', $sfl, '수령자핸드폰'); ?>
				<?php echo option_selected('b_addr1', $sfl, '배송지주소'); ?>
				<?php echo option_selected('gonumber', $sfl, '송장번호'); ?>
				<?php echo option_selected('pt_id', $sfl, '가맹점ID'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>주문</th>
		<td colspan="3">
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<ul class="or_totalbox mart20">
	<li>결제금액 합계 <p class="mart5 fc_red"><b><?php echo number_format($total[use_a]);?></b> 원</p></li>
	<li>쿠폰할인 <p class="mart5 fc_7d6"><b><?php echo number_format($total[dc_amt]);?></b> 원</p></li>
	<li>적립금결제 <p class="mart5 fc_7d6"><b><?php echo number_format($total[use_p]);?></b> 원</p></li>
	<li>배송비결제 <p class="mart5 fc_7d6"><b><?php echo number_format($total[del_a]);?></b> 원</p></li>
</ul>

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="60px">
		<col width="80px">
		<col>
		<col width="70px">
		<col width="65px">
		<col width="65px">
		<col width="80px">
		<col width="60px">
		<col width="80px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th>NO</th>
		<th>이미지</th>
		<th><?php echo subject_sort_link('orderno',$q2)?>일련번호</a></th>
		<th><?php echo subject_sort_link('odrkey',$q2)?>주문번호</a></th>
		<th><?php echo subject_sort_link('orderdate',$q2)?>주문날짜</a></th>
		<th><?php echo subject_sort_link('b_name',$q2)?>수령자명</a></th>
		<th><?php echo subject_sort_link('buymethod',$q2)?>결제방법</a></th>
		<th><?php echo subject_sort_link('use_account',$q2)?>결제금액</a></th>
		<th><?php echo subject_sort_link('dan',$q2)?>주문상태</a></th>
		<th><?php echo subject_sort_link('itempay_yes',$q2)?>정산</a></th>
		<th>공급가총계</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$od_table = $row[index_no];

		$gs = get_order_goods($row[orderno]);

		// 장바구니 검사
		$sql = " select * from shop_cart where orderno = '$row[orderno]' ";
		$sql.= " group by gs_id order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		for($j=0; $ct=sql_fetch_array($res); $j++) {
			$mny = (int)$gs['daccount'];

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + $mny) * ct_qty))) as mny
						   from shop_cart
					  where gs_id = '$ct[gs_id]'
						and odrkey = '$ct[odrkey]' ";
			$sum = sql_fetch($sql);
		}

		$sell_mny = (int)$sum['mny'];

		// 배송추적 값이 없을때
		$baesong = "<div class='padt5'>";
		$delivery = explode('|', $row['delivery']);
		if(!$delivery[1]) {
			$baesong .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\">";
		} else {
			$baesong .= "<a href='".$delivery[1].$row['gonumber']."' onclick=\"openwindow(this,'pops','600','650','yes');return false\">";
		}

		$baesong .= "<img src='/img/btn_od_dvr.gif'></a></div>";

		$max = get_order_max($sql_search, $row[odrkey]);
		$sum = get_order_sum($sql_search, $row[odrkey]);
	?>
	<tr>
		<td rowspan="2"><?php echo $num--;?></td>
		<td rowspan="2"><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $row['gs_id'];?>" target="_blank"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 40, 40); ?></a></td>
		<td><a href="<?php echo TW_ADMIN_URL;?>/pop_order_main.php?index_no=<?php echo $od_table;?>" onclick="openwindow(this,'pop_order','953','800','yes');return false" class="fc_197"><?php echo get_text($row[orderno]);?></a></td>
		<td><?php echo get_text($row[odrkey]);?></td>
		<td><?php echo date("Y/m/d",$row[orderdate]);?></td>
		<td rowspan="2"><?php echo get_text($row[b_name]).$baesong;?></td>
		<td rowspan="2"><?php echo $ar_method[$row[buymethod]];?></td>
		<td rowspan="2" class="tar bold"><?php echo number_format($row[use_account]);?></td>
		<td rowspan="2"><?php echo $ar_dan[$row[dan]];?></td>
		<td rowspan="2"><?php echo $row[itempay_yes]?'완료':'대기';?></td>
		<td rowspan="2" class="tar fc_197"><?php echo number_format($sell_mny);?></td>
	</tr>
	<tr>
		<td colspan="3"><?php echo $delivery[0].'&nbsp;&nbsp;'.$row[gonumber];?></td>
	</tr>
	<?php
		if($max[max_idx] == $od_table) {
			echo "<tr class='list1'>";
			echo "<td colspan='7'>&#183;쿠폰할인 : <b class='marr20'>".number_format($sum[dc_amt])."</b>";
			echo "적립금결제 : <b class='marr20'>".number_format($sum[po_amt])."</b>";
			echo "배송비 : <b class='marr20'>".number_format($sum[del_amt])."</b>";
			echo "주문자명 : <b>".$row[name]."</b></td>";
			echo "<td class='tar fc_255 bold'>".number_format($sum[use_amt])."</td>";
			echo "<td class='tar fc_red bold' colspan='3'>총계 : ".number_format($sum[amt]+$sum[del_amt])."</td>";
			echo "</tr>";
		}
	}
	if($i==0)
		echo '<tr><td colspan="11" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>