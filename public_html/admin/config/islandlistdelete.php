<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($_POST['chk']); $i++) 
{
	// 실제 번호를 넘김
	$k = $_POST['chk'][$i];

	// 삭제
	$sql = " delete from shop_island where is_id = '{$_POST['is_id'][$k]}' ";
	sql_query($sql);
}

goto_url("../config.php?$q1&page=$page");
?>