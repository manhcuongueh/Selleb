<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">창닫기</a>
</h2>

<form name="fuserform" id="fuserform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fuserform_submit(this);">
<input type="hidden" name='w' value='<?php echo $w; ?>'>
<input type="hidden" name='gs_id' value='<?php echo $gs_id; ?>'>
<input type="hidden" name='gs_se_id' value="<?php echo $gs['mb_id']; ?>">
<input type="hidden" name='token' value="<?php echo $token; ?>">
<input type="hidden" name='me_id' value="<?php echo $me_id; ?>">

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
			<td class="mi_dt vam tac">내용</td>
			<td class="mi_bt"><textarea name="wr_content"><?php echo $wr_content; ?></textarea></td>
		</tr>
		<tr>
			<td class="mi_dt vam tac">평점</td>
			<td class="mi_bt">
				<select name='wr_score' style="width:100%">
					<option value="">평점 선택하기</option>
					<option value="5"<?php echo get_selected($wr_score, '5'); ?>><?php echo $arr_sco[5]; ?></option>
					<option value="4"<?php echo get_selected($wr_score, '4'); ?>><?php echo $arr_sco[4]; ?></option>
					<option value="3"<?php echo get_selected($wr_score, '3'); ?>><?php echo $arr_sco[3]; ?></option>
					<option value="2"<?php echo get_selected($wr_score, '2'); ?>><?php echo $arr_sco[2]; ?></option>
					<option value="1"<?php echo get_selected($wr_score, '1'); ?>><?php echo $arr_sco[1]; ?></option>
				</select>
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
function fuserform_submit(f) {
	if(!f.wr_content.value) {
		alert('내용을 입력하세요.');
		f.wr_content.focus();
		return false;
	}

	if(!getSelectVal(f["wr_score"])){
		alert('평점을 선택하세요.');
		f.wr_score.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;

    return true;
}
</script>
