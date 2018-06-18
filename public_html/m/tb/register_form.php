<?php
include_once("./_common.php");

if($w == "") {
	if($is_member) {
		goto_url($tb['root']);
	}

	// 본사쇼핑몰에서 회원가입을 받지 않을때
	$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
	if($config['admin_reg_yes']=='no' && $pt_id == 'admin') {
		alert($config['admin_reg_msg']);
	}

	// 실명인증 사용중일때
	if($default['de_certify'] == '0') {
		if(get_session("allow") != 'Y') {
			alert("정상적인 접근이 아닙니다.", "./register.php");
		}

		$sql = " select * from shop_joincheck where j_key='".get_session('j_key')."' ";
		$jcheck = sql_fetch($sql);

		$name  = $jcheck['j_name'];
		$year  = substr($jcheck['j_birthdate'],0,4);
		$month = substr($jcheck['j_birthdate'],4,2);
		$day   = substr($jcheck['j_birthdate'],6,2);

		$member['name']		    = get_text($name);
		$member['birth_year']   = get_text($year);
		$member['birth_month']  = get_text($month);
		$member['birth_day']	= get_text($day);
		$member['smsser']		= 'Y';
		$member['mailser']		= 'Y';
	}

	$tb['title'] = "회원가입";
	$btn_name	 = "가입하기";

} else if($w == 'u') {
	if(!$is_member) {
		 alert('로그인 후 이용하여 주십시오.');
	}

	$tb['title'] = "회원정보 수정";
	$btn_name	 = "수정하기";
}

// 필수표시
$required = "<span class='fc_red'>&radic;&nbsp;</span>";
$input_attr = "readonly style='background-color:#dddddd'";

include_once("./_head.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$register_action_url = './register_form_update.php';
include_once($theme_path.'/register_form.skin.php');

include_once("./_tail.php");
?>