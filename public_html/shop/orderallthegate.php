<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no']) {
    alert("결제할 주문서가 없습니다.");
}

$gw_head_title = '결제하기';
include_once("./_head.php");

if($default['cf_card_test_yn']) { // 테스트 결제시
	$StoreId = "aegis";
} else { // 실결제시
	$StoreId = $default['cf_ags_id'];
}
?>

<script language=javascript src="https://www.allthegate.com/plugin/AGSWallet_New.js"></script>
<!-- Euc-kr 이 아닌 다른 charset 을 이용할 경우에는 AGS_pay_ing(결제처리페이지) 상단의 
	[ AGS_pay.html 로 부터 넘겨받을 데이터파라미터 ] 선언부에서 파라미터 값들을 euc-kr로
	인코딩 변환을 해주시기 바랍니다.	-->
<script>
<!--
StartSmartUpdate();  

function Pay(form){
	if(form.Flag.value == "enable"){
		if(Check_Common(form) == true){
			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("플러그인 설치 후 다시 시도 하십시오.");
			} else {
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
					form.QuotaInf.value = "<?php echo $default[cf_ags_quota]; ?>";				
	
				if(form.DeviId.value == "9000400002")
					form.NointInf.value = "<?php echo $default[cf_ags_noint_mt]; ?>";
				   
				MakePayMessage(form);
			}
		}
	}
}

function Enable_Flag(form){
        form.Flag.value = "enable"
}

function Disable_Flag(form){
        form.Flag.value = "disable"
}

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("상점아이디를 입력하십시오.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("상점명을 입력하십시오.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("주문번호를 입력하십시오.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("상품명을 입력하십시오.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("금액을 입력하십시오.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("상점URL을 입력하십시오.");
		return false;
	}
	return true;
}
-->
</script>

<?php
$good_mny = get_session('total_amt'); // 결제금액
$ss_pay_method = get_session('ss_pay_method'); // 결제방법

$goodname = "";
$goods_count = -1;

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";
$result = sql_query($sql);

include_once($theme_path.'/orderallthegate.skin.php');

include_once("./_tail.php");
?>