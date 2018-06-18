<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>본인인증 / I-PIN 가입정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원가입시 본인인증</th>
		<td class="td_label">
			<input id='ids_1' type="radio" value="0" name="de_certify" <?php echo ($default[de_certify]==0)?"checked":""?>> <label for="ids_1">사용함</label>
			<input id='ids_2' type="radio" value="1" name="de_certify" <?php echo ($default[de_certify]==1)?"checked":""?>> <label for="ids_2">사용안함</label>
		</td>
	</tr>
	<tr>
		 <th>본인인증 회사</th>
		 <td>
			 <select size="1" name="de_certify_nm">
				<option value="namecheck">나이스체크</option>
			 </select>
			 <a href="http://idcheck.co.kr" target="_blank" class="btn_small grey">나이스체크 바로가기</a>
		 </td>
	</tr>
	<tr>
		 <th>본인인증) 사이트 코드</th>
		 <td><input type="text" name="de_checkplus_id" value="<?php echo $default[de_checkplus_id]; ?>" maxlength="255" class="frm_input w200"></td>
	</tr>
	<tr>
		 <th>본인인증) 사이트 패스워드</th>
		 <td><input type="text" name="de_checkplus_pw" value="<?php echo $default[de_checkplus_pw]; ?>" maxlength="255" class="frm_input w200"></td>
	</tr>
	<tr>
		 <th>아이핀) 사이트 코드</th>
		 <td><input type="text" name="de_ipin_id" value="<?php echo $default[de_ipin_id]; ?>" maxlength="255" class="frm_input w200"></td>
	</tr>
	<tr>
		 <th>아이핀) 사이트 패스워드</th>
		 <td><input type="text" name="de_ipin_pw" value="<?php echo $default[de_ipin_pw]; ?>" maxlength="255" class="frm_input w200"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ안내1) 본인인증을 사용하실 경우 회원가입시 <strong>휴대폰인증 및 아이핀인증</strong>이 작동 됩니다.</p>
			<p>ㆍ안내2) 본인인증은 반드시 아래 계약담당자와 계약체결 이후 사용하셔야 합니다.</p>
			<p>ㆍ안내3) <strong>담당자 : 박헌수(대리)</strong> / 직통전화 : 02-2122-4541 / F A X : 02-2122-4805</p>
			<p>ㆍ안내4) 본인인증 서비스에 관해 좀더 궁금하신 사항이 있으시면 투비웹 사이트를 참조하시기 바랍니다.</p>
		</div>
	 </div>
</div>

<script>
function fregform_submit(f) {
	f.action = "./config/nicecheck_update.php";
    return true;
}
</script>
