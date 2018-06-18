<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$basicgroup = array('gr_mall','gr_item');

$sql_common = " from shop_board_group ";
$sql_search = " where (1) ";
$sql_order  = " order by gr_id asc";

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
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="config.php?code=board_group_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>

<form name="fboardgrouplist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="local_ov">
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
		<col width="120px">
		<col>
		<col width="60px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th>그룹아이디</th>
		<th>제목</th>
		<th>게시판</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		// 게시판수
		$sql2 = " select count(*) as cnt from shop_board_conf where gr_id = '$row[gr_id]' ";
		$row2 = sql_fetch($sql2);

		$s_upd = "<a href='config.php?code=board_group_form&w=u&gr_id=$row[gr_id]&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.$i%2;

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="gr_id[<?php echo $i; ?>]" value="<?php echo $row[gr_id]; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td class="tal"><?php echo $row[gr_id]; ?></td>
		<td class="tal"><input type="text" class="frm_input" name="gr_subject[<?php echo $i; ?>]" value="<?php echo get_text($row[gr_subject]); ?>"></td>
		<td><?php echo $row2[cnt]; ?></td>
		<td><?php echo $s_upd; ?></td>
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
	var f = document.fboardgrouplist;

    if(act == "update") // 선택수정
    {
        f.action = './config/board_group_list_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './config/board_group_list_delete.php';
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
