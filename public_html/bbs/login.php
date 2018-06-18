<?php
include_once("./_common.php");

if($member['id']) {
	goto_url(TW_URL); 
}

$gw_head_title = '로그인';
include_once("./_head.php"); 

$form_action_url = TW_BBS_URL.'/login_check.php';
include_once($theme_path.'/login.skin.php');

include_once("./_tail.php");
?>