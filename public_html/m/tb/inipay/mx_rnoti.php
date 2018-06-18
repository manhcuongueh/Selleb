<?php
include_once("./_common.php");

//*******************************************************************************
// FILE NAME : mx_rnoti.php
// FILE DESCRIPTION :
// 이니시스 smart phone 결제 결과 수신 페이지 샘플
// 기술문의 : ts@inicis.com
// HISTORY 
// 2010. 02. 25 최초작성 
// 2010  06. 23 WEB 방식의 가상계좌 사용시 가상계좌 채번 결과 무시 처리 추가(APP 방식은 해당 없음!!)
// WEB 방식일 경우 이미 P_NEXT_URL 에서 채번 결과를 전달 하였으므로, 
// 이니시스에서 전달하는 가상계좌 채번 결과 내용을 무시 하시기 바랍니다.
//*******************************************************************************

$PGIP = $_SERVER['REMOTE_ADDR'];

if($PGIP == "211.219.96.165" || $PGIP == "118.129.210.25")	//PG에서 보냈는지 IP로 체크
{
	// 이니시스 NOTI 서버에서 받은 Value
	$P_TID;				// 거래번호
	$P_MID;				// 상점아이디
	$P_AUTH_DT;			// 승인일자
	$P_STATUS;			// 거래상태 (00:성공, 01:실패)
	$P_TYPE;			// 지불수단
	$P_OID;				// 상점주문번호
	$P_FN_CD1;			// 금융사코드1
	$P_FN_CD2;			// 금융사코드2
	$P_FN_NM;			// 금융사명 (은행명, 카드사명, 이통사명)
	$P_AMT;				// 거래금액
	$P_UNAME;			// 결제고객성명
	$P_RMESG1;			// 결과코드
	$P_RMESG2;			// 결과메시지
	$P_NOTI;			// 노티메시지(상점에서 올린 메시지)
	$P_AUTH_NO;			// 승인번호

	$P_TID = $_REQUEST[P_TID];
	$P_MID = $_REQUEST[P_MID];
	$P_AUTH_DT = $_REQUEST[P_AUTH_DT];
	$P_STATUS = $_REQUEST[P_STATUS];
	$P_TYPE = $_REQUEST[P_TYPE];
	$P_OID = $_REQUEST[P_OID];
	$P_FN_CD1 = $_REQUEST[P_FN_CD1];
	$P_FN_CD2 = $_REQUEST[P_FN_CD2];
	$P_FN_NM = iconv_utf8($_REQUEST[P_FN_NM]);
	$P_AMT = $_REQUEST[P_AMT];
	$P_UNAME = iconv_utf8($_REQUEST[P_UNAME]);
	$P_RMESG1 = iconv_utf8($_REQUEST[P_RMESG1]);
	$P_RMESG2 = iconv_utf8($_REQUEST[P_RMESG2]);
	$P_NOTI = iconv_utf8($_REQUEST[P_NOTI]);
	$P_AUTH_NO = $_REQUEST[P_AUTH_NO];

	$bSucc = true;	

	$escw_yn = 'N';
	if($default['cf_escrow_yn'] && in_array($P_TYPE, array('VBANK')) && in_array(get_session('ss_pay_method'), array('ER','ES'))) {
		$escw_yn = 'Y';
	}

	// WEB 방식의 경우 가상계좌 채번 결과 무시 처리
	// APP 방식의 경우 해당 내용을 삭제 또는 주석 처리 하시기 바랍니다.
	if($P_TYPE == "VBANK")	// 결제수단이 가상계좌이며
	{
		if($P_STATUS == "02") // 입금통보 "02" 가 아니면(가상계좌 채번 : 00 또는 01 경우)
		{
			$ca = sql_fetch("select count(*) as cnt from shop_order where odrkey = '$P_OID'");
			$od = sql_fetch("select odrkey from shop_order where odrkey = '$P_OID'");

			if($ca['cnt'] == 1) {
				$sql = " update shop_order 
							set dan = '1',
								incomedate = '$server_time',
								incomedate_s = '$time_ymd' 
						  where odrkey = '$P_OID' ";
				$r = sql_query($sql);		

				$sql = " update shop_cart set ct_select='1' where odrkey='$P_OID' ";
				sql_query($sql);
			}

			if(!$r) { $bSucc = false; }			
		
		} else {
			echo "OK";
			return;
		}
	} 
	else {

		if($P_STATUS == "00")
		{		
			$cash = array();
			$cash['tpg']			= 'inicis';		// PG사
			$cash['tid']			= $P_TID;		// 거래번호
			$cash['mid']			= $P_MID;		// 상점아이디
			$cash['resultcode']		= $P_STATUS;	// 거래상태
			$cash['moid']			= $P_OID;		// 상점주문번호
			$cash['resultmsg']		= '결과코드:'.$P_RMESG1.' 결과메시지:'.$P_RMESG2;	// 결과메시지
			$cash['paymethod']		= $P_TYPE;		// 지불수단
			$cash['resultprice']	= $P_AMT;		// 승인금액

			$cash['card_code']		= $P_FN_NM;		// 금융사명 (은행명, 카드사명, 이통사명)
			$cash['card_purchase']	= $P_FN_CD1;	// 금융사코드1
			$cash['card_bankcode']	= $P_FN_CD2;	// 금융사코드2
			$cash['appldate']		= $P_AUTH_DT;	// 승인일자
			$cash['applnum']		= $P_AUTH_NO;	// 승인번호

			$cash['escw_yn']		= $escw_yn;		// 에스크로 사용여부
			$cash['ss_pg_id']		= $ss_pg_id;	// 개별결제 검사 (가맹점 ID)
			$cash_info = serialize($cash);

			$sql = "update shop_order 
					   set cash_info = '$cash_info',
						   dan = '2',
						   incomedate = '$server_time',
						   incomedate_s	= '$time_ymd' 
					 where odrkey = '$P_OID'";
			$r = sql_query($sql, FALSE);

			sql_query("update shop_cart set ct_select='1' where odrkey='$P_OID'");

			if(!$r) { $bSucc = false; }
		}
	}

	$PageCall_time = date("H:i:s");

	$value = array(
			"PageCall time" => $PageCall_time,
			"P_TID"			=> $P_TID,  
			"P_MID"     => $P_MID,  
			"P_AUTH_DT" => $P_AUTH_DT,      
			"P_STATUS"  => $P_STATUS,
			"P_TYPE"    => $P_TYPE,     
			"P_OID"     => $P_OID,  
			"P_FN_CD1"  => $P_FN_CD1,
			"P_FN_CD2"  => $P_FN_CD2,
			"P_FN_NM"   => $P_FN_NM,  
			"P_AMT"     => $P_AMT,  
			"P_UNAME"   => $P_UNAME,  
			"P_RMESG1"  => $P_RMESG1,  
			"P_RMESG2"  => $P_RMESG2,
			"P_NOTI"    => $P_NOTI,  
			"P_AUTH_NO" => $P_AUTH_NO
			);


		// 결제처리에 관한 로그 기록
	writeLog($value);

	/***********************************************************************************
	 ' 위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 "OK"를 이니시스로 실패시는 "FAIL" 을
	 ' 리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
	 ' (주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 "OK"를 수신할때까지 계속 재전송을 시도합니다
	 ' 기타 다른 형태의 echo "" 는 하지 않으시기 바랍니다
	'***********************************************************************************/

	//절대로 지우지 마세요
	if($bSucc == true) {
		echo "OK"; 
	} else {
		echo "FAIL";
	}
}

function writeLog($msg)
{
    $file = M_PATH_INI."/noti_input_".date("Ymd").".log";

    if(!($fp = fopen($path.$file, "a+"))) return 0;
                
    ob_start();
    print_r($msg);
    $ob_msg = ob_get_contents();
    ob_clean();
		
    if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
    {
        fclose($fp);
        return 0;
    }
    fclose($fp);
    return 1;
}
?>