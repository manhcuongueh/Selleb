<?php
$dir = "../..";
include_once("$dir/inc/connect.php");
include_once("$dir/inc/session.php");
include_once("$dir/inc/config.php");
include_once("$dir/inc/extend.php");

if($member['id']) {
	alert_close('현재 로그인 중입니다.');
}

include_once('./_apikey.php');
?>