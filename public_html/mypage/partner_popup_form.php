<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "팝업 관리";
include_once("./admin_head.sub.php");

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$pp = sql_fetch("select * from shop_popup where index_no='$pp_id'");

if($w == "") {
	$pp[state] = 0;
	$pp[begin_date] = $time_ymd;
} else if($w == "u") {
    if(!$pp[index_no])
        alert("팝업이 존재하지 않습니다.");
}
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this)">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="pp_id" value="<?php echo $pp_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>제목</th>
		<td><input type="text" name="title" value="<?php echo get_text($pp[title]); ?>" required itemname="제목" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th>팝업크기(pixel)</th>
		<td>
			<input type="text" name="width" value="<?php echo $pp[width]; ?>" required numeric itemname="팝업크기" class="frm_input w80"> X <input type="text" name="height" value="<?php echo $pp[height]; ?>" required numeric itemname="팝업크기" class="frm_input w80"></td>
		</td>
	</tr>
	<tr>
		<th>팝업위치(pixel)</th>
		<td>
			<input type="text" name="top" value="<?php echo $pp[top]; ?>" required numeric itemname="팝업위치" class="frm_input w80"> X <input type="text" name="lefts" value="<?php echo $pp[lefts]; ?>" required numeric itemname="팝업위치" class="frm_input w80"></td>
		</td>
	</tr>
	<tr>
		<th>실행기간</th>
		<td>
			<input type="text" required itemname="실행기간" value="<?php echo $pp[begin_date]; ?>" name="begin_date" id="begin_date" class="frm_input w80"> ~
			<input type="text" required itemname="실행기간" value="<?php echo $pp[end_date]; ?>"
			name="end_date" id="end_date" class="frm_input w80">
		</td>
	</tr>
	<tr>
		<th>노출여부</th>
		<td class="td_label">
			<input type="radio" value="0" name="state" id='state_yes'<?php echo ($pp[state]==0)?" checked":"";?>> <label for="state_yes">노출함</label>
			<input type="radio" value="1" name="state" id='state_no'<?php echo ($pp[state]==1)?" checked":"";?>> <label for="state_no">노출안함</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="mart10"><?php echo editor_html('memo', get_text($pp['memo'], 0)); ?></div>

<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
	<a href="page.php?code=partner_popup_list<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('memo'); ?>

	f.action = "./partner_popup_form_update.php";
    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#begin_date,#end_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>