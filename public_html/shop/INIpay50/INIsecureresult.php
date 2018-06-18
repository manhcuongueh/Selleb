<?php
/**************************
 * 1. 라이브러리 인클루드 *
 **************************/
require(ROOT_INICIS."/libs/INILib.php");

/***************************************
 * 2. INIpay50 클래스의 인스턴스 생성 *
 ***************************************/
$inipay = new INIpay50;

/*********************
 * 3. 지불 정보 설정 *
 *********************/
if($default['cf_tax_flag_use']) { // 복합과세 사용 때
    $inipay->SetXPath("INIpay/GoodsInfo/Tax",     $_POST['comm_vat_mny']);  // 부가세 금액
    $inipay->SetXPath("INIpay/GoodsInfo/TaxFree", $_POST['comm_free_mny']); // 면세 금액
}

$inipay->SetField("inipayhome", ROOT_INICIS); // 이니페이 홈디렉터리(상점수정 필요)
$inipay->SetField("type", "securepay"); // 고정 (절대 수정 불가)
$inipay->SetField("pgid", "INIphp".$pgid); // 고정 (절대 수정 불가)
$inipay->SetField("subpgip","203.238.3.10"); // 고정 (절대 수정 불가)
$inipay->SetField("admin", $_SESSION['INI_ADMIN']); // 키패스워드(상점아이디에 따라 변경)
$inipay->SetField("debug", "true"); // 로그모드("true"로 설정하면 상세로그가 생성됨.)
$inipay->SetField("uid", $uid); // INIpay User ID (절대 수정 불가)
$inipay->SetField("goodname", iconv_euckr($goodname)); // 상품명
$inipay->SetField("currency", $currency); // 화폐단위
$inipay->SetField("mid", $_SESSION['INI_MID']); // 상점아이디
$inipay->SetField("rn", $_SESSION['INI_RN']); // 웹페이지 위변조용 RN값
$inipay->SetField("price", $_SESSION['INI_PRICE']); // 가격
$inipay->SetField("enctype", $_SESSION['INI_ENCTYPE']); // 고정 (절대 수정 불가)
$inipay->SetField("buyername", iconv_euckr($buyername)); // 구매자 명
$inipay->SetField("buyertel",  $buyertel); // 구매자 연락처(휴대폰 번호 또는 유선전화번호)
$inipay->SetField("buyeremail",$buyeremail); // 구매자 이메일 주소
$inipay->SetField("paymethod", $paymethod); // 지불방법 (절대 수정 불가)
$inipay->SetField("encrypted", $encrypted); // 암호문
$inipay->SetField("sessionkey",$sessionkey); // 암호문
$inipay->SetField("url", "http://".$_SERVER['HTTP_HOST']); // 실제 서비스되는 상점 SITE URL로 변경할것
$inipay->SetField("cardcode", $cardcode); // 카드코드 리턴
$inipay->SetField("parentemail", $parentemail); // 보호자 이메일 주소(핸드폰 , 전화결제시에 14세 미만의 고객이 결제하면  부모 이메일로 결제 내용통보 의무, 다른결제 수단 사용시에 삭제 가능)
$inipay->SetField("recvname", iconv_euckr($recvname)); // 수취인 명
$inipay->SetField("recvtel",$recvtel); // 수취인 연락처
$inipay->SetField("recvaddr",$recvaddr); // 수취인 주소
$inipay->SetField("recvpostnum",$recvpostnum); // 수취인 우편번호
$inipay->SetField("recvmsg", iconv_euckr($recvmsg)); // 전달 메세지

$inipay->SetField("joincard",$joincard); // 제휴카드코드
$inipay->SetField("joinexpire",$joinexpire); // 제휴카드유효기간
$inipay->SetField("id_customer",$id_customer); // user_id

/****************
 * 4. 지불 요청 *
 ****************/
$inipay->startAction();

/* ============================================================================== */
/* =  승인 결과 값 추출                                                         = */
/* = -------------------------------------------------------------------------- = */

/* = -------------------------------------------------------------------------- = */
/* =   신용카드 승인 결과 처리                                                  = */
/* = -------------------------------------------------------------------------- = */

if($inipay->GetResult('PayMethod') == "Card" || $inipay->GetResult('PayMethod') == "VCard"){
	$card_code		= $inipay->GetResult( "CARD_Code" ); // 카드사 코드
	$card_bankcode	= $inipay->GetResult( "CARD_BankCode" ); // 카드 종류
	$appldate		= $inipay->GetResult( "ApplDate" ).$inipay->GetResult( "ApplTime" ); // 승인 시간
	$applnum		= $inipay->GetResult( "ApplNum"  ); // 승인 번호
	$card_interest	= $inipay->GetResult( "CARD_Interest"); // 무이자 여부 ( 'Y' : 무이자 )
	$card_quota		= $inipay->GetResult( "CARD_Quota"); // 할부 개월 수
}

/* = -------------------------------------------------------------------------- = */
/* =   계좌이체 승인 결과 처리                                                  = */
/* = -------------------------------------------------------------------------- = */

if($inipay->GetResult('PayMethod') == "DirectBank" ){
	$appldate		= $inipay->GetResult( "ApplDate" ).$inipay->GetResult( "ApplTime" ); // 승인 시간
	$acct_bankcode	= $inipay->GetResult( "ACCT_BankCode"  );  // 은행코드
}

/* = -------------------------------------------------------------------------- = */
/* =   가상계좌 승인 결과 처리                                                  = */
/* = -------------------------------------------------------------------------- = */
if($inipay->GetResult('PayMethod') == "VBank" ){
	$vact_regnum	= $inipay->GetResult('VACT_RegNum'); //가상계좌 채번에 사용된 주민번호
	$vact_num		= $inipay->GetResult('VACT_Num'); //가상계좌 번호
	$vact_bankcode	= $inipay->GetResult('VACT_BankCode'); //입금할 은행 코드
	$vact_date		= $inipay->GetResult('VACT_Date'); //입금예정일
	$vact_inputname	= $inipay->GetResult('VACT_InputName'); //송금자 명
	$vact_name		= $inipay->GetResult('VACT_Name'); //예금주 명
}

/* = -------------------------------------------------------------------------- = */
/* =   핸드폰 결제 승인 결과 처리                                                  = */
/* = -------------------------------------------------------------------------- = */

if($inipay->GetResult('PayMethod') == "HPP" ){
	$hpp_num		= $inipay->GetResult('HPP_Num');
}

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

if($inipay->GetResult('ResultCode') == "00") {

	$escw_yn = 'N';
	if($default['cf_escrow_yn'] &&
		in_array($inipay->GetResult('PayMethod'), array('DirectBank','VBank')) &&
		in_array(get_session('ss_pay_method'), array('ER','ES'))) {
		$escw_yn = 'Y';
	}

	$cash = array();
	$cash['tpg']			= 'inicis';			// PG사
	$cash['tid']			= $inipay->GetResult('TID'); // 거래번호
	$cash['card_code']		= $card_code;		// 카드사 코드
	$cash['card_bankcode']	= $card_bankcode;	// 카드 종류
	$cash['appldate']		= $appldate;		// 승인 시간
	$cash['applnum']		= $applnum;			// 승인 번호
	$cash['acct_bankcode']	= $acct_bankcode;	// 은행코드
	$cash['vact_num']		= $vact_num;		// 가상계좌 번호
	$cash['vact_bankcode']	= $arr[$vact_bankcode];	// 입금할 은행 코드
	$cash['vact_date']		= $vact_date;		// 입금예정일
	$cash['vact_inputname']	= iconv_utf8($vact_inputname); // 송금자 명
	$cash['vact_name']		= iconv_utf8($vact_name); // 예금주 명
	$cash['hpp_num']		= $hpp_num;			// 휴대폰 번호
	$cash['escw_yn']		= $escw_yn;			// 에스크로 사용여부
	$cash['ss_pg_id']		= $ss_pg_id;		// 개별결제 검사 (가맹점 ID)
	$cash_info = serialize($cash);

	/* 결제성공시 */
    $odrkey = $inipay->GetResult('MOID'); //주문번호

	if($inipay->GetResult('PayMethod') == "Card" || $inipay->GetResult('PayMethod') == "VCard") {

		// 1. 신용카드
		$sql = "update shop_order
				   set cash_info	= '$cash_info',
					   dan			= '2',
					   incomedate	= '$server_time',
					   incomedate_s	= '$time_ymd'
				 where odrkey		= '$odrkey'";
		$msg = "결제가 완료 되었습니다.";

	} else if($inipay->GetResult('PayMethod') == "DirectBank") {

		// 2. 계좌이체
		$sql = "update shop_order
				   set cash_info	= '$cash_info',
					   dan			= '2',
					   incomedate	= '$server_time',
					   incomedate_s	= '$time_ymd'
				 where odrkey		= '$odrkey'";

		$msg = "결제가 완료 되었습니다.";

	} else if($inipay->GetResult('PayMethod') == "VBank") {

		// 3. 가상계좌
		$sql = "update shop_order
				   set vact_num		= '$vact_num',
					   cash_info	= '$cash_info',
					   dan			= '1'
				 where odrkey		= '$odrkey'";

		$msg = "가상계좌 발급이 완료 되었습니다.";

	} else if($inipay->GetResult('PayMethod') == "HPP") {

		// 2. 핸드폰 결제
		$sql = "update shop_order
				   set cash_info	= '$cash_info',
					   dan			= '2',
					   incomedate	= '$server_time',
					   incomedate_s	= '$time_ymd'
				 where odrkey		= '$odrkey'";

		$msg = "결제가 완료 되었습니다.";
	}

	$r = sql_query($sql, FALSE);

	sql_query("update shop_cart set ct_select='1' where odrkey='$odrkey'");

	if(!$r) { $bSucc = "false"; }

} else {
	//결제 실패시
	alert("결제에 실패하였습니다.(".iconv_utf8($inipay->GetResult('ResultMsg')).")");
}

/*******************************************************************
 * DB연동 실패 시 강제취소                                      *
 *                                                                 *
 * 지불 결과를 DB 등에 저장하거나 기타 작업을 수행하다가 실패하는  *
 * 경우, 아래의 코드를 참조하여 이미 지불된 거래를 취소하는 코드를 *
 * 작성합니다.                                                     *
 *******************************************************************/
if($inipay->GetResult('ResultCode') == "00") {
    if( $bSucc == "false" ) {
		$TID = $inipay->GetResult("TID");
		$inipay->SetField("type", "cancel"); // 고정
		$inipay->SetField("tid", $TID); // 고정
		$inipay->SetField("cancelmsg", "DB FAIL"); // 취소사유

		$inipay->startAction();
		if($inipay->GetResult('ResultCode') == "00") {
			$inipay->MakeTXErrMsg(MERCHANT_DB_ERR,"Merchant DB FAIL");
		}

		alert('결과 처리 오류로 인하여 자동 취소가 되었습니다.');

    } else {
		alert("$msg", TW_SHOP_URL."/orderinquiryview.php?odrkey=$odrkey");
    }
}
?>