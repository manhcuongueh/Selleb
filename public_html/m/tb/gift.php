<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url("./login.php?url=".urlencode("$tb[bbs_root]/gift.php"));
}

if(!$config['sp_gift']) {
    alert("쿠폰사용이 중지 되었습니다.");
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$tb['title'] = "쿠폰인증";
include_once("./_head.php");

$form_action_url = './gift_update.php';

include_once("$theme_path/gift.skin.php");
include_once("./_tail.php");
?>