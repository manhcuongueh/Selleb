<?php
include_once("./_common.php");

if($_REQUEST['mode'] == 'decide') { // 구매결정
	user_ok($idx, $mb_no);		
	alert("정상적으로 처리 되었습니다", TW_SHOP_URL."/orderview.php?odrkey=$odrkey");
}

$gw_head_title = '주문상세조회';
include_once("./_head.php");

$od = get_order($odrkey);

if($mb_no != $od['mb_no']) // 주문자 체크  
	goto_url(TW_SHOP_URL.'/orderlist.php');

// 결제 캐쉬정보
$cash = unserialize($od['cash_info']);

$appname = ""; 
$receipt = "";
switch($od['buymethod']) {
	case 'K' : // 카카오페이
	case 'C' : // 신용카드
	case 'ER' : // 에스크로 계좌이체
	case 'R' : // 실시간 계좌이체
	case 'H' : // 휴대폰결제							
		if($cash['appldate']) {
			$appname .= "승인시간"; 
			$receipt .= "{$cash[appldate]}";
			if($cash['applnum']) $receipt .= " (승인번호 : {$cash[applnum]})";
		}
		break;
	case 'ES' : // 에스크로가상계좌
	case 'S' : // 가상계좌
		$appname  = "가상계좌정보";
		$receipt  = "계좌번호 : {$cash[vact_num]}";
		if($cash['vact_name']) $receipt .= "<br>예금주명 : {$cash[vact_name]} ";
		if($cash['vact_bankcode']) $receipt .= "<br>은행명(코드) : {$cash[vact_bankcode]}";
		if($cash['vact_date']) $receipt .= "<br>입금마감시간 : {$cash[vact_date]}";
		if($cash['vact_inputname']) $receipt .= "<br>입금자명 : {$cash[vact_inputname]}";	
		break; 
	case 'B' : // 무통장결제
		$appname  = "입금계좌정보"; 
		$receipt  = "계좌번호 : {$od[bank]}<br>입금예정일 : {$od[indate]}";
		break;
}

$sql = " select * 
		   from shop_order
		  where mb_no = '$mb_no' 
		    and odrkey = '$odrkey'
		  group by odrkey 
		  order by index_no desc ";
$result = sql_query($sql);

include_once($theme_path.'/orderview.skin.php');

include_once("./_tail.php");
?>