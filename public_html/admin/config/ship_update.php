<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['delivery_type'] = $_POST['delivery_type'];	 //배송방법 (택배발송,직접배달,퀵서비스등)
$value['delivery_method'] = $_POST['delivery_method']; //배송방식 101,102,103,104
$value['delivery_103mon'] = conv_number($_POST['delivery_103mon']); //고정 배송비요금
$value['delivery_104mon'] = conv_number($_POST['delivery_104mon']); //조건 배송비요금
$value['delivery_104mon_up'] = conv_number($_POST['delivery_104mon_up']); //조건 배송비면제 금액	

$supply_infos = '';
$supply_count = count($_POST['spl_name']);
if($supply_count) {
	$arr_spl = array();
	for($i=0; $i<$supply_count; $i++) {
		$spl_val = trim($_POST['spl_name'][$i]).'|'.trim($_POST['spl_url'][$i]);
		$arr_spl[] = $spl_val;
	}

	$supply_infos = implode(',', $arr_spl);
}

$value['delivery_sorts'] = $supply_infos; // 배송업체
$value['sp_send_cost'] = $_POST['sp_send_cost']; // 쇼핑몰 배송,교환,반품안내
$value['mo_send_cost'] = $_POST['mo_send_cost']; // 모바일 배송,교환,반품안내
update("shop_config", $value);

goto_url('../config.php?code=ship');
?>