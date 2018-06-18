<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_my.skin.php');
?>

<div class="rbody">

	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 회원정보수정</p>
	<h2 class="stit">회원정보수정</h2>

	<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col width="18%">
			<col width="82%">
		</colgroup>
		<tbody>
		<tr>
			<th><span class='fc_red'>*</span> 회원명</th>
			<td><input class="ed" type="text" name="name" value="<?php echo $member['name']; ?>" <?php echo $input_attr; ?> size="20"></td>
		</tr>
		<tr>
			<th><span class='fc_red'>*</span> 아이디</th>
			<td><input class="ed" type="text" name="id" value="<?php echo $member['id']; ?>" <?php echo $input_attr; ?> size="20"></td>
		</tr>
		<tr>
			<th><span class='fc_red'>*</span> 현재비밀번호</th>
			<td><input class="ed" type="password" name="dbpasswd" required itemname="현재비밀번호" size="20"></td>
		</tr>
		<tr>
			<th><span class='marl9'></span> 새비밀번호</th>
			<td><input class="ed marr5" type="password" name="passwd" size="20">
			<font color="#66a2c8">※ 4자 이상의 영문 및 숫자</font></td>
		</tr>
		<tr>
			<th><span class='marl9'></span> 새비밀번호확인</th>
			<td><input class="ed" type="password" name="repasswd" size="20"></td>
		</tr>
		<tr>
			<th><span class='fc_red'>*</span> 생년월일</th>
			<td>
				<div class="tbl_wrap">
				<table>
				<tr>
					<td><input class="ed" value="<?php echo $member['birth_year']; ?>" size="7" maxlength="4" required numeric itemname="생년월일" name="birth_year"> 년</td>
					<td class="padl5"><input class="ed" value="<?php echo $member['birth_month']; ?>" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_month"> 월</td>
					<td class="padl5"><input class="ed" value="<?php echo $member['birth_day']; ?>" size="4" maxlength="2" required numeric itemname="생년월일" name="birth_day"> 일</td>
					<td class="padl5">
						<select name="gender">
						<option value="">성별</option>
						<option <?php echo get_selected($member['birth_type'],"M"); ?> value="M">남자</option>
						<option <?php echo get_selected($member['birth_type'],"F"); ?> value="F">여자</option>
						</select>
					</td>
					<td class="padl5">
						<select name="birth_type">
						<option <?php echo get_selected($member['birth_type'],"S"); ?> value="S">양력</option>
						<option <?php echo get_selected($member['birth_type'],"L"); ?> value="L">음력</option>
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
			<td><input class="ed" type="text" name="telephone" value="<?php echo $member['telephone']; ?>" size="20" <?php echo $config['sp_req_tel']?'required':''; ?> itemname="전화번호"></td>
		</tr>
		<?php } ?>
		<?php if($config['sp_use_hp']) { ?>
		<tr>
			<th><?php echo $config['sp_rep_hp']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 휴대전화</th>
			<td>
				<input class="ed" type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" size="20" <?php echo $config['sp_rep_hp']?'required':''; ?> itemname="휴대전화">
				<input type="checkbox" value='Y' name='smsser' class="marl7" <?php echo $member['smsser'] == 'Y'?'checked':''; ?>> SMS를 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['sp_use_email']) { ?>
		<tr>
			<th><?php echo $config['sp_req_email']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 이메일</th>
			<td>
				<input class="ed" type="text" name="email" value="<?php echo $member['email']; ?>" <?php echo $config['sp_req_email']?'required':''; ?> email itemname="이메일" size="40"> <input type='checkbox' value='Y' name='mailser' class="marl7" <?php echo $member['mailser'] == 'Y'?'checked':''; ?>> E-Mail을 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['sp_use_addr']) { ?>
		<tr>
			<th><?php echo $config['sp_req_addr']?"<span class='fc_red'>*</span>":"<span class='marl9'></span>"; ?> 주소</th>
			<td>
				<div>
					<input class="ed" type="text" name="zip" value="<?php echo $member['zip']; ?>" <?php echo $config['sp_req_addr']?'required':''; ?> numeric itemname="우편번호" size="7" maxlength="5" readonly> <a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">우편번호</a>
				</div>
				<div class="mart5">
					<input class="ed" type="text" name="addr1" value="<?php echo $member['addr1']; ?>" <?php echo $config['sp_req_addr']?'required':''; ?> itemname="주소" size="60" readonly>
				</div>
				<div class="mart5">
					<input class="ed marr5" type="text" name="addr2" value="<?php echo $member['addr2']; ?>" <?php echo $config['sp_req_addr']?'required':''; ?> itemname="상세주소" size="60"> ※ 상세주소
				</div>
				<div class="mart5">
					<input class="ed marr5" type="text" name="addr3" value="<?php echo $member['addr3']; ?>"  itemname="참고항목" size="60"> ※ 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="tac mart20">
		<input type="submit" value="정보수정" class="btn_medium">
		<a href="<?php echo TW_BBS_URL; ?>/leave_form.php" class="btn_medium bx-white marl3">회원탈퇴</a>
	</div>
	</form>
</div>

<script>
function fregisterform_submit(f)
{
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

	<?php if($config['sp_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	return true;
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
</script>
