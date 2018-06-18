<?php
include_once("./_common.php");

if($is_member) {
    alert('로그중이므로 회원가입을 할 수 없습니다.');
}

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes']=='no' && $pt_id == 'admin') {
	alert($config['admin_reg_msg']);
}

$tb['title'] = "회원가입";
include_once("./_head.php");
include_once($theme_path.'/register.skin.php');
include_once("./_tail.php");
?>