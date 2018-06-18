<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no']) {
    alert("결제할 주문서가 없습니다.");
}

$gw_head_title = '결제하기';
include_once("./_head.php");

include_once("./kcp/cfg/site_conf_inc.php"); 

/* ============================================================================== */
/* =   Javascript source Include                                                = */
/* = -------------------------------------------------------------------------- = */
/* =   ※ 필수                                                                  = */
/* = -------------------------------------------------------------------------- = */
?>
<script src="<?php echo $g_conf_js_url; ?>"></script>
<?php
/* = -------------------------------------------------------------------------- = */
/* =   Javascript source Include END                                            = */
/* ============================================================================== */
?>

<script>
/* Payplus Plug-in 실행 */
function  jsf__pay( form )
{
	var RetVal = false;

	/* Payplus Plugin 실행 */
	if( MakePayMessage( form ) == true )
	{
		openwin = window.open( "./kcp/proc_win.html", "proc_win", "width=449, height=209, top=300, left=300" );
		RetVal = true ;
	}
	
	else
	{
		/*  res_cd와 res_msg변수에 해당 오류코드와 오류메시지가 설정됩니다.
			ex) 고객이 Payplus Plugin에서 취소 버튼 클릭시 res_cd=3001, res_msg=사용자 취소
			값이 설정됩니다.
		*/
		res_cd  = document.order_info.res_cd.value ;
		res_msg = document.order_info.res_msg.value ;

	}

	return RetVal ;
}

function CheckPayplusInstall()
{
    StartSmartUpdate();

    if(ChkBrowser())
    {
        if(document.Payplus.object != null) {
            document.getElementById("display_setup_message").style.display = "none" ;
            document.getElementById("display_pay_button").style.display = "block" ;
        }
    }
    else
    {
        setTimeout("init_pay_button();",300);
    }
}

// Payplus Plug-in 설치 안내 
function init_pay_button()
{
    if(navigator.userAgent.indexOf('MSIE') > 0)
    {
        try
        {
            if( document.Payplus.object == null )
            {
                document.getElementById("display_setup_message").style.display = "block" ;
                document.getElementById("display_pay_button").style.display = "none" ;
                document.getElementById("display_setup_message").scrollIntoView();
            }
            else{
                document.getElementById("display_setup_message").style.display = "none" ;
                document.getElementById("display_pay_button").style.display = "block" ;
            }
        }
        catch (e)
        {
            document.getElementById("display_setup_message").style.display = "block" ;
            document.getElementById("display_pay_button").style.display = "none" ;
            document.getElementById("display_setup_message").scrollIntoView();
        }
    }
    else
    {
        try
        {
            if( Payplus == null )
            {
                document.getElementById("display_setup_message").style.display = "block" ;
                document.getElementById("display_pay_button").style.display = "none" ;
                document.getElementById("display_setup_message").scrollIntoView();
            }
            else{
                document.getElementById("display_setup_message").style.display = "none" ;
                document.getElementById("display_pay_button").style.display = "block" ;
            }
        }
        catch (e)
        {
            document.getElementById("display_setup_message").style.display = "block" ;
            document.getElementById("display_pay_button").style.display = "none" ;
            document.getElementById("display_setup_message").scrollIntoView();
        }
    }
}
</script>

<?php
$goodname = "";
$good_info = "";
$good_mny = get_session('total_amt'); // 결제금액
$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$goods_count = -1;

$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";	
$result = sql_query($sql);

include_once($theme_path.'/orderkcp.skin.php');

include_once("./_tail.php");
?>