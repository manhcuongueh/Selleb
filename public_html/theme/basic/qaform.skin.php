<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);">
<input type="hidden" name='w' value='<?php echo $w; ?>'>
<input type="hidden" name='gs_id' value='<?php echo $gs_id; ?>'>
<input type="hidden" name='gs_se_id' value='<?php echo $gs['mb_id']; ?>'>
<input type="hidden" name='gs_use_aff' value='<?php echo $gs['use_aff']; ?>'>
<input type="hidden" name='mb_id' value='<?php echo $member['id']; ?>'>
<input type="hidden" name='token' value="<?php echo $token; ?>">
<input type="hidden" name='iq_id' value="<?php echo $iq_id; ?>">

<div class="pop_wrap">
	<h2 class="pop_tit"><i class="fa fa-pencil-square-o"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
	<div class="tbl_frm01 pop_inner">
		<table class="wfull">
		<colgroup>
			<col width='25%'>
			<col width='75%'>
		</colgroup>
		<tbody>
		<tr>
			<th>상품명</th>
			<td><?php echo $gs['gname']; ?></td>
		</tr>
		<tr>
			<th>옵션</th>
			<td>
				<select name="iq_ty" required itemname="문의유형">
					<option value=''<?php echo get_selected($iq_ty, ''); ?>>문의유형(선택)</option>
					<option value='상품'<?php echo get_selected($iq_ty, '상품'); ?>>상품</option>
					<option value='배송'<?php echo get_selected($iq_ty, '배송'); ?>>배송</option>
					<option value='반품/환불/취소'<?php echo get_selected($iq_ty, '반품/환불/취소'); ?>>반품/환불/취소</option>
					<option value='교환/변경'<?php echo get_selected($iq_ty, '교환/변경'); ?>>교환/변경</option>
					<option value='기타'<?php echo get_selected($iq_ty, '기타'); ?>>기타</option>
				</select>
				<input id="iq_secret" type="checkbox" name="iq_secret" value='1'
				<?php echo get_checked($iq_secret, '1'); ?> class="marl7">
				<label for="iq_secret">비밀글</label>
			</td>
		</tr>
		<tr>
			<th>성명</th>
			<td><input type="text" name="iq_name" value='<?php echo $iq_name; ?>' required itemname="성명" class="ed w200"></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td><input type="text" name="iq_email" value='<?php echo $iq_email; ?>' required email itemname='이메일' class="ed w200"></td>
		</tr>
		<tr>
			<th>핸드폰</th>
			<td><input type="text" name="iq_hp" value='<?php echo $iq_hp; ?>' required itemname='핸드폰' class="ed w200"></td>
		</tr>
		<tr>
			<th>제목</th>
			<td><input type="text" name="iq_subject" value='<?php echo $iq_subject; ?>' required itemname='제목' class="ed wfull"></td>
		</tr>
		<tr>
			<th>질문</th>
			<td>
				<textarea name="iq_question" rows="10" required itemname='질문' class="frm_textbox wufll"><?php echo $iq_question; ?></textarea>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="tac">
		<input type="submit" value="확인" class="btn_lsmall">
		<a href="javascript:window.close()" class="btn_lsmall bx-white">취소</a>
	</div>
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("등록 하시겠습니까?") == false)
		return false;

    return true;
}
</script>
