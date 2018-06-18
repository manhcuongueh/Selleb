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

$sql_common = " from shop_partner_payuse a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where a.mb_id!='admin' ";

if($stx && $sfl) {
    switch($sfl) {
        case "mb_id" :
            $sql_search .= " and (a.$sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " and (b.$sfl like '%$stx%') ";
            break;
    }
}

if($sst)
{	$sql_search .= " and b.grade='$sst' "; }

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

$btn_frmline = <<<EOF
<a href="./partner/pt_pay_log_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
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
<input type="hidden" name="code" value="<?php echo $code;?>">
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
				<?php echo option_selected('mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>정산일</th>
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
		<col width="80px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col width="">
	</colgroup>
	<thead>
	<tr>
		<th>NO</th>
		<th><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th><?php echo subject_sort_link('a.mb_id',$q2)?>아이디</a></th>
		<th><?php echo subject_sort_link('b.grade',$q2)?>레벨</a></th>
		<th><?php echo subject_sort_link('a.wdate',$q2)?>정산일</a></th>
		<th><?php echo subject_sort_link('a.out_money',$q2)?><?php echo ($config[p_type]=='time')?"출금신청":"수수료"?></a></th>
		<th><?php echo subject_sort_link('a.tax2_money',$q2)?>세금공제</a></th>
		<th><?php echo subject_sort_link('a.tax3_money',$q2)?>실수령액</a></th>
		<th>입금계좌 은행</td>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id]);
		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg;?>'>
		<td><?php echo $num--;?></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false"><?php echo get_text($mb[name]);?></a></td>
		<td><?php echo $mb[id];?></td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td><?php echo date('Y/m/d',$row[wdate]);?></td>
		<td class="tar"><?php echo number_format($row[out_money]);?></td>
		<td class="tar"><?php echo number_format($row[tax2_money]);?></td>
		<td class="tar bold"><?php echo number_format($row[tax3_money]);?></td>
		<td class="tal"><?php echo $row[bankinfo];?></td>
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
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>