<?php
include_once("_common.php");

// 관리자 세션 변수에 등록
if(is_admin()) {
	set_session('admin_ss_mb_id', get_session('ss_mb_id'));
}

// 세션 변수에 등록
set_session('ss_mb_id', $_GET['mb_id']);

if($_GET['sel_field'] == 'item') {
	goto_url(TW_MYPAGE_URL.'/page.php?code=seller_main');
} else if($_GET['sel_field'] == 'drapt') {
	goto_url(TW_MYPAGE_URL.'/page.php?code=partner_info');
} else {
	goto_url(TW_URL);
}
?>