<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['sup_code'] = code_uniqid();
$value['mb_id'] = $_POST['mb_id'];
$value['in_item'] = $_POST['in_item'];
$value['in_compay'] = $_POST['in_compay'];
$value['in_sanumber'] = $_POST['in_sanumber'];
$value['in_up'] = $_POST['in_up'];
$value['in_upte'] = $_POST['in_upte'];
$value['in_name'] = $_POST['in_name'];
$value['in_phone'] = $_POST['in_phone'];
$value['in_zipcode'] = $_POST['in_zipcode'];
$value['in_addr1'] = $_POST['in_addr1'];
$value['in_addr2'] = $_POST['in_addr2'];
$value['in_addr3'] = $_POST['in_addr3'];
$value['in_addr_jibeon'] = $_POST['in_addr_jibeon'];
$value['in_fax'] = $_POST['in_fax'];
$value['in_home'] = $_POST['in_home'];
$value['in_dam'] = $_POST['in_dam'];
$value['in_jik'] = $_POST['in_jik'];
$value['memo'] = $_POST['memo'];
$value['n_name'] = $_POST['n_name'];
$value['n_bank'] = $_POST['n_bank'];
$value['n_bank_num'] = $_POST['n_bank_num'];
$value['n_email'] = $_POST['n_email'];
$value['n_phone'] = $_POST['n_phone'];
$value['wdate'] = $server_time;
insert("shop_seller", $value);

goto_url('../seller.php?code=rigister');
?>