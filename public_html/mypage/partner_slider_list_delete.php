<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$upl_dir = TW_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$ba_table = trim($_POST['ba_table'][$k]);
	$row = sql_fetch("select * from shop_banner_slider where index_no='$ba_table'");	
	
	$upl->del($row['bn_file']);

	sql_query(" delete from shop_banner_slider where index_no='$ba_table' ");
}

goto_url("./page.php?$q1&page=$page");
?>