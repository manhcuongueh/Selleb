<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>기본설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원가입시 포인트</th>
		<td>
			<input type="text" name='join_point' value="<?php echo number_format($config['join_point']); ?>" class="frm_input w80" onkeyup="addComma(this)"> 원 <span class='"fc_197"'>(최초 1회 적립)</span>
		</td>
	</tr>
	<tr>
		<th>추천인 포인트</th>
		<td>
			<input type="text" name='reco_point' value="<?php echo number_format($config['reco_point']); ?>" class="frm_input w80" onkeyup="addComma(this)"> 원 <span class='"fc_197"'>(최초 1회 적립)</span>
		</td>
	</tr>
	<tr>
		<th>핸드폰 입력</th>
		<td class="td_label">
			<input id="ids_sp_use_hp" type='checkbox' name='sp_use_hp' value='1' <?php echo $config['sp_use_hp']?'checked':''; ?>> <label for="ids_sp_use_hp">보이기</label>
			<input id="ids_sp_rep_hp" type='checkbox' name='sp_rep_hp' value='1' <?php echo $config['sp_rep_hp']?'checked':''; ?>> <label for="ids_sp_rep_hp">필수입력</label>
		</td>
	</tr>
	<tr>
		<th>전화번호 입력</th>
		<td class="td_label">
			<input id="ids_sp_use_tel" type='checkbox' name='sp_use_tel' value='1' <?php echo $config['sp_use_tel']?'checked':''; ?>> <label for="ids_sp_use_tel">보이기</label>
			<input id="ids_sp_req_tel" type='checkbox' name='sp_req_tel' value='1' <?php echo $config['sp_req_tel']?'checked':''; ?>> <label for="ids_sp_req_tel">필수입력</label>
		</td>
	</tr>
	<tr>
		<th>주소 입력</th>
		<td class="td_label">
			<input id="ids_sp_use_addr" type='checkbox' name='sp_use_addr' value='1' <?php echo $config['sp_use_addr']?'checked':''; ?>> <label for="ids_sp_use_addr">보이기</label>
			<input id="ids_sp_req_addr" type='checkbox' name='sp_req_addr' value='1' <?php echo $config['sp_req_addr']?'checked':''; ?>> <label for="ids_sp_req_addr">필수입력</label>
		</td>
	</tr>
	<tr>
		<th>이메일 입력</th>
		<td class="td_label">
			<input id="ids_sp_use_email" type='checkbox' name='sp_use_email' value='1' <?php echo $config['sp_use_email']?'checked':''; ?>> <label for="ids_sp_use_email">보이기</label>
			<input id="ids_sp_req_email" type='checkbox' name='sp_req_email' value='1' <?php echo $config['sp_req_email']?'checked':''; ?>> <label for="ids_sp_req_email">필수입력</label>
		</td>
	</tr>
	<tr>
		<th>가입불가 ID</th>
		<td>
			<textarea name="sp_prohibit_id" class="frm_textbox wfull" rows="3"><?php echo $config['sp_prohibit_id']; ?></textarea>
			<?php echo help('입력된 단어가 포함된 내용은 회원아이디로 사용할 수 없습니다. 단어와 단어 사이는 , 로 구분합니다.'); ?>
		</td>
	</tr>
	<tr>
		<th>가입불가 E-mail</th>
		<td>
			<textarea name="sp_prohibit_email" class="frm_textbox wfull" rows="3"><?php echo $config['sp_prohibit_email']; ?></textarea>
			<?php echo help('hanmail.net과 같은 메일 주소는 입력을 못합니다. 엔터로 구분합니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>약관 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원가입약관<br>(회원가입 시)</th>
		<td><textarea name="sp_provision" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['sp_provision']); ?></textarea></td>
	</tr>
	<tr>
		<th>개인정보 수집 및 이용<br>(회원가입 시)</th>
		<td><textarea name="sp_private" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['sp_private']); ?></textarea></td>
	</tr>
	<tr>
		<th>개인정보처리방침</th>
		<td><textarea name="sp_policy" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['sp_policy']); ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./config/register_update.php";
    return true;
}
</script>