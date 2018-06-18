<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fregister_form">
	<p class="relay"><?php echo $required; ?> 표시는 필수 입력 및 선택 사항 입니다.</p>
	<table class="horiz">
	<tbody>
	<tr>
		<td>
			<div><?php echo $required; ?>회원명</div>
			<input type="text" name="name" value="<?php echo $member['name']; ?>" <?php if(!$default['de_certify']){echo $input_attr;}?> <?php if($w=='u') echo $input_attr; ?> placeholder='예) 홍길동' required itemname="회원명">
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>아이디 <span id="sit_mb_id"></span></div>
			<input type="text" name="id" id="mb_id" value="<?php echo $member['id']; ?>" onkeyup="reg_mb_id_ajax();" <?php if($w=='u') echo $input_attr; ?> placeholder='공백없는 4 ~ 10 의 영문과 숫자' required memberid itemname="아이디">
		</td>
	</tr>
	<?php if($w=='') { ?>
	<tr>
		<td>
			<div><?php echo $required; ?>비밀번호</div>
			<input type="password" name="passwd" placeholder='4 자 이상의 영문 및 숫자' required itemname="비밀번호">
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>비밀번호확인</div>
			<input type="password" name="repasswd" required itemname="비밀번호확인">
		</td>
	</tr>
	<?php } else if($w=='u') { ?>
	<tr>
		<td>
			<div><?php echo $required; ?>현재비밀번호</div>
			<input type="password" name="dbpasswd" required itemname="비밀번호">
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>새비밀번호</div>
			<input type="password" name="passwd" placeholder='4 자 이상의 영문 및 숫자'>
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>새비밀번호확인</div>
			<input type="password" name="repasswd" placeholder='4 자 이상의 영문 및 숫자'>
		</td>
	</tr>
	<?php } ?>	
	<tr>
		<td>
			<div><?php echo $required; ?>생년월일</div>
			<input name="birth_year" value="<?php echo $member['birth_year']; ?>" maxlength='4' style='width:100px;' required numeric itemname="생년월일" <?php if(!$default['de_certify']){echo $input_attr;}?> placeholder='예) <?php echo date('Y'); ?>'>년&nbsp;&nbsp;
			<input name="birth_month" value="<?php echo $member['birth_month']; ?>" maxlength='2' style='width:50px;' required numeric itemname="생년월일" <?php if(!$default['de_certify']){echo $input_attr;}?> placeholder='예) <?php echo date('m'); ?>'>월&nbsp;&nbsp;
			<input name="birth_day" value="<?php echo $member['birth_day']; ?>" maxlength='2' style='width:50px;' required numeric itemname="생년월일" <?php if(!$default['de_certify']){echo $input_attr;}?> placeholder='예) <?php echo date('d'); ?>'>일
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>성별</div>
			<select name="gender" style="width:100%">
				<option value="">성별</option>
				<option value="M"<?php if($jcheck['j_sex']=='1'){echo " selected";}?>>남자</option>
				<option value="F"<?php if($jcheck['j_sex']=='0'){echo " selected";}?>>여자</option>
			</select>
			<?php if($w=='u') { ?>
			<script>document.fregisterform.gender.value = '<?php echo $member[gender]; ?>';</script>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td>
			<div><?php echo $required; ?>생일구분</div>
			<select name="birth_type" style="width:100%">
				<?php echo option_selected('S', $member['birth_type'], '양력'); ?>
				<?php echo option_selected('L', $member['birth_type'], '음력'); ?>
			</select>
		</td>
	</tr>
	<?php if($config['sp_use_tel']) { if($config['sp_req_tel']) $sp_req_tel = 'required'; ?>
	<tr>
		<td>
			<div><?php echo $config['sp_req_tel'] ? $required : ""; ?>전화번호</div>
			<input type="number" name="telephone" value="<?php echo $member['telephone']; ?>" <?php echo $config['sp_req_tel']?'required':''; ?> itemname="전화번호">
		</td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_hp']) { if($config['sp_rep_hp']) $sp_rep_hp = 'required'; ?>
	<tr>
		<td>
			<div><?php echo $config['sp_rep_hp'] ? $required : ""; ?>핸드폰</div>
			<input type="number" name="cellphone" value="<?php echo $member['cellphone']; ?>" <?php echo $config['sp_rep_hp']?'required':''; ?> itemname="핸드폰">
			<div class="padt5">
				<input name="smsser" type="checkbox" value="Y" <?php echo $member['smsser'] == 'Y'?'checked':''; ?> id="ids_smsser" class="css-checkbox lrg"><label for="ids_smsser" class="css-label padr5">SMS를 수신합니다.</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_email']) { ?>
	<tr>
		<td>
			<div><?php echo $config['sp_req_email'] ? $required : ""; ?>이메일</div>
			<input type="email" name="email" value="<?php echo $member['email']; ?>" placeholder='예) abcd@naver.com' <?php echo $config['sp_req_email']?'required':''; ?> email itemname="이메일">
			<div class="padt5">
				<input name="mailser" type="checkbox" value="Y" <?php echo $member['mailser'] == 'Y'?'checked':''; ?> id="ids_mailser" class="css-checkbox lrg"><label for="ids_mailser" class="css-label padr5">E-Mail을 수신합니다.</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['sp_use_addr']) { ?>
	<tr>
		<td>
			<div><?php echo $config['sp_req_addr'] ? $required : ""; ?>주소</div>
			<ul class='ofh'>
				<li class='fl'><input type="text" name="zip" value="<?php echo $member['zip']; ?>" maxlength='5'
				<?php echo $config['sp_req_addr']?'required':''; ?> itemname="우편번호" style='width:60px;'></li>
				<li class='fl padl3'><a href="javascript:void(0);" onclick="win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_lsmall grey">주소검색</a></li>
			</ul>
			<div class="padt5">
				<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" placeholder='주소' <?php echo $config['sp_req_addr']?'required':''; ?> itemname="주소">
			</div>
			<div class="padt5">
				<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" placeholder='상세주소' <?php echo $config['sp_req_addr']?'required':''; ?> itemname="상세주소">
			</div>
			<div class="padt5">
				<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" placeholder='참고항목' readonly="readonly" itemname="참고항목">
				<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
			</div>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="tac mart10 marb30">
	<input type='submit' value='<?php echo $btn_name; ?>' class='btn_medium'>
	<button type="button" onclick="history.go(-1);" class="btn_medium bx-white">취소</button>
</div>
</form>

<script>
function fregister_submit(f)
{
	var str;
	<?php if($w=='') { ?>
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}
	<?php } ?>

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert('아이디를 3글자 이상 입력하십시오.');
		f.id.focus();
		return false;
	}

	<?php if($w=='') { ?>
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

	str = "회원가입";
	<?php } else if($w=='u') { ?>
	if(f.passwd.value) {
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
	}

	str = "정보수정";
	<?php } ?>

	<?php if($config[sp_use_email]) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm(str+" 하시겠습니까?") == false)
		return false;

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config[sp_prohibit_id])); ?>";
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

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config[sp_prohibit_email]))); ?>";
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