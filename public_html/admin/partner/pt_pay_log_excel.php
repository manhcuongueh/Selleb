<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$j_sdate1 = preg_replace('/[^0-9]/', '',$j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '',$j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$sql_common = " from shop_partner_payuse a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where a.mb_id!='admin' ";

if($stx && $sfl) {
    switch($sfl) {
        case "mb_id" :
            $sql_search .= " and (a.$sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " and (b.$sfl like '%$stx%') ";
            break;
    }
}

if($sst)
{	$sql_search .= " and b.grade='$sst' "; }

if($j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')"; }

if($j_sdate && !$j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')"; }

if(!$j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')"; }

if (!$orderby) {
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

$fname = tempnam(TW_DATA_PATH, "tmp-paylog.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('회원명','아이디','레벨','정산일','수수료','세금공제','실수령액','입금계좌');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);

	$mb = get_member($row[mb_id]);
	$mb = array_map('iconv_euckr', $mb);
	
	$j = 0;
	$worksheet->write($i, $j++, $mb[name]);
	$worksheet->write($i, $j++, ' '.$mb[id]);
	$worksheet->write($i, $j++, $mb[grade]);
	$worksheet->write($i, $j++, date('Y-m-d H:i:s',$row[wdate]));
	$worksheet->write($i, $j++, $row[out_money]);
	$worksheet->write($i, $j++, $row[tax2_money]);
	$worksheet->write($i, $j++, $row[tax3_money]);
	$worksheet->write($i, $j++, ' '.$row[bankinfo]);
	$i++;
}

$workbook->close();

$title = iconv_euckr("가맹점 정산처리내역");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>