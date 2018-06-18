<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_order_cancel a left join shop_order b on (a.ca_od_uid=b.index_no) ";
$sql_search = " where a.ca_cancel_use='주문취소' and a.ca_it_aff='0' and ca_it_seller ='$seller[sup_code]' ";

if($stx && $sfl) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and ( ";
    switch($sst) {
		case "ca_g" :
			$sql_search .= " (a.ca_type = '일반취소') ";
			break;
		case "ca_s" :
			$sql_search .= " (a.ca_type = '부분취소') ";
			break;
		case "ca_n" :
			$sql_search .= " (a.ca_yn = '0') ";
			break;
		case "ca_y" :
			$sql_search .= " (a.ca_yn = '1') ";
			break;
		default :
			$sql_search .= " (b.buymethod = '$sst') ";
			break;
    }
    $sql_search .= " ) ";
}

// 신청일
if($j_sdate && $j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_sdate' and left(a.ca_wdate,10) <= '$j_ddate')";  

if($j_sdate && !$j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_sdate' and left(a.ca_wdate,10) <= '$j_sdate')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_ddate' and left(a.ca_wdate,10) <= '$j_ddate')";

if(!$orderby) {
    $filed = "a.ca_yn";
    $sod = "asc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod, ca_uid desc";

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

$fname = tempnam(TW_DATA_PATH, "tmp-ordercancellist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('주문자명','수령자명','주문번호','일련번호','주문상품','사유','상세사유','취소금액','환불계좌정보','신청일','처리상태','처리담당자','처리완료일','PG LOG');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);

	$gs = get_order_goods($row['orderno']);
	$gs = array_map('iconv_euckr', $gs);

	$ca_bank = '';
	if($row['ca_bankcd'] && $row['ca_banknum'] && $row['ca_bankname']) {
		$ca_bank = $row['ca_bankcd']." (계좌번호:".$row['ca_banknum']." 예금주:".$row['ca_bankname'].")";
	}

	$ca_yn = $row['ca_yn']?'완료':'대기';

	$sql = " select * from shop_cart where orderno = '$row[ca_key]' ";
	$sql.= " order by io_type asc, index_no asc ";
	$res = sql_query($sql);
	$it_name = '';
	for($k=0; $ct=sql_fetch_array($res); $k++) {
		
		if((int)$sum['price'] > 0) {
			$io_price = '&nbsp;('.number_format($sum['price'], 0).'원'.')';
		}

		if($ct['io_type'])
			$it_name .= $comma . "[추가상품] ".$ct['ct_option']." ".$ct['ct_qty']."개".$io_price;
		else
			$it_name .= $comma . $ct['ct_option']." ".$ct['ct_qty']."개".$io_price;

		$comma = '|';
	}

	
	$j = 0;
	$worksheet->write($i, $j++, $row['name']);
	$worksheet->write($i, $j++, $row['b_name']);
	$worksheet->write($i, $j++, ' '.$row['odrkey']);
	$worksheet->write($i, $j++, ' '.$row['orderno']);
	$worksheet->write($i, $j++, iconv_euckr($it_name));
	$worksheet->write($i, $j++, $row['ca_cancel']);
	$worksheet->write($i, $j++, $row['ca_memo']);
	$worksheet->write($i, $j++, $row['use_account']);
	$worksheet->write($i, $j++, iconv_euckr($ca_bank));
	$worksheet->write($i, $j++, $row['ca_wdate']);
	$worksheet->write($i, $j++, iconv_euckr($ca_yn));
	$worksheet->write($i, $j++, $row['ca_yname']);
	$worksheet->write($i, $j++, (substr($row['ca_ydate'],0,1) > 0) ? $row['ca_ydate']:"");
	$worksheet->write($i, $j++, $row['ca_logs']);
	$i++;
}

$workbook->close();

$title = iconv_euckr("주문취소 요청");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>