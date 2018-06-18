<?php
if(!defined('_TUBEWEB_')) exit;

$group = sql_fetch("select * from shop_board_group where gr_id = '$gr_id'");

if($w == "") {
	$gr_id_attr = "required";

} else if($w == "u") {
    if(!$group[gr_id])
        alert("존재하지 않은 게시판그룹 입니다.");

	$gr_id_attr = "readonly style='background-color:#ddd'";
}
?>

<form name="fboardgroup" method="post" onsubmit="return fboardgroup_check(this);" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>그룹 ID</th>
		<td><input type="text" name="gr_id" value='<?php echo $group[gr_id]; ?>' <?php echo $gr_id_attr; ?> alphanumericunderline itemname='그룹 아이디' class="frm_input"> 영문자, 숫자, _ 만 가능 (공백없이)</td>
	</tr>
	<tr>
		<th>그룹 제목</th>
		<td><input type="text" name="gr_subject" value="<?php echo get_text($group[gr_subject]); ?>" required itemname="그룹 제목" class="frm_input" size="80"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="config.php?code=board_group&page=<?php echo $page; ?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function fboardgroup_check(f) {
	f.action = "./config/board_group_form_update.php";
    return true;
}
</script>
