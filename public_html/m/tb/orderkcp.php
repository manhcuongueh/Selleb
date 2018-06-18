<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no'])
    alert("결제할 주문서가 없습니다.");

$tb['title'] = $arr_mhd[$od['buymethod']]." 결제하기";
$tb['body_script'] = ' onload="chk_pay();jsf__chk_type();"';
$tb['kcp_header'] = '<div id="layer_cont">';
$tb['kcp_footer'] = '</div>';
include_once("./_head.php");

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

include M_PATH_KCP."/settle_kcp.inc.php";

/* kcp와 통신후 kcp 서버에서 전송되는 결제 요청 정보 */
$req_tx          = $_POST["req_tx"]; // 요청 종류
$res_cd          = $_POST["res_cd"]; // 응답 코드
$tran_cd         = $_POST["tran_cd"]; // 트랜잭션 코드
$ordr_idxx       = $_POST["ordr_idxx"]; // 쇼핑몰 주문번호
$good_name       = $_POST["good_name"]; // 상품명
$good_mny        = $_POST["good_mny"]; // 결제 총금액
$buyr_name       = $_POST["buyr_name"]; // 주문자명
$buyr_tel1       = $_POST["buyr_tel1"]; // 주문자 전화번호
$buyr_tel2       = $_POST["buyr_tel2"]; // 주문자 핸드폰 번호
$buyr_mail       = $_POST["buyr_mail"]; // 주문자 E-mail 주소
$use_pay_method  = $_POST["use_pay_method"] ; // 결제 방법
$ipgm_date       = $_POST["ipgm_date"]; // 가상계좌 마감시간
$enc_info        = $_POST["enc_info"]; // 암호화 정보
$enc_data        = $_POST["enc_data"]; // 암호화 데이터
$van_code        = $_POST["van_code"];
$cash_yn         = $_POST["cash_yn"];
$cash_tr_code    = $_POST["cash_tr_code"];
$param_opt_1     = $_POST["param_opt_1"]; // 기타 파라메터 추가 부분
$param_opt_2     = $_POST["param_opt_2"]; // 기타 파라메터 추가 부분
$param_opt_3     = $_POST["param_opt_3"]; // 기타 파라메터 추가 부분

// 결제등록 요청시 사용할 입금마감일
$ipgm_date = date("Ymd", ($server_time + 86400 * 5));
$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)
$ret_url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$goodname  = "";
$good_info = "";
$good_mny  = get_session('total_amt'); // 결제금액
$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$goods_count = -1;

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";
$result = sql_query($sql);
?>
<!-- 거래등록 하는 kcp 서버와 통신을 위한 스크립트 -->
<script src="<?php echo $tb['bbs_root']; ?>/kcp/approval_key.js"></script>

<script>
/* kcp web 결제창 호츨 (변경불가) */
function call_pay_form()
{
	var v_frm = document.order_info;

    document.getElementById("layer_cont").style.display = "none";
    document.getElementById("layer_all").style.display  = "block";

	v_frm.target = "frm_all";
	v_frm.action = PayUrl;

	if(v_frm.Ret_URL.value == "")
	{
		/* Ret_URL값은 현 페이지의 URL 입니다. */
		alert("연동시 Ret_URL을 반드시 설정하셔야 됩니다.");
		return false;
	}
	else
	{
		v_frm.submit();
	}
}

/* kcp 통신을 통해 받은 암호화 정보 체크 후 결제 요청 (변경불가) */
function chk_pay()
{
	self.name = "tar_opener";
	var pay_form = document.pay_form;

	if(pay_form.res_cd.value == "3001" )
	{
		alert("사용자가 취소하였습니다.");
		pay_form.res_cd.value = "";
	}
	else if(pay_form.res_cd.value == "3000" )
	{
		alert("30만원 이상 결제를 할 수 없습니다.");
		pay_form.res_cd.value = "";
	}

	document.getElementById("layer_cont").style.display  = "block";
	document.getElementById("layer_all").style.display  = "none";

	if(pay_form.enc_info.value)
		pay_form.submit();
}

function jsf__chk_type()
{
	var ActionResult = "<?php echo $ss_pay_method; ?>";

	// 신용카드
	if( ActionResult == "C" )
	{
		document.order_info.ActionResult.value = "card";
		document.order_info.pay_method.value = "CARD";
		document.pay_form.use_pay_method.value = "100000000000";
	}

	// 실시간계좌이체
	else if( ActionResult == "R" || ActionResult == "ER" )
	{
		document.order_info.ActionResult.value = "acnt";
		document.order_info.pay_method.value = "BANK";
		document.pay_form.use_pay_method.value = "010000000000";
	}

	// 가상계좌
	else if( ActionResult == "S" || ActionResult == "ES" )
	{
		document.order_info.ActionResult.value = "vcnt";
		document.order_info.pay_method.value = "VCNT";
		document.pay_form.use_pay_method.value = "001000000000";
	}

	// 휴대폰결제
	else if( ActionResult == "H" )
	{
		document.order_info.ActionResult.value = "mobx";
		document.order_info.pay_method.value = "MOBX";
		document.pay_form.use_pay_method.value = "000010000000";
	}
}
</script>

<?php
include_once($theme_path."/orderkcp.skin.php");
include_once("./_tail.php");
?>

<!-- 스마트폰에서 KCP 결제창을 레이어 형태로 구현-->
<div id="layer_all" style="position:absolute;left:0px;top:0px;width:100%;height:100%;z-index:1;display:none;">
	<table height="100%" width="100%" border="-" cellspacing="0" cellpadding="0" style="text-align:center">
	<tr height="100%" width="100%">
		<td>
			<iframe name="frm_all" frameborder="0" marginheight="0" marginwidth="0" border="0" width="100%" height="100%" scrolling="auto"></iframe>
		</td>
	</tr>
	</table>
</div>

<form name="pay_form" method="post" action="./kcp/pp_ax_hub.php">
<input type="hidden" name="req_tx"         value="<?php echo $req_tx; ?>">		<!-- 요청 구분          -->
<input type="hidden" name="res_cd"         value="<?php echo $res_cd; ?>">		<!-- 결과 코드          -->
<input type="hidden" name="tran_cd"        value="<?php echo $tran_cd; ?>">		<!-- 트랜잭션 코드      -->
<input type="hidden" name="ordr_idxx"      value="<?php echo $ordr_idxx; ?>">	<!-- 주문번호           -->
<input type="hidden" name="good_mny"       value="<?php echo $good_mny; ?>">	<!-- 휴대폰 결제금액    -->
<input type="hidden" name="good_name"      value="<?php echo $good_name; ?>">	<!-- 상품명             -->
<input type="hidden" name="buyr_name"      value="<?php echo $buyr_name; ?>">	<!-- 주문자명           -->
<input type="hidden" name="buyr_tel1"      value="<?php echo $buyr_tel1; ?>">	<!-- 주문자 전화번호    -->
<input type="hidden" name="buyr_tel2"      value="<?php echo $buyr_tel2; ?>">	<!-- 주문자 휴대폰번호  -->
<input type="hidden" name="buyr_mail"      value="<?php echo $buyr_mail; ?>">	<!-- 주문자 E-mail      -->
<input type="hidden" name="cash_yn"		   value="<?php echo $cash_yn; ?>">		<!-- 현금영수증 등록여부-->
<input type="hidden" name="enc_info"       value="<?php echo $enc_info; ?>">
<input type="hidden" name="enc_data"       value="<?php echo $enc_data; ?>">
<input type="hidden" name="use_pay_method" value="<?php echo $use_pay_method; ?>">
<input type="hidden" name="cash_tr_code"   value="<?php echo $cash_tr_code; ?>">

<!-- 추가 파라미터 -->
<input type="hidden" name="param_opt_1"	   value="<?php echo $param_opt_1; ?>">
<input type="hidden" name="param_opt_2"	   value="<?php echo $param_opt_2; ?>">
<input type="hidden" name="param_opt_3"	   value="<?php echo $param_opt_3; ?>">
</form>

<script type="text/JavaScript">
$(function(){
	if($("iframe")){
		$("iframe").height($(window).height());
	}
});
</script>
</body>
</html>