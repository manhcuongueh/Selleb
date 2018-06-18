<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '',$j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '',$j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_paylog a, shop_member b, shop_order c ";
$sql_search = " where a.mb_id=b.id and a.etc1=c.orderno and a.mb_id!='admin' and a.etc2='shop' and c.dan=5 ";

if($stx && $sfl)
{	$sql_search .= " and ($sfl like '$stx%') "; }

if($sst)
{	$sql_search .= " and (b.grade='$sst') "; }

if($j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')"; }

if($j_sdate && !$j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')"; }

if(!$j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')"; }

if(!$orderby) {
    $filed = "a.wdate";
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

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
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
<input type="hidden" name='code' value="<?php echo $code;?>">
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
			<select name="sst">
				<option value=''>레벨</option>
				<?php
				$sql = "select * from shop_member_grade where index_no!='1' and grade_name!=''";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++){
					echo option_selected($row[index_no], $sst, $row[grade_name]);
				}
				?>
			</select>
			<select name="sfl">
				<?php echo option_selected('a.mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('b.name', $sfl, '회원명'); ?>
				<?php echo option_selected('c.name', $sfl, '주문자명'); ?>
				<?php echo option_selected('c.odrkey', $sfl, '주문번호'); ?>
				<?php echo option_selected('c.orderno', $sfl, '일련번호'); ?>
				<?php echo option_selected('c.incomename', $sfl, '입금자명'); ?>
				<?php echo option_selected('c.b_name', $sfl, '수령자명'); ?>
				<?php echo option_selected('c.b_telephone', $sfl, '수령자집전화'); ?>
				<?php echo option_selected('c.b_cellphone', $sfl, '수령자핸드폰'); ?>
				<?php echo option_selected('c.b_addr1', $sfl, '배송지주소'); ?>
				<?php echo option_selected('c.gonumber', $sfl, '송장번호'); ?>
				<?php echo option_selected('c.gs_se_id', $sfl, '판매자ID'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>적립일</th>
		<td>
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

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="60px">
		<col width="130px">
		<col width="130px">
		<col width="130px">
		<col width="80px">
		<col width="80px">
		<col width="">
		<col width="">
		<col width="">
		<col width="130px">
	</colgroup>
	<thead>
	<tr>
		<th>NO</th>
		<th>IMG</th>
		<th><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th><?php echo subject_sort_link('a.mb_id',$q2)?>아이디</a></th>
		<th><?php echo subject_sort_link('b.grade',$q2)?>레벨</a></th>
		<th><?php echo subject_sort_link('a.wdate',$q2)?>적립일</a></th>
		<th>수량</th>
		<th><?php echo subject_sort_link('c.use_account',$q2)?>결제금액</a></th>
		<th><?php echo subject_sort_link('c.use_point',$q2)?>적립금결제</a></th>
		<th><?php echo subject_sort_link('c.del_account',$q2)?>배송비</a></th>
		<th><?php echo subject_sort_link('c.orderno',$q2)?>일련번호</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id]);

		// 장바구니 검사
		$sql  = " select * from shop_cart where orderno = '$row[etc1]' ";
		$sql .= " group by gs_id ";
		$sql .= " order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		for($j=0; $ct=sql_fetch_array($res); $j++) {
			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (0),(ct_qty))) as qty
					   from shop_cart
					  where gs_id = '$ct[gs_id]'
						and odrkey = '$ct[odrkey]' ";
			$sum = sql_fetch($sql);
		}

		$qty = (int)$sum['qty'];

		$od = get_order($row[etc1]);
		$gs = get_order_goods($row[etc1]);

		if($gs[money_type]=='1')
			$s_ty = "<span class='fc_197 bold'>[개별]</span>&nbsp;";
		else
			$s_ty = "<span class='fc_255 bold'>[공통]</span>&nbsp;";

		$bg = 'list'.($i%2);
	?>
	<tr class='<?php echo $bg;?>'>
		<td rowspan="2"><?php echo $num--;?></td>
		<td rowspan="2"><?php echo get_od_image($od['odrkey'], $gs['simg1'], 50, 50); ?></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false"><?php echo get_text($mb[name]);?></a></td>
		<td><?php echo $mb[id];?></td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td><?php echo date('Y/m/d',$row[wdate]);?></td>
		<td><?php echo number_format($qty);?></td>
		<td class="tar"><?php echo number_format($od[use_account]);?></td>
		<td class="tar"><?php echo number_format($od[use_point]);?></td>
		<td class="tar"><?php echo number_format($od[del_account]);?></td>
		<td><a href='./pop_order_main.php?index_no=<?php echo $od[index_no];?>' onclick="openwindow(this,'pop_order','953','800','yes');return false"><font class="fc_197"><?php echo $row[etc1];?></font></a></td>
	</tr>
	<tr class='<?php echo $bg;?>'>
		<td colspan="8" class="tal"><?php echo $s_ty.get_text($row[memo]);?></td>
		<td><?php echo $ar_method[$od[buymethod]];?></td>
	</tr>
	<?php 
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
