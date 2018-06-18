<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_PATH.'/head.sub.php');
?>

<form name="flogin" id="flogin" action="<?php echo TW_BBS_URL; ?>/login_check.php" method="post" onsubmit="return flogin_submit(this);">
<div id="intro">
	<div id="int_wrap">
		<div class="lcont">
			<h1><?php echo display_logo(); ?></h1>
			<h2 class="tit">MEMBER <b>LOGIN</b></h2>
			<p class="fs13">아이디와 패스워드를 입력하신 후 로그인 버튼을 눌러주세요.</p>
			<dl class="int_login">
				<dt><input type="submit" value="로그인" class="btn_large wset"></dt>
				<dd><input name='mb_id' id="mb_id" type="text" value="<?php echo get_cookie('ck_saveid'); ?>" placeholder="아이디를 입력해주세요"></dd>
				<dd><input name="mb_password" type="password" placeholder="비밀번호를 입력해주세요"></dd>
			</dl>
			<p class="marb20"><input type="checkbox" name="auto_saveid" id="auto_saveid" value='y' <?php if(get_cookie('ck_saveid')) { echo "checked";}?>> <label for="auto_saveid" class="fs11">아이디저장</label></p>
			<div class="int_btn">
				<a href="<?php echo TW_BBS_URL; ?>/register.php" class="btn_lsmall grey">회원가입</a>
				<a href="<?php echo TW_BBS_URL; ?>/password_lost.php" onclick="openwindow(this,'pop_password_lost','500','400','no');return false" class="btn_lsmall bx-white">아이디/비밀번호 찾기</a>
			</div>
			<ul class="int-txt">
				<li>일부 브라우저에서 제공하는 자동 로그인 기능을 사용할 경우 개인정보가 유출될 수 있으니 주의 바랍니다.</li>
				<li>아이디/비밀번호를 분실하신 경우, 아이디/비밀번호 찾기 또는 상담센터로 문의 바랍니다.</li>
				<li>상담센터 <?php echo $config['company_tel']; ?>(<?php echo $config['company_hours']; ?>)</li>
			</ul>
		</div>
		<div class="rbanner">
			<?php echo intro_banner_repeat(1,0,0,$pt_id); ?>
			<script>
			$(document).ready(function(){
				$('.rbanner ul').slick({
					autoplay: true,
					dots: true,
					arrows: false
				});
			});
			</script>
		</div>
	</div>
	<div class="int_copy">
		<?php echo $config['company_name']; ?> <span class="g_hl"></span> 대표자 : <?php echo $config['company_owner']; ?> <span class="g_hl"></span> <?php echo $config['company_addr']; ?><br>
		Email : <?php echo $super['email']; ?> <span class="g_hl"></span> 사업자번호 : <?php echo $config['company_saupja_no']; ?> <a  href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall bx-white marl5">사업자정보확인</a> <span class="g_hl"></span> 통신판매번호 : <?php echo $config['tongsin_no']; ?><br>
		<p class="mart5 fc_137 fs11">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
	</div>
</div>
</form>

<script>
document.getElementById('mb_id').focus();
function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert('아이디를 입력하세요.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert('비밀번호를 입력하세요.');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once(TW_PATH."/tail.sub.php");
?>