<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_board_group
                set gr_subject = '{$_POST['gr_subject'][$k]}'
              where gr_id = '{$_POST['gr_id'][$k]}' ";
    sql_query($sql);
}

goto_url("../config.php?$q1&page=$page");
?>