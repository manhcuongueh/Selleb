<?php
$g_conf_home_dir = $_SERVER['DOCUMENT_ROOT']."/shop/kcp"; // 절대경로 입력 /kcp/bin <= 폴더권한을 777로 변경

$test = "";

// 테스트 결제시
if($default['cf_card_test_yn']) {

	$g_conf_gw_url = "testpaygw.kcp.co.kr";

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
	
	$g_conf_site_name = "KCP TEST SHOP"; 

	$test = "_test";
} else {
	$g_conf_gw_url    = "paygw.kcp.co.kr";

	$g_conf_site_cd   = $default['cf_kcp_id'];
	$g_conf_site_key  = $default['cf_kcp_key'];
	$g_conf_site_name = $default['cf_nm_pg'];
}

$g_conf_js_url = "http://pay.kcp.co.kr/plugin/payplus{$test}.js";

$g_conf_log_level = "3";
$g_conf_gw_port   = "8090"; // 포트번호(변경불가)
$module_type      = "01"; // 변경불가
?>
