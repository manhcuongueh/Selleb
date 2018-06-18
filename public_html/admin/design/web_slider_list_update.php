<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_banner_slider  
                set bn_rank = '{$_POST['bn_rank'][$k]}',
					bn_width = '{$_POST['bn_width'][$k]}',
				    bn_height = '{$_POST['bn_height'][$k]}',
					bn_target = '{$_POST['bn_target'][$k]}',
					bn_use = '{$_POST['bn_use'][$k]}',
					bn_link = '{$_POST['bn_link'][$k]}'
              where index_no = '{$_POST['ba_table'][$k]}' ";
    sql_query($sql);
}

goto_url("../design.php?$q1&page=$page");
?>