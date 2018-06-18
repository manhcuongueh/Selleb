<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fordercancel" id="fordercancel" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fordercancel_check(this);">
<input type='hidden' name='ca_key' value="<?php echo $od_id; ?>">
<input type='hidden' name='ca_type' value="<?php echo $ca_type; ?>">
<input type='hidden' name='ca_od_uid' value='<?php echo $od['index_no']; ?>'>
<input type='hidden' name='ca_od_dan' value='<?php echo $od['dan']; ?>'>
<input type='hidden' name='ca_it_aff' value='<?php echo $gs['use_aff']; ?>'>
<input type='hidden' name='ca_it_seller' value='<?php echo $gs['mb_id']; ?>'>
<input type='hidden' name='ca_cancel_use' value='주문취소'>
<input type='hidden' name='token' value="<?php echo $token; ?>">

<div class="pop_wrap">
	<h2 class="pop_tit"><i class="fa fa-pencil-square-o"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
	<div class="tbl_frm01 pop_inner">
		<table class="wfull">
		<colgroup>
			<col width='17%'>
			<col width='83%'>
		</colgroup>
		<tr>
			<th>주문번호</th>
			<td><?php echo $od['odrkey']; ?></td>
		</tr>
		<tr>
			<th>주문상품</th>
			<td><?php echo $gs['gname']; ?></td>
		</tr>
		<tr>
			<th>사유</th>
			<td>
				<?php echo get_cancel_select("ca_cancel"); ?>
			</td>
		</tr>
		<tr>
			<th>상세사유</th>
			<td><textarea name="ca_memo" class="frm_textbox wufll"></textarea></td>
		</tr>
		<tr>
			<th>환불계좌</th>
			<td>
				<?php echo get_bank_select("ca_bankcd"); ?>
				계좌번호 <input type="text" name="ca_banknum" class="ed" size="20">
				예금주 <input type="text" name="ca_bankname" class="ed" size="10">
			</td>
		</tr>
		</table>
	</div>
	<div class="tac">
		<input type="submit" value="확인" class="btn_lsmall">
		<a href="javascript:window.close()" class="btn_lsmall bx-white">취소</a>
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

    return true;
}
</script>