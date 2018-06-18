<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$s_date = date('Y-m');

if(!$j_sdate)
	$j_sdate = $s_date."-01";

if(!$j_ddate)
	$j_ddate = $s_date."-31";

$sql_common = " from shop_seller ";
$sql_search = " where state='1' ";
if($stx && $sfl) {
    $sql_search .= " and $sfl like '$stx%' ";
}

if(!$orderby) {
    $filed = "wdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$sql = " select * $sql_common $sql_search $sql_order ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(TW_DATA_PATH, "tmp-totallist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('업체명','업체코드','회원ID','총건수','주문총계','공급가총계','결제수수료','정산액총계','가맹점수수료','포인트적립','포인트결제','쿠폰할인','배송비','옵션가','실공급가','본사마진','정산금액','은행명','계좌번호','예금주명');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);	
	
	$u			= 0;
	$i_table	= '';
	$i_total	= 0;
	$tot_damt	= 0;
	$tot_amt	= 0;
	$tot_upoint	= 0;
	$tot_point	= 0;
	$tot_result	= 0;
	$tot_af_amt = 0;
	$tot_del	= 0;
	$tot_dc		= 0;
	$tot_iamt	= 0;
	$tot_di_amt = 0;
	$tot_super	= 0;

	$sql = "select * 
	           from shop_order 
			  where gs_se_id = '$row[sup_code]' 
				and dan = '5' 
				and itempay_yes = '0' 
				and ('$j_sdate' <= overdate_s and overdate_s <= '$j_ddate') 
				and user_ok = '1'";
	$result2 = sql_query($sql);
	while($row2 = sql_fetch_array($result2)) {

		// 상품정보
		$gs = get_order_goods($row2['orderno']);
		
		// 수수료 적립로그
		$p_log = sql_fetch("select SUM(in_money) as total from shop_partner_paylog where etc1='$row2[orderno]'");	

		$sql  = " select * from shop_cart where orderno = '$row2[orderno]' ";
		$sql .= " group by gs_id order by io_type asc, index_no asc ";
		$res = sql_query($sql);
		for($k=0; $ct=sql_fetch_array($res); $k++) {
			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + $gs[daccount]) * ct_qty))) as damt,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as iamt
					   from shop_cart
					  where gs_id = '$ct[gs_id]' 
						and odrkey = '$ct[odrkey]' ";
			$sum = sql_fetch($sql);
		}

		$damt	= (int)$sum['damt'];
		$point	= (int)$sum['point'];
		$qty	= (int)$sum['qty'];
		$iamt	= (int)$sum['iamt'];

		$tot_damt	+= $damt; // 공급가
		$tot_amt	+= (int)$row2['account']; // 주문금액
		$tot_upoint += (int)$row2['use_point']; // 적립금결제
		$tot_del	+= (int)$row2['del_account']; // 배송비결제
		$tot_dc		+= (int)$row2['dc_exp_amt']; // 쿠폰할인
		$tot_point  += $point; // 포인트적립	
		$tot_iamt	+= $iamt; // 옵션가
		$tot_di_amt += $damt - $iamt; // 실공급가

		$tot_af_amt += (int)$p_log['total'];

		if($u==0)
			$i_table = $i_table.$row2['index_no'];
		else
			$i_table = $i_table."|".$row2['index_no'];
		
		$u++;
		
		switch($row2['buymethod']) {
			case 'C': // 신용카드				
				$pg_amt	= ($row2['use_account']/100) * $config['shop_card'];
				$tot_result	+= (int)$pg_amt;
				break;			
			case 'ER': // 에스크로 계좌이체
			case 'R': // 계좌이체					
				$pg_amt	= ($row2['use_account']/100) * $config['shop_bank'];
				$tot_result	+= (int)$pg_amt;
				break;			
			case 'H': // 핸드폰					
				if($config['shop_phone_type']=='%')
					$pg_amt = ($row2['use_account']/100) * $config['shop_phone'];
				else
					$pg_amt = $config['shop_phone'];

				$tot_result	+= (int)$pg_amt;
				break;			
			case 'ES': // 에스크로 가상계좌
			case 'S': // 가상계좌					
				if($config['shop_yesc_type']=='%')
					$pg_amt = ($row2['use_account']/100) * $config['shop_yesc'];
				else
					$pg_amt = $config['shop_yesc'];

				$tot_result	+= (int)$pg_amt;
				break;
		}
	}

	// 결제수수료 0:공급업체부담 , 1:본사부담
	if($config['shop_i']==0)
		$tot_margin	= $tot_damt - $tot_result;
	else
		$tot_margin	= $tot_damt;			
	
	// 본사마진 계산
	$tot_super = ($tot_amt - $tot_margin) - ($tot_af_amt + $tot_upoint + $tot_point + $tot_dc);

	$sql_search = " and ('$j_sdate' <= month and month <= '$j_ddate') ";
	$q = sql_query("select * from shop_seller_cal where mb_id='$row[mb_id]' $sql_search ");
	while($r=sql_fetch_array($q)){
		$i_total += (int)$r['money'];
	}

	$j = 0;	
	$worksheet->write($i, $j++, $row['in_compay']);
	$worksheet->write($i, $j++, $row['sup_code']);
	$worksheet->write($i, $j++, ' '.$row['mb_id']);
	$worksheet->write($i, $j++, $u);
	$worksheet->write($i, $j++, $tot_amt);
	$worksheet->write($i, $j++, $tot_damt);
	$worksheet->write($i, $j++, $tot_result);
	$worksheet->write($i, $j++, $tot_margin);
	$worksheet->write($i, $j++, $tot_af_amt);
	$worksheet->write($i, $j++, $tot_point);	
	$worksheet->write($i, $j++, $tot_upoint);
	$worksheet->write($i, $j++, $tot_dc);
	$worksheet->write($i, $j++, $tot_del);
	$worksheet->write($i, $j++, $tot_iamt);
	$worksheet->write($i, $j++, $tot_di_amt);
	$worksheet->write($i, $j++, $tot_super);
	$worksheet->write($i, $j++, $i_total);	
	$worksheet->write($i, $j++, $row['n_bank']);
	$worksheet->write($i, $j++, ' '.$row['n_bank_num']);
	$worksheet->write($i, $j++, $row['n_name']);
	$i++;
}

$workbook->close();

$title = iconv_euckr("공급업체정산");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>