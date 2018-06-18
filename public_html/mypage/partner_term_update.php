<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$row = sql_fetch(" select * from shop_partner_config where mb_grade='$member[grade]' ");

unset($value);
$value['mb_id'] = $member['id'];
$value['go_date'] = $_POST['go_date'];
$value['bank'] = $_POST['bank'];
$value['bank_name'] = $_POST['bank_name'];
$value['bank_acc'] = $_POST['bank_acc'];
$value['money'] = (int)$row['etc3'] * (int)$_POST['go_date'];
$value['wdate'] = $server_time;
insert("shop_partner_term", $value);

goto_url('./page.php?code=partner_term');
?>