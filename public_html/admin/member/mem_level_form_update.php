<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = "update shop_member_grade
			   set grade_name = '{$_POST['mb_grade'][$k]}',
				   mb_sale = '{$_POST['mb_sale'][$k]}',
				   mb_cutting = '{$_POST['mb_cutting'][$k]}',
				   mb_per = '{$_POST['mb_per'][$k]}'
			 where index_no = '{$_POST['gr_table'][$k]}'";
	sql_query($sql);

	sql_query("update shop_partner_config set etc1='{$_POST['mb_grade'][$k]}' where mb_grade='{$_POST['gr_table'][$k]}'");
}

goto_url("/admin/member.php?code=level_form");
?>