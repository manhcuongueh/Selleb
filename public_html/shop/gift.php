<?php
include_once("./_common.php");

if(!$member['id'])
	alert('회원 전용 서비스입니다.',TW_BBS_URL."/login.php?url=$nowurl");

if(!$config['sp_gift'])
    alert("쿠폰사용이 중지 되었습니다.");

$gw_head_title = '쿠폰인증';
include_once("./_head.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TW_SHOP_URL.'/gift_update.php';

include_once($theme_path.'/gift.skin.php');

include_once("./_tail.php");
?>