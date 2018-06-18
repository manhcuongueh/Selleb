<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TW_DATA_PATH."/brand";
$upl = new upload_files($upl_dir);

unset($value);
if($br_logo_del) {
	$upl->del($br_logo_del);
	$value['br_logo'] = '';
}
if($_FILES['br_logo']['name']) {
	$value['br_logo'] = $upl->upload($_FILES['br_logo']);
}

$value['br_name'] = $_POST['br_name'];
$value['br_name_eng'] = $_POST['br_name_eng'];
$value['br_udate'] = $time_ymdhis;

if($w == '') {
	$value['mb_id'] = $seller['sup_code'];
	$value['br_wdate'] = $time_ymdhis;
	insert("shop_brand", $value);
	
	goto_url("./page.php?$q1&page=$page");

} else if($w == 'u') {	
	update("shop_brand", $value, "where br_id='$br_id'");

	// 상품 정보도 동시에 수정
	$sql = "update shop_goods set brand_nm = '{$_POST['br_name']}' where brand_uid = '$br_id'";
	sql_query($sql, false);

	goto_url("./page.php?code=seller_goods_brand_form&w=u&br_id=$br_id$qstr&page=$page");
}
?>