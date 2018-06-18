<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['sp_possible_ip'] = $_POST['sp_possible_ip']; //접근가능 IP
$value['sp_intercept_ip'] = $_POST['sp_intercept_ip']; //접근차단 IP
update("shop_config", $value);

goto_url('../config.php?code=ipaccess');
?>