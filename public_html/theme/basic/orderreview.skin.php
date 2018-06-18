<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="forderreview" class="pop_wrap" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return forderreview_submit(this);">
<input type="hidden" name='gs_id' value='<?php echo $gs_id; ?>'>
<input type="hidden" name='gs_se_id' value="<?php echo $gs['mb_id']; ?>">
<input type="hidden" name='token' value="<?php echo $token; ?>">

<h2 class="pop_tit"><i class="fa fa-pencil-square-o"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
<div class="tbl_frm01 pop_inner">
	<table class="wfull">
	<colgroup>
		<col width='15%'>
		<col width='85%'>
	</colgroup>
	<tbody>
	<tr>
		<th>상품명</th>
		<td><?php echo get_text($gs['gname']); ?></td>
	</tr>
	<tr>
		<th>주문번호</th>
		<td><?php echo $od_id; ?></td>
	</tr>
	<tr>
		<th>이름</th>
		<td><?php echo $member['name']; ?></td>
	</tr>
	<tr>
		<th>평점</th>
		<td>
			<input type="radio" name="score" value="5" checked>
			<img src="<?php echo TW_IMG_URL ?>/sub/score_5.gif">
			<input type="radio" name="score" value="4">
			<img src="<?php echo TW_IMG_URL ?>/sub/score_4.gif">
			<input type="radio" name="score" value="3">
			<img src="<?php echo TW_IMG_URL ?>/sub/score_3.gif">
			<input type="radio" name="score" value="2">
			<img src="<?php echo TW_IMG_URL ?>/sub/score_2.gif">
			<input type="radio" name="score" value="1">
			<img src="<?php echo TW_IMG_URL ?>/sub/score_1.gif">
		</td>
	</tr>
	<tr>
		<th>내용</th>
		<td><textarea name="memo" class="frm_textbox wufll"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="tac">
	<input type="submit" value="확인" class="btn_lsmall">
	<a href="javascript:window.close()" class="btn_lsmall bx-white">취소</a>
</div>
</form>

<script>
function forderreview_submit(f) {
	if(!f.memo.value) {
		alert('내용을 입력하세요.');
		f.memo.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;

    return true;
}
</script>