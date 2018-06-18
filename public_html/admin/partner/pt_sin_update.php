<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$si = sql_fetch("select * from shop_partner_term where index_no='{$_POST['si_table'][$k]}'");	
	$mb = sql_fetch("select * from shop_member where id='$si[mb_id]'");
	$cf = sql_fetch("select * from shop_partner_config where mb_grade='$mb[grade]'");
	$total = $cf[etc3] * $si[go_date];
	
	if(!$mb[term_date]) {
		$r_y = date("Y");
		$r_m = date("m");
		$r_d = date("d");
	} else {
		$r_y = date("Y",$mb[term_date]);
		$r_m = date("m",$mb[term_date]);
		$r_d = date("d",$mb[term_date]);
	}

	$new_month = $r_m + $si[go_date];
	$term_date = mktime(0,0,1,$new_month,$r_d,$r_y);

	sql_query("update shop_member set term_date='$term_date' where id='$si[mb_id]'");
	sql_query("update shop_partner_term set state=1 where index_no='{$_POST['si_table'][$k]}'");	
	
	$ju = date('W');
	$month_date	= date('Y-m');
	
	$sql = "insert into shop_partner_paylog 
				   ( mb_id, pt_id, in_money, memo, wdate, ju_date, month_date, etc2 )
			VALUES ('admin','$mb[id]','$total','관리비적립','$server_time','$ju','$month_date','p_month')";
	sql_query($sql);
}

goto_url("../partner.php?$q1&page=$page");
?>
