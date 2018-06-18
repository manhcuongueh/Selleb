<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$ba_table = trim($_POST['ba_table'][$k]);

	$row = sql_fetch("select * from shop_banner where index_no='$ba_table'");	
	if($row['mb_id'] == 'admin') {
		alert("본사배너는 수정하실 수 없습니다.\\n미리보기 용도이므로 새로 등록해 주세요.");
	}

    $sql = " update shop_banner  
                set bn_code = '{$_POST['bn_code'][$k]}',
					bn_width = '{$_POST['bn_width'][$k]}',
				    bn_height = '{$_POST['bn_height'][$k]}',
					bn_target = '{$_POST['bn_target'][$k]}',
					bn_use = '{$_POST['bn_use'][$k]}',
					bn_link = '{$_POST['bn_link'][$k]}'
              where index_no = '{$_POST['ba_table'][$k]}' ";
    sql_query($sql);
}

goto_url("./page.php?$q1&page=$page");
?>