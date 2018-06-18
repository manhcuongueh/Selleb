<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where odrkey='$odrkey'");
if(!$od['index_no'])
    alert("결제할 주문서가 없습니다.");

$tb['title'] = $arr_mhd[$od['buymethod']]." 결제하기";
include_once("./_head.php");

$p_app_base = '';
$inipay_url = M_HTTP."/inipay";
$ss_pay_method = get_session('ss_pay_method'); // 결제방법
$good_mny = get_session('total_amt'); // 결제금액

// 결제등록 요청시 사용할 입금마감일
$p_vbank_dt = date("Ymd", ($tb['server_time'] + 86400 * 5));

if($default['cf_card_test_yn']) { // 테스트 결제시
    if($default['cf_escrow_yn'] && in_array($ss_pay_method, array('ER','ES')))
        $p_mid = "iniescrow0"; // 에스크로결제 테스트
    else        
        $p_mid = "INIpayTest"; // 일반결제 테스트
} else { // 실결제시
    if($default['cf_escrow_yn'] && in_array($ss_pay_method, array('ER','ES')))
        $p_mid = $default['cf_inicis_escrow_id'];
    else
        $p_mid = $default['cf_inicis_id'];
}

// 지불방법
switch($ss_pay_method) {
	case 'C' : // 신용카드
		$paymethod = "wcard";
		break;
	case 'ER' : // 계좌이체 (에스크로)
	case 'R'  : // 계좌이체
		$paymethod = "bank";
		$p_app_base = 'ON';
		break;
	case 'H' : // 휴대폰
		$paymethod = "mobile";
		break;
	case 'ES' : // 가상계좌 (에스크로)
	case 'S'  : // 가상계좌
		$paymethod = "vbank";
		break;
}

// 총금액 뽑기
$sql = " select SUM(account) as it_amt,
				SUM(del_account) as de_amt,
				SUM(dc_exp_amt) as dc_amt,
				SUM(use_point) as po_amt,
				SUM(use_account) as buy_amt
		   from shop_order
		  where mb_no='$mb_no' and odrkey='$odrkey' ";
$tot_sum = sql_fetch($sql);

$goodname = "";
$goods_count = -1;
$sql = " select * from shop_cart where odrkey = '$odrkey' group by gs_id order by index_no ";
$result = sql_query($sql);

include_once($theme_path."/orderinicis.skin.php");
include_once("./_tail.php");
?>