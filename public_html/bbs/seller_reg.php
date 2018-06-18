<?php
include_once("./_common.php");

if(!$config['shop_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.');
}

if($seller['state']==1) {
	goto_url(TW_MYPAGE_URL.'/page.php?code=seller_main');
}

$gw_head_title = '온라인 입점신청';
include_once("./_head.php");
include_once($theme_path.'/seller_reg.skin.php');
include_once("./_tail.php");
?>