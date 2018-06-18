<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

include_once("./_head.php");
?>

<form name="flogin" method="post" autocomplete="off">
<input type="hidden" name="url" value='<?php echo $login_url;?>'>
<div class="login_box mart30">
	<h2 class="log_tit">MEMBER <strong>LOGIN</strong></h2>
	<p class="mart15"><input name="mb_id" type="email" value="<?php echo get_cookie('ck_saveid'); ?>" tabindex="1" placeholder='아이디' onFocus="this.placeholder=''"></p>
	<p class="mart3"><input name="mb_password" type="password" tabindex="2" placeholder='비밀번호' onFocus="this.placeholder=''"></p>
	<p class="tal mart10">
		<input name="auto_saveid" type="checkbox" value="1" <?php if(get_cookie('ck_saveid')) { echo "checked";}?> id="ids_saveid" class="css-checkbox lrg"><label for="ids_saveid" class="css-label padr5">아이디저장</label>
		<input name="auto_login" type="checkbox" value="1" id="ids_auto" onclick="if(this.checked) { if(confirm('자동로그인을 사용하시면 다음부터 회원아이디와 패스워드를 입력하실 필요가 없습니다.\n\n자동로그인을 사용하시겠습니까?')) { this.checked = true; } else { this.checked = false; } }" class="css-checkbox lrg"><label for="ids_auto" class="css-label">자동로그인</label>
	</p>
	<p class="mart10"><a href="javascript:flogin_submit();" class="btn_medium wset wfull">로그인</a></p>
	<p class="mart3"><a href="./tb/register.php" class="btn_medium bx-white wfull">회원가입</a></p>
	<p class="tar mart7">
		<span><a href="./tb/password_lost.php">아이디/비밀번호 찾기</a></span>
	</p>
</div>
</form>

<script>
function flogin_submit()
{
	var f = document.flogin;

    if(!f.mb_id.value) {
        alert("아이디를 입력해주세요.");
        f.mb_id.focus();
        return;
    }

    if(!f.mb_password.value) {
        alert("패스워드를 입력해주세요.");
        f.mb_password.focus();
        return;
    }

    f.action = './tb/login_check.php';
    f.submit();
}
</script>

<?php
include_once("./_tail.php");
?>