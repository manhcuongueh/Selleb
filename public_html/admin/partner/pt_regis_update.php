<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['accent_one'] = $_POST['accent_one'];
$value['accent_tree'] = $_POST['accent_tree']; 
$value['accent_max'] = preg_replace('/[^0-9]/', '', $_POST['accent_max']);
$value['accent_tax'] = $_POST['accent_tax'];
$value['p_type'] = $_POST['p_type']; 
$value['p_month'] = $_POST['p_month'];
$value['p_member'] = $_POST['p_member'];
$value['p_login'] = $_POST['p_login'];
$value['p_shop'] = $_POST['p_shop'];
$value['p_shop_flag'] = $_POST['p_shop_flag'];	
$value['p_use_good'] = $_POST['p_use_good'];
$value['p_use_cate'] = $_POST['p_use_cate'];
$value['p_use_pg'] = $_POST['p_use_pg'];
$value['p_reg_agree'] = $_POST['p_reg_agree'];
$value['p_payment_yes'] = $_POST['p_payment_yes'];	
$value['partner_reg_yes'] = $_POST['partner_reg_yes'];	
update("shop_config", $value);

goto_url("../partner.php?code=regis");
?>