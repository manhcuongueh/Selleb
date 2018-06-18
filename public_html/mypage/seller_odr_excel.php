<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$sql_common = " from shop_order ";
$sql_search = " where gs_se_id = '$seller[sup_code]' ";

switch($set){
	case "today":
		$sql_search .= " and orderdate_s='$time_ymd' and dan != '0' ";
		break;
	case "whole":
		$sql_search .= " and dan != '0' ";
		break;
	default:
		$sql_search .= " and dan = '$set' ";
		break;
}

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and ( ";
    switch ($sst) {
		case "Y" :
			$sql_search .= " (mb_yes = '1') ";
			break;
		case "N" :
			$sql_search .= " (mb_yes = '0') ";
			break;
		case "monitor" :
			$sql_search .= " (path = '0') ";
			break;
		case "mobile" :
			$sql_search .= " (path = '1') ";
			break;
		default :
			if(in_array($sst, array('C','B','R','H','S','ER','ES')))
				$sql_search .= " (buymethod = '$sst') ";
			else
				$sql_search .= " (dan = '$sst') ";
			break;
    }
    $sql_search .= " ) ";
}

if($j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_ddate')";
}
if($j_sdate && !$j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_sdate')";
}
if(!$j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_ddate' and orderdate_s <= '$j_ddate')";
}

if(!$orderby) {
    $filed = "orderdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod, index_no asc";

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

$fname = tempnam(TW_DATA_PATH, "tmp-orderlist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('주문번호','일련번호','상품명','옵션','주문수량','쿠폰할인','적립금결제','배송비','실결제금액','총계','공급가','결제방법','현황','주문자명','수취인명','수취인연락처','수취인핸드폰','수취인우편번호','수취인주소','주문시전달사항','배송업체','송장번호','입금자명','입금날짜','주문날짜','사업자등록번호','사업자상호명','대표명','사업장소재지','업태','종목','매입계산서 요청','현금영수증 사업자지출용','현금영수증 개인소득용');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);	
	
	$gs = get_order_goods($row['orderno'], 'gname');

	// 장바구니 검사
	$sql = " select * from shop_cart where orderno = '$row[orderno]' ";
	$sql.= " group by gs_id order by io_type asc, index_no asc ";
	$res = sql_query($sql);
	while($ct=sql_fetch_array($res)) {
		$mny = (int)$gs['daccount'];
		
		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + {$mny}) * ct_qty))) as mny,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty
				   from shop_cart
				  where gs_id = '$ct[gs_id]' 
					and odrkey = '$ct[odrkey]' ";
		$sum = sql_fetch($sql);

		unset($it_name);
		$it_options = print_complete_options($ct['gs_id'], $ct['odrkey'], 1);
		if($it_options && $ct['io_id']){
			$it_name = $it_options;
		}
	}

	$sell_mny = (int)$sum['mny'];
	$sell_qty = (int)$sum['qty'];

	$taxbill_yes = "";
	if($row[taxbill_yes]=='Y')
		$taxbill_yes = "세금계산서 발행요청";
	else if($row[taxsave_yes]=='Y')
		$taxbill_yes = "현금영수증발행요청[개인소득공제용]";
	else if($row[taxsave_yes]=='S')
		$taxbill_yes = "현금영수증발행요청[사업자지출증빙용]";

	$delivery = explode('|', $row['delivery']);

	$j = 0;
	$worksheet->write($i, $j++, ' '.$row[odrkey]);
	$worksheet->write($i, $j++, ' '.$row[orderno]);
	$worksheet->write($i, $j++, iconv_euckr($gs[gname]));	
	$worksheet->write($i, $j++, iconv_euckr($it_name));
	$worksheet->write($i, $j++, $sell_qty);	
	$worksheet->write($i, $j++, $row[dc_exp_amt]);
	$worksheet->write($i, $j++, $row[use_point]);
	$worksheet->write($i, $j++, $row[del_account]);
	$worksheet->write($i, $j++, $row[use_account]);
	$worksheet->write($i, $j++, $row[account]+$row[del_account]);
	$worksheet->write($i, $j++, $sell_mny);
	$worksheet->write($i, $j++, iconv_euckr($ar_method[$row[buymethod]]));
	$worksheet->write($i, $j++, iconv_euckr($ar_dan[$row[dan]]));
	$worksheet->write($i, $j++, $row[name]);
	$worksheet->write($i, $j++, $row[b_name]);
	$worksheet->write($i, $j++, ' '.$row[b_telephone]);
	$worksheet->write($i, $j++, ' '.$row[b_cellphone]);
	$worksheet->write($i, $j++, ' '.$row[b_zip]);
	$worksheet->write($i, $j++, print_address($row[b_addr1], $row[b_addr2], $row[b_addr3], $row[b_addr_jibeon]));
	$worksheet->write($i, $j++, $row[memo]);
	$worksheet->write($i, $j++, $delivery[0]);
	$worksheet->write($i, $j++, ' '.$row[gonumber]);
	$worksheet->write($i, $j++, $row[incomename]);
	$worksheet->write($i, $j++, $row[incomedate_s]);
	$worksheet->write($i, $j++, $row[orderdate_s]);
	$worksheet->write($i, $j++, ' '.$row[company_saupja_no]);
	$worksheet->write($i, $j++, $row[company_name]);
	$worksheet->write($i, $j++, $row[company_owner]);
	$worksheet->write($i, $j++, $row[company_addr]);
	$worksheet->write($i, $j++, $row[company_item]);
	$worksheet->write($i, $j++, $row[company_service]);
	$worksheet->write($i, $j++, iconv_euckr($taxbill_yes));
	$worksheet->write($i, $j++, ' '.$row[tax_saupja_no]);
	$worksheet->write($i, $j++, ' '.$row[tax_hp]);
	$i++;
}

$workbook->close();

$title = iconv_euckr("주문내역");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>