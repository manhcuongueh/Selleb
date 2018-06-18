<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> 1:1 상담문의</p>
	<h2 class="stit">1:1 상담문의</h2>

	<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
	<input type='hidden' name='mode' value="w">
	<input type='hidden' name='token' value="<?php echo $token; ?>">
	<input type='hidden' name='index_no' value="<?php echo $index_no; ?>">

	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col width='18%'>
			<col width='82%'>
		</colgroup>
		<tbody>
		<tr>
			<th>질문유형</th>
			<td>
				<select name='catename'>
					<option value=''>문의하실 유형을 선택하세요</option>
					<?php
					$sql = "select * from shop_qa_cate where isuse='Y'";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {
						echo "<option ".get_selected($qa['catename'],$row['catename'])." value='$row[catename]'>$row[catename]</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>제목</th>
			<td><input type="text" name='subject' value="<?php echo $qa['subject']; ?>" class="ed wfull"></td>
		</tr>
		<tr>
			<th>내용</th>
			<td><textarea name='memo' class='frm_textbox wufll'><?php echo $qa['memo']; ?></textarea></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td class="td_label">
				<input type="text" name="email" value='<?php echo $qa['email']; ?>' class="ed">
				<p class="mart7">
					<span class="marr10">답변 여부를 문자로 받아보시겠습니까?</span>
					<label><input type='radio' name='email_send_yes' value='1'<?php echo get_checked($qa['email_send_yes'],'1'); ?>> 예</label>
					<label><input type='radio' name='email_send_yes' value='0'<?php echo get_checked($qa['email_send_yes'],'0'); ?>> 아니오</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>휴대폰</th>
			<td class="td_label">
				<input type="text" name="cellphone" value='<?php echo $qa['cellphone']; ?>' class="ed">
				<p class="mart7">
					<span class="marr10">답변 여부를 문자로 받아보시겠습니까?</span>
					<label><input type='radio' name='sms_send_yes' value='1'<?php echo get_checked($qa['sms_send_yes'],'1'); ?>> 예</label>
					<label><input type='radio' name='sms_send_yes' value='0'<?php echo get_checked($qa['sms_send_yes'],'0'); ?>> 아니오</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="mart15 tac">
		<input type="submit" value="수정" class="btn_lsmall marr3">
		<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">취소</a>
	</div>
	</form>
</div>

<script>
function fqaform_submit(f) {
	if(confirm("수정 하시겠습니까?") == false)
		return false;

	return true;
}
</script>