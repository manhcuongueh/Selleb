<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['cate'] = $_POST['faq_cate'];
$value['subject'] = $_POST['subject'];
$value['memo'] = $_POST['memo'];

if($w == "") {
	$value['wdate'] = $time_ymdhis;
	insert("shop_faq",$value);
	$faq_table = sql_insert_id();

	goto_url("../help.php?code=faq_from&w=u&faq_table=$faq_table");

} else if($w == "u") {
	update("shop_faq",$value," where index_no='$faq_table'");

	goto_url("../help.php?code=faq_from&w=u&faq_table=$faq_table$qstr&page=$page");
}
?>