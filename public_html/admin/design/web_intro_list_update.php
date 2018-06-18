<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_banner_intro  
                set bn_code = '{$_POST['bn_code'][$k]}',
					bn_width = '{$_POST['bn_width'][$k]}',
				    bn_height = '{$_POST['bn_height'][$k]}',
					bn_target = '{$_POST['bn_target'][$k]}',
					bn_use = '{$_POST['bn_use'][$k]}',
					bn_link = '{$_POST['bn_link'][$k]}'
              where bn_id = '{$_POST['bn_id'][$k]}' ";
    sql_query($sql);
}

goto_url("../design.php?$q1&page=$page");
?>
