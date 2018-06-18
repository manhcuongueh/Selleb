<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gw_head_title = '쿠폰 (인쇄용)';
include_once(TW_ADMIN_PATH."/admin_head.php");

$query_string = "gr_id=$gr_id$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_gift ";
$sql_search = " where gr_id='$gr_id' ";

if($stx && $sfl) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

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

$group = sql_fetch("select * from shop_gift_group where gr_id = '$gr_id'");
?>

<h1 class="newp_tit"><?php echo $gw_head_title; ?></h1>
<div class="new_win_body">
	<form name="fsearch" id="fsearch" method="get">
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col width="120px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>검색키워드</th>
			<td>
				<select name="sfl">
					<?php echo option_selected('mb_name', $sfl, '회원명'); ?>
					<?php echo option_selected('mb_id', $sfl, '회원ID'); ?>
					<?php echo option_selected('gi_num', $sfl, '인증번호'); ?>
				</select>
				<input class="frm_input" type="text" name="stx" value='<?php echo $stx; ?>'>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="검색" class="btn_lsmall">
		<input type="button" value="초기화" id="frmRest" class="btn_lsmall grey">
	</div>
	</form>

	<div class="local_ov mart30">
		전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
		<span class="ov_a">쿠폰명 : <?php echo get_text($group['gr_subject']); ?></span>
	</div>
	<div class="local_frm01">
		<a href="./goods_gift_excel.php?gr_id=<?php echo $gr_id.$qstr; ?>" class="btn_small bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
	</div>
	<div class="tbl_head01">
		<table>
		<colgroup>
			<col width="50px">
			<col width="150px">
			<col width="50px">
			<col>
			<col>
			<col width="130px">
		</colgroup>
		<thead>
		<tr>
			<th>번호</th>
			<th>인증번호</th>
			<th><?php echo subject_sort_link('gi_use',$q2); ?>사용</a></th>
			<th><?php echo subject_sort_link('mb_id',$q2); ?>회원ID</a></th>
			<th><?php echo subject_sort_link('mb_name',$q2); ?>회원명</a></th>
			<th><?php echo subject_sort_link('mb_wdate',$q2); ?>최종사용일</a></th>
		</tr>
		</thead>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if(is_null_time($row[mb_wdate])) {
				$row[mb_wdate] = '';
			}

			$bg = 'list'.($i%2);

			if($i==0)
				echo '<tbody class="list">'.PHP_EOL;
		?>
		<tr class='<?php echo $bg; ?>'>
			<td><?php echo $num--; ?></td>
			<td><?php echo $row[gi_num]; ?></td>
			<td><?php echo $row[gi_use]?'yes':''; ?></td>
			<td><?php echo $row[mb_id]; ?></td>
			<td><?php echo $row[mb_name]; ?></td>
			<td><?php echo $row[mb_wdate]; ?></td>
		</tr>
		<?php 
		}
		if($i==0)
			echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
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
</div>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>