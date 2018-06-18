<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no']) {
    alert("조회하실 주문서가 없습니다.");
}

$gw_head_title = '주문완료';
include_once("./_head.php");

$use_point = (int)get_session('use_point');

// 새로고침 중복 실행 방지			
if(!get_session('reorder-'.$odrkey))
{
	if(!in_array($od['buymethod'],array('B','P')) && $mb_yes) 
	{ 
		// 포인트 결제시 차감
		if($use_point > 0) { 
			insert_point($mb_no, $use_point, "포인트결제-주문번호 : $odrkey", 1);	
		}
	
		// 쿠폰 사용함으로 변경
		$sql = "select * from shop_order where odrkey='$odrkey'";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			if((int)$row['dc_exp_amt'] > 0) {
				$sql = "update shop_coupon_log 
						   set mb_use = '1',
							   od_id = '$row[orderno]',
							   cp_udate	= '$time_ymdhis'
						 where lo_id = '$row[dc_exp_lo_id]' ";
				sql_query($sql);
			}
		}
	}

	//주문완료 문자전송
	icode_order_sms_send($od['cellphone'], '2', $odrkey);

	// 새로고침 중복 실행방지
	set_session('reorder-'.$odrkey , $time_ymdhis);

	// 메일발송
	if($od['email']) {
		include_once(TW_INC_PATH."/mail.php");

		// 제목
		$subject1 = get_text($od['name'])."님 주문이 정상적으로 처리되었습니다.";
		$subject2 = get_text($od['name'])." 고객님께서 신규주문을 신청하셨습니다.";

		ob_start();
		include_once(TW_SHOP_PATH.'/orderform_update_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		// 주문자에게 메일발송
		mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

		// 관리자에게 메일발송
		if($super['email'] != $od['email']) {
			mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
		}
	}
}

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

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";	
$result = sql_query($sql);

include_once($theme_path.'/orderinquiryview.skin.php');

include_once("./_tail.php");
?>