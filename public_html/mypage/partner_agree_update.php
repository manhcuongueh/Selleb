<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['sp_provision'] = $_POST['sp_provision']; // 회원가입약관
$value['sp_private'] = $_POST['sp_private']; // 개인정보 수집 및 이용
$value['sp_policy'] = $_POST['sp_policy']; // 개인정보처리방침
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=partner_agree');
?>