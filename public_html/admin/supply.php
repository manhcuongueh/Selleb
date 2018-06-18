<?php
include_once("_common.php");

$gw_head_title = '업체선택';
include_once(TW_ADMIN_PATH."/admin_head.php");

$query_string = $qstr;
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_seller ";
$sql_search = " where state='1' ";

if($sfl) {
    $sql_search .= " and sup_code = '$sfl' ";
}

if($stx) {
    $sql_search .= " and (in_compay like '%$stx%') ";
}

if(!$orderby) {
    $filed = "in_compay";
    $sod = "asc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod";

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
?>

<h1 class="newp_tit"><?php echo $gw_head_title; ?></h1>
<div class="new_win_body">
	<form name="fsearch" id="fsearch" method="get">
	<div class="guidebox tac">
		<b>업체코드 : </b> <input type="text" name="sfl" value="<?php echo $sfl?>" class="frm_input w100 marr10">
		<b>업체명 : </b> <input type="text" name="stx" value="<?php echo $stx?>" class="frm_input w120">
		<input type="submit" value="검색" class="btn_small">
	</div>
	</form>

	<div class="local_frm01 mart20">
		전체 : <b class="fc_197"><?php echo $total_count;?></b> 건 조회
	</div>
	<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="80px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th>번호</th>
		<th>업체코드</th>
		<th><?php echo subject_sort_link('in_compay',$q2)?>업체명</a></th>
		<th><?php echo subject_sort_link('in_name',$q2)?>대표자명</a></th>
		<th><?php echo subject_sort_link('in_dam',$q2)?>담당자명</a></th>
		<th>선택</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$list = $i%2;
	?>
	<tr class="list<?php echo $list;?>">
		<td><?php echo $num--;?></td>
		<td><?php echo $row[sup_code]?></td>
		<td class="tal"><?php echo $row[in_compay]?></td>
		<td><?php echo $row[in_name]?></td>
		<td><?php echo $row[in_dam]?></td>
		<td><button type="button" onClick="yes('<?php echo $row[sup_code]?>')" class="btn_small grey">선택</button></td>
	</tr>
	<?php
	}
	if($total_count==0) {
	?>
	<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>
	<?php } ?>
	</tbody>
	</table>
	</div>

	<?php if($total_count > 0) { ?>
	<div class="btn_confirm">
		<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
	</div>
	<?php } ?>
</div>

<script>
function yes(mb_id){
	opener.document.fregform.mb_id.value = mb_id;
	self.close();
}
</script>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>