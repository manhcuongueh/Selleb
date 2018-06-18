<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="fregister" method="post" onsubmit="return fregister_submit(this);" autocomplete="off">
<?php
if(!$default['de_certify']) {
	set_session('j_key', '');
	set_session('allow', '');
	sql_query("delete from shop_joincheck where j_key='".get_session('REQ_SEQ')."'");
	@include "./chekplus/checkplus_main.php";
	@include "./chekplus/ipin_main.php";
?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo get_session('REQ_SEQ'); ?>">
<?php } ?>
<div class="s_cont">
	<div class="fregister_agree">
		<h3 class="fr_tit">회원가입 약관<a href="javascript:win_open('./provision.php','pop_provision');" class="btn_small bx-white">전문보기</a></h3>
		<div class="agree_txt"><?php echo nl2br($config['sp_provision']); ?></div>
		<p class="agree_chk"><input name="agree1" type="checkbox" value="1" id="ids_agree1" class="css-checkbox lrg"><label for="ids_agree1" class="css-label">회원가입 약관의 내용에 동의합니다</label></p>
	</div>
	<div class="fregister_agree mart10">
		<h3 class="fr_tit">개인정보 수집 및 이용<a href="javascript:win_open('./policy.php','pop_policy');" class="btn_small bx-white">전문보기</a></h3>
		<div class="agree_txt"><?php echo nl2br($config['sp_private']); ?></div>
		<p class="agree_chk"><input name="agree2" type="checkbox" value="1" id="ids_agree2" class="css-checkbox lrg"><label for="ids_agree2" class="css-label">개인정보 수집 및 이용 내용에 동의합니다.</label></p>
	</div>
	<div class="tac mart10">
		<?php if(!$default['de_certify']) { ?>
		<button type="button" onclick="fnPopup(1);" class="btn_medium bx-white">휴대폰인증</button>
		<button type="button" onclick="fnPopup(0);" class="btn_medium bx-white">I-PIN 인증</button>
		<?php } else { ?>
		<input type='submit' value='확인' class='btn_medium'>
		<button type="button" onclick="history.go(-1);" class="btn_medium bx-white">취소</button>
		<?php } ?>
	</div>
</div>
</form>

<script language="javascript">
window.name ="Parent_window";
function fnPopup(val){
	var f = document.fregister;
	if(!f.agree1.checked) {
        alert("회원가입 약관에 동의하셔야 합니다.");
        return false;
    }

	if(!f.agree2.checked) {
        alert("개인정보 수집 및 이용 내용에 동의하셔야 합니다.");
        return false;
    }

	switch(val){
		case 1: //휴대폰인증
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // 아이핀인증
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
    if(!f.agree1.checked) {
        alert("회원가입 약관에 동의하셔야 합니다.");
        return false;
    }

	if(!f.agree2.checked) {
        alert("개인정보 수집 및 이용 내용에 동의하셔야 합니다.");
        return false;
    }

	f.action = "./register_form.php";
	return true;
}
</script>
