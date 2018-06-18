<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['catename'] = $_POST['catename'];
insert("shop_faq_cate", $value);

goto_url("../help.php?$q1&page=$page");
?>