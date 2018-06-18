<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if($cash['tpg'] != 'kakaopay') return;

include_once(ROOT_KAKAOPAY.'/incKakaopayCommon.php');
include_once(ROOT_KAKAOPAY.'/lgcns_CNSpay.php');

$mod_mny = (int)$ca_mod_mny;
if($od['taxflag'] && $od['gs_notax'])
{
	$mod_tax_mny = round($mod_mny / 1.1);
	$mod_vat_mny = $mod_mny - $mod_tax_mny;
}

$_REQUEST['MID']		= $MID;
$_REQUEST['TID']	    = $cash['tid'];
$_REQUEST['CancelAmt']  = $mod_mny;
$_REQUEST['SupplyAmt']  = (int)$mod_tax_mny;
$_REQUEST['GoodsVat']   = (int)$mod_vat_mny;
$_REQUEST['ServiceAmt'] = 0;
$_REQUEST['CancelMsg']  = $ca_cancel.'-'.$ca_memo;
if ($ca_type == '부분취소') {
	$_REQUEST['PartialCancelCode'] = 1; // 부분 취소
	$CancelNo = get_next_wr_num('shop_order', 'casseqno', " odrkey = '$od[odrkey]' ");
	$_REQUEST['CancelNo'] = $CancelNo;
} else {
	$_REQUEST['PartialCancelCode'] = 0; // 전체 취소
}

$_REQUEST['CheckRemainAmt']	= (int)$ca_rem_mny - $mod_mny;


// 로그 저장 위치 지정
$connector = new CnsPayWebConnector($LogDir);
$connector->CnsActionUrl($CnsPayDealRequestUrl);
$connector->CnsPayVersion($phpVersion);
$connector->setRequestData($_REQUEST);
$connector->addRequestData("actionType", "CL0");
$connector->addRequestData("CancelPwd", $cancelPwd);
$connector->addRequestData("CancelIP", $_SERVER['REMOTE_ADDR']);

//가맹점키 셋팅 (MID 별로 틀림) 
$connector->addRequestData("EncodeKey", $merchantKey);

// 4. CNSPAY Lite 서버 접속하여 처리
$connector->requestAction();

// 5. 결과 처리
$resultCode = $connector->getResultData("ResultCode"); // 결과코드 (정상 :2001(취소성공), 그 외 에러)
$resultMsg = $connector->getResultData("ResultMsg"); // 결과메시지
$cancelAmt = $connector->getResultData("CancelAmt"); // 취소금액
$cancelDate = $connector->getResultData("CancelDate"); // 취소일
$cancelTime = $connector->getResultData("CancelTime"); // 취소시간
$CancelNum = $connector->getResultData("CancelNum"); // 취소번호
$payMethod = $connector->getResultData("PayMethod"); // 취소 결제수단
$mid = 	$connector->getResultData("MID"); // MID
$tid = $connector->getResultData("TID"); // TID
$errorCD = $connector->getResultData("ErrorCD"); // 상세 에러코드
$errorMsg = $connector->getResultData("ErrorMsg"); // 상세 에러메시지
$authDate = $cancelDate . $cancelTime; // 취소거래시간
$authDate = $connector->makeDateString($authDate);
$stateCD = $connector->getResultData("StateCD"); // 거래상태코드 (0: 승인, 1:전취소, 2:후취소)
$ccPartCl = $connector->getResultData("CcPartCl"); // 부분취소 가능여부 (0:부분취소불가, 1:부분취소가능)
$PreCancelCode = $connector->getResultData("PreCancelCode"); // 부분취소 가능여부 (0:부분취소불가, 1:부분취소가능)
$errorMsg  = iconv("euc-kr", "utf-8", $errorMsg);
$resultMsg = iconv("euc-kr", "utf-8", $resultMsg);

$ca_logs = "";
if($resultCode == "2001" || $resultCode == "2002") {

    $sql = " update shop_order
                set casseqno = '$CancelNo'
              where index_no = '$index_no' ";
    sql_query($sql);

	$ca_logs .= "결과코드 : 성공";
	$ca_logs .= ", 결과메시지 : " . $resultMsg;
	$ca_logs .= ", 취소거래시간 : " . $authDate;
	$ca_logs .= ", 취소번호 : " . $CancelNum;

} else {
	$ca_logs .= "결과코드 : 실패";
	$ca_logs .= ", 결과메시지 : " . $resultMsg;
}

set_session('ss_pay_method', '');
?>