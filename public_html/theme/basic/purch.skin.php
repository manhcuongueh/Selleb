<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fpurchform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fpurchform_submit(this);" autocomplete="off">
<input type="hidden" name='wr_gname' value='<?php echo $gs['gname']; ?>'>
<input type="hidden" name='wr_gcode' value='<?php echo $gs['gcode']; ?>'>
<input type="hidden" name='wr_guser' value='<?php echo $gs['use_aff']; ?>'>
<input type="hidden" name='token' value='<?php echo $token; ?>'>
<input type="hidden" name='mb_id' value='<?php echo $mb_id; ?>'>

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
			<th>상품코드</th>
			<td><?php echo $gs['gcode']; ?></td>
		</tr>
		<tr>
			<th>가격</th>
			<td><?php echo get_price($gs['index_no'])?></td>
		</tr>
		<tr>
			<th>성명</th>
			<td><input class="ed w200" type="text" name="wr_name" value='<?php echo $member['name']; ?>' required itemname="성명"></td>
		</tr>
		<tr>
			<th>발송 이메일</th>
			<td><input class="ed w200" type="text" name="wr_semail" value='<?php echo $member['email']; ?>' required itemname='발송 이메일'></td>
		</tr>
		<tr>
			<th>수신 이메일</th>
			<td><input class="ed w200" type="text" name="wr_remail" value='<?php echo $seller['email']; ?>' required itemname='수신 이메일'></td>
		</tr>
		<tr>
			<th>메일제목</th>
			<td><input class="ed wfull" type="text" name="wr_subject" required itemname='메일제목'></td>
		</tr>
		<tr>
			<th>내용</th>
			<td><textarea name="wr_content" rows="10" class="frm_textbox wufll"></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="tac">
		<input type="submit" value="메일전송" class="btn_lsmall">
		<a href="javascript:window.close()" class="btn_lsmall bx-white">취소</a>
	</div>
</div>
</form>

<script>
function fpurchform_submit(f) {
	if(confirm("발송 하시겠습니까?") == false)
		return false;

	return true;
}
</script>
