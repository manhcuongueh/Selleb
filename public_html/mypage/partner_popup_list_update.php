<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_popup
                set title = '{$_POST['title'][$k]}',
                    width = '{$_POST['width'][$k]}',
					height = '{$_POST['height'][$k]}',
					top = '{$_POST['top'][$k]}',
					lefts = '{$_POST['lefts'][$k]}'
              where index_no = '{$_POST['pp_id'][$k]}' ";
    sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>