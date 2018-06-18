<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "관리자메모 확인";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order_memo a left join shop_order b on a.order_no=b.index_no ";
$sql_search = " where a.gs_se_id = '$seller[sup_code]' ";

if($stx && $sfl) {
    $sql_search .= " and (b.$sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and (b.dan = '$sst') ";
}

if($j_sdate && $j_ddate) {
	$sql_search .= " and (b.orderdate_s >= '$j_sdate' and b.orderdate_s <= '$j_ddate')";
}
if($j_sdate && !$j_ddate) {
	$sql_search .= " and (b.orderdate_s >= '$j_sdate' and b.orderdate_s <= '$j_sdate')";
}
if(!$j_sdate && $j_ddate) {
	$sql_search .= " and (b.orderdate_s >= '$j_ddate' and b.orderdate_s <= '$j_ddate')";
}

if(!$orderby) {
    $filed = "a.wdate";
    $sod = "desc";
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

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
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
		<th>주문현황</th>
		<td>
			<select name="sst">
				<option value=''>선택</option>
				<?php
				for($i=1; $i<=10; $i++) {
					if($i == 9) continue;						
					echo option_selected($i, $sst, $ar_dan[$i]);
				}
				?>
			</select>
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

<form name="frmlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">
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
		<col width="50px">
		<col width="80px">
		<col width="70px">
		<col width="90px">
		<col>
		<col width="70px">
		<col width="130px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th><?php echo subject_sort_link('b.orderno',$q2);?>일련번호</a></th>
		<th><?php echo subject_sort_link('b.orderdate',$q2);?>주문일</a></th>
		<th><?php echo subject_sort_link('b.name',$q2);?>주문자명</a></th>
		<th>메모내용</th>
		<th><?php echo subject_sort_link('a.wdate',$q2);?>작성일</a></th>
		<th><?php echo subject_sort_link('b.dan',$q2);?>현황</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$od = get_order_no($row['order_no']);

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><a href='<?php echo TW_ADMIN_URL;?>/pop_order_main.php?index_no=<?php echo $row['order_no'];?>' onclick="openwindow(this,'pop_order','953','800','yes');return false" class="fc_197"><?php echo get_text($od['orderno']);?></a></td>
		<td><?php echo date("Y/m/d",$od['orderdate']);?></td>
		<td><?php echo get_text($od['name']);?></td>
		<td class="tal"><?php echo get_text($row['amemo']);?></td>
		<td><?php echo date("Y/m/d",$row['wdate']);?></td>
		<td><?php echo $ar_dan[$od['dan']];?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.frmlist;

	if(act == "delete") // 선택삭제
    {
        f.action = './seller_odr_memo_delete.php';
        str = "삭제";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for(i=0; i<chk.length; i++)
    {
        if(chk[i].checked)
            bchk = true;
    }

    if(!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    }

    if(act == "delete")
    {
        if(!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
            return;
    }

    f.submit();
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>