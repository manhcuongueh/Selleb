<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

unset($value);
$value['delivery_type'] = $_POST['delivery_type']; //배송방법 (택배발송,직접배달,퀵서비스등)
$value['delivery_method'] = $_POST['delivery_method']; //배송방식 101,102,103,104
$value['delivery_103mon'] = rpc($_POST['delivery_103mon']); //고정 배송비요금
$value['delivery_104mon'] = rpc($_POST['delivery_104mon']); //조건 배송비요금
$value['delivery_104mon_up'] = rpc($_POST['delivery_104mon_up']); //조건 배송비면제 금액
if(!get_magic_quotes_gpc()) {
	$sp_send_cost = addslashes($_POST['sp_send_cost']);
	$mo_send_cost = addslashes($_POST['mo_send_cost']);
}

$value['sp_send_cost'] = $sp_send_cost; // 쇼핑몰 배송,교환,반품안내
$value['mo_send_cost'] = $mo_send_cost; // 모바일 배송,교환,반품안내
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=partner_conf');
?>