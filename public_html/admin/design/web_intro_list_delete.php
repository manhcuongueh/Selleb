<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$upl_dir = TW_DATA_PATH."/intro";
$upl = new upload_files($upl_dir);

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$bn_id = trim($_POST['bn_id'][$k]);

	$row = sql_fetch("select * from shop_banner_intro where bn_id='$bn_id'");	
	
	$upl->del($row['bn_file']);

	sql_query(" delete from shop_banner_intro where bn_id = '$bn_id' ");
}

goto_url("../design.php?$q1&page=$page");
?>
