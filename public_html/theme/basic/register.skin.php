<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregister" method="POST" onsubmit="return fregister_submit(this);" autocomplete="off">
<?php
if($default['de_certify'] == '0') { //  실명인증 사용시
	unset($_SESSION['j_key']);
	unset($_SESSION['allow']);
	sql_query(" delete from shop_joincheck where j_key='".get_session('REQ_SEQ')."' ");

	@include TW_PLUGIN_PATH."/chekplus/checkplus_main.php";
	@include TW_PLUGIN_PATH."/chekplus/ipin_main.php";
?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo get_session('REQ_SEQ'); ?>">
<?php } ?>

<div><img src="<?php echo TW_IMG_URL; ?>/register_1.gif"></div>
<div class="regi_box mart20">
	<h3 class="s_stit">회원가입 약관</h3>
	<div class="agree_box mart7">
		<?php echo nl2br($config['sp_provision']); ?>
	</div>
</div>
<div class="regi_box mart20">
	<h3 class="s_stit">개인정보 수집 및 이용</h3>
	<div class="agree_box mart7">
		<?php echo nl2br($config['sp_private']); ?>
	</div>
</div>
<div class="agree_txt mart20">
	<i class="fa fa-exclamation-circle"></i> 회원가입전 회원가입 약관 및 개인정보 수집 및 이용을 반드시 읽어보시기 바랍니다.
	<span class="bold marl20">
		<input type="radio" value="1" name="agree" id="agree11">
		<label for="agree11" class="marr10">동의함</label>
		<input type="radio" value="0" name="agree" id="agree10">
		<label for="agree10">동의안함</label>
	</span>
</div>

<?php if($default['de_certify'] == '0') { // 실명인증 사용시 ?>
<div class="mart40">
	<h3><img src="<?php echo TW_IMG_URL; ?>/20131028_235617.jpg"></h3>
	<div class="agree_txt mart10">
		<i class="fa fa-exclamation-circle"></i> 개정 정보통신법 제23조에 따라 회원가입시에는 주민등록번호를 수집하지 않습니다.
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">휴대폰 인증</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">아이핀 인증</label>
		</span>
	</div>
</div>
<?php } ?>
<div class="tac mart20">
	<input type="submit" value="확인" class="btn_medium marr3">
	<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
</div>
</form>

<script language="javascript">
window.name ="Parent_window";
function fnPopup(val){
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
    var agree = document.getElementsByName("agree");
    if(!agree[0].checked) {
        alert("회원가입 약관 및 개인정보 수집 및 이용에 동의하셔야 회원가입 하실 수 있습니다.");
        agree[0].focus();
        return false;
    }
	<?php if($default['de_certify'] == '0') { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("휴대폰인증 및 아이핀인증 후 회원가입 하실 수 있습니다.");
        return false;
    }
	if(chkplus[0].checked) {
        fnPopup(1);
		return false;
    }
	if(chkplus[1].checked) {
        fnPopup(0);
		return false;
    }
	<?php } else { ?>
	f.action = "./register_form.php";
	return true;
	<?php } ?>
}
</script>