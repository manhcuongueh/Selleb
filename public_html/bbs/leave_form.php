<?php
include_once("./_common.php");

if(!$member['id'])
	goto_url(TW_BBS_URL."/login.php?url=$nowurl");	

if(is_admin())
	alert('관리자는 탈퇴하실 수 없습니다.');

$gw_head_title = '회원탈퇴';
include_once("./_head.php"); 

$form_action_url = TW_BBS_URL.'/leave_form_update.php';

include_once($theme_path.'/leave_form.skin.php');

include_once("./_tail.php");
?>