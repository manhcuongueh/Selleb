<?php
include_once("./_common.php");

$p = parse_url($url);
if($p['scheme'] || $p['host']) {
    alert("url에 도메인을 지정할 수 없습니다.");
}

// 이미 로그인 중이라면
if($is_member) {
    if($url)
        goto_url($url);
    else
        goto_url($tb['root']);
}

if($url)
    $login_url = urlencode($url);
else
    $login_url = urlencode($_SERVER['REQUEST_URI']);

$tb['title'] = "로그인";
include_once("./_head.php");
include_once($theme_path.'/login.skin.php');
include_once("./_tail.php");
?>