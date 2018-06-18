<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no']) {
    alert("결제할 주문서가 없습니다.");
}

$gw_head_title = '결제하기';
$body_script = ' onload="javascript:enable_click()" onFocus="javascript:focus_control()"';
include_once("./_head.php");

if($default['cf_card_test_yn']) { // 테스트 결제시
    if($default['cf_escrow_yn'] && in_array(get_session('ss_pay_method'), array('ER','ES')))
        $ini_mid = "iniescrow0";
    else
        $ini_mid = "INIpayTest";
} else { // 실결제시
    if($default['cf_escrow_yn'] && in_array(get_session('ss_pay_method'), array('ER','ES')))
        $ini_mid = $default['cf_inicis_escrow_id'];
    else
        $ini_mid = $default['cf_inicis_id'];
}
?>

<script src="http://plugin.inicis.com/pay61_secuni_cross.js"></script>
<script>
StartSmartUpdate();
</script>
<script>
var openwin;
function pay(frm)
{
	// MakePayMessage()를 호출함으로써 플러그인이 화면에 나타나며, Hidden Field
	// 에 값들이 채워지게 됩니다. 일반적인 경우, 플러그인은 결제처리를 직접하는 것이
	// 아니라, 중요한 정보를 암호화 하여 Hidden Field의 값들을 채우고 종료하며,
	// 다음 페이지인 INIsecureresult.php로 데이터가 포스트 되어 결제 처리됨을 유의하시기 바랍니다.

	if(document.ini.clickcontrol.value == "enable")
	{
		
		if(document.ini.goodname.value == "")  // 필수항목 체크 (상품명, 상품가격, 구매자명, 구매자 이메일주소, 구매자 전화번호)
		{
			alert("상품명이 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.ini.buyername.value == "")
		{
			alert("구매자명이 빠졌습니다. 필수항목입니다.");
			return false;
		} 
		else if(document.ini.buyeremail.value == "")
		{
			alert("구매자 이메일주소가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.ini.buyertel.value == "")
		{
			alert("구매자 전화번호가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if( ( navigator.userAgent.indexOf("MSIE") >= 0 || navigator.appName == 'Microsoft Internet Explorer' ) &&  (document.INIpay == null || document.INIpay.object == null) )  // 플러그인 설치유무 체크
		{
			alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.");
			return false;
		}
		else
		{
			/******
			 * 플러그인이 참조하는 각종 결제옵션을 이곳에서 수행할 수 있습니다.
			 * (자바스크립트를 이용한 동적 옵션처리)
			 */			
						 
			if(MakePayMessage(frm))
			{
				disable_click();
				openwin = window.open("./INIpay50/childwin.html","childwin","width=299,height=149");		
				return true;
			}
			else
			{
				if( IsPluginModule() ) //plugin타입 체크
   				{
					alert("결제를 취소하셨습니다.");
				}
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


function enable_click()
{
	document.ini.clickcontrol.value = "enable"
}

function disable_click()
{
	document.ini.clickcontrol.value = "disable"
}

function focus_control()
{
	if(document.ini.clickcontrol.value == "disable")
		openwin.focus();
}
</script>

<script>
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
	if(init==true) with (navigator) {if((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
	else if(innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if(restore) selObj.selectedIndex=0;
}
//-->
</script>

<?php
$goodname = "";
$good_mny = get_session('total_amt'); // 결제금액
$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$goods_count = -1;

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";	
$result = sql_query($sql);

include_once($theme_path.'/orderinicis.skin.php');

include_once("./_tail.php");
?>