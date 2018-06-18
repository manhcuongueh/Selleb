<?php
include_once("./_common.php");

if(!$member['id'])
	alert('회원 전용 서비스입니다.',TW_BBS_URL."/login.php?url=$nowurl");

$gw_head_title = '회원정보 수정';
include_once("./_head.php");

$input_attr = "readonly style='background-color:#ddd;'";

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$register_action_url = TW_BBS_URL.'/register_mod_update.php';

include_once($theme_path.'/register_mod.skin.php');
include_once("./_tail.php");
?>