<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>신규회원 등록</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원명</th>
		<td><input class="frm_input" type="text" name="name" required itemname="회원명"></td>
	</tr>
	<tr>
		<th>아이디</th>
		<td>
			<input class="frm_input" type="text" name="id" id="mb_id" required memberid itemname="아이디" onkeyup="reg_mb_id_ajax();"> <span id="sit_mb_id"></span>
			<p class="mart5 fc_197">※ 영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</p>
		</td>
	</tr>
	<tr>
		<th>비밀번호</th>
		<td><input class="frm_input" type="password" name="passwd" required itemname="비밀번호"> <span class="fc_197">※ 4 자 이상의 영문 및 숫자</span></td>
	</tr>
	<tr>
		<th>비밀번호확인</th>
		<td><input class="frm_input" type="password" name="repasswd" required itemname="비밀번호확인"></td>
	</tr>	
	<tr>
		<th>생년월일</th>
		<td>
			<input class="frm_input" size="7" maxlength="4" required numeric itemname="생년월일" name="birth_year"> 년
			<input class="frm_input marl7" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_month"> 월
			<input class="frm_input marl7" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_day"> 일
			<select name="birth_type" class="marl10">
				<option value="S">양력</option>
				<option value="L">음력</option>
			</select>
			<select name="gender">
				<option value="">성별</option>
				<option value="M">남자</option>
				<option value="F">여자</option>
			</select>
		</td>
	</tr>
	<?php
	if($config[sp_use_tel]) {
		if($config[sp_req_tel]) $sp_req_tel = 'required';
	?>
	<tr>
		<th>전화번호</th>
		<td>
			<input class="frm_input" type="text" name="telephone" <?php echo $config[sp_req_tel]?'required':'';?> itemname="전화번호">
		</td>
	</tr>
	<?php } ?>
	<?php
	if($config[sp_use_hp]) {
		if($config[sp_rep_hp]) $sp_rep_hp = 'required';
	?>
	<tr>
		<th>휴대전화</th>
		<td>
			<input class="frm_input" type="text" name="cellphone" <?php echo $config[sp_rep_hp]?'required':'';?> itemname="휴대전화">
			<input type="checkbox" checked value='Y' name='smsser' class="marl10">
			<span class="fc_197">SMS를 수신합니다.</span>
		</td>
	</tr>
	<?php } ?>
	<?php if($config[sp_use_email]) { ?>
	<tr>
		<th>이메일</th>
		<td>
			<input class="frm_input" type="text" name="email" <?php echo $config[sp_req_email]?'required':'';?> email itemname="이메일">
			<input type="checkbox" checked value='Y' name='mailser' class="marl10"> <span class="fc_197">E-Mail을 수신합니다.</span>
		</td>
	</tr>
	<?php } ?>
	<?php if($config[sp_use_addr]) { ?>
	<tr>
		<th>주소</th>
		<td>
			<p>
			<input class="frm_input" type="text" name="zip" <?php echo $config[sp_req_addr]?'required':'';?>
			numeric itemname="우편번호" size="7" maxlength="5" readonly>
			<a href="javascript:win_zip('fregform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">우편번호</a>
			</p>
			<p class="mart5"><input class="frm_input w460" type="text" name="addr1" <?php echo $config[sp_req_addr]?'required':'';?>
			itemname="주소"  readonly></p>
			<p class="mart5"><input class="frm_input w460" type="text" name="addr2" <?php echo $config[sp_req_addr]?'required':'';?>
			itemname="상세주소"> <span class="fc_197">※ 상세주소</span></p>
			<p class="mart5"><input class="frm_input w460" type="text" name="addr3" itemname="참고항목"> ※ 참고항목
			<input type="hidden" name="addr_jibeon" value=""></p>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th>추천인</th>
		<td><input class="frm_input" type="text" name="pt_id" value="admin" required itemname="추천인"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert('아이디를 3글자 이상 입력하십시오.');
		f.id.focus();
		return false;
	}

    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert('패스워드를 4글자 이상 입력하십시오.');
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert('패스워드가 같지 않습니다.');
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert('패스워드를 4글자 이상 입력하십시오.');
            f.repasswd.focus();
            return false;
        }
    }

	<?php if($config[sp_use_email]) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n저장 하시려면 '확인'버튼을 클릭하세요") == false)
		return false;

    f.action = "./member/mem_register_form_update.php";
    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config[sp_prohibit_id]));?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
}

// 금지 메일 도메인 검사
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config[sp_prohibit_email])));?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 메일 도메인만 얻는다

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#mb_id").val());
	$.post(
		gw_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#sit_mb_id").empty().html(data);
		}
	);
}
</script>