<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no'])
    alert("결제할 주문서가 없습니다.");

$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$good_mny = get_session('total_amt'); // 결제금액

// 지불방법
switch($ss_pay_method) {
	case 'C' : // 신용카드
		$paymethod = "cardnormal";
		break;
	case 'ER' : // 계좌이체 (에스크로)
	case 'R'  : // 계좌이체
		$paymethod = "";
		break;
	case 'H' : // 휴대폰
		$paymethod = "hp";
		break;
	case 'ES' : // 가상계좌 (에스크로)
		$paymethod = "virtualescrow";
		break;
	case 'S'  : // 가상계좌
		$paymethod = "virtualnormal";
		break;
}

if(!$paymethod) {
	alert("계좌이체 결제는 하실 수 없습니다.");
}

$tb['title'] = $arr_mhd[$ss_pay_method]." 결제하기";
include_once("./_head.php");

if($default['cf_card_test_yn']) { // 테스트 결제시
    $StoreId = "aegis";
} else { // 실결제시
    $StoreId = $default['cf_ags_id'];
}

set_session('ss_store_id', $StoreId);

// 올더게이트
$strAegis = "https://www.allthegate.com";
$strCsrf = "csrf.real.js";

$RtnUrl = M_HTTP ."/ags/AGSMobile_approve.php";
$CancelUrl = M_HTTP ."/ags/AGSMobile_user_cancel.php";
?>

<script type="text/javascript" charset="euc-kr" src="<?php echo $strAegis; ?>/payment/mobilev2/csrf/<?php echo $strCsrf; ?>"></script> 

<script type="text/javascript" charset="euc-kr">
function doPay(form) {
	<?php if(!$default['cf_ags_noint_yn']) { ?>
	form.DeviId.value = "9000400001";
	<?php } else { ?>
	if(parseInt(form.Amt.value) < 50000)
		form.DeviId.value = "9000400001";
	else
		form.DeviId.value = "9000400002";
	<?php } ?>	

	//결제금액이 5만원 미만건을 할부결제로 요청할경우 결제실패
	if(parseInt(form.Amt.value) < 50000)
		form.QuotaInf.value = "0";
	else
		form.QuotaInf.value = "<?php echo $default[cf_ags_quota];?>";

	if(form.DeviId.value == "9000400002") {
		form.NointInf.value = "<?php echo $default[cf_ags_noint_mt];?>";
	}	
		
	AllTheGate.pay(document.form);
	return false;
}
</script>

<?php
$goodname = "";
$goods_count = -1;

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";
$result = sql_query($sql);

include_once($theme_path."/orderallthegate.skin.php");
include_once("./_tail.php");
?>