<?php
if(!defined('_TUBEWEB_')) exit;

$s_date = date('Y-m');

if(!$j_sdate) $j_sdate = $s_date."-01";
if(!$j_ddate) $j_ddate = $s_date."-31";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

if($mode == 'w') {
	check_demo();

	$sql = "insert into shop_seller_cal
			   set month = '$time_ymd',
			       idx = '$_POST[i_table]',
				   money = '$_POST[money]',
				   mb_id = '$_POST[mb_id]',
				   wdate = '$server_time'";
	sql_query($sql);

	$i_table = explode("|",$i_table);
	for($i=0; $i<count($i_table); $i++){
		sql_query("update shop_order set itempay_yes='1' where index_no='$i_table[$i]' and itempay_yes='0'");
	}

	alert("정상적으로 처리 되었습니다.","./seller.php?$q1&page=$page");
}

if($mode == 'dell') {
	check_demo();

	$m_day  = sql_fetch("select * from shop_seller_cal where index_no='$idx'");
	$db_idx = explode("|",$m_day[idx]);

	for($i=0; $i<count($db_idx); $i++){
		sql_query("update shop_order set itempay_yes='0' where index_no='$db_idx[$i]' and itempay_yes='1'");
	}

	sql_query("delete from shop_seller_cal where index_no='$idx'");

	alert("정상적으로 취소 되었습니다.","./seller.php?$q1&page=$page");
}

$sql_common = " from shop_seller ";
$sql_search = " where state='1' ";
if($stx && $sfl) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "wdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

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

$btn_frmline = <<<EOF
<a href="./seller/item_total_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
EOF;
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name='code' value="<?php echo $code;?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>검색키워드</th>
		<td>
			<select name="sfl">				
				<?php echo option_selected('in_compay', $sfl, '업체명'); ?>
				<?php echo option_selected('sup_code', $sfl, '업체코드'); ?>
				<?php echo option_selected('in_name', $sfl, '대표자명'); ?>
				<?php echo option_selected('mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('in_dam', $sfl, '담당자명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>판매기간</th>
		<td>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate, false); ?>
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
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col>
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th rowspan="2">No</th>
		<th colspan="13">업체정보</th>
		<th rowspan="2">정산금액(정산일)</th>
		<th rowspan="2">처리</th>
	</tr>
	<tr class="rows">
		<th>총건수</th>
		<th>주문총계</th>
		<th>공급가총계</th>
		<th>결제수수료</th>
		<th>정산액총계</th>
		<th>가맹점수수료</th>
		<th>포인트적립</th>
		<th>포인트결제</th>
		<th>쿠폰할인</th>
		<th>배송비</th>
		<th>옵션가</th>
		<th>실공급가</th>
		<th>본사마진</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$mb = get_member($row['mb_id']);

		$u			= 0;
		$i_table	= '';
		$i_total	= 0;
		$tot_damt	= 0;
		$tot_amt	= 0;
		$tot_upoint	= 0;
		$tot_point	= 0;
		$tot_result	= 0;
		$tot_af_amt = 0;
		$tot_del	= 0;
		$tot_dc		= 0;
		$tot_iamt	= 0;
		$tot_di_amt = 0;
		$tot_super	= 0;

		$sql = "select * 
				  from shop_order
				 where gs_se_id = '$row[sup_code]'
				   and dan = '5'
				   and itempay_yes = '0'
				   and ('$j_sdate' <= overdate_s and overdate_s <= '$j_ddate')
				   and user_ok = '1'";
		$result2 = sql_query($sql);
		while($row2 = sql_fetch_array($result2)) {
			// 상품정보
			$gs = get_order_goods($row2['orderno']);

			// 수수료 적립로그
			$p_log = sql_fetch("select SUM(in_money) as total from shop_partner_paylog where etc1='$row2[orderno]'");

			$sql  = " select * from shop_cart where orderno = '$row2[orderno]' ";
			$sql .= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			for($k=0; $ct=sql_fetch_array($res); $k++) {
				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + $gs[daccount]) * ct_qty))) as damt,
								SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
								SUM(io_price * ct_qty) as iamt
						   from shop_cart
						  where gs_id = '$ct[gs_id]'
							and odrkey = '$ct[odrkey]' ";
				$sum = sql_fetch($sql);
			}

			$damt	= (int)$sum['damt'];
			$point	= (int)$sum['point'];
			$qty	= (int)$sum['qty'];
			$iamt	= (int)$sum['iamt'];

			$tot_damt	+= $damt; // 공급가
			$tot_amt	+= (int)$row2['account']; // 주문금액
			$tot_upoint += (int)$row2['use_point']; // 적립금결제
			$tot_del	+= (int)$row2['del_account']; // 배송비결제
			$tot_dc		+= (int)$row2['dc_exp_amt']; // 쿠폰할인
			$tot_point  += $point; // 포인트적립
			$tot_iamt	+= $iamt; // 옵션가
			$tot_di_amt += $damt - $iamt; // 실공급가

			$tot_af_amt += (int)$p_log['total'];

			if($u==0)
				$i_table = $i_table.$row2['index_no'];
			else
				$i_table = $i_table."|".$row2['index_no'];

			$u++;

			switch($row2['buymethod']) {
				case 'C': // 신용카드
					$pg_amt	= ($row2['use_account']/100) * $config['shop_card'];
					$tot_result	+= (int)$pg_amt;
					break;
				case 'ER': // 에스크로 계좌이체
				case 'R': // 계좌이체
					$pg_amt	= ($row2['use_account']/100) * $config['shop_bank'];
					$tot_result	+= (int)$pg_amt;
					break;
				case 'H': // 핸드폰
					if($config['shop_phone_type']=='%')
						$pg_amt = ($row2['use_account']/100) * $config['shop_phone'];
					else
						$pg_amt = $config['shop_phone'];

					$tot_result	+= (int)$pg_amt;
					break;
				case 'ES': // 에스크로 가상계좌
				case 'S': // 가상계좌
					if($config['shop_yesc_type']=='%')
						$pg_amt = ($row2['use_account']/100) * $config['shop_yesc'];
					else
						$pg_amt = $config['shop_yesc'];

					$tot_result	+= (int)$pg_amt;
					break;
			}
		}

		// 결제수수료 0:공급업체부담 , 1:본사부담
		if($config['shop_i']==0)
			$tot_margin	= $tot_damt - $tot_result;
		else
			$tot_margin	= $tot_damt;

		// 본사마진 계산
		$tot_super = ($tot_amt - $tot_margin) - ($tot_af_amt + $tot_upoint + $tot_point + $tot_dc);
	?>
	<form name="f<?php echo $row[index_no]?>" method='post'>
	<input type="hidden" name="mode" value="w">
	<input type="hidden" name="money" value="<?php echo $tot_margin;?>">
	<input type="hidden" name="i_table" value="<?php echo $i_table;?>">
	<input type="hidden" name="mb_id" value="<?php echo $row['mb_id'];?>">
	<input type="hidden" name="q1" value="<?php echo $q1;?>">
	<tr class='<?php echo $bg;?>'>
		<td rowspan="2"><?php echo $num--;?></td>
		<td colspan="13" class="tal"><?php echo $row['sup_code'];?></b>) <b><?php echo $row['in_compay'];?></b></td>
		<td rowspan="2" class="tar">
			<?php
			$sql_search = " and ('$j_sdate' <= month and month <= '$j_ddate') ";
			$q = sql_query("select * from shop_seller_cal where mb_id='$row[mb_id]' $sql_search ");
			while($r=sql_fetch_array($q)){
				$i_total += (int)$r[money];
			?>
			<span class="fs14">(<?php echo date('Y.m.d',$r['wdate'])?>)</span>&nbsp;<img src='./img/tab-close-on.gif' onclick="month_del('<?php echo $r['index_no']?>','<?php echo $q1;?>')" style="cursor:pointer" align="absmiddle"><br>
			정산금액 : <?php echo number_format($r['money'])?>원<br>
			<?php } // while ?><span class="fs14 bold">총계 : <?php echo number_format($i_total)?>원</span>
		</td>
		<td rowspan="2">
			<?php if($u > 0) { ?><img src='./img/btn_calculate.gif' onclick="if(confirm('정산처리 하시겠습니까?')){pay_submit(document.forms['f<?php echo $row['index_no']?>'])};" style="cursor:pointer" align="absmiddle"><?php } ?>
		</td>
	</tr>
	<tr class='<?php echo $bg;?> rows'>
		<td><?php echo $u;?></td>
		<td class="tar"><?php echo number_format($tot_amt);?></td>
		<td class="tar"><?php echo number_format($tot_damt);?></td>
		<td class="tar">(-) <?php echo number_format($tot_result);?></td>
		<td class="tar"><font class="fc_197"><?php echo number_format($tot_margin);?></font></td>
		<td class="tar"><?php echo number_format($tot_af_amt);?></td>
		<td class="tar"><?php echo number_format($tot_point);?>P</td>
		<td class="tar"><?php echo number_format($tot_upoint);?>P</td>
		<td class="tar"><?php echo number_format($tot_dc);?></td>
		<td class="tar"><?php echo number_format($tot_del);?></td>
		<td class="tar"><?php echo number_format($tot_iamt);?></td>
		<td class="tar"><?php echo number_format($tot_di_amt);?></td>
		<td class="tar fc_red"><?php echo number_format($tot_super);?></td>
	</tr>
	</form>
	<?php 
	}
	if($i==0)
		echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<script>
function pay_submit(obj){
	obj.submit();
}

function month_del(idx, q1){
	if(confirm('선택한 정산을 삭제 하시겠습니까?')){
		location.href = './seller.php?'+q1+'&mode=dell&idx='+idx;
	}
}
</script>
