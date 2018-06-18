<?php
include_once('./_common.php');

include_once(M_PATH_KAKAOPAY.'/incKakaopayCommon.php');
include_once(M_PATH_KAKAOPAY.'/lgcns_CNSpay.php');

// 로그 저장 위치 지정
$connector = new CnsPayWebConnector($LogDir);
$connector->CnsActionUrl($CnsPayDealRequestUrl);
$connector->CnsPayVersion($phpVersion);

// 요청 페이지 파라메터 셋팅
$connector->setRequestData($_REQUEST);

// 추가 파라메터 셋팅
$connector->addRequestData("actionType", "PY0"); // actionType : CL0 취소, PY0 승인, CI0 조회
$connector->addRequestData("MallIP", $_SERVER['REMOTE_ADDR']); // 가맹점 고유 ip
$connector->addRequestData("CancelPwd", $cancelPwd);

//가맹점키 셋팅 (MID 별로 틀림)
$connector->addRequestData("EncodeKey", $merchantKey);

// 4. CNSPAY Lite 서버 접속하여 처리
$connector->requestAction();

// 5. 결과 처리

$resultCode = $connector->getResultData("ResultCode"); // 결과코드 (정상 :3001 , 그 외 에러)
$resultMsg = $connector->getResultData("ResultMsg"); // 결과메시지
$authDate = $connector->getResultData("AuthDate"); // 승인일시 YYMMDDHH24mmss
$authCode = $connector->getResultData("AuthCode"); // 승인번호
$buyerName = $connector->getResultData("BuyerName"); // 구매자명
$goodsName = $connector->getResultData("GoodsName"); // 상품명
$payMethod = $connector->getResultData("PayMethod"); // 결제수단
$mid = $connector->getResultData("MID"); // 가맹점ID
$tid = $connector->getResultData("TID"); // 거래ID
$moid = $connector->getResultData("Moid"); // 주문번호
$amt = $connector->getResultData("Amt"); // 금액
$cardCode = $connector->getResultData("CardCode"); // 카드사 코드
$cardName = $connector->getResultData("CardName"); // 결제카드사명
$cardQuota = $connector->getResultData("CardQuota"); // 할부개월수 ex) 00:일시불,02:2개월
$cardInterest = $connector->getResultData("CardInterest"); // 무이자 여부 (0:일반, 1:무이자)
$cardCl = $connector->getResultData("CardCl"); // 체크카드여부 (0:일반, 1:체크카드)
$cardBin = $connector->getResultData("CardBin"); // 카드BIN번호
$cardPoint = $connector->getResultData("CardPoint"); // 카드사포인트사용여부 (0:미사용, 1:포인트사용, 2:세이브포인트사용)

//부인방지토큰값
$nonRepToken =$_REQUEST["NON_REP_TOKEN"];

$paySuccess = false; // 결제 성공 여부    

$resultMsg = iconv("euc-kr", "utf-8", $resultMsg);
$cardName  = iconv("euc-kr", "utf-8", $cardName);
$goodsName = iconv("euc-kr", "utf-8", $goodsName);

/** 위의 응답 데이터 외에도 전문 Header와 개별부 데이터 Get 가능 */
if($payMethod == "CARD"){	//신용카드
	if($resultCode == "3001") $paySuccess = true; // 결과코드 (정상 :3001 , 그 외 에러)
}

if($paySuccess) {
	$cash = array();
	$cash['tpg']			= 'kakaopay';	// PG사
	$cash['tid']			= $tid;			// 거래ID
	$cash['mid']			= $mid;			// 가맹점ID
	$cash['card_code']		= $cardCode;	// 카드사 코드
	$cash['card_bankcode']	= $cardName;	// 카드 종류
	$cash['appldate']		= '20'.$authDate; // 승인 시간
	$cash['applnum']		= $authCode;	// 승인번호
	$cash['hpp_num']		= '';			// 휴대폰 번호
	$cash['escw_yn']		= '0';			// 에스크로 사용여부
	$cash['ss_pg_id']		= $ss_pg_id;	// 개별결제 검사 (가맹점 ID)
	$cash_info = serialize($cash);

	$sql = "update shop_order 
			   set cash_info = '$cash_info',
				   dan = '2',
				   incomedate = '$server_time',
				   incomedate_s	= '$time_ymd' 
			 where odrkey = '$moid'";
	$r = sql_query($sql);
	if($r) {
		sql_query("update shop_cart set ct_select='1' where odrkey='$moid'");
		$msg = "결제가 완료 되었습니다.";
		alert($msg, "$tb[bbs_root]/orderinquiryview.php?odrkey=$moid");
	}
} else {
   alert('[RESULT_CODE] : ' . $resultCode . '\\n[RESULT_MSG] : ' . $resultMsg);
}
?>