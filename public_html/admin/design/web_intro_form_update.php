<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TW_DATA_PATH."/intro";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['bn_file_del']) {
	$upl->del($_POST['bn_file_del']);
	$value['bn_file'] = '';
}
if($_FILES['bn_file']['name']) {
	$bn_file = $upl->upload($_FILES['bn_file']); 
	$value['bn_file'] = $bn_file; 
}	

$value['mb_id']		= $_POST['mb_id'];
$value['bn_code']	= $_POST['bn_code'];
$value['bn_link']	= $_POST['bn_link'];
$value['bn_target']	= $_POST['bn_target'];
$value['bn_width']	= $_POST['bn_width'];
$value['bn_height']	= $_POST['bn_height'];
$value['bn_use']	= $_POST['bn_use'];

if($w == "") {
	insert("shop_banner_intro", $value);

	goto_url("../design.php?code=intro_form&bn_width=$bn_width&bn_height=$bn_height&bn_code=$bn_code");
} else if($w == "u") {
	update("shop_banner_intro",$value," where bn_id='$bn_id'");
	
	goto_url("../design.php?code=intro_form&w=u&bn_id=$bn_id$qstr&page=$page");
}
?>