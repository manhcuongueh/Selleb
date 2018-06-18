<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "수익금 로그분석";
include_once("./admin_head.sub.php");

$j_sdate1 = preg_replace('/[^0-9]/', '',$j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '',$j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_paylog ";
$sql_search = " where mb_id = '$member[id]' ";

if($sop)
{	$sql_search .= " and etc2='$sop' "; }

if($j_sdate && $j_ddate)
{	$sql_search .= " and (wdate >= '$j_sdate2' and wdate <= '$j_ddate3')"; }

if($j_sdate && !$j_ddate)
{	$sql_search .= " and (wdate >= '$j_sdate2' and wdate <= '$j_sdate3')"; }

if(!$j_sdate && $j_ddate)
{	$sql_search .= " and (wdate >= '$j_ddate2' and wdate <= '$j_ddate3')"; }

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
<a href="./partner_record_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
EOF;
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<h2>실적선택</h2>
<form name="flinks">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>실적종류</th>
		<td class="td_label">
			<input id="ids_sop1" type="checkbox" name="links" onclick="chk_certify('');" <?php echo get_checked($sop, ''); ?>> <label for="ids_sop1">전체실적</label>
			<?php if($config[p_login]=='y') { ?>
			<input id="ids_sop2" type="checkbox" name="links" onclick="chk_certify('login');" <?php echo get_checked($sop, 'login'); ?>> <label for="ids_sop2"><?php echo $ar_record['login'];?></label>
			<?php } ?>
			<?php if($config[p_member]=='y') { ?>
			<input id="ids_sop3" type="checkbox" name="links" onclick="chk_certify('member');" <?php echo get_checked($sop, 'member'); ?>> <label for="ids_sop3"><?php echo $ar_record['member'];?></label>
			<?php } ?>
			<?php if($config[p_shop]=='y') { ?>
			<input id="ids_sop4" type="checkbox" name="links" onclick="chk_certify('shop');" <?php echo get_checked($sop, 'shop'); ?>> <label for="ids_sop4"><?php echo $ar_record['shop'];?></label>
			<?php } ?>
			<?php if($config[p_month]=='y') { ?>
			<input id="ids_sop5" type="checkbox" name="links" onclick="chk_certify('p_month');" <?php echo get_checked($sop, 'p_month'); ?>> <label for="ids_sop5"><?php echo $ar_record['p_month'];?></label>
			<?php } ?>
			<input id="ids_sop6" type="checkbox" name="links" onclick="chk_certify('admin');" <?php echo get_checked($sop, 'admin'); ?>> <label for="ids_sop6"><?php echo $ar_record['admin'];?></label>
			<input id="ids_sop8" type="checkbox" name="links" onclick="chk_certify('cancel');" <?php echo get_checked($sop, 'cancel'); ?>> <label for="ids_sop8"><?php echo $ar_record['cancel'];?></label>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</form>

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

<div class="local_frm01">
	<?php echo $btn_frmline; ?>	
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="130px">
		<col width="130px">
		<col width="130px">
		<col width="100px">
		<col width="80px">
		<col width="100px">
		<col width="100px">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th>NO</th>
		<th>회원명</th>
		<th>아이디</th>
		<th>레벨</th>
		<th><?php echo subject_sort_link('etc2',$q2);?>구분</a></th>
		<th><?php echo subject_sort_link('wdate',$q2);?>적립일</a></th>
		<th><?php echo subject_sort_link('in_money',$q2);?>적립수수료</a></th>
		<th><?php echo subject_sort_link('ca_money',$q2);?>차감수수료</a></th>
		<th>내역</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id], 'name, grade');

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg;?>'>
		<td><?php echo $num--;?></td>
		<td><?php echo get_text($mb[name]);?></td>
		<td><?php echo $row[mb_id];?></td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td><?php echo $ar_record[$row[etc2]];?></td>
		<td><?php echo date('Y/m/d',$row[wdate]);?></td>
		<td class="tar"><?php echo number_format($row[in_money]);?></td>
		<td class="tar"><?php echo number_format($row[ca_money]);?></td>
		<td class="tal">
			<?php
			switch($row[etc2]) {
				case "login":
					echo "(IP:{$row[ip]}) " . get_text($row[memo]);
					break;
				default:
					echo get_text($row[memo]);
					break;
			}

			if($sop=='shop') echo "<br>주문일련번호:{$row[etc1]}";
			?>
		</td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>

<script>
function chk_certify(sop) {
	location.href = "./page.php?code=partner_record&sop="+sop;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>