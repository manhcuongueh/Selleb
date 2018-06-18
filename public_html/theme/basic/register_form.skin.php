<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div><img src="<?php echo TW_IMG_URL; ?>/register_2.gif"></div>
<div class="tbl_frm01 mart20">
	<table class="wfull">
	<colgroup>
		<col width="18%">
		<col width="82%">
	</colgroup>
	<tbody>
	<tr>
		<th><span class='fc_red'>*</span> 회원명</th>
		<td><input class="ed" type="text" name="name" required itemname="회원명" size="20"
		value="<?php echo $name; ?>" <?php if($default['de_certify']=='0'){echo $input_attr;}?>></td>
	</tr>
	<tr>
		<th><span class='fc_red'>*</span> 아이디</th>
		<td>
			<input class="ed" type="text" name="id" id="mb_id" required memberid itemname="아이디" size="20"  onkeyup="reg_mb_id_ajax();"> <span id="sit_mb_id" class="marl5"></span>
			<p class="fc_red mart7">※ 영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</p>
		</td>
	</tr>
	<tr>
		<th><span class='fc_red'>*</span> 비밀번호</th>
		<td><input class="ed" type="password" name="passwd" required itemname="비밀번호" size="20"> <span class="fc_red marl5">※ 4 자 이상의 영문 및 숫자</span></td>
	</tr>
	<tr>
		<th><span class='fc_red'>*</span> 비밀번호확인</th>
		<td><input class="ed" type="password" name="repasswd" required itemname="비밀번호확인" size="20"></td>
	</tr>	
	<tr>
		<th><span class='fc_red'>*</span> 생년월일</th>
		<td>
			<div class="tbl_wrap">
			<table>
			<tr>
				<td><input class="ed" type="text" value="<?php echo $year; ?>" size="7" maxlength="4" required numeric itemname="생년월일" name="birth_year" <?php if($default['de_certify']=='0'){echo $input_attr;}?>> 년</td>
				<td class="padl5"><input class="ed" type="text" value="<?php echo $month; ?>" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_month" <?php if($default['de_certify']=='0'){echo $input_attr;}?>> 월</td>
				<td class="padl5"><input class="ed w40" type="text" value="<?php echo $day; ?>" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_day" <?php if($default['de_certify']=='0'){echo $input_attr;}?>> 일</td>
				<td class="padl5">
					<select name="gender">
					<option value="">성별</option>
					<option value="M"<?php if($jcheck['j_sex']=='1'){echo " selected";}?>>남자</option>
					<option value="F"<?php if($jcheck['j_sex']=='0'){echo " selected";}?>>여자</option>
					</select>
				</td>
				<td class="padl5">
					<select name="birth_type">
					<option value="S">양력</option>
					<option value="L">음력</option>
					</select>
				</td>
			</tr>
			</table>
			</div>
		</td>
	</tr>
	<?php if($config['sp_use_tel']) { ?>
	<tr>
		<th><?php echo $config['sp_req_tel']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 전화번호</th>
		<td><input class="ed" type="text" name="telephone" size="20"<?php echo $config['sp_req_tel']?' required':''; ?> itemname="전화번호"></td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_hp']) { ?>
	<tr>
		<th><?php echo $config['sp_rep_hp']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 휴대전화</th>
		<td>
			<input class="ed" type="text" name="cellphone" value="<?php echo $jcheck['cell']; ?>" <?php if($default['de_certify']=='0' && $jcheck['cell']){echo $input_attr;}?> size="20"<?php echo $config['sp_rep_hp']?' required':''; ?> itemname="휴대전화">
			<input type="checkbox" checked value='Y' name='smsser' class="marl7"> SMS를 수신합니다.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_email']) { ?>
	<tr>
		<th><?php echo $config['sp_req_email']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 이메일</th>
		<td>
			<input class="ed" type="email" name="email"<?php echo $config['sp_req_email']?' required':''; ?>
			email itemname="이메일" size="40">
			<input type="checkbox" checked value='Y' name='mailser' class="marl7"> E-Mail을 수신합니다.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_addr']) { ?>
	<tr>
		<th><?php echo $config['sp_req_addr']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 주소</th>
		<td>
			<div>
				<input class="ed" type="text" name="zip"<?php echo $config['sp_req_addr']?' required':''; ?> numeric itemname="우편번호" size="7" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">우편번호</a>
			</div>
			<div class="mart5">
				<input class="ed" type="text" name="addr1"<?php echo $config['sp_req_addr']?' required':''; ?> itemname="주소" size="60" readonly>
			</div>
			<div class="mart5">
				<input class="ed marr5" type="text" name="addr2"<?php echo $config['sp_req_addr']?' required':''; ?> itemname="상세주소" size="60"> ※ 상세주소
			</div>
			<div class="mart5">
				<input class="ed marr5" type="text" name="addr3" itemname="참고항목" size="60"> ※ 참고항목
				<input type="hidden" name="addr_jibeon" value="">
			</div>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="tac mart20"><input type="submit" value="회원가입" class="btn_medium"></div>
</form>

<script>
function fregisterform_submit(f)
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

	<?php if($config['sp_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm("회원가입 하시겠습니까?") == false)
		return false;

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['sp_prohibit_id'])); ?>";
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

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['sp_prohibit_email']))); ?>";
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
		"./ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#sit_mb_id").empty().html(data);
		}
	);
}
</script>