<?php
define('_PURENESS_', true);
include_once('./_common.php');

/*------------------------------------------------------------------------------
    ※ KCP 에서 가맹점의 결과처리 페이지로 데이터를 전송할 때에, 아래와 같은
       IP 에서 전송을 합니다. 따라서 가맹점측께서 전송받는 데이터에 대해 KCP
       에서 전송된 건이 맞는지 체크하는 부분을 구현할 때에, 아래의 IP 에 대해
       REMOTE ADDRESS 체크를 하여, 아래의 IP 이외의 다른 경로를 통해서 전송된
       데이터에 대해서는 결과처리를 하지 마시기 바랍니다.
------------------------------------------------------------------------------*/
switch ($_SERVER['REMOTE_ADDR']) {
	case '203.238.36.58' :
	case '203.238.36.160' :
	case '203.238.36.161' :
	case '203.238.36.173' :
	case '203.238.36.178' :
		break;
	default :
		exit;
}

/* ============================================================================== */
/* =   01. 공통 통보 페이지 설명(필독!!)                                        = */
/* = -------------------------------------------------------------------------- = */
/* =   공통 통보 페이지에서는,                                                  = */
/* =   가상계좌 입금 통보 데이터와 모바일안심결제 통보 데이터 등을 KCP를 통해   = */
/* =   실시간으로 통보 받을 수 있습니다.                                        = */
/* =                                                                            = */
/* =   common_return 페이지는 이러한 통보 데이터를 받기 위한 샘플 페이지        = */
/* =   입니다. 현재의 페이지를 업체에 맞게 수정하신 후, 아래 사항을 참고하셔서  = */
/* =   KCP 관리자 페이지에 등록해 주시기 바랍니다.                              = */
/* =                                                                            = */
/* =   등록 방법은 다음과 같습니다.                                             = */
/* =  - KCP 관리자페이지(admin.kcp.co.kr)에 로그인 합니다.                      = */
/* =  - [쇼핑몰 관리] -> [정보변경] -> [공통 URL 정보] -> [공통 URL 변경 후]에  = */
/* =    결과값은 전송받을 가맹점 URL을 입력합니다.                              = */
/* ============================================================================== */


/* ============================================================================== */
/* =   02. 공통 통보 데이터 받기                                                = */
/* = -------------------------------------------------------------------------- = */
$site_cd      = $_POST [ "site_cd"  ];                 // 사이트 코드
$tno          = $_POST [ "tno"      ];                 // KCP 거래번호
$order_no     = $_POST [ "order_no" ];                 // 주문번호
$tx_cd        = $_POST [ "tx_cd"    ];                 // 업무처리 구분 코드
$tx_tm        = $_POST [ "tx_tm"    ];                 // 업무처리 완료 시간
/* = -------------------------------------------------------------------------- = */
$ipgm_name    = "";                                    // 주문자명
$remitter     = "";                                    // 입금자명
$ipgm_mnyx    = "";                                    // 입금 금액
$bank_code    = "";                                    // 은행코드
$account      = "";                                    // 가상계좌 입금계좌번호
$op_cd        = "";                                    // 처리구분 코드
$noti_id      = "";                                    // 통보 아이디
$cash_a_no    = "";                                    // 현금영수증 승인번호
/* = -------------------------------------------------------------------------- = */

/* = -------------------------------------------------------------------------- = */
/* =   02-1. 가상계좌 입금 통보 데이터 받기                                     = */
/* = -------------------------------------------------------------------------- = */
if ( $tx_cd == "TX00" )
{
	$ipgm_name = $_POST[ "ipgm_name" ];                // 주문자명
	$remitter  = $_POST[ "remitter"  ];                // 입금자명
	$ipgm_mnyx = $_POST[ "ipgm_mnyx" ];                // 입금 금액
	$bank_code = $_POST[ "bank_code" ];                // 은행코드
	$account   = $_POST[ "account"   ];                // 가상계좌 입금계좌번호
	$op_cd     = $_POST[ "op_cd"     ];                // 처리구분 코드
	$noti_id   = $_POST[ "noti_id"   ];                // 통보 아이디
	$cash_a_no = $_POST[ "cash_a_no" ];                // 현금영수증 승인번호
}

/* = -------------------------------------------------------------------------- = */
/* =   02-2. 모바일안심결제 통보 데이터 받기                                    = */
/* = -------------------------------------------------------------------------- = */
else if ( $tx_cd == "TX08" )
{
	$ipgm_mnyx = $_POST[ "ipgm_mnyx" ];                // 입금 금액
	$bank_code = $_POST[ "bank_code" ];                // 은행코드
}
/* ============================================================================== */


/* ============================================================================== */
/* =   03. 공통 통보 결과를 업체 자체적으로 DB 처리 작업하시는 부분입니다.      = */
/* = -------------------------------------------------------------------------- = */
/* =   통보 결과를 DB 작업 하는 과정에서 정상적으로 통보된 건에 대해 DB 작업에  = */
/* =   실패하여 DB update 가 완료되지 않은 경우, 결과를 재통보 받을 수 있는     = */
/* =   프로세스가 구성되어 있습니다.                                            = */
/* =                                                                            = */
/* =   * DB update가 정상적으로 완료된 경우                                     = */
/* =   하단의 [04. result 값 세팅 하기] 에서 result 값의 value값을 0000으로     = */
/* =   설정해 주시기 바랍니다.                                                  = */
/* =                                                                            = */
/* =   * DB update가 실패한 경우                                                = */
/* =   하단의 [04. result 값 세팅 하기] 에서 result 값의 value값을 0000이외의   = */
/* =   값으로 설정해 주시기 바랍니다.                                           = */
/* = -------------------------------------------------------------------------- = */

/* = -------------------------------------------------------------------------- = */
/* =   03-1. 가상계좌 입금 통보 데이터 DB 처리 작업 부분                        = */
/* = -------------------------------------------------------------------------- = */
if ( $tx_cd == "TX00" )
{
	$od = sql_fetch("select * from shop_order where odrkey = '$order_no'");
	
	if ($od['odrkey']) {
		$sql = "update shop_order 
				   set dan			= '2',
					   incomedate	= '$server_time',
					   incomedate_s	= '$time_ymd' 
				 where odrkey		= '$order_no'";
		sql_query($sql, FALSE);		

		$sql = "update shop_cart set ct_select='1' where odrkey='$order_no'";
		sql_query($sql, FALSE);	
	}
}

/* = -------------------------------------------------------------------------- = */
/* =   03-2. 모바일안심결제 통보 데이터 DB 처리 작업 부분                       = */
/* = -------------------------------------------------------------------------- = */
else if ( $tx_cd == "TX08" )
{
	$od = sql_fetch("select * from shop_order where odrkey = '$order_no'");
	
	if ($od['odrkey']) {
		$sql = "update shop_order 
				   set dan			= '2',
					   incomedate	= '$server_time',
					   incomedate_s	= '$time_ymd' 
				 where odrkey		= '$order_no'";
		sql_query($sql, FALSE);		

		$sql = "update shop_cart set ct_select='1' where odrkey='$order_no'";
		sql_query($sql, FALSE);	
	}
}
/* ============================================================================== */


/* ============================================================================== */
/* =   04. result 값 세팅 하기                                                  = */
/* ============================================================================== */
?>
<html><body><form><input type="hidden" name="result" value="0000"></form></body></html>
