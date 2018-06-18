<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_banner ";
$sql_search = " where (bn_theme = '{$super['theme']}' or bn_mobile_theme = '{$super['mobile_theme']}') 
				  and mb_id = 'admin' ";

$sql_order  = " order by bn_code desc ";

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
<a href="design.php?code=banner_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>

<form name="fbannerlist" method="post">
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

<h2>PC스킨(<?php echo $super['theme']; ?>), 모바일스킨(<?php echo $super['mobile_theme']; ?>)</h2>
<div class="local_ov">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="60px">
		<col width="220px">
		<col width="50px">
		<col width="70px">
		<col width="70px">
		<col width="80px">
		<col>
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>코드</th>
		<th>배너</th>
		<th>감춤</th>
		<th>가로</th>
		<th>세로</th>
		<th>새창</th>
		<th>링크주소</th>		
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$ba_table = $row['index_no'];

		$s_upd = "<a href='design.php?code=banner_form&w=u&ba_table=$ba_table&page=$page' class=\"btn_small\">수정</a>";

		$bimg_str = "";
		$bimg = TW_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($bimg) && $row['bn_file']) {
			$bimg = TW_DATA_URL.'/banner/'.$row['bn_file'];
			$bimg_str = '<a href="'.$bimg.'" target="_blank"><img src="'.$bimg.'" width="200" height="50"></a>';
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="ba_table[<?php echo $i; ?>]" value="<?php echo $ba_table; ?>">
		</td>
		<td><input type="text" class="frm_input" name="bn_code[<?php echo $i; ?>]" value="<?php echo $row['bn_code']; ?>"></td>
		<td><?php echo $bimg_str; ?></td>
		<td><input type="checkbox" name="bn_use[<?php echo $i; ?>]" value="1" <?php echo get_checked($row['bn_use'],"1"); ?>></td>
		<td><input type="text" class="frm_input" name="bn_width[<?php echo $i; ?>]" value="<?php echo $row['bn_width']; ?>"></td>
		<td><input type="text" class="frm_input" name="bn_height[<?php echo $i; ?>]" value="<?php echo $row['bn_height']; ?>"></td>
		<td>
			<select name="bn_target[<?php echo $i; ?>]">
				<option value="_self"<?php echo get_selected($row['bn_target'],"_self"); ?>>현재창</option>
				<option value="_blank"<?php echo get_selected($row['bn_target'],"_blank"); ?>>새창</option>
			</select>
		</td>
		<td><input type="text" name="bn_link[<?php echo $i; ?>]" class="frm_input" value="<?php echo $row['bn_link']; ?>" placeholder="링크주소"></td>
		<td><?php echo $s_upd; ?></td>
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
	var f = document.fbannerlist;

    if(act == "update") // 선택수정
    {
        f.action = './design/web_banner_list_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './design/web_banner_list_delete.php';
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
