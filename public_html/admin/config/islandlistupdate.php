<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($_POST['act_button'] == "추가") {

	check_admin_token();

	// 일괄 공백제거
	foreach($_POST as $key => $value){
		$_POST[$key] = trim($value);
	}

	$sql = " insert shop_island
				set is_name  = '{$_POST['is_name']}',
					is_zip1  = '".conv_number($_POST['is_zip1'])."',
					is_zip2  = '".conv_number($_POST['is_zip2'])."',
					is_price = '".conv_number($_POST['is_price'])."' ";
	sql_query($sql);
} 
else {
	for($i=0; $i<count($_POST['chk']); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_island
					set is_name  = '{$_POST['is_name'][$k]}',
						is_zip1  = '".conv_number($_POST['is_zip1'][$k])."',
						is_zip2  = '".conv_number($_POST['is_zip2'][$k])."',
						is_price = '".conv_number($_POST['is_price'][$k])."'
				  where is_id = '{$_POST['is_id'][$k]}' ";
		sql_query($sql);
	}
}

goto_url("../config.php?$q1&page=$page");
?>