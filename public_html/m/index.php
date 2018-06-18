<?php
define('_MINDEX_', true);
include_once("./_common.php");

// 인트로를 사용중인지 검사
if(!$is_member && $config['sp_intro']) {
	include_once($theme_path.'/intro.skin.php');
    return;
}

include_once("$tb[root]/_head.php"); // 상단
include_once("$tb[root]/popup.php"); // 팝업
include_once($theme_path.'/main.skin.php'); // 팝업레이어
include_once("$tb[root]/_tail.php"); // 하단
?>