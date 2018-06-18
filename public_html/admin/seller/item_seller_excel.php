<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$j_sdate1 = preg_replace('/[^0-9]/', '', $j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '', $j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$sql_common = " from shop_seller a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where (1) ";
if($stx && $sfl) {
    switch($sfl) {
        case "name" :
            $sql_search .= " and (b.$sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " and (a.$sfl like '%$stx%') ";
            break;
    }
}

if($sst) 
	$sql_search .= " and b.grade='$sst' ";

if(in_array($sca, array('0','1')))
	$sql_search .= " and a.state='$sca' ";

// 신청날짜
if($j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')";  

if($j_sdate && !$j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')";

if(!$orderby) {
    $filed = "a.wdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

$sql = " select a.* $sql_common $sql_search $sql_order ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(TW_DATA_PATH, "tmp-sellerlist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('아이디','업체코드','제공상품','업체(법인)명','사업자등록번호','전화번호','팩스번호','우편번호','사업장주소','업태','종목','대표자명','홈페이지','전달사항','은행명','계좌번호','예금주명','담당자명','담당자이메일','담당자전화번호');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);
	
	$j = 0;
	$worksheet->write($i, $j++, ' '.$row[mb_id]);
	$worksheet->write($i, $j++, $row[sup_code]);
	$worksheet->write($i, $j++, $row[in_item]);
	$worksheet->write($i, $j++, $row[in_compay]);
	$worksheet->write($i, $j++, ' '.$row[in_sanumber]);
	$worksheet->write($i, $j++, ' '.$row[in_phone]);
	$worksheet->write($i, $j++, ' '.$row[in_fax]);
	$worksheet->write($i, $j++, ' '.$row[in_zipcode]);
	$worksheet->write($i, $j++, print_address($row[in_addr1], $row[in_addr2], $row[in_addr3], $row[in_addr_jibeon]));	
	$worksheet->write($i, $j++, $row[in_upte]);
	$worksheet->write($i, $j++, $row[in_up]);
	$worksheet->write($i, $j++, $row[in_name]);
	$worksheet->write($i, $j++, set_http($row[in_home]));
	$worksheet->write($i, $j++, $row[memo]);	
	$worksheet->write($i, $j++, $row[n_bank]);
	$worksheet->write($i, $j++, ' '.$row[n_bank_num]);
	$worksheet->write($i, $j++, $row[n_name]);
	$worksheet->write($i, $j++, $row[in_dam]);
	$worksheet->write($i, $j++, $row[n_email]);
	$worksheet->write($i, $j++, $row[n_phone]);
	$i++;
}

$workbook->close();

$title = iconv_euckr("공급업체");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>