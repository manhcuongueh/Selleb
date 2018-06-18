<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="flogin" id="flogin" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return flogin_submit(this);" autocomplete="off">
<input type="hidden" name="url" value="<?php echo $url; ?>">

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 로그인</p>
<h2 class="stit">LOGIN</h2>
<ul class="login_tab">
	<li data-tab="mb_info_fld"><span>회원 로그인</span></li>
	<li data-tab="gu_info_fld" <?php echo get_checked($sel_field,"guest"); ?>><span>비회원 주문조회</span></li>
</ul>
<div class="login_wrap" id="mb_info_fld">
	<dl class="log_inner">
		<dt>회원 로그인</dt>
		<dd class="stxt">로그인하시면 다양한 서비스와 혜택을 받으실 수 있습니다.</dd>
		<dd><input type="text" name="mb_id" value="<?php echo get_cookie('ck_saveid'); ?>" id="mb_id" tabindex="1" placeholder="아이디를 입력해주세요"></dd>
		<dd><input type="password" name="mb_password" tabindex="2" placeholder="비밀번호를 입력해주세요"></dd>
		<dd><button type="submit" class="btn_large">로그인</button></dd>
		<?php if(preg_match("/orderform/", $url)) { ?>
		<dd><a href="<?php echo TW_SHOP_URL; ?>/orderform.php" class="btn_large red wfull">비회원 구매하기</a></dd>
		<?php } ?>
		<dd class="log_op">
			<span><input type="checkbox" name="auto_saveid" id="auto_saveid" value='y'<?php if(get_cookie('ck_saveid')) { echo " checked";}?>> <label for="auto_saveid">아이디저장</label></span>
			<span class="fr"><a href="<?php echo TW_BBS_URL; ?>/password_lost.php" onclick="openwindow(this,'pop_password_lost','500','400','no');return false;">아이디 / 비밀번호 찾기</a></span>
		</dd>
	</dl>
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_btn">
		<h3>SNS 계정 로그인</h3>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<div class="login_wrap" id="gu_info_fld">
	<dl class="log_inner">
		<dt>비회원 주문조회</dt>
		<dd class="stxt">결제 완료 후 안내해드린 주문번호와 주문 결제 시에 작성한 비밀번호를 입력해주세요.</dd>
		<dd><input type="text" name="od_id" tabindex="1" placeholder="주문번호를 입력해주세요"></dd>
		<dd><input type="password" name="od_pwd" tabindex="2" placeholder="비밀번호를 입력해주세요"></dd>
		<dd><button type="submit" class="btn_large">확인</button></dd>
	</dl>
</div>
<div class="log_bt_box">
	회원가입하시고 풍성한 혜택을 누리세요.
	<a href="<?php echo TW_BBS_URL; ?>/register.php" class="btn_lsmall bx-white marl15">회원가입</a>
</div>
</form>

<script>
function flogin_submit(f)
{
	if($("#gu_info_fld").hasClass('active')) {
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

    return true;
}

$(document).ready(function(){
	<?php if($sel_field == 'guest') { ?>
		$(".login_tab>li:eq(1)").addClass('active');
		$("#gu_info_fld").addClass('active');
	<?php } else { ?>
		$(".login_tab>li:eq(0)").addClass('active');
		$("#mb_info_fld").addClass('active');
	<?php } ?>

	$(".login_tab>li").click(function() {
		var activeTab = $(this).attr('data-tab');
		$(".login_tab>li").removeClass('active');
		$(".login_wrap").removeClass('active');
		$(this).addClass('active');
		$("#"+activeTab).addClass('active');
	});
});
</script>
