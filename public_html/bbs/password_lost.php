<?php
include_once("./_common.php");

if($member['id']) {
    alert("이미 로그인중입니다.");
}

$gw_head_title = '회원정보 찾기';
include_once(TW_PATH.'/head.sub.php');

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TW_BBS_URL."/password_lost2.php";
include_once($theme_path.'/password_lost.skin.php');
include_once(TW_PATH."/tail.sub.php");
?>