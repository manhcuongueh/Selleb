<?php
include_once("./_common.php");

define('_INDEX_', true);

// 인트로를 사용 검사
if(!$member['id'] && $config['sp_intro']) {
	include_once($theme_path.'/intro.skin.php');
    return;
}

include_once(TW_PATH.'/head.php'); // 상단
include_once($theme_path.'/main.skin.php'); // 메인
include_once(TW_PATH.'/tail.php'); // 하단
?>