<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '', $j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '', $j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_point a left join shop_member b ON ( a.mb_no=b.index_no ) ";
$sql_search = " where a.mb_no != '1' ";

if($sfl && $stx) {
    $sql_search .= " and (b.$sfl like '%$stx%') ";
}
if($j_sdate && $j_ddate) {
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')";
}
if($j_sdate && !$j_ddate) {
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')";
}
if(!$j_sdate && $j_ddate) {
	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')"; 
}

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

$mb = sql_fetch("select sum(point) as total from shop_member where id!='admin'");

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택삭제</button>
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
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>검색키워드</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>적립날짜</th>
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
	<span class="stxt">전체 : <b class="fc_197"><?php echo number_format($total_count);?></b> 건 ,</span>
	<span>회원 포인트 합계 : <b class="fc_197"><?php echo number_format($mb['total']);?></b> 원</span>	
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>

<form name="fpointlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="130px">
		<col width="130px">
		<col width="130px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th scope="col">NO</th>
		<th scope="col"><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('b.id',$q2)?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('b.grade',$q2)?>레벨</a></th>
		<th scope="col">내역</th>
		<th scope="col"><?php echo subject_sort_link('a.outcome',$q2)?>차감액</a></th>
		<th scope="col"><?php echo subject_sort_link('a.income',$q2)?>적립액</a></th>
		<th scope="col"><?php echo subject_sort_link('a.total',$q2)?>잔액</a></th>
		<th scope="col"><?php echo subject_sort_link('a.wdate',$q2)?>적립날짜</a></th>
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
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td class="tal"><a href="pop_member_main.php?index_no=<?php echo $row[mb_no];?>" onclick="openwindow(this,'pop_member','1000','600','yes');return false;"><?php echo get_text($mb[name]);?></a></td>
		<td class="tal"><?php echo $mb[id];?></td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td class="tal"><?php echo get_text($row[memo]);?></td>
		<td class="tar"><?php echo number_format($row[outcome]);?></span></td>
		<td class="tar"><?php echo number_format($row[income]);?></span></td>
		<td class="tar"><?php echo number_format($row[total]);?></span></td>
		<td><?php echo date("Y/m/d",$row['wdate']);?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<form>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

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
	var f = document.fpointlist;

    if(act == "delete") // 선택삭제
    {
        f.action = '/admin/member/mem_point_delete.php';
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