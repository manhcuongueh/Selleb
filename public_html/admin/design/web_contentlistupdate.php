<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_content  
                set co_subject = '{$_POST['co_subject'][$k]}'
              where co_id = '{$_POST['co_id'][$k]}' ";
    sql_query($sql);
}

goto_url("../design.php?$q1&page=$page");
?>