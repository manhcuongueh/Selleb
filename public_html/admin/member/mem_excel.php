<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_member ";
$sql_search = " where id <> 'admin' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}
if($sst) {
	$sql_search .= " and grade='$sst' ";
}
if($sca) {
	$sql_search .= " and gender='$sca' ";
}

// 기간검색
if($j_sdate && $j_ddate)
    $sql_search .= " and $q_date_field between '$j_sdate 00:00:00' and '$j_ddate 23:59:59' ";
else if($j_sdate && !$j_ddate)
	$sql_search .= " and $q_date_field between '$j_sdate 00:00:00' and '$j_sdate 23:59:59' ";
else if(!$j_sdate && $j_ddate)
	$sql_search .= " and $q_date_field between '$j_ddate 00:00:00' and '$j_ddate 23:59:59' ";

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

$sql = " select * $sql_common $sql_search $sql_order  ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(TW_DATA_PATH, "tmp-memberlist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('회원명','아이디','성별','레벨','추천인','전화번호','핸드폰','우편번호','주소','이메일','가입일','로그인','메일수신','SMS수신','최후아이피','보유적립금');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);
	
	$j = 0;
	$worksheet->write($i, $j++, $row['name']);
	$worksheet->write($i, $j++, ' '.$row['id']);
	$worksheet->write($i, $j++, $row['gender']);
	$worksheet->write($i, $j++, $row['grade']);
	$worksheet->write($i, $j++, ' '.$row['pt_id']);
	$worksheet->write($i, $j++, ' '.$row['telephone']);
	$worksheet->write($i, $j++, ' '.$row['cellphone']);
	$worksheet->write($i, $j++, ' '.$row['zip']);
	$worksheet->write($i, $j++, print_address($row['addr1'], $row['addr2'], $row['addr3'], $row['addr_jibeon']));
	$worksheet->write($i, $j++, $row['email']);
	$worksheet->write($i, $j++, $row['reg_time']);
	$worksheet->write($i, $j++, $row['login_sum']);
	$worksheet->write($i, $j++, $row['mailser']);
	$worksheet->write($i, $j++, $row['smsser']);
	$worksheet->write($i, $j++, $row['login_ip']);
	$worksheet->write($i, $j++, $row['point']);	
	$i++;
}

$workbook->close();

$title = iconv_euckr("회원목록");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>