<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_plan ";
$sql_search = " where (1) ";
$sql_order  = " order by pl_no desc ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

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

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('update');" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택삭제</button>
<a href="goods.php?code=plan_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 기획전등록</a>
EOF;
?>

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
				<?php echo option_selected('pl_name', $sfl, '기획전명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w325">
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

<form name='fplanlist' method='post'>
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

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
		<col width="50px">
		<col>
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>번호</th>
		<th>노출</th>
		<th>기획전명</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$s_upd = "<a href='goods.php?code=plan_form&w=u&pl_no={$row['pl_no']}$qstr&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="pl_no[<?php echo $i; ?>]" value="<?php echo $row['pl_no']; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="checkbox" name="pl_use[<?php echo $i; ?>]" value="1" <?php echo get_checked($row['pl_use'], 1); ?>></td>
		<td><input type="text" name="pl_name[<?php echo $i; ?>]" value="<?php echo $row['pl_name']; ?>" class="frm_input"></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
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
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
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
	var f = document.fplanlist;

    if(act == "update") // 선택수정
    {
        f.action = './goods/goods_plan_list_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './goods/goods_plan_list_delete.php';
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
