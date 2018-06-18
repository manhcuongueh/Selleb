<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

$sql_common = " from shop_goods a ";
$sql_search = " where a.mb_id = '$member[id]' ";

if($code == 'partner_goods_stock') {
	$sql_search .= " and (a.stock_qty <= a.noti_qty and a.stock_mod = 1 and a.opt_subject = '') ";
}

if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate b on a.index_no=b.gs_id ";
    $sql_search .= " and (left(b.gcate,$len) = '$sca') ";
}

// 검색어
if($stx) {
    switch($sfl) {
        case "gname" :
		case "explan" :
		case "maker" :
		case "origin" :		
		case "model" :
            $sql_search .= " and a.$sfl like '%$stx%' ";
            break;
        default : 
            $sql_search .= " and a.$sfl like '$stx%' ";
            break;
    }
}

// 기간검색
if($j_sdate && $j_ddate)
    $sql_search .= " and a.$q_date_field between '$j_sdate 00:00:00' and '$j_ddate 23:59:59' ";
else if($j_sdate && !$j_ddate)
	$sql_search .= " and a.$q_date_field between '$j_sdate 00:00:00' and '$j_sdate 23:59:59' ";
else if(!$j_sdate && $j_ddate)
	$sql_search .= " and a.$q_date_field between '$j_ddate 00:00:00' and '$j_ddate 23:59:59' ";

// 브랜드
if(isset($q_brand) && $q_brand)
	$sql_search .= " and a.brand_uid = '$q_brand' ";

// 배송가능 지역
if(isset($q_zone) && $q_zone)
	$sql_search .= " and a.zone = '$q_zone' ";

// 상품재고
if($fr_stock && $to_stock)
	$sql_search .= " and a.$q_stock_field between '$fr_stock' and '$to_stock' ";

// 상품가격
if($fr_price && $to_price)
	$sql_search .= " and a.$q_price_field between '$fr_price' and '$to_price' "; 

// 판매여부
if(isset($q_isopen) && is_numeric($q_isopen))
	$sql_search .= " and a.isopen='$q_isopen' ";

// 과세유형
if(isset($q_notax) && is_numeric($q_notax))
	$sql_search .= " and a.notax = '$q_notax' ";

// 상품 필수옵션
if(isset($q_option) && is_numeric($q_option)) {
	if($q_option)
		$sql_search .= " and a.opt_subject <> '' ";
	else
		$sql_search .= " and a.opt_subject = '' ";
}

// 상품 추가옵션
if(isset($q_supply) && is_numeric($q_supply)) {
	if($q_supply)
		$sql_search .= " and a.spl_subject <> '' ";
	else
		$sql_search .= " and a.spl_subject = '' ";
}

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod ";

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

$fname = tempnam(TW_DATA_PATH, "tmp-goodslist.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('상품코드','카테고리','상품명','짧은설명','검색키워드','A/S가능여부','모델명','브랜드','과세설정','판매가능지역','판매가능지역 추가설명','원산지','제조사','판매여부','공급가격','시중가격','판매가격','가격대체문구','재고적용타입','재고수량','재고통보수량','최소주문한도','최대주문한도','적립금','판매기간 시작일','판매기간 종료일','구매가능레벨','가격공개','배송비유형','배송비결제','기본배송비','조건배송비','이미지등록방식','소이미지','중이미지1','중이미지2','중이미지3','중이미지4','중이미지5','상세설명','관리자메모');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row = sql_fetch_array($result)) {
	$row = array_map('iconv_euckr', $row);

	$comma = $gcate = "";
	$sql2 = "select * from shop_goods_cate where gs_id = '$row[index_no]'";
	$res2 = sql_query($sql2);
	while($row2=sql_fetch_array($res2)) {
		$gcate .= $comma.$row2['gcate'];
		$comma = ",";
	}

	if(is_null_time($row['sb_date'])) $row['sb_date'] = '';
	if(is_null_time($row['eb_date'])) $row['eb_date'] = '';
	
	$j = 0;
	$worksheet->write($i, $j++, ' '.$row['gcode']); // 상품코드
	$worksheet->write($i, $j++, ' '.$gcate); // 카테고리
	$worksheet->write($i, $j++, $row['gname']); // 상품명
	$worksheet->write($i, $j++, $row['explan']); // 짧은설명
	$worksheet->write($i, $j++, $row['keywords']); // 검색키워드
	$worksheet->write($i, $j++, $row['repair']); // A/S가능여부
	$worksheet->write($i, $j++, $row['model']); // 모델명
	$worksheet->write($i, $j++, $row['brand_nm']); // 브랜드
	$worksheet->write($i, $j++, $row['notax']); // 과세설정
	$worksheet->write($i, $j++, $row['zone']); // 판매가능지역
	$worksheet->write($i, $j++, $row['zone_msg']); // 판매가능지역 추가설명
	$worksheet->write($i, $j++, $row['origin']); // 원산지
	$worksheet->write($i, $j++, $row['maker']); // 제조사
	$worksheet->write($i, $j++, $row['isopen']); // 판매여부
	$worksheet->write($i, $j++, $row['daccount']); // 공급가격
	$worksheet->write($i, $j++, $row['saccount']); // 시중가격
	$worksheet->write($i, $j++, $row['account']); // 판매가격
	$worksheet->write($i, $j++, $row['price_msg']); // 가격대체문구
	$worksheet->write($i, $j++, $row['stock_mod']); // 재고적용타입
	$worksheet->write($i, $j++, $row['stock_qty']); // 재고수량
	$worksheet->write($i, $j++, $row['noti_qty']); // 재고통보수량
	$worksheet->write($i, $j++, $row['odr_min']); // 최소주문한도
	$worksheet->write($i, $j++, $row['odr_max']); // 최대주문한도
	$worksheet->write($i, $j++, $row['gpoint']);	// 적립금
	$worksheet->write($i, $j++, $row['sb_date']); // 판매기간 시작일
	$worksheet->write($i, $j++, $row['eb_date']); // 판매기간 종료일
 	$worksheet->write($i, $j++, $row['buy_level']); // 구매가능레벨
 	$worksheet->write($i, $j++, $row['buy_only']); // 가격공개
	$worksheet->write($i, $j++, $row['sc_type']); // 배송비유형
	$worksheet->write($i, $j++, $row['sc_method']); // 배송비결제
	$worksheet->write($i, $j++, $row['sc_amt']); // 기본배송비
	$worksheet->write($i, $j++, $row['sc_minimum']); // 조건배송비	
	$worksheet->write($i, $j++, $row['img_mod']); // 이미지등록방식
	$worksheet->write($i, $j++, $row['simg1']); // 소이미지
	$worksheet->write($i, $j++, $row['simg2']); // 중이미지1
	$worksheet->write($i, $j++, $row['simg3']); // 중이미지2
	$worksheet->write($i, $j++, $row['simg4']); // 중이미지3
	$worksheet->write($i, $j++, $row['simg5']); // 중이미지4
	$worksheet->write($i, $j++, $row['simg6']); // 중이미지5
	$worksheet->write($i, $j++, $row['memo']); // 상세설명
	$worksheet->write($i, $j++, $row['admin_memo']); // 관리자메모
	$i++;
}

$workbook->close();

$title = iconv_euckr("상품");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>