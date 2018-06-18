<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$p_use_pg) {
	alert('개별 PG결제 사용 권한이 없습니다.');
}

unset($value);
$value['de_naverpay_mid'] = $_POST['de_naverpay_mid'];  
$value['de_naverpay_cert_key'] = $_POST['de_naverpay_cert_key'];
$value['de_naverpay_button_key'] = $_POST['de_naverpay_button_key'];
$value['de_naverpay_test'] = $_POST['de_naverpay_test'];
$value['de_naverpay_mb_id']	= $_POST['de_naverpay_mb_id'];
$value['de_naverpay_sendcost']	= $_POST['de_naverpay_sendcost'];
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=partner_naverpay');
?>