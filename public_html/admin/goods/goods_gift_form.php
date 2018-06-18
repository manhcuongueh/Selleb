<?php
if(!defined('_TUBEWEB_')) exit;

$group = sql_fetch("select * from shop_gift_group where gr_id = '$gr_id'");
$gr_attr = "readonly style='background-color:#dddddd'";

if($w == "") {
	$gr_id = substr(strtoupper(uniqid()),-12);

} else if($w == "u") {
    if(!$group['gr_id'])
        alert("존재하지 않은 쿠폰 입니다.");
}

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="goods.php?code=gift'.$qstr.'&page='.$page.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="goods.php?code=gift_form" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="q1" value="<?php echo $qstr; ?>">
<input type="hidden" name="gr_id" value="<?php echo $gr_id; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>일련번호</th>
		<td><input class="frm_input w200" type="text" name="gr_id" value="<?php echo get_text($gr_id); ?>" <?php echo $gr_attr; ?>></td>
	</tr>
	<tr>
		<th>쿠폰명</th>
		<td><input class="frm_input w325" type="text" name="gr_subject" value='<?php echo get_text($group[gr_subject]); ?>' required></td>
	</tr>
	<tr>
		<th>설명</b></th>
		<td><input class="frm_input w325" type="text" name="gr_explan" value='<?php echo get_text($group[gr_explan]); ?>'></td>
	</tr>
	<tr>
		<th>발행금액</th>
		<td><input class="frm_input w100" type="text" name="gr_price" value='<?php echo $group[gr_price]; ?>'
		required> 원</td>
	</tr>
	<?php if($w == "")  {?>
	<tr>
		<th>발행매수</th>
		<td><input class="frm_input w100" type="text" name="gr_quant" value='<?php echo $group[gr_quant]; ?>'
		<?php echo ($w == "")?'required':''?>> 매</td>
	</tr>
	<?php } ?>
	<tr>
		<th>사용기간</th>
		<td>
			<input type='text' name="gr_sdate" value='<?php echo $group[gr_sdate]; ?>' id="gr_sdate" required class="frm_input w80"> ~
			<input type='text' name="gr_edate" value='<?php echo $group[gr_edate]; ?>' id="gr_edate" required class="frm_input w80">
		</td>
	</tr>
	<?php if($w == "") { ?>
	<tr>
		<th>발행방식</th>
		<td class="td_label">
			<input id="ids_p_use_pg1" type="radio" name="use_gift" value="0" checked>
			<label for="ids_p_use_pg1">숫자로만 발행</label>
			<input id="ids_p_use_pg2" type="radio" name="use_gift" value="1">
			<label for="ids_p_use_pg2">영문, 숫자 혼합해서 발행</label>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function fregform_submit(f) {
	f.action = "./goods/goods_gift_form_update.php";
    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#gr_sdate,#gr_edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>