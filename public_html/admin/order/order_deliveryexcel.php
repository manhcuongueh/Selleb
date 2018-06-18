<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_order ";
$sql_search = " where (left(gs_se_id,3)='AP-' or gs_se_id = 'admin') and dan = '3' ";
$sql_order  = " order by orderdate desc, index_no asc";

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

$fname = tempnam(TW_DATA_PATH, "tmp-orderdeliverylist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('판매자ID','주문번호','일련번호','주문자명','주문자전화1','주문자전화2','수령자명','수령지전화1','수령지전화2','수령지우편번호','수령지주소','배송회사','운송장번호');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);	
	
	$delivery = explode('|', $row['delivery']);

	$j = 0;
	$worksheet->write($i, $j++, ' '.$row[gs_se_id]);
	$worksheet->write($i, $j++, ' '.$row[odrkey]);
	$worksheet->write($i, $j++, ' '.$row[orderno]);
	$worksheet->write($i, $j++, $row[name]);
	$worksheet->write($i, $j++, ' '.$row[telephone]);
	$worksheet->write($i, $j++, ' '.$row[cellphone]);
	$worksheet->write($i, $j++, $row[b_name]);
	$worksheet->write($i, $j++, ' '.$row[b_telephone]);
	$worksheet->write($i, $j++, ' '.$row[b_cellphone]);
	$worksheet->write($i, $j++, ' '.$row[b_zip]);
	$worksheet->write($i, $j++, print_address($row[b_addr1], $row[b_addr2], $row[b_addr3], $row[b_addr_jibeon]));
	$worksheet->write($i, $j++, $delivery[0]);
	$worksheet->write($i, $j++, ' '.$row[gonumber]);
	$i++;
}

$workbook->close();

$title = iconv_euckr("엑셀배송처리");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>