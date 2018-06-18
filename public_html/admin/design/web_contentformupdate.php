<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['co_subject'] = $_POST['co_subject'];
$value['co_content'] = $_POST['co_content'];
$value['co_mobile_content'] = $_POST['co_mobile_content'];

if($w == "") {
	insert("shop_content", $value);
	$co_id = sql_insert_id();

	goto_url("../design.php?code=contentform&w=u&co_id=$co_id");

} else if($w == "u") {
	update("shop_content", $value," where co_id='$co_id'");
	
	goto_url("../design.php?code=contentform&w=u&co_id=$co_id$qstr&page=$page");
}
?>