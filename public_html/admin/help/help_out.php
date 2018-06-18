<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member_leave ";
$sql_search = " where (1) ";
$sql_order  = " order by index_no desc";

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
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
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
				<?php echo option_selected('mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
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

<form name="fleave" method="post">
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="130px">
		<col width="130px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th>번호</th>
		<th>회원명</th>
		<th>아이디</th>
		<th>탈퇴사유</th>
		<th>신청일</th>
		<th>처리시간</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$re_table = $row[index_no];

		if($row['isover']==0) {
			$s_upd = "<a href='/admin/member_delete.php?index_no=$row[mb_no]$qstr&page=$page&replace=alldel' class=\"btn_small\">탈퇴승인</a>";
		} else {
			$s_upd = "완료";
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="re_table[<?php echo $i; ?>]" value="<?php echo $re_table; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><?php echo get_text($row[name]);?></td>
		<td><?php echo $row[mb_id];?></td>
		<td class="tal">
			<?php
			if($row['memo']=='기타' && $row[other]) {
				echo get_text($row[other]);
			} else {
				echo get_text($row[memo]);
			}
			?>
		</td>
		<td><?php echo date("Y-m-d",$row[wdate]);?></td>
		<td><?php echo ($row[isover]==1) ? $row[dwdate] : '';?></td>
		<td><?php echo $s_upd;?></td>
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
	var f = document.fleave;

    if(act == "delete") // 선택삭제
    {
		f.action = './help/help_out_list_delete.php';
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
