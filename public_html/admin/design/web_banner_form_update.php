<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TW_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($bn_file_del) {
	$upl->del($bn_file_del);
	$value['bn_file'] = '';
}
if($_FILES['bn_file']['name']) {
	$value['bn_file'] = $upl->upload($_FILES['bn_file']);
}

$value['mb_id'] = 'admin';
$value['bn_theme'] = $super['theme'];
$value['bn_mobile_theme'] = $super['mobile_theme'];
$value['bn_code'] = $_POST['bn_code'];
$value['bn_link'] = $_POST['bn_link'];
$value['bn_target'] = $_POST['bn_target'];
$value['bn_width'] = $_POST['bn_width'];
$value['bn_height'] = $_POST['bn_height'];
$value['bn_text'] = $_POST['bn_text'];
$value['bn_bg']	= $_POST['bn_bg'];
$value['bn_use'] = $_POST['bn_use'];

if($w == "") {
	insert("shop_banner", $value);
	$ba_table = sql_insert_id();

	$qstr = "code=banner_form&w=u&ba_table=$ba_table";
} else if($w == "u") {
	update("shop_banner", $value," where index_no='$ba_table'");
	$qstr = "code=banner_form&w=u&ba_table=$ba_table&page=$page";
}

goto_url("../design.php?$qstr");
?>