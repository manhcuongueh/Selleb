<?php
include_once("./_common.php");
require_once("./libs/INImx.php");

$inimx = new INImx;

$inimx->reqtype		= "PAY";  //결제요청방식
$inimx->inipayhome 	= M_PATH_INI."/"; //로그기록 경로 (log폴더에 대해 777 권한 설정)
$inimx->id_merchant = substr($P_TID,'10','10');  //
$inimx->status		= $P_STATUS;
$inimx->rmesg1		= $P_RMESG1;
$inimx->tid			= $P_TID;
$inimx->req_url		= $P_REQ_URL;
$inimx->noti		= $P_NOTI;

// 카드 발급사(은행) 코드
$arr = array(
	"02"=>"한국산업은행",
	"03"=>"기업은행", 
	"04"=>"국민은행 (주택은행)", 
	"05"=>"외환은행",
	"07"=>"수협중앙회",
	"11"=>"농협중앙회", 
	"12"=>"단위농협", 
	"16"=>"축협중앙회",
	"20"=>"우리은행",
	"21"=>"신한은행 (조흥은행)", 
	"23"=>"제일은행", 
	"25"=>"하나은행 (서울은행)",
	"26"=>"신한은행",
	"27"=>"한국씨티은행 (한미은행)", 
	"31"=>"대구은행", 
	"32"=>"부산은행",
	"34"=>"광주은행",
	"35"=>"제주은행", 
	"37"=>"전북은행", 
	"38"=>"강원은행",
	"39"=>"경남은행",
	"41"=>"비씨카드", 
	"53"=>"씨티은행", 
	"54"=>"홍콩상하이은행",
	"71"=>"우체국",
	"81"=>"하나은행", 
	"83"=>"평화은행", 
	"87"=>"신세계",
	"88"=>"신한은행(조흥 통합)"
);

if($inimx->status =="00")   // 모바일 인증이 성공시
{
	$inimx->startAction();  // 승인요청  
  	$inimx->getResult();	//승인결과 파싱

	$escw_yn = 'N';
	if($default['cf_escrow_yn'] && 
		in_array($inimx->m_payMethod, array('VBANK')) && 
		in_array(get_session('ss_pay_method'), array('ER','ES'))) {
		$escw_yn = 'Y';
	}
	
	$odrkey = $inimx->m_moid;
	$cash = array();
	$cash['tpg']			= 'inicis';				// PG사
	$cash['tid']			= $inimx->m_tid;		// 거래번호
	$cash['mid']			= $inimx->m_mid;		// 상점ID
	$cash['resultcode']		= $inimx->m_resultCode;	// 승인결과코드
	$cash['moid']			= $inimx->m_moid;		// 주문번호
	$cash['resultmsg']		= iconv_utf8($inimx->m_resultMsg);	// 결과메시지
	$cash['paymethod']		= $inimx->m_payMethod;	// 지불수단
	$cash['prtc']			= $inimx->m_prtc;		// 부분취소가능여부(0:불가, 1:가능)
	$cash['resultprice']	= $inimx->m_resultprice; // 승인금액

	$cash['card_number']	= $inimx->m_cardNumber;	// 카드번호
	$cash['card_quota']		= $inimx->m_cardQuota;	// 할부개월
	$cash['card_code']		= $inimx->m_cardCode;	// 카드코드
	$cash['card_purchase']	= $inimx->m_cardpurchase;	// 매입사코드
	$cash['card_bankcode']	= $inimx->m_cardIssuerCode;	// 발급사코드
	$cash['appldate']		= $inimx->m_pgAuthDate."-".$inimx->m_pgAuthTime; // 승인일
	$cash['applnum']		= $inimx->m_authCode;	// 승인번호
	$cash['acct_bankcode']	= $inimx->m_cardMember;	// 가맹점번호
	$cash['vact_num']		= $inimx->m_vacct;		// 가상계좌번호
	$cash['vact_bankcode']	= $arr[$inimx->m_vcdbank]; // 은행코드
	$cash['vact_date']		= $inimx->m_dtinput;	// 입금예정일
	$cash['vact_time']		= $inimx->m_tminput;	// 입금예정시각

	$cash['vact_inputname']	= iconv_utf8($inimx->m_buyerName); // 구매자명
	$cash['vact_name']		= iconv_utf8($inimx->m_nmvacct);   // 예금주
	$cash['hpp_num']		= $inimx->m_codegw;		// 통신사
	$cash['escw_yn']		= $escw_yn;				// 에스크로 사용여부
	$cash['ss_pg_id']		= $ss_pg_id;			// 개별결제 검사 (가맹점 ID)
	$cash_info = serialize($cash);

	switch($inimx->m_payMethod)
	{   
		case(CARD):  //신용카드 안심클릭
		case(MOBILE):  //휴대폰결제
			$sql = "update shop_order 
					   set cash_info = '$cash_info',
						   dan = '2',
						   incomedate = '$server_time',
						   incomedate_s	= '$time_ymd' 
					 where odrkey = '$odrkey'";

			$msg = "결제가 완료 되었습니다.";
			break;	
		case(VBANK):  //가상계좌
			$sql = "update shop_order 
					   set vact_num = '".$inimx->m_vacct."',
						   cash_info = '$cash_info',
						   dan = '1'
					 where odrkey = '$odrkey'";

			$msg = "가상계좌 발급이 완료 되었습니다.";
			break;
	}

	sql_query($sql, FALSE);

	sql_query("update shop_cart set ct_select='1' where odrkey='$odrkey'");

	alert($msg, "$tb[bbs_root]/orderinquiryview.php?odrkey=$odrkey");
}
else // 모바일 인증 실패
{
	$res_msg  = "인증결과코드:".iconv_utf8($inimx->status)."\\r\\n";
	$res_msg .= "인증결과메시지:".iconv_utf8($inimx->rmesg1);
	alert($res_msg);
}  
?>
