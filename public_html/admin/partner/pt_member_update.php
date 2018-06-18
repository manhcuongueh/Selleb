<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$mb_no = trim($_POST['mb_table'][$k]);
	$mb = get_member_no($mb_no, 'term_date');

	if(!$mb['term_date'])	{
		$r_y = date("Y");
		$r_m = date("m");
		$r_d = date("d");
	} else {
		$r_y = date("Y",$mb['term_date']);
		$r_m = date("m",$mb['term_date']);
		$r_d = date("d",$mb['term_date']);
	}

	$new_month	= $r_m + $term_date;
	$new_term_date = mktime(0,0,1,$new_month,$r_d,$r_y);
	
	sql_query("update shop_member set term_date='$new_term_date' where index_no='$mb_no'");	
}

goto_url("../partner.php?$q1&page=$page");
?>