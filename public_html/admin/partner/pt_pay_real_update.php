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
		$b_money = sql_fetch("select * from shop_partner_payrun where index_no='{$_POST['p_table'][$k]}'");
		
		$sql = "select * from shop_partner_pay where mb_id='$b_money[mb_id]'"; 
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			if($row[total] > 0 && $b_money[money] > 0) {
				if($b_money[money] > $row[total]) {
					$outcome = $row[total];
					$total = '0';				
				} else {
					$outcome = $b_money[money];
					$total = $row[total]-$b_money[money];
				}

				sql_query("update shop_partner_pay set outcome=outcome+$outcome, total='$total' where index_no='$row[index_no]'");
			}
		}

		sql_query("update shop_partner_payrun set state='1' where index_no='{$_POST['p_table'][$k]}'");
		sql_query("update shop_member set pay=pay-$b_money[money] where id='$b_money[mb_id]'");

		$sql = "insert into shop_partner_payuse 
					   ( out_money,tax2_money,tax3_money,mb_id,wdate,ju_date,month_date,memo,bankinfo )  
				values ('$b_money[money]','$b_money[tax1_money]','$b_money[tax2_money]','$b_money[mb_id]',
						'$server_time','실시간','실시간','정산완료','$b_money[membank]')";
		sql_query($sql);
	}
	
	// 정산유보
	if($mode == 'defer') {
		sql_query("update shop_partner_payrun set state='2' where index_no='{$_POST['p_table'][$k]}'");
	}

	// 정산거절
	if($mode == 'refusal') {		
		sql_query("update shop_partner_payrun set state='3' where index_no='{$_POST['p_table'][$k]}'");
	}
}

goto_url("../partner.php?$q1&page=$page");
?>
