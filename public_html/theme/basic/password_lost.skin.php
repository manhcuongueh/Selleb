<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="pop_wrap">
	<h2 class="pop_tit"><i class="fa fa-search"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
	<div class="pop_inner">
        <p class="marb10 lh4">
            회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
            해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
        </p>

		<form name="fpasswordlost" action="<?php echo $form_action_url; ?>" method="post" autocomplete="off">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<dl class="pop_form">
			<dt>E-mail 주소</dt>
			<dd><input type="text" name="mb_email" id="mb_email" required email itemname="E-mail 주소" class="ed wfull"></dd>
		</dl>
		<div class="tac mart15">
			<input type="submit" value="확인" class="btn_medium">
			<a href="javascript:window.close()" class="btn_medium bx-white">창닫기</a>
		</div>		
		</form>
	</div>
</div>
