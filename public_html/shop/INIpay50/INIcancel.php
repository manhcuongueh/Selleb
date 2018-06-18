<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if($cash['tpg'] != 'inicis') return;

// 테스트 결제시
if ($default['cf_card_test_yn']) {
	if ($cash['escw_yn'] == 'Y') {
		// 에스크로결제 테스트
		$mid = "iniescrow0";
	}
	else {
		// 일반결제 테스트
		$mid = "INIpayTest";
	}
} 
// 실결제시
else {
	if ($cash['escw_yn'] == 'Y') {
		// 에스크로결제 테스트
		$mid = $default['cf_inicis_escrow_id'];
	}
	else {
		// 일반결제 테스트
		$mid = $default['cf_inicis_id'];
	}
}

$tid = trim($cash['tid']);
$cancelmsg = $ca_cancel.'-'.$ca_memo;

/**************************
 * 1. 라이브러리 인클루드 *
 **************************/
require(ROOT_INICIS . "/libs/INILib.php");

/***************************************
 * 2. INIpay41 클래스의 인스턴스 생성 *
 ***************************************/
$inipay = new INIpay50;

/*********************
 * 3. 취소 정보 설정 *
 *********************/
$inipay->SetField("inipayhome", ROOT_INICIS);	// 이니페이 홈디렉터리(상점수정 필요)
$inipay->SetField("type", "cancel");			// 고정 (절대 수정 불가)
$inipay->SetField("debug", "true");				// 로그모드("true"로 설정하면 상세로그가 생성됨.)
$inipay->SetField("mid", $mid);					// 상점아이디

/**************************************************************************************************
 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
 **************************************************************************************************/  	

$inipay->SetField("admin", "1111");				// 비대칭 사용키 키패스워드
$inipay->SetField("tid", $tid);					// 취소할 거래의 거래아이디
$inipay->SetField("cancelmsg", $cancelmsg);	    // 취소사유

/****************
 * 4. 취소 요청 *
 ****************/
$inipay->startAction();

$canceldate = $inipay->getResult('CancelDate');
$canceldate = substr($canceldate,0,4)."-".substr($canceldate,4,2)."-".substr($canceldate,6,2);

$canceltime = $inipay->getResult('CancelTime');
$canceltime = substr($canceltime,0,2).":".substr($canceltime,2,2).":".substr($canceltime,4,2);

$ResultMsg = iconv_utf8($inipay->getResult('ResultMsg'));
$CSHR_CancelNum = $inipay->getResult('CSHR_CancelNum');

$ca_logs  = "";
if ($inipay->getResult('ResultCode') != '00') {
	$ca_logs .= "결과코드 : 실패";
	$ca_logs .= ", 결과메시지 : " . $ResultMsg;
} else {
	$ca_logs .= "결과코드 : 성공";
	$ca_logs .= ", 결과메시지 : " . $ResultMsg;
	$ca_logs .= ", 취소거래시간 : " . $canceldate . " ". $canceltime;
	
	if ($CSHR_CancelNum) {
		$ca_logs .= "현금영수증 취소 승인번호 : " . $CSHR_CancelNum;
	}
}

set_session('ss_pay_method', '');
/****************************************************************
 * 5. 취소 결과                                           	*
 *                                                        	*
 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
 * (현금영수증 발급 취소시에만 리턴됨)                          * 
 ****************************************************************/
?>