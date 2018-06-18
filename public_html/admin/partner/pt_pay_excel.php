<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_partner_pay a left join shop_member b on (a.mb_id = b.id) ";
$sql_search = " where a.mb_id != 'admin'
				  and (b.grade between 2 and 6)
				  and a.total > 0 ";
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
	$sql_search .= " and b.grade='$sst' ";

if(in_array($sca, array('0','2','3')))
	$sql_search .= " and a.ragi='$sca' ";

// 년/월 기간
if($sh_year && $sh_month) {
	$month_date = $sh_year . "-" . $sh_month;
	$sql_search .= " and (a.month_date = '$month_date') ";
}

// 주별 기간
if($sh_week) {
	$month_date = $sh_year . "-" . $sh_month;
	$sql_search .= " and (a.ju_date = '$sh_week') ";
}

if(!$orderby) {
    $filed = "a.reg_date";
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

$fname = tempnam(TW_DATA_PATH, "tmp-paylist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('회원명','아이디','레벨','확정수수료','세금공제','실수령액','은행명','계좌번호','예금주명','기간','정산');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);	
	
	$pt = sql_fetch("select * from shop_partner where mb_id='$row[mb_id]'");
	$pt = array_map('iconv_euckr', $pt);	

	$mb = get_member($row[mb_id]);
	$mb = array_map('iconv_euckr', $mb);	

	if($config[p_month]=='y'){
		$h_y = date("Y",$mb[term_date]);
		$h_m = date("m",$mb[term_date]);
		$h_d = date("d",$mb[term_date]);
		$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
		$ed = $new_hold - time();

		if($ed > 0) {  $extra_date = round($ed/(60*60*24)); $default_check = 1;}
		else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }
	}
	
	$tax2 = round(($row[total] * $config[accent_tax]) / 100); // 세금공제
	$tax3 = $row[total] - $tax2; // 실수령액	

	unset($p_type);
	if($config[p_type]=='month')
	{	$p_type = $row[month_date]; }		
	else if($config[p_type]=='ju')
	{	$p_type = $row[ju_date]; }

	switch($row[ragi]){
		case '0' : $pay_yes = "대기"; break;
		case '1' : $pay_yes = "완료"; break;
		case '2' : $pay_yes = "유보"; break;
		case '3' : $pay_yes = "거절"; break;
	}

	$j = 0;
	$worksheet->write($i, $j++, $mb[name]);
	$worksheet->write($i, $j++, ' '.$mb[id]);
	$worksheet->write($i, $j++, $mb[grade]);
	$worksheet->write($i, $j++, $row[total]);
	$worksheet->write($i, $j++, $tax2);
	$worksheet->write($i, $j++, $tax3);
	$worksheet->write($i, $j++, $pt[bank_company]);
	$worksheet->write($i, $j++, ' '.$pt[bank_number]);
	$worksheet->write($i, $j++, $pt[bank_name]);
	$worksheet->write($i, $j++, ' '.$p_type);
	if($config[p_month]=='y') {
		if($default_check==1)
			$worksheet->write($i, $j++, iconv_euckr($pay_yes));
		else
			$worksheet->write($i, $j++, iconv_euckr("미납"));
	} else {
		$worksheet->write($i, $j++, iconv_euckr($pay_yes));
	} 
	$i++;
}

$workbook->close();

$title = iconv_euckr("가맹점 수수료정산");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>