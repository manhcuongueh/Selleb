<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_gift ";
$sql_search = " where gr_id = '$gr_id' ";

if($stx && $sfl) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod";

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

$fname = tempnam(TW_DATA_PATH, "tmp-giftlist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('일련번호','쿠폰명','설명','발행금액','시작일','종료일','등록일','인증번호','사용','사용자아이디','사용자이름','최종사용일');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);

	$grp = sql_fetch("select * from shop_gift_group where gr_id = '$row[gr_id]'");
	$grp = array_map('iconv_euckr', $grp);

	if(is_null_time($row['mb_wdate'])) $row['mb_wdate'] = '';
	$row['gi_use'] = $row['gi_use'] ? 'yes' : 'no';

	$j = 0;
	$worksheet->write($i, $j++, ' '.$row['gr_id']);
	$worksheet->write($i, $j++, $row['gr_subject']);
	$worksheet->write($i, $j++, $grp['gr_explan']);
	$worksheet->write($i, $j++, $row['gr_price']);
	$worksheet->write($i, $j++, $row['gr_sdate']);
	$worksheet->write($i, $j++, $row['gr_edate']);
	$worksheet->write($i, $j++, $grp['gr_wdate']);
	$worksheet->write($i, $j++, ' '.$row['gi_num']);
	$worksheet->write($i, $j++, $row['gi_use']);
	$worksheet->write($i, $j++, ' '.$row['mb_id']);
	$worksheet->write($i, $j++, $row['mb_name']);
	$worksheet->write($i, $j++, $row['mb_wdate']);
	$i++;
}

$workbook->close();

$title = iconv_euckr("쿠폰(인쇄용)");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>