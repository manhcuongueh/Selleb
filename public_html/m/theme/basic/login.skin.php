<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="flogin" method="post" autocomplete="off">
<input type="hidden" name="url" value='<?php echo $login_url; ?>'>
<div class="login_box mart30">
	<section id="mb_info_fld">
		<p class="mart15"><input type="email" name="mb_id" value="<?php echo get_cookie('ck_saveid'); ?>" tabindex="1" placeholder='아이디'></p>
		<p class="mart3"><input type="password" name="mb_password" tabindex="2" placeholder='비밀번호'></p>
	</section>
	<section id="gu_info_fld">
		<p class="mart15"><input type="text" name="od_id" tabindex="1" placeholder='주문번호'></p>
		<p class="mart3"><input type="password" name="od_pwd" tabindex="2" placeholder='비밀번호'></p>
	</section>
	<p class="tal mart10">
		<input type="checkbox" name="auto_saveid" value="1" id="auto_saveid" <?php if(get_cookie('ck_saveid')) { echo "checked";}?> class="css-checkbox lrg"><label for="auto_saveid" class="css-label padr5">아이디저장</label>
		<input type="checkbox" name="chk_guest" value="1" id="chk_guest" class="css-checkbox lrg" <?php echo get_checked($sel_field,"guest"); ?>><label for="chk_guest" class="css-label">비회원 주문/배송조회</label>
	</p>
	<p class="mart10"><a href="javascript:flogin_submit();" class="btn_medium wfull">로그인</a></p>
	<p class="mart3"><a href="./register.php" class="btn_medium bx-white wfull">회원가입</a></p>
	<?php if(preg_match("/orderform.php/", $url)) { ?>
	<p class="mart3"><a href="javascript:guest_submit(document.flogin);" class="btn_medium red wfull">비회원으로 주문하기</a></p>
	<?php } ?>
	<p class="tar mart7">
		<span><a href="./password_lost.php">아이디/비밀번호 찾기</a></span>
	</p>
	<?php if($default['de_sns_login_use']) { ?>
	<p class="sns_btn">
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</p>
	<?php } ?>
</div>
</form>

<script>
function flogin_submit()
{
	var f = document.flogin;

	if(document.getElementById('chk_guest').checked) {
		if(!f.od_id.value) {
			alert('주문번호를 입력하세요.');
			f.od_id.focus();
			return false;
		}
		if(!f.od_pwd.value) {
			alert('비밀번호를 입력해주세요.');
			f.od_pwd.focus();
			return false;
		}
	} else {
		if(!f.mb_id.value) {
			alert('아이디를 입력하세요.');
			f.mb_id.focus();
			return false;
		}
		if(!f.mb_password.value) {
			alert('비밀번호를 입력하세요.');
			f.mb_password.focus();
			return false;
		}
	}

    f.action = './login_check.php';
    f.submit();
}

function guest_submit(f)
{
	f.url.value = "<?php echo $url; ?>";
	f.action = "<?php echo $url; ?>";
	f.submit();
}

$(function(){
	<?php if($sel_field == 'guest') { ?>
	$("#mb_info_fld").hide();
	$("#gu_info_fld").show();
	<?php } ?>
    $("#chk_guest").on("click", function() {
		if($(this).is(":checked")) {
			$("#mb_info_fld").hide();
			$("#gu_info_fld").show();
		} else {
			$("#mb_info_fld").show();
			$("#gu_info_fld").hide();
		}
	});
});
</script>
