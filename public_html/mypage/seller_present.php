<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "정산내역";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_search = " where dan != '0' and gs_se_id = '$seller[sup_code]' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and ( ";
    switch($sst) {
		case "itempay_yes" :
            $sql_search .= " (itempay_yes = '1') ";
            break;
        case "itempay_no" :
            $sql_search .= " (itempay_yes = '0') ";
            break;
		case "monitor" :
			$sql_search .= " (path = '0') ";
			break;
		case "mobile" :
			$sql_search .= " (path = '1') ";
			break;
		case "Y" :
			$sql_search .= " (mb_yes = '1') ";
			break;
		case "N" :
			$sql_search .= " (mb_yes = '0') ";
			break;
		default :
			if(in_array($sst, array('C','B','R','H','S','ER','ES')))
				$sql_search .= " (buymethod = '$sst') ";
			else
				$sql_search .= " (dan = '$sst') ";
			break;
    }
    $sql_search .= " ) ";
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

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>	
	<tr>
		<th>검색키워드</th>
		<td>
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
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>주문일</th>
		<td>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
		</td>
	</tr>
	<tr>
		<th>구분</th>
		<td>
			<select name="sst">
			<option value=''>선택</option>
			<optgroup id="optg1">
				<option value='Y'>회원</option>
				<option value='N'>비회원</option>
			</optgroup>
			<optgroup id="optg2">
				<option value='monitor'>일반결제</option>
				<option value='mobile'>모바일샵결제</option>
			</optgroup>
			<optgroup id="optg3">
				<option value='itempay_no'>대기</option>
				<option value='itempay_yes'>정산</option>
			</optgroup>
			<optgroup id="optg4">
				<?php
				if($default['cf_card_yn'])
					echo "<option value='C'>".$ar_method['C']."</option>\n";
				if($default['cf_bank_yn'])
					echo "<option value='B'>".$ar_method['B']."</option>\n";
				if($default['cf_iche_yn'])
					echo "<option value='R'>".$ar_method['R']."</option>\n";
				if($default['cf_hp_yn'])
					echo "<option value='H'>".$ar_method['H']."</option>\n";
				if($default['cf_vbank_yn'])
					echo "<option value='S'>".$ar_method['S']."</option>\n";
				if($default['cf_iche_yn'])
					echo "<option value='ER'>".$ar_method['ER']."</option>\n";
				if($default['cf_vbank_yn'])
					echo "<option value='ES'>".$ar_method['ES']."</option>\n";
				?>
			</optgroup>
			<optgroup id="optg5">
				<?php
				for($i=1; $i<=10; $i++) {
					if($i != 9)
						echo "<option value='{$i}'>".$ar_dan[$i]."</option>\n";
				}
				?>
			</optgroup>
			</select>
			<script>document.fsearch.sst.value='<?php echo $sst;?>';</script>
			<script language="JavaScript">
				document.getElementById("optg1").label = "회원구분";
				document.getElementById("optg2").label = "모바일샵";
				document.getElementById("optg3").label = "정산현황";
				document.getElementById("optg4").label = "결제방법";
				document.getElementById("optg5").label = "주문상태";
			</script>
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

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="60px">
		<col width="80px">
		<col width="100px">
		<col>
		<col width="50px">
		<col width="90px">
		<col width="90px">
		<col width="80px">
		<col width="50px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="90px">
		<col width="60px">
		<col width="90px">
		<col width="90px">
	</colgroup>
	<thead>
	<tr>
		<th>번호</th>
		<th>이미지</th>
		<th><?php echo subject_sort_link('orderdate_s',$q2);?>주문일</a></th>
		<th><?php echo subject_sort_link('orderno',$q2);?>일련번호</a></th>
		<th><?php echo subject_sort_link('odrkey',$q2);?>주문번호</a></th>
		<th>수량</th>
		<th><?php echo subject_sort_link('name',$q2);?>주문자명</a></th>
		<th><?php echo subject_sort_link('b_name',$q2);?>수령자명</a></th>
		<th>판매가</th>
		<th><?php echo subject_sort_link('mb_yes',$q2);?>회원</a></th>
		<th><?php echo subject_sort_link('del_account',$q2);?>배송비</a></th>
		<th><?php echo subject_sort_link('use_point',$q2);?>적립금결제</a></th>
		<th><?php echo subject_sort_link('use_account',$q2);?>결제금액</a></th>
		<th><?php echo subject_sort_link('buymethod',$q2);?>결제방법</a></th>
		<th><?php echo subject_sort_link('itempay_yes',$q2);?>정산</a></th>
		<th>공급가</th>
		<th><?php echo subject_sort_link('dan',$q2);?>주문상태</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$od_table = $row['index_no'];

		$gs = get_order_goods($row['orderno']);

		// 장바구니 검사
		$sql = " select * from shop_cart where orderno = '$row[orderno]' ";
		$sql.= " group by gs_id order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		for($g=0; $ct=sql_fetch_array($res); $g++) {
			$mny = (int)$gs['daccount'];

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + $mny) * ct_qty))) as mny,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty
					   from shop_cart
					  where gs_id = '$ct[gs_id]'
						and odrkey = '$ct[odrkey]' ";
			$row2 = sql_fetch($sql);

			$gs_id = $ct['gs_id'];
		}

		$sell_mny = (int)$row2['mny'];
		$sell_qty = (int)$row2['qty'];

		$max = get_order_max($sql_search, $row['odrkey']);
		$sum = get_order_sum($sql_search, $row['odrkey']);

		// 배송추적 값이 없을때
		$baesong = "";
		if($row['delivery']) {
			$delivery = explode('|', trim($row['delivery']));
			if(!trim($delivery[1])) {
				$baesong .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\" class=\"btn_ssmall bx-white\">";
			} else {
				$baesong .= "<a href='".trim($delivery[1]).$row['gonumber']."' onclick=\"openwindow(this,'pop_delivery','600','650','yes');return false\" class=\"btn_ssmall bx-white\">";
			}
			$baesong .= "배송추적</a>";
		}

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg;?>">
		<td><?php echo $num--;?></td>
		<td><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $gs_id;?>" target="_blank"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 40, 40); ?></a></td>
		<td><?php echo $row['orderdate_s'];?></td>
		<td><a href="<?php echo TW_ADMIN_URL;?>/pop_order_main.php?index_no=<?php echo $od_table;?>" onclick="openwindow(this,'pop_order','953','800','yes');return false" class="fc_197"><?php echo get_text($row['orderno']);?></a></td>
		<td><?php echo get_text($row['odrkey']);?></td>
		<td><?php echo number_format($sell_qty);?></td>
		<td><?php echo get_text($row['name']);?></td>
		<td><?php echo get_text($row['b_name']);?></td>
		<td class="tar"><?php echo number_format($gs['account']);?></td>
		<td><?php echo $row['mb_yes']?'yes':'no';?></td>
		<td class="tar"><?php echo number_format($row['del_account']);?></td>
		<td class="tar"><?php echo number_format($row['use_point']);?></td>
		<td class="tar"><?php echo number_format($row['use_account']);?></td>
		<td><?php echo $ar_method[$row['buymethod']];?></td>
		<td class="txt_succeed"><?php echo $row['itempay_yes']?'yes':'no';?></td>
		<td class="tar fc_00f"><?php echo number_format($sell_mny);?></td>
		<td><?php echo $ar_dan[$row['dan']];?></td>
	</tr>
	<?php
		if($max['max_idx'] == $od_table) {
			echo "<tr class='list2'>\n";
			echo "<td colspan='2'><a href=\"javascript:window.open('".TW_ADMIN_URL."/order/order_print2.php?odrkey={$row[odrkey]}', '', 'scrollbars=yes,width=670,height=600,top=10,left=20');\" class=\"btn_small bx-blue\"><i class=\"fa fa-print\"></i> 인쇄하기</a></b></td>\n";
			echo "<td colspan='2'>".$baesong.'&nbsp;&nbsp;'.$delivery[0].'&nbsp;&nbsp;'.$row['gonumber']."</td>\n";
			echo "<td colspan='8'>쿠폰할인 : <b>".number_format($sum['dc_amt'])."</b> ";
			echo "적립금결제 : <b>".number_format($sum['po_amt'])."</b> ";
			echo "배송비 : <b>".number_format($sum['del_amt'])."</b></td>\n";
			echo "<td class='fc_red tar bold'>".number_format($sum['use_amt'])."</td>\n";
			echo "<td class='tac'>".buypath($row['path'])."</td>\n";
			echo "<td colspan='3' class='tar bold'>총계 : ".number_format($sum['amt']+$sum['del_amt'])."</td>\n";
			echo "</tr>\n";
		}
	}
	if($i==0)
		echo '<tr><td colspan="17" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<?php
include_once("./admin_tail.sub.php");
?>