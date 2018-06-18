<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가 

$g_conf_home_dir  = M_PATH_KCP; // 절대경로 입력 /kcp/bin <= 폴더권한을 755로 변경

// 테스트 결제시
if($default['cf_card_test_yn']) {
    if($default['cf_escrow_yn'] && in_array(get_session('ss_pay_method'), array('ER','ES'))) {
        // 에스크로결제 테스트
        $g_conf_site_cd  = "T0007";
        $g_conf_site_key = "4Ho4YsuOZlLXUZUdOxM1Q7X__";
    }
    else {
        // 일반결제 테스트
        $g_conf_site_cd = "T0000";
        $g_conf_site_key = "3grptw1.zW0GSo4PQdaGvsF__";
    }

	$g_wsdl           = "KCPPaymentService.wsdl";
	$g_conf_gw_url	  = "testpaygw.kcp.co.kr";
	$g_conf_site_name = "KCP TEST SHOP"; 
	$g_conf_js_url    = "http://pay.kcp.co.kr/plugin/payplus_test_un.js";
} 
else {		
	$g_conf_site_cd   = $default['cf_kcp_id'];
	$g_conf_site_key  = $default['cf_kcp_key'];
	$g_conf_site_name = $default['cf_nm_pg'];

	$g_wsdl			  = "real_KCPPaymentService.wsdl";
	$g_conf_gw_url    = "paygw.kcp.co.kr";	
	$g_conf_js_url    = "http://pay.kcp.co.kr/plugin/payplus_un.js";
}

$g_conf_log_level = "3";
$g_conf_gw_port   = "8090"; // 포트번호(변경불가)
?>