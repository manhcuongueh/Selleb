<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];
	
	// 정산완료
	if($mode == 'update') {
		$b_point = sql_fetch("select * from shop_partner_pay where index_no='{$_POST['p_table'][$k]}'");
		$level = sql_fetch("select * from shop_partner where mb_id='$b_point[mb_id]'");
		$bankinfo = $level[bank_name]." ".$level[bank_number]." ".$level[bank_company];	
		
		$tax2 = round(($b_point[total] * $config[accent_tax]) / 100); // 세금공제
		$tax3 = $b_point[total] - $tax2; // 실수령액	

		if($config[p_type]=='month') {
			$sql_certify = "month_date = '$b_point[month_date]'";
		} else {
			$sql_certify = "ju_date = '$b_point[ju_date]'";
		}

		$sql = "update shop_partner_pay 
				   set outcome = outcome+$b_point[total], 
					   total = '0', 
					   ragi = '1'
				 where $sql_certify 
				   and index_no ='{$_POST['p_table'][$k]}'";
		sql_query($sql);

		sql_query("update shop_member set pay=pay-$b_point[total] where id='$b_point[mb_id]'");
		
		$sql = "insert into shop_partner_payuse 
					   ( out_money,tax2_money,tax3_money,mb_id,wdate,ju_date, month_date,memo,bankinfo) 
				values ('$b_point[total]','$tax2','$tax3','$b_point[mb_id]','$server_time','$b_point[ju_date]',
						'$b_point[month_date]','정산완료','$bankinfo')";
		sql_query($sql);
	}
	
	// 정산유보
	if($mode == 'defer') {
		$b_point = sql_fetch("select * from shop_partner_pay where index_no='{$_POST['p_table'][$k]}'");
		
		if($config[p_type]=='month') {
			sql_query("update shop_partner_pay set ragi=2 where month_date='$b_point[month_date]' and index_no='{$_POST['p_table'][$k]}'");
		} else {
			sql_query("update shop_partner_pay set ragi=2 where ju_date='$b_point[ju_date]' and index_no='{$_POST['p_table'][$k]}'");
		}
	}

	// 정산거절
	if($mode == 'refusal') {
		$b_point = sql_fetch("select * from shop_partner_pay where index_no='{$_POST['p_table'][$k]}'");
		
		if($config[p_type]=='month') {
			sql_query("update shop_partner_pay set ragi=3 where month_date='$b_point[month_date]' and index_no='{$_POST['p_table'][$k]}'");
		} else {
			sql_query("update shop_partner_pay set ragi=3 where ju_date='$b_point[ju_date]' and index_no='{$_POST['p_table'][$k]}'");
		}
	}
}

goto_url("../partner.php?$q1&page=$page");
?>
