<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no'])
    alert("결제할 주문서가 없습니다.");

$tb['title'] = $arr_mhd[$od['buymethod']]." 결제하기";
include_once("./_head.php");

$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$Amt = get_session('total_amt'); // 결제금액

include(M_PATH_KAKAOPAY.'/incKakaopayCommon.php');
include(M_PATH_KAKAOPAY.'/lgcns_CNSpay.php');

$ediDate = date("YmdHis");  // 전문생성일시

////////위변조 처리/////////
//결제요청용 키값
$cnspay_lib = new CnsPayWebConnector($LogDir);
$md_src = $ediDate.$MID.$Amt;
$salt = hash("sha256",$merchantKey.$md_src,false);
$hash_input = $cnspay_lib->makeHashInputString($salt);
$hash_calc = hash("sha256", $hash_input, false);
$hash_String = base64_encode($hash_calc);

//기본값
$AuthFlg = "10";
$currency = "KRW";
$remoteaddr = $_SERVER['REMOTE_ADDR'];
$serveraddr = $_SERVER['SERVER_ADDR'];
$ediDate = $ediDate;
?>

<!-- OpenSource Library -->
<script src="<?php echo ($CnsPayDealRequestUrl) ?>/dlp/scripts/lib/easyXDM.min.js" type="text/javascript"></script>
<script src="<?php echo ($CnsPayDealRequestUrl) ?>/dlp/scripts/lib/json3.min.js" type="text/javascript"></script>

<!-- DLP창에 대한 KaKaoPay Library -->
<script type="text/javascript" src="<?php echo ($CNSPAY_WEB_SERVER_URL) ?>/js/dlp/client/kakaopayDlpConf.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ($CNSPAY_WEB_SERVER_URL) ?>/js/dlp/client/kakaopayDlp.min.js" charset="utf-8"></script>

<link href="https://pg.cnspay.co.kr:443/dlp/css/kakaopayDlp.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	/**
	cnspay 를 통해 결제를 시작합니다.
	*/
	function cnspay() {
		// TO-DO : 가맹점에서 해줘야할 부분(TXN_ID)과 KaKaoPay DLP 호출 API
		// 결과코드가 00(정상처리되었습니다.)
		if(document.payForm.resultCode.value == '00') {
			// TO-DO : 가맹점에서 해줘야할 부분(TXN_ID)과 KaKaoPay DLP 호출 API
		    kakaopayDlp.setTxnId(document.payForm.txnId.value);

			//kakaopayDlp.setChannelType('WPM', 'TMS'); // PC결제
			kakaopayDlp.setChannelType('MPM', 'WEB'); // 모바일 웹(브라우저)결제
			kakaopayDlp.addRequestParams({ MOBILE_NUM : '<?php echo $od[cellphone]; ?>'}); // 초기값 세팅

			kakaopayDlp.callDlp('kakaopay_layer', document.payForm, submitFunc);
		} else {
			alert('[RESULT_CODE] : ' + document.payForm.resultCode.value + '\n[RESULT_MSG] : ' + document.payForm.resultMsg.value);
		}
	}

	function getTxnId() {
		// form에 iframe 주소 세팅
		document.payForm.target = "txnIdGetterFrame";
		document.payForm.action = "./kakaopay/getTxnId.php";
		document.payForm.acceptCharset = "utf-8";
	  if(document.payForm.canHaveHTML) { // detect IE
	      document.charset = payForm.acceptCharset;
	  }

		// post로 iframe 페이지 호출
		document.payForm.submit();
		// payForm의 타겟, action을 수정한다

		document.payForm.target = "";
		document.payForm.action = "./kakaopay/kakaopayLiteResult.php";
		document.payForm.acceptCharset = "utf-8";
		if(document.payForm.canHaveHTML) { // detect IE
			document.charset = payForm.acceptCharset;
		}

		// getTxnId.jsp의 onload 이벤트를 통해 cnspay() 호출

	}

	var submitFunc = function cnspaySubmit(data){

        if(data.RESULT_CODE === '00') {

            // 부인방지토큰은 기본적으로 name="NON_REP_TOKEN"인 input박스에 들어가게 되며, 아래와 같은 방법으로 꺼내서 쓸 수도 있다.
            // 해당값은 가군인증을 위해 돌려주는 값으로서, 가맹점과 카카오페이 양측에서 저장하고 있어야 한다.
            // var temp = data.NON_REP_TOKEN;

            document.payForm.submit();

	        } else if(data.RESLUT_CODE === 'KKP_SER_002') {
        	    // X버튼 눌렀을때의 이벤트 처리 코드 등록
	        alert('[RESULT_CODE] : ' + data.RESULT_CODE + '\n[RESULT_MSG] : ' + data.RESULT_MSG);
	        } else {
        	alert('[RESULT_CODE] : ' + data.RESULT_CODE + '\n[RESULT_MSG] : ' + data.RESULT_MSG);
	        }
	};
</script>

<?php
// 총금액 뽑기
$sql = " select SUM(account) as it_amt,
				SUM(del_account) as de_amt,
				SUM(dc_exp_amt) as dc_amt,
				SUM(use_point) as po_amt,
				SUM(use_account) as buy_amt
		   from shop_order
		  where mb_no='$mb_no' 
		    and odrkey='$odrkey' ";
$tot_sum = sql_fetch($sql);

$goodname = "";
$goods_count = -1;
$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";
$result = sql_query($sql);

include_once($theme_path."/orderkakaopay.skin.php");
include_once("./_tail.php");
?>