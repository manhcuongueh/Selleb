<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['shop_reg_yes'] = $_POST['shop_reg_yes'];
$value['shop_reg_auto'] = $_POST['shop_reg_auto'];
$value['shop_mod_auto'] = $_POST['shop_mod_auto'];
$value['shop_card'] = $_POST['shop_card']; 
$value['shop_bank'] = $_POST['shop_bank'];
$value['shop_yesc'] = $_POST['shop_yesc']; 
$value['shop_phone'] = $_POST['shop_phone'];
$value['shop_yesc_type'] = $_POST['shop_yesc_type'];
$value['shop_phone_type'] = $_POST['shop_phone_type'];
$value['shop_i'] = $_POST['shop_i'];
$value['shop_reg_agree'] = $_POST['shop_reg_agree'];
$value['shop_reg_guide'] = $_POST['shop_reg_guide'];	
update("shop_config",$value);

goto_url('../config.php?code=supply');
?>