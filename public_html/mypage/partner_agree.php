<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "쇼핑몰 약관 설정";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>약관 설정</h2>
<div class="local_cmd01">
	<p>※ 아래 설정값이 없으면 본사 설정값으로 대체되어 노출됩니다.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원가입약관<br>(회원가입 시)</th>
		<td><textarea name="sp_provision" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['sp_provision']); ?></textarea></td>
	</tr>
	<tr>
		<th>개인정보 수집 및 이용<br>(회원가입 시)</th>
		<td><textarea name="sp_private" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['sp_private']); ?></textarea></td>
	</tr>
	<tr>
		<th>개인정보처리방침</th>
		<td><textarea name="sp_policy" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['sp_policy']); ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./partner_agree_update.php";
    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>