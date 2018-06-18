<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TW_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($ico = $_FILES['favicon_ico']['name']) {
	if(!preg_match("/(\.ico)$/i", $ico))
		alert("파비콘 아이콘은 ico 파일만 업로드 가능합니다.");
}

if($basic_logo_del) {
	$upl->del($basic_logo_del);
	$value['basic_logo'] = '';
}
if($mobile_logo_del) {
	$upl->del($mobile_logo_del);
	$value['mobile_logo'] = '';
}
if($sns_logo_del) {
	$upl->del($sns_logo_del);
	$value['sns_logo'] = '';
}
if($favicon_ico_del) {
	$upl->del($favicon_ico_del);
	$value['favicon_ico'] = '';
}

if($_FILES['basic_logo']['name']) {
	$value['basic_logo'] = $upl->upload($_FILES['basic_logo']);
}
if($_FILES['mobile_logo']['name']) {
	$value['mobile_logo'] = $upl->upload($_FILES['mobile_logo']);
}
if($_FILES['sns_logo']['name']) {
	$value['sns_logo'] = $upl->upload($_FILES['sns_logo']);
}
if($_FILES['favicon_ico']['name']) {
	$value['favicon_ico'] = $upl->upload($_FILES['favicon_ico']);
}	

$value['mb_id'] = $member['id'];

$row = sql_fetch("select * from shop_logo where mb_id = '$member[id]'");
if($row['index_no']) {
	update("shop_logo", $value, "where mb_id = '$member[id]'");
} else {
	insert("shop_logo", $value);
}

goto_url('./page.php?code=partner_logo');
?>