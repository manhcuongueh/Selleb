<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$money = conv_number($_POST['reg_price']);
$tax1_money = round(($money * $config['accent_tax']) / 100);
$tax2_money = $money - $tax1_money;
$membank = $_POST['bank_name'].' '.$_POST['bank_number'].' '.$_POST['bank_company'];

unset($value);
$value['mb_id'] = $member['id'];
$value['money'] = $money;
$value['tax1_money'] = $tax1_money;
$value['tax2_money'] = $tax2_money;
$value['membank'] = $membank;
$value['wdate'] = $server_time;
insert("shop_partner_payrun", $value);

unset($value);
$value['bank_name'] = $_POST['bank_name'];
$value['bank_number'] = $_POST['bank_number'];
$value['bank_company'] = $_POST['bank_company'];
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=partner_stats');
?>