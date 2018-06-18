<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$log = sql_fetch("select * from shop_partner_paylog where index_no='{$_POST['p_table'][$k]}'");	

	$sql_certify = "income=income-$log[in_money], total=total-$log[in_money],";
	if ($log[etc2]=='shop') {
		$sql_certify .= "p_shop=p_shop-$log[in_money]";	
	} else if ($log[etc2]=='member') {
		$sql_certify .= "p_member=p_member-$log[in_money]";	
	} else if ($log[etc2]=='login') {
		$sql_certify .= "p_login=p_login-$log[in_money]";	
	} else if ($log[etc2]=='admin') {
		$sql_certify .= "p_admin=p_admin-$log[in_money]";
	}

	// 월
	if ($config['p_type'] == 'month') {
		$sql_search = " where month_date='$log[month_date]' ";
	} 	
	// 주,실시간
	else {
		$sql_search = " where ju_date='$log[ju_date]' ";
	}

	sql_query("update shop_partner_pay set $sql_certify $sql_search and mb_id='$log[mb_id]' ");
	sql_query("update shop_member set pay=pay-$log[in_money] where id='$log[mb_id]'");

	sql_query("delete from shop_partner_paylog where index_no='{$_POST['p_table'][$k]}'");	
}

goto_url("../partner.php?$q1&page=$page");
?>
