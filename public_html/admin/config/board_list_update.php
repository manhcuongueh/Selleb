<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_board_conf
                set gr_id = '{$_POST['gr_id'][$k]}',
                    boardname = '{$_POST['bo_subject'][$k]}'
              where index_no = '{$_POST['bo_table'][$k]}' ";
    sql_query($sql);
}

goto_url("../config.php?$q1&page=$page");
?>