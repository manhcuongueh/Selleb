<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['mo_shop_yn'] = $_POST['mo_shop_yn'];
$value['mo_about_limit'] = $_POST['mo_about_limit'];
$value['mo_se_default'] = $_POST['mo_se_default'];
$value['mo_se_yn'] = $_POST['mo_se_yn'];
$value['mo_noti_yn'] = $_POST['mo_noti_yn'];
update("shop_config", $value);

goto_url('../config.php?code=mobile');
?>