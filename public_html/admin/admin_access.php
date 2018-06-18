<?php
if(!defined('_TUBEWEB_')) exit;

if($_SERVER["QUERY_STRING"])
	$url = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
else
	$url = $_SERVER["PHP_SELF"];

$url = urlencode($url);

if(!$member['id']) {
	goto_url(TW_BBS_URL."/login.php?url=$url");
}

// admin 세션 변수에 등록
$admin_id = get_session('admin_ss_mb_id');
if($admin_id) { 
	set_session('ss_mb_id', $admin_id);

	$member = sql_fetch("select * from shop_member where id = '".$admin_id."'");
	
	// 초기화
	unset($admin_id);
	set_session('admin_ss_mb_id', '');
}

if(!is_admin()) {
	alert('관리자 권한이 없습니다.', TW_URL);
}
?>