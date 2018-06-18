<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">창닫기</a>
</h2>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);">
<input type="hidden" name='w' value='<?php echo $w; ?>'>
<input type="hidden" name='gs_id' value='<?php echo $gs_id; ?>'>
<input type="hidden" name='gs_se_id' value='<?php echo $gs['mb_id']; ?>'>
<input type="hidden" name='gs_use_aff' value='<?php echo $gs['use_aff']; ?>'>
<input type="hidden" name='mb_id' value='<?php echo $member['id']; ?>'>
<input type="hidden" name='token' value="<?php echo $token; ?>">
<input type="hidden" name='iq_id' value="<?php echo $iq_id; ?>">

<div class="m_post">
	<table class="tbl_post">
	<colgroup>
		<col width="80">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td class="mi_dt"><?php echo get_it_image($gs_id, $gs['simg1'], 80, 80); ?></td>
		<td class="mi_bt">
			<?php echo get_text($gs['gname']); ?>
			<p class="bold mart5"><?php echo get_price($gs['index_no']); ?></p>
		</td>
	</tr>
	</tbody>
	</table>

	<div class="w_post mart10">
		<table class="horiz">
		<colgroup>
			<col style="width:80px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<td class="mi_dt vam tac">옵션</td>
			<td class="mi_bt">
				<select name="iq_ty" required itemname="문의유형" style="width:150px">
					<option <?php echo get_selected($iq_ty, ''); ?> value=''>문의유형(선택)</option>
					<option <?php echo get_selected($iq_ty, '상품'); ?> value='상품'>상품</option>
					<option <?php echo get_selected($iq_ty, '배송'); ?> value='배송'>배송</option>
					<option <?php echo get_selected($iq_ty, '반품/환불/취소'); ?> value='반품/환불/취소'>반품/환불/취소</option>
					<option <?php echo get_selected($iq_ty, '교환/변경'); ?> value='교환/변경'>교환/변경</option>
					<option <?php echo get_selected($iq_ty, '기타'); ?> value='기타'>기타</option>
				</select>&nbsp;&nbsp;&nbsp;
				<input id="iq_secret" type="checkbox" name="iq_secret" value='1' class="css-checkbox lrg"
				<?php echo get_checked($iq_secret, '1'); ?>><label for="iq_secret" class="css-label padr5">비밀글</label>
			</td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">성명</td>
			<td class="mi_bt"><input type="text" name="iq_name" value='<?php echo $iq_name; ?>'
			required itemname="성명"></td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">이메일</td>
			<td class="mi_bt"><input type="text" name="iq_email" value='<?php echo $iq_email; ?>'
			required email itemname='이메일'></td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">핸드폰</td>
			<td class="mi_bt"><input type="text" name="iq_hp" value='<?php echo $iq_hp; ?>'
			required itemname='핸드폰'></td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">제목</td>
			<td class="mi_bt"><input type="text" name="iq_subject" value='<?php echo $iq_subject; ?>'
			required itemname='제목'></td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">질문</td>
			<td class="mi_bt">
				<textarea name="iq_question" required itemname='질문'><?php echo $iq_question; ?></textarea>
			</td>
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
function fqaform_submit(f) {
	if(confirm("등록 하시겠습니까?") == false)
		return false;

    return true;
}
</script>
