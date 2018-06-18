<?php
if(!defined('_TUBEWEB_')) exit;

$mb = get_member_no($index_no);
$query_string = "code=$code&index_no=$index_no$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page&index_no=$index_no";

$sql_common = " from shop_point a left join shop_member b on a.mb_no=b.index_no ";
$sql_search = " where a.mb_no='$index_no' ";

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

// 총금액 뽑기
$sql = " select SUM(outcome) as outcome, SUM(income) as income $sql_common $sql_search ";
$total = sql_fetch($sql);
?>

<h2>포인트적립내역</h2>
<ul class="or_totalbox mart20">
	<li>전체 <p class="mart5 fc_197"><b><?php echo number_format($total_count);?></b> 건</p></li>
	<li>총 차감액 <p class="mart5 fc_red"><b><?php echo number_format($total[outcome]);?></b> 원</p></li>
	<li>총 적립액 <p class="mart5 fc_7d6"><b><?php echo number_format($total[income]);?></b> 원</p></li>
	<li>잔액 <p class="mart5 fc_7d6"><b><?php echo number_format($mb[point]);?></b> 원</p></li>
</ul>

<div class="tbl_head01 mart10">
	<table>
	<colgroup>
		<col width="50px">
		<col>
		<col width="70px">
		<col width="70px">
		<col width="70px">
		<col width="70px">
	</colgroup>
	<thead>
	<tr>
		<th>NO</th>
		<th>내역</th>
		<th><?php echo subject_sort_link('a.outcome',$q2)?>차감액</a></th>
		<th><?php echo subject_sort_link('a.income',$q2)?>적립액</a></th>
		<th><?php echo subject_sort_link('a.total',$q2)?>잔액</a></th>
		<th><?php echo subject_sort_link('a.wdate',$q2)?>적립날짜</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member_no($row[mb_no]);
		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td><?php echo $num--;?></td>
		<td class="tal"><?php echo get_text($row[memo]);?></td>
		<td class="tar"><?php echo number_format($row[outcome]);?></td>
		<td class="tar"><?php echo number_format($row[income]);?></td>
		<td class="tar"><?php echo number_format($row[total]);?></td>
		<td><?php echo date("Y/m/d",$row[wdate]);?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>