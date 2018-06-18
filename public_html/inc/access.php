<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

if($_SERVER["QUERY_STRING"])
	$url = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
else
	$url = $_SERVER["PHP_SELF"];

$url = urlencode($url);

if(!$member['id']) {
	goto_url(TW_BBS_URL."/login.php?url=$url");
}
?>