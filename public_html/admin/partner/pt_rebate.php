<?php
if(!defined('_TUBEWEB_')) exit;

// 추천 분양수수료 사용일경우
if($config['p_member'] == 'y') {

	$sql_dayweek = sql_dayofweek();
	$dayofweek = get_dayofweek();
	
	if($cf['etc4']=='item1')      { $etc4 ='item_tree1'; }	
	else if($cf['etc4']=='item2') { $etc4 ='item_tree2'; }	
	else if($cf['etc4']=='item3') { $etc4 ='item_tree3'; }	
	else if($cf['etc4']=='item4') { $etc4 ='item_tree4'; }	
	else if($cf['etc4']=='item5') { $etc4 ='item_tree5'; }

	$sort = sql_fetch("select state,p_tree from shop_partner_config where etc4='item_etc'");
	$conf = sql_fetch("select etc1 from shop_partner_config where etc4='$etc4'");

	$mny = explode("|", $conf['etc1']);
	$mb_recommend = $mb['pt_id'];

	if((int)$sort['p_tree'] > 0){	
		
		// 단계별로 루프실행		
		for($n=0; $n<(int)$sort['p_tree']; $n++) {  

			// 최상위일때 중지
			if($mb_recommend == '' || $mb_recommend == 'admin') { break; } 	
			
			$su = sql_fetch("select grade,pt_id from shop_member where id='$mb_recommend'");
			
			// %일때
			if($sort['state']=='%') {  
				$dr_ac_mny = ($cf['etc2'] / 100) * $mny[$n];
			}
			// 금액일때
			else {
				$dr_ac_mny = $mny[$n]; 
			}

			// 추가 수수료설정
			$set = sql_fetch("select ch,ch_ty from shop_partner_config where mb_grade='$su[grade]'");

			$dr_ch_mny	= 0;
			if($set['ch'] > 0) { 
				// %일때
				if($set['ch_ty'] == '%') {
					$dr_ch_mny = ($cf['etc2'] / 100) * $set['ch']; 			
				}
				
				//금액일때
				else { 
					$dr_ch_mny = $set['ch']; // 금액 그대로 계산                           
				}
			}

			$dr_ac_mny = (int)$dr_ac_mny + (int)$dr_ch_mny;

			$sql = "update shop_member set pay=pay+$dr_ac_mny where id='$mb_recommend'";
			sql_query($sql);


			$sql_search = "where mb_id='$mb_recommend' ";			
			if($config['p_type'] == 'month') {
				 // 월
				$sql_search .= " and month_date='$time_ym' ";
			} else { 
				// 주, 실시간
				$sql_search .= " and $sql_dayweek ";
			}

			$reb = sql_fetch("select index_no from shop_partner_pay $sql_search");
			if($reb['index_no']) {
				$sql = " update shop_partner_pay 
						    set income = income+$dr_ac_mny,
							    total = total+$dr_ac_mny,
							    p_member = p_member+$dr_ac_mny,
							    reg_date = '$server_time' 
							    $sql_search ";
				sql_query($sql);
			} else {
				$sql = "insert into shop_partner_pay 
				               ( mb_no, income, total, wdate, ju_date, month_date, reg_date, p_member, mb_id ) 
					    VALUES ('$mb[index_no]', '$dr_ac_mny', '$dr_ac_mny', '$server_time','$dayofweek',
						        '$time_ym','$server_time','$dr_ac_mny','$mb_recommend')";
				sql_query($sql);
			}
			
			// 적립 로그내역
			$k = $n + 1;

			$dr_content = $mb['id']."(".$mb['name'].")님 분양완료 ".$k."단계 ".number_format($dr_ac_mny)."원 적립";
			$sql = "insert into shop_partner_paylog 
						   ( mb_id, pt_id, in_money, memo, wdate, ju_date, month_date, etc2, month_date2 ) 
				    VALUES ('$mb_recommend', '$su[pt_id]', '$dr_ac_mny', '$dr_content', '$server_time','$dayofweek',
					        '$time_ym','member','$time_ymd')";
			sql_query($sql);

			$mb_recommend = $su['pt_id'];				
		} 		
	}
}
?>
