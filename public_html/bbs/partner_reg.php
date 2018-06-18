<?php
include_once("./_common.php");

if(!$config['partner_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.');
}

if(!$member['id'])
	alert('회원 전용 서비스입니다.',TW_BBS_URL."/login.php?url=$nowurl");

if(is_admin())
	alert('관리자는 신청을 하실 수 없습니다.');

$gw_head_title = '쇼핑몰 분양신청';
include_once("./_head.php");

if($partner['mb_id']) {
	include_once($theme_path.'/partner_reg_result.skin.php');
} else {
	$token = md5(uniqid(rand(), true));
	set_session("ss_token", $token);

	$from_action_url = TW_BBS_URL.'/partner_reg_update.php';

	include_once($theme_path.'/partner_reg.skin.php');
}

include_once("./_tail.php");
?>