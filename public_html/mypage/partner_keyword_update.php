<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update shop_keyword
                set scount = '{$_POST['scount'][$k]}',
					old_scount = '{$_POST['old_scount'][$k]}',
					keyword = '{$_POST['keyword'][$k]}'
              where index_no = '{$_POST['index_no'][$k]}' ";
    sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>