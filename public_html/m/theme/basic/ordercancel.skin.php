<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">닫기</a>
</h2>

<form name="fordercancel" id="fordercancel" method="post" onsubmit="return fordercancel_check(this);">
<input type='hidden' name='ca_key' value="<?php echo $od_id; ?>">
<input type='hidden' name='ca_type' value="<?php echo $ca_type; ?>">
<input type='hidden' name='ca_od_uid' value='<?php echo $od['index_no']; ?>'>
<input type='hidden' name='ca_od_dan' value='<?php echo $od['dan']; ?>'>
<input type='hidden' name='ca_it_aff' value='<?php echo $gs['use_aff']; ?>'>
<input type='hidden' name='ca_it_seller' value='<?php echo $gs['mb_id']; ?>'>
<input type='hidden' name='ca_cancel_use' value='주문취소'>
<input type='hidden' name='token' value="<?php echo $token; ?>">

<div class="m_od_bg">
	<p class="relay bold">1, 취소사유</p>
	<div class="w_post">
		<table class="horiz">
		<colgroup>
			<col style="width:80px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam tar">주문번호</td>
			<td class="mi_st"><?php echo $od['odrkey']; ?></td>
		</tr>
		<tr>
			<td class="mi_dt vam tar">주문상품</td>
			<td class="mi_st"><?php echo $gs['gname']; ?></td>
		</tr>
		<tr>
			<td class="mi_dt vam tar"><span class="fc_red">*</span> 사유</td>
			<td class="mi_bt">
				<?php echo get_cancel_select("ca_cancel", 'style="width:100%"'); ?>			
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam tar"><span class="fc_red">*</span> 상세사유</td>
			<td class="mi_bt"><textarea name="ca_memo" class="frm_textbox wufll h60"></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

	<p class="relay bold">2, 환불계좌</p>
	<div class="w_post">
		<table class="horiz">
		<colgroup>
			<col style="width:80px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam tar">은행명</td>
			<td class="mi_bt">
				<?php echo get_bank_select("ca_bankcd", 'style="width:100%"'); ?>			
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam tar">계좌번호</td>
			<td class="mi_bt"><input type="text" name="ca_banknum" itemname='계좌번호'></td>
		</tr>
		<tr>
			<td class="mi_dt vam tar">예금주명</td>
			<td class="mi_bt"><input type="text" name="ca_bankname" itemname='예금주명'></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="tac mart10">
		<button type="submit" class="btn_medium">확인</button>
		<button type="button" onclick="window.close();" class="btn_medium bx-white">취소</button>
	</div>
</div>
</form>

<script>
function fordercancel_check(f) {
	if(!f.ca_cancel.value) {
		alert('사유를 선택해 주십시오.');
		f.ca_cancel.focus();
		return false;
	}
	if(!f.ca_memo.value) {
		alert('상세사유를 입력해 주십시오.');
		f.ca_memo.focus();
		return false;
	}
	if(confirm(f.ca_cancel_use.value+" 요청 하시겠습니까?") == false)
		return false;

	f.action = "./ordercancel_update.php";
    return true;
}
</script>