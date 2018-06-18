<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($_POST[mode]=='w') {
	$index_no   = $_POST['index_no'];
	$po_point   = $_POST['po_point'];
	$po_content = $_POST['po_content'];
	$po_kind	= $_POST['po_kind'];

	$sql_dayweek = sql_dayofweek();
	$dayofweek	 = get_dayofweek();

	$mb = sql_fetch("select id from shop_member where index_no='$index_no'");
	$sum = sql_fetch("select SUM(total) as total from shop_partner_pay where mb_id='$mb[id]'");

	// 적립
	if($po_kind=='I') {
		sql_query("update shop_member set pay=pay+$po_point where id='$mb[id]'");

		$sql_search = "where mb_id='$mb[id]' ";
		if($config['p_type'] == 'month') { 
			// 월
			$sql_search .= " and month_date='$time_ym' ";
		} else {
			// 주,실시간
			$sql_search .= " and $sql_dayweek ";
		}

		$reb = sql_fetch("select index_no from shop_partner_pay $sql_search");
		if($reb['index_no']){
			$sql = "update shop_partner_pay 
					   set income	= income+$po_point,
						   total	= total+$po_point,
						   p_admin	= p_admin+$po_point,
						   reg_date	= '$server_time' 
						   $sql_search ";
			sql_query($sql);
		} else {
			$sql = "insert into shop_partner_pay 
						   ( mb_no, income, total, wdate, ju_date, month_date, reg_date, p_admin, mb_id ) 
					VALUES ('$index_no', '$po_point', '$po_point', '$server_time','$dayofweek','$time_ym','$server_time','$po_point','$mb[id]')";
			sql_query($sql);
		}
		
		$sql = "insert into shop_partner_paylog 
					   ( mb_id, pt_id, in_money,  memo, wdate, ju_date, month_date, etc2, month_date2 ) 
				VALUES ('$mb[id]', '$mb[pt_id]', '$po_point', '$po_content', '$server_time','$dayofweek','$time_ym','admin','$time_ymd')";
		sql_query($sql);
	} 
	
	// 차감
	else if($po_kind=='O') {
		if(($sum['total'] - $po_point) < 0) {
			alert('수수료차감 후 잔액이 음의 값이 되므로 차감하실 수 없습니다.');
		}

		$chk_po_point = $po_point;
		$sql = "select * from shop_partner_pay where total > 0 and mb_id = '$mb[id]' ";
		$result = sql_query($sql);
		for($i=0; $row = sql_fetch_array($result); $i++) {	

			if($chk_po_point == 0 ) { break; } // 0 일때 중지

			$is_total = (int)$row['total'];
			$is_po_point = 0;

			// 차감 금액이 잔액보다 같거나 크다면?
			if($chk_po_point >= $is_total) {
				$is_po_point = $is_total;
			} 
			else if($chk_po_point < $is_total) {
				$is_po_point = $chk_po_point;
			}		
		
			$sql = " update shop_partner_pay 
						set outcome	= outcome+$is_po_point,
							total = total-$is_po_point,
							p_cancel = p_cancel+$is_po_point,
							reg_date = '$server_time' 
					  where index_no = '$row[index_no]' ";
			sql_query($sql);			

			$chk_po_point = $chk_po_point - $is_po_point;
		}

		$sql = "update shop_member set pay=pay-$po_point where id='$mb[id]'";
		sql_query($sql);

		$sql = "insert into shop_partner_paylog 
					   ( mb_id, pt_id, ca_money,  memo, wdate, ju_date, month_date, etc2, month_date2 ) 
				VALUES ('$mb[id]', '$mb[pt_id]', '$po_point', '$po_content', '$server_time','$dayofweek','$time_ym','cancel','$time_ymd')";
		sql_query($sql);
	}

	alert('정상적으로 처리 되었습니다.','replace');
}
?>