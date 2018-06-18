<?php
include_once("./_common.php");

if($member['id']) {	
	goto_url(TW_URL);
}

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes']=='no' && $pt_id == 'admin') {
	alert($config['admin_reg_msg']);
}

$gw_head_title = '회원가입';
include_once("./_head.php"); 

// 실명인증 사용중일때
if($default['de_certify'] == '0') {
	if(get_session("allow") != 'Y')
		alert("정상적인 접근이 아닙니다.", TW_BBS_URL."/register.php");

	$input_attr = "readonly style='background-color:#ddd'";

	$sql = " select * from shop_joincheck where j_key='".get_session('j_key')."' ";
	$jcheck = sql_fetch($sql);

	$name = $jcheck['j_name'];
	$year = substr($jcheck['j_birthdate'],0,4);
	$month = substr($jcheck['j_birthdate'],4,2);
	$day = substr($jcheck['j_birthdate'],6,2);
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$register_action_url = TW_BBS_URL.'/register_form_update.php';

include_once($theme_path.'/register_form.skin.php');
include_once("./_tail.php");
?>